<?php

use App\Models\MentoringBooking;
use App\Models\MentorProfile;
use App\Models\MentorSlot;
use App\Models\Module;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

function modrAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

function modrMentor(array $attributes = []): User
{
    $mentor = User::factory()->create(array_merge(['role' => 'mentor', 'status' => 'active'], $attributes));

    MentorProfile::create([
        'user_id' => $mentor->id,
        'whatsapp' => '081234567890',
        'university' => 'PTN KawanNalar',
        'major' => 'Informatika',
        'high_school' => 'SMA Magetan',
    ]);

    return $mentor;
}

function modrModule(User $uploader, string $status = 'pending'): Module
{
    return Module::create([
        'title' => 'Catatan Integral Parsial',
        'description' => 'Ringkasan materi',
        'subject' => 'Matematika',
        'grade' => 'Kelas 12',
        'file_path' => 'modules/catatan.pdf',
        'uploaded_by' => $uploader->id,
        'status' => $status,
        'download_count' => 0,
    ]);
}

/** @return array{0: User, 1: User, 2: MentoringBooking} */
function modrPendingBooking(): array
{
    $mentor = modrMentor();
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'name' => 'Siswa Peminta']);
    $slot = MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '19:00',
        'end_time' => '20:00',
        'topic' => 'Curhat',
        'meeting_link' => 'https://meet.google.com/abc-defg-hij',
        'status' => 'kosong',
    ]);
    $booking = MentoringBooking::create([
        'student_id' => $student->id,
        'mentor_id' => $mentor->id,
        'mentor_slot_id' => $slot->id,
        'topic' => 'Curhat',
        'status' => 'pending',
    ]);

    return [$mentor, $student, $booking];
}

// ── Pop-up konfirmasi menggantikan submit langsung ──────────────────────────

it('asks for confirmation before approving or rejecting mentors and modules', function () {
    $admin = modrAdmin();
    modrMentor(['status' => 'pending', 'name' => 'Calon Mentor']);
    modrModule(User::factory()->create(['role' => 'siswa']));

    $html = $this->actingAs($admin)->get(route('admin.verification.index'))->assertOk()->getContent();

    // Tabel desktop + kartu HP, mentor + modul → 8 tombol, satu pop-up.
    expect(substr_count($html, "\$dispatch('moderation'"))->toBe(8)
        ->and(substr_count($html, '@moderation.window'))->toBe(1)
        ->and($html)->toContain('name="reason"')
        ->toContain('Ya, Tolak')
        ->toContain('Ya, Setujui')
        ->not->toMatch('/<form[^>]+action="[^"]+\/(approve|reject)"/');

    $this->actingAs($admin)->get(route('admin.users.mentor'))
        ->assertOk()
        ->assertSee("\$dispatch('moderation'", false)
        ->assertSee('@moderation.window', false);
});

it('asks the mentor for confirmation before approving or rejecting a booking', function () {
    [$mentor] = modrPendingBooking();

    foreach ([route('mentor.teman-nalar.index'), route('dashboard.mentor')] as $url) {
        $html = $this->actingAs($mentor)->get($url)->assertOk()->getContent();

        expect(substr_count($html, "\$dispatch('moderation'"))->toBe(2)
            ->and(substr_count($html, '@moderation.window'))->toBe(1)
            ->and($html)->toContain('siswa di halaman Booking Saya')
            ->not->toMatch('/<form[^>]+action="[^"]+\/booking\/\d+\/(approve|reject)"/');
    }
});

// ── Pendaftaran mentor ──────────────────────────────────────────────────────

it('stores the mentor rejection reason and shows it when the mentor tries to log in', function () {
    $admin = modrAdmin();
    $mentor = modrMentor(['status' => 'pending', 'email' => 'calon@kawannalar.test']);

    $this->actingAs($admin)
        ->patch(route('admin.mentors.reject', $mentor->id), ['reason' => 'Foto KTM buram, mohon daftar ulang.'])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'Pendaftaran mentor berhasil ditolak.');

    expect($mentor->fresh())
        ->status->toBe('rejected')
        ->rejection_reason->toBe('Foto KTM buram, mohon daftar ulang.');

    Auth::logout();

    $this->post(route('login'), ['email' => 'calon@kawannalar.test', 'password' => 'password'])
        ->assertSessionHasErrors([
            'email' => 'Pendaftaran mentor Anda ditolak oleh Admin. Alasan: Foto KTM buram, mohon daftar ulang. Silakan hubungi tim KawanNalar.',
        ]);
    $this->assertGuest();
});

it('keeps the old login message for mentors rejected without a reason', function () {
    User::factory()->create(['role' => 'mentor', 'status' => 'rejected', 'email' => 'lama@kawannalar.test']);

    $this->post(route('login'), ['email' => 'lama@kawannalar.test', 'password' => 'password'])
        ->assertSessionHasErrors(['email' => 'Pendaftaran mentor Anda ditolak oleh Admin. Silakan hubungi tim KawanNalar.']);
});

it('requires a reason to reject a mentor and leaves the registration pending otherwise', function () {
    $admin = modrAdmin();
    $mentor = modrMentor(['status' => 'pending']);

    $this->actingAs($admin)->from(route('admin.verification.index'))
        ->patch(route('admin.mentors.reject', $mentor->id), ['reason' => '   '])
        ->assertRedirect(route('admin.verification.index'))
        ->assertSessionHasErrorsIn('moderation', ['reason' => 'Alasan penolakan wajib diisi.']);

    expect($mentor->fresh()->status)->toBe('pending');

    $this->actingAs($admin)->get(route('admin.verification.index'))->assertOk();
    $this->actingAs($admin)->from(route('admin.verification.index'))->followingRedirects()
        ->patch(route('admin.mentors.reject', $mentor->id))
        ->assertOk()
        ->assertSeeText('Alasan penolakan wajib diisi.');
});

it('approves a mentor without needing a reason', function () {
    $mentor = modrMentor(['status' => 'pending']);

    $this->actingAs(modrAdmin())
        ->patch(route('admin.mentors.approve', $mentor->id))
        ->assertSessionHasNoErrors();

    expect($mentor->fresh())->status->toBe('active')->rejection_reason->toBeNull();
});

// ── Modul Ruang Nalar ───────────────────────────────────────────────────────

it('shows the real module rejection reason to the student uploader instead of a hardcoded note', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $module = modrModule($student);

    $this->actingAs(modrAdmin())
        ->patch(route('admin.modules.reject', $module), ['reason' => 'Halaman 3 terpotong, unggah ulang versi lengkap.'])
        ->assertSessionHasNoErrors();

    expect($module->fresh())
        ->status->toBe('rejected')
        ->rejection_reason->toBe('Halaman 3 terpotong, unggah ulang versi lengkap.');

    $this->actingAs($student)->get(route('siswa.ruang-nalar.index'))
        ->assertOk()
        ->assertSeeText('Catatan Revisi Admin')
        ->assertSeeText('Halaman 3 terpotong, unggah ulang versi lengkap.')
        ->assertDontSeeText('Gambar atau modul terlalu buram');
});

it('shows the module rejection reason to the mentor uploader', function () {
    $mentor = modrMentor();
    $module = modrModule($mentor);

    $this->actingAs(modrAdmin())
        ->patch(route('admin.modules.reject', $module), ['reason' => 'Materi duplikat dengan modul lain.']);

    $this->actingAs($mentor)->get(route('mentor.ruang-nalar.index'))
        ->assertOk()
        ->assertSeeText('Alasan ditolak Admin:')
        ->assertSeeText('Materi duplikat dengan modul lain.');
});

it('requires a reason to reject a module', function () {
    $module = modrModule(User::factory()->create(['role' => 'siswa']));

    $this->actingAs(modrAdmin())
        ->patch(route('admin.modules.reject', $module))
        ->assertSessionHasErrorsIn('moderation', 'reason');

    expect($module->fresh()->status)->toBe('pending');
});

it('shows a neutral note for modules rejected before reasons existed', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    modrModule($student, 'rejected');

    $this->actingAs($student)->get(route('siswa.ruang-nalar.index'))
        ->assertOk()
        ->assertSeeText('Admin tidak mencantumkan alasan penolakan.');
});

// ── Booking siswa ───────────────────────────────────────────────────────────

it('stores the booking rejection reason and shows it in Booking Saya', function () {
    Http::fake();
    [$mentor, $student, $booking] = modrPendingBooking();

    $this->actingAs($mentor)
        ->patch(route('mentor.teman-nalar.booking.reject', $booking->id), ['reason' => 'Jadwal bentrok dengan ujian kampus, silakan pilih slot lain.'])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'Booking telah ditolak/dibatalkan.');

    expect($booking->fresh())
        ->status->toBe('rejected')
        ->rejection_reason->toBe('Jadwal bentrok dengan ujian kampus, silakan pilih slot lain.')
        ->and($booking->slot->fresh()->status)->toBe('kosong');

    $this->actingAs($student)->get(route('siswa.teman-nalar.index', ['tab' => 'my-bookings']))
        ->assertOk()
        ->assertSeeText('Alasan ditolak mentor:')
        ->assertSeeText('Jadwal bentrok dengan ujian kampus, silakan pilih slot lain.');
});

it('requires a reason to reject a booking without opening the add-slot modal', function () {
    Http::fake();
    [$mentor, , $booking] = modrPendingBooking();

    $this->actingAs($mentor)->from(route('mentor.teman-nalar.index'))->followingRedirects()
        ->patch(route('mentor.teman-nalar.booking.reject', $booking->id), ['reason' => ''])
        ->assertOk()
        ->assertSeeText('Alasan penolakan wajib diisi.')
        ->assertSee('showModal: false', false);

    expect($booking->fresh()->status)->toBe('pending');
    Http::assertNothingSent();
});

it('still approves a booking after confirmation without a reason', function () {
    Http::fake();
    [$mentor, , $booking] = modrPendingBooking();

    $this->actingAs($mentor)
        ->patch(route('mentor.teman-nalar.booking.approve', $booking->id))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'Booking berhasil disetujui!');

    expect($booking->fresh())->status->toBe('approved')->rejection_reason->toBeNull();
});

// ── Flashcard & header dashboard admin ──────────────────────────────────────

it('reloads the page to draw new random flashcards and starts them right away', function () {
    $quiz = Quiz::create([
        'mentor_id' => modrMentor()->id,
        'subject_id' => Subject::firstOrCreate(['code' => 'MTK'], ['name' => 'Matematika'])->id,
        'class_level' => '12',
        'title' => 'Paket Flashcard',
        'total_questions' => 1,
        'status' => 'approved',
    ]);
    Question::create([
        'quiz_id' => $quiz->id,
        'question_text' => 'Berapa 2 + 2?',
        'option_a' => '3', 'option_b' => '4', 'option_c' => '5', 'option_d' => '6', 'option_e' => '7',
        'correct_answer' => 'B',
        'order' => 1,
    ]);

    $html = $this->actingAs(User::factory()->create(['role' => 'siswa', 'status' => 'active'])->fresh())
        ->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertSeeText('Kartu Acak Baru')
        ->getContent();

    expect($html)
        ->toContain("started: new URLSearchParams(window.location.search).has('kartu')")
        ->toContain("'?kartu=' + Date.now() + '#flashcard'")
        ->not->toContain('href="'.route('siswa.uji-nalar.index').'#flashcard"');
});

it('lets the admin dashboard card headers wrap on small screens', function () {
    modrMentor(['status' => 'pending']);

    $html = $this->actingAs(modrAdmin())->get(route('dashboard.admin'))->assertOk()->getContent();

    expect(substr_count($html, 'flex flex-wrap items-center gap-x-2 gap-y-1'))->toBe(2)
        ->and(substr_count($html, 'shrink-0 whitespace-nowrap pt-0.5'))->toBe(2);
});

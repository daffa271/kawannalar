<?php

use App\Models\LiveClass;
use App\Models\MentoringBooking;
use App\Models\MentorProfile;
use App\Models\MentorSlot;
use App\Models\Module;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Carbon;

const MP_EMOJI = '/[\x{1F000}-\x{1FAFF}\x{2300}-\x{23FF}\x{2600}-\x{27BF}\x{25A0}-\x{25FF}\x{2039}\x{203A}]/u';

function mpMentor(array $attributes = []): User
{
    $mentor = User::factory()->create(array_merge(['role' => 'mentor', 'status' => 'active'], $attributes));
    MentorProfile::create(['user_id' => $mentor->id, 'whatsapp' => '0812', 'university' => 'ITS', 'major' => 'Informatika', 'high_school' => 'SMAN 1 Magetan']);

    return $mentor->fresh();
}

function mpQuiz(User $mentor, string $title, string $status = 'approved', ?string $reason = null): Quiz
{
    $quiz = Quiz::create([
        'mentor_id' => $mentor->id,
        'subject_id' => Subject::firstOrCreate(['code' => 'MTK'], ['name' => 'Matematika'])->id,
        'class_level' => '12', 'title' => $title, 'total_questions' => 5, 'status' => $status,
        'rejection_reason' => $reason,
    ]);
    Question::create(['quiz_id' => $quiz->id, 'question_text' => 'Soal '.$title, 'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E', 'correct_answer' => 'A', 'order' => 1]);

    return $quiz;
}

function mpModule(User $uploader, string $title, string $status = 'approved'): Module
{
    return Module::create(['title' => $title, 'subject' => 'Matematika', 'grade' => 'Kelas 12', 'file_path' => 'modules/x.pdf', 'uploaded_by' => $uploader->id, 'status' => $status, 'download_count' => 0]);
}

/** Isi halaman tanpa navbar, sidebar, dan footer aplikasi. */
function mpContent(string $html): string
{
    $start = strpos($html, '<main');

    return substr($html, $start, strrpos($html, '<footer') - $start);
}

// ── Dashboard = ringkasan 5 teratas ─────────────────────────────────────────

it('shows only the top 5 of each dashboard section and links to the feature pages for the rest', function () {
    Carbon::setTestNow(Carbon::parse('2026-10-03 08:00', 'Asia/Jakarta'));
    $mentor = mpMentor();

    foreach (range(1, 7) as $i) {
        $slot = MentorSlot::create(['mentor_id' => $mentor->id, 'date' => '2026-10-'.str_pad(10 + $i, 2, '0', STR_PAD_LEFT), 'start_time' => '19:00', 'end_time' => '20:00', 'topic' => 'Curhat', 'status' => 'kosong']);
        $student = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'name' => "Peminta {$i}"]);
        MentoringBooking::create(['student_id' => $student->id, 'mentor_id' => $mentor->id, 'mentor_slot_id' => $slot->id, 'topic' => 'Curhat', 'status' => 'pending']);
        mpQuiz($mentor, "Paket Ringkas {$i}")->forceFill(['created_at' => now()->subMinutes(10 - $i)])->save();
        mpModule($mentor, "Modul Ringkas {$i}")->forceFill(['created_at' => now()->subMinutes(10 - $i)])->save();
    }

    $response = $this->actingAs($mentor)->get(route('dashboard.mentor'))
        ->assertOk()
        ->assertViewHas('mySlots', fn ($slots) => $slots->count() === 5)
        ->assertViewHas('slotTotal', 7)
        ->assertViewHas('pendingBookings', fn ($bookings) => $bookings->count() === 5)
        ->assertViewHas('statPending', 7)
        ->assertViewHas('myQuizzes', fn ($quizzes) => $quizzes->count() === 5)
        ->assertViewHas('quizTotal', 7)
        ->assertViewHas('myModules', fn ($modules) => $modules->count() === 5)
        ->assertViewHas('moduleTotal', 7)
        ->assertSeeText('7 Baru')
        ->assertSeeText('Menampilkan 5 slot teratas dari 7 slot')
        ->assertSeeText('Kelola semua slot di Sesi Mentoring (7)')
        ->assertSeeText('Lihat semua permintaan (7)')
        ->assertSeeText('Lihat semua paket soal (7)')
        ->assertSeeText('Lihat semua modul, pratinjau & unduh (7)')
        ->assertSeeText('Paket Ringkas 7')        // terbaru tampil
        ->assertDontSeeText('Paket Ringkas 1')    // paling lama tidak tampil
        ->assertDontSeeText('Modul Ringkas 1');

    $html = $response->getContent();
    expect($html)->toContain('href="'.route('mentor.teman-nalar.index').'"')
        ->toContain('href="'.route('mentor.uji-nalar.index').'"')
        ->toContain('href="'.route('mentor.ruang-nalar.index').'"');

    // Halaman fitur tetap menampilkan semuanya.
    $this->actingAs($mentor)->get(route('mentor.uji-nalar.index'))->assertSeeText('Paket Ringkas 1')->assertSeeText('Paket Ringkas 7');
    $this->actingAs($mentor)->get(route('mentor.ruang-nalar.index'))->assertSeeText('Modul Ringkas 1')->assertSeeText('Modul Ringkas 7');

    Carbon::setTestNow();
});

// ── Sesi Mentoring ──────────────────────────────────────────────────────────

it('marks past Belajar Bersama sessions, lists upcoming ones first, and counts only active ones', function () {
    Carbon::setTestNow(Carbon::parse('2026-10-03 08:00', 'Asia/Jakarta'));
    $mentor = mpMentor();
    LiveClass::create(['mentor_id' => $mentor->id, 'title' => 'Kelas Kemarin', 'schedule_time' => '2026-10-02 19:00', 'quota' => 0, 'meet_link' => 'https://meet.google.com/aaa-bbbb-ccc', 'registered_count' => 0]);
    LiveClass::create(['mentor_id' => $mentor->id, 'title' => 'Kelas Besok', 'schedule_time' => '2026-10-04 19:00', 'quota' => 0, 'meet_link' => 'https://meet.google.com/ddd-eeee-fff', 'registered_count' => 0]);

    $content = mpContent($this->actingAs($mentor)->get(route('mentor.teman-nalar.index'))
        ->assertOk()
        ->assertSeeInOrder(['Kelas Besok', 'Kelas Kemarin'])
        ->assertSeeText('Sudah lewat')
        ->assertSeeText('Sesi sudah selesai')
        ->assertSeeText('Belajar Bersama Aktif')
        ->assertDontSeeText('Live Class')
        ->getContent());

    expect(substr_count($content, 'href="https://meet.google.com/ddd-eeee-fff"'))->toBe(1)
        ->and($content)->not->toContain('href="https://meet.google.com/aaa-bbbb-ccc"')
        ->toContain('Belajar Bersama Aktif</p>
        <p class="mt-1 text-2xl font-extrabold text-gray-900">1</p>');

    Carbon::setTestNow();
});

it('asks for confirmation before marking a mentoring session as finished', function () {
    $mentor = mpMentor();
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $slot = MentorSlot::create(['mentor_id' => $mentor->id, 'date' => now()->addDay()->toDateString(), 'start_time' => '19:00', 'end_time' => '20:00', 'topic' => 'Curhat', 'meeting_link' => 'https://meet.google.com/abc', 'status' => 'terisi']);
    MentoringBooking::create(['student_id' => $student->id, 'mentor_id' => $mentor->id, 'mentor_slot_id' => $slot->id, 'topic' => 'Curhat', 'status' => 'approved']);

    $this->actingAs($mentor)->get(route('mentor.teman-nalar.index'))
        ->assertOk()
        ->assertSee("return confirm('Tandai sesi mentoring ini sudah selesai?')", false);
});

// ── Buat Soal ───────────────────────────────────────────────────────────────

it('shows the rejection reason on the quiz list and keeps typed questions after a validation error', function () {
    $mentor = mpMentor();
    mpQuiz($mentor, 'Paket Ditolak', 'rejected', 'Kunci jawaban soal 3 keliru.');

    $this->actingAs($mentor)->get(route('mentor.uji-nalar.index'))
        ->assertOk()
        ->assertSeeText('Alasan ditolak:')
        ->assertSeeText('Kunci jawaban soal 3 keliru.');

    $this->actingAs($mentor)->from(route('mentor.uji-nalar.create'))->followingRedirects()
        ->post(route('mentor.uji-nalar.store'), [
            'title' => 'Paket Belum Lengkap', 'subject_id' => 999999, 'class_level' => '12', 'total_questions' => '10',
            'questions' => [['question_text' => 'Berapa 7 x 8?', 'option_a' => '54', 'option_b' => '56', 'option_c' => '58', 'option_d' => '64', 'option_e' => '72', 'correct_answer' => 'B']],
        ])
        ->assertOk()
        ->assertSee('totalQuestions: 10', false)
        ->assertSee('Berapa 7 x 8?', false)
        ->assertSeeText('Terjadi kesalahan pada input:');
});

// ── Upload Modul ────────────────────────────────────────────────────────────

it('lists the published Ruang Nalar materials on the mentor page with preview and download', function () {
    $mentor = mpMentor();
    $other = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'name' => 'Pengunggah Lain']);
    $module = mpModule($other, 'Materi Tayang Umum');

    $this->actingAs($mentor)->get(route('mentor.ruang-nalar.index'))
        ->assertOk()
        ->assertSeeText('Materi Tayang di Ruang Nalar')
        ->assertSeeText('Materi Tayang Umum')
        ->assertSeeText('oleh Pengunggah Lain')
        ->assertSee(route('mentor.ruang-nalar.download', $module->id), false);
});

// ── Bebas emoji & warna CTA konsisten di semua halaman mentor ──────────────

it('renders every mentor page, navbar, and sidebar without emoji and with brand CTA colors', function () {
    $mentor = mpMentor();
    $quiz = mpQuiz($mentor, 'Paket Detail', 'rejected', 'Perbaiki soal.');
    mpModule($mentor, 'Modul Saya', 'pending');

    $pages = [
        route('dashboard.mentor'),
        route('mentor.teman-nalar.index'),
        route('mentor.uji-nalar.index'),
        route('mentor.uji-nalar.create'),
        route('mentor.uji-nalar.show', $quiz),
        route('mentor.ruang-nalar.index'),
        route('mentor.ruang-nalar.create'),
    ];

    foreach ($pages as $url) {
        $html = $this->actingAs($mentor)->get($url)->assertOk()->getContent();
        $body = substr($html, strpos($html, '<body'));

        expect(preg_match(MP_EMOJI, $body))->toBe(0, $url)
            ->and($body)->not->toContain('bg-[#FF6B00]')
            ->and(preg_match('/class="[^"]*bg-cta\b[^"]*text-white|class="[^"]*text-white[^"]*bg-cta\b/', $body))->toBe(0, $url);
    }

    // Navbar & sidebar milik siswa juga ikut bebas emoji.
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active'])->fresh();
    $html = $this->actingAs($student)->get(route('dashboard.siswa'))->assertOk()->getContent();
    $chrome = substr($html, strpos($html, '<nav'), strpos($html, '<main') - strpos($html, '<nav'));
    expect(preg_match(MP_EMOJI, $chrome))->toBe(0);
});

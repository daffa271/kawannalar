<?php

use App\Models\LiveClass;
use App\Models\MentoringBooking;
use App\Models\MentorProfile;
use App\Models\MentorSlot;
use App\Models\StudentProfile;
use App\Models\User;
use App\Notifications\BookingRequestMailNotification;
use App\Notifications\NewBookingNotification;
use Illuminate\Contracts\Mail\Factory as MailFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

function schedMentor(array $attributes = [], array $profile = []): User
{
    $mentor = User::factory()->create(array_merge(['role' => 'mentor', 'status' => 'active'], $attributes));

    MentorProfile::create(array_merge([
        'user_id' => $mentor->id,
        'whatsapp' => '081234567890',
        'university' => 'PTN KawanNalar',
        'major' => 'Informatika',
        'high_school' => 'SMA Magetan',
    ], $profile));

    return $mentor;
}

function schedStudent(): User
{
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'name' => 'Siswa Pemesan']);
    StudentProfile::create(['user_id' => $student->id, 'school' => 'SMAN 2 Magetan']);

    return $student->fresh();
}

function schedSlot(User $mentor, string $date, string $start, string $end, string $status = 'kosong'): MentorSlot
{
    return MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => $date,
        'start_time' => $start,
        'end_time' => $end,
        'topic' => 'Strategi UTBK',
        'meeting_link' => 'https://meet.google.com/abc-defg-hij',
        'status' => $status,
    ]);
}

function schedWib(string $datetime): Carbon
{
    return Carbon::parse($datetime, 'Asia/Jakarta');
}

// ── Email booking ke mentor ───────────────────────────────────────────────

it('emails the mentor when a student books a private session', function () {
    Notification::fake();
    Http::fake();
    $mentor = schedMentor();
    $student = schedStudent();
    $slot = schedSlot($mentor, now()->addDay()->toDateString(), '19:00', '20:00');

    $this->actingAs($student)->post(route('siswa.teman-nalar.booking.store'), [
        'mentor_slot_id' => $slot->id,
        'topic' => 'Lainnya',
        'custom_topic' => 'Strategi belajar personal',
        'message' => 'Tolong bantu jadwal belajar',
    ])->assertSessionHasNoErrors()->assertSessionHas('success');

    Notification::assertSentTo($mentor, BookingRequestMailNotification::class, function ($notification, array $channels) {
        return $channels === ['mail']
            && $notification->studentName === 'Siswa Pemesan'
            && $notification->studentSchool === 'SMAN 2 Magetan'
            && $notification->topic === 'Strategi belajar personal'
            && $notification->studentMessage === 'Tolong bantu jadwal belajar';
    });
    Notification::assertSentTo($mentor, NewBookingNotification::class);
    Notification::assertNotSentTo($student, BookingRequestMailNotification::class);
});

it('renders the booking email without any meeting link', function () {
    $mentor = schedMentor(['name' => 'Mentor Uji']);
    $mail = (new BookingRequestMailNotification('Siswa Pemesan', 'SMAN 2 Magetan', 'Curhat', 'Sen, 28 Sep 2026 19:00 WIB', 'Halo kak'))
        ->toMail($mentor);

    $html = (string) $mail->render();

    expect($mail->subject)->toBe('Booking Bimbingan Baru — KawanNalar')
        ->and($html)->toContain('Halo, Kak Mentor Uji')
        ->toContain('Siswa Pemesan (SMAN 2 Magetan)')
        ->toContain('Curhat')
        ->toContain('Sen, 28 Sep 2026 19:00 WIB')
        ->toContain('Halo kak')
        ->toContain(route('mentor.teman-nalar.index'))
        ->not->toContain('meet.google.com');
});

it('keeps the booking successful when the email cannot be sent', function () {
    Http::fake();
    Exceptions::fake();
    $this->mock(MailFactory::class, fn ($mock) => $mock->shouldReceive('mailer')->andThrow(new RuntimeException('SMTP tidak tersedia')));
    $mentor = schedMentor();
    $slot = schedSlot($mentor, now()->addDay()->toDateString(), '19:00', '20:00');

    $this->actingAs(schedStudent())->post(route('siswa.teman-nalar.booking.store'), [
        'mentor_slot_id' => $slot->id,
        'topic' => 'Curhat',
    ])->assertSessionHasNoErrors()->assertSessionHas('success');

    expect(MentoringBooking::where('mentor_slot_id', $slot->id)->value('status'))->toBe('pending')
        ->and($mentor->notifications()->count())->toBe(1);
    Exceptions::assertReported(RuntimeException::class);
});

// ── Sesi di waktu yang sudah lewat ─────────────────────────────────────────

it('rejects Belajar Bersama in the past and reopens the modal with the error and old input', function () {
    Http::fake();
    $this->travelTo(schedWib('2026-09-30 10:00'));
    $mentor = schedMentor();

    foreach (['2026-09-29' => '19:00', '2026-09-30' => '09:00'] as $date => $time) {
        $this->actingAs($mentor)->from(route('mentor.teman-nalar.index'))
            ->post(route('mentor.teman-nalar.slot.store'), [
                'session_type' => 'live_class',
                'title' => 'Uji Jadwal Lampau',
                'live_date' => $date,
                'live_time' => $time,
                'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            ])
            ->assertRedirect(route('mentor.teman-nalar.index'))
            ->assertSessionHasErrors('live_date');
    }
    expect(LiveClass::count())->toBe(0);

    $this->actingAs($mentor)->from(route('mentor.teman-nalar.index'))->followingRedirects()
        ->post(route('mentor.teman-nalar.slot.store'), [
            'session_type' => 'live_class',
            'title' => 'Uji Jadwal Lampau',
            'live_date' => '2026-09-29',
            'live_time' => '19:00',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
        ])
        ->assertOk()
        ->assertSee('showModal: true', false)
        ->assertSeeText('Belajar Bersama harus dijadwalkan untuk waktu yang akan datang.')
        ->assertSee('value="Uji Jadwal Lampau"', false);

    // Waktu mendatang tetap bisa dibuat.
    $this->actingAs($mentor)->post(route('mentor.teman-nalar.slot.store'), [
        'session_type' => 'live_class',
        'title' => 'Kelas Mendatang',
        'live_date' => '2026-09-30',
        'live_time' => '19:00',
        'meeting_link' => 'https://meet.google.com/abc-defg-hij',
    ])->assertSessionHasNoErrors();
    expect(LiveClass::count())->toBe(1);
});

it('rejects a private slot in the past and shows the error in the reopened modal', function () {
    Http::fake();
    $this->travelTo(schedWib('2026-09-30 10:00'));
    $mentor = schedMentor();

    $this->actingAs($mentor)->from(route('dashboard.mentor'))->followingRedirects()
        ->post(route('mentor.teman-nalar.slot.store'), [
            'session_type' => '1on1',
            'date' => '2026-09-29',
            'start_time' => '19:00',
            'end_time' => '20:00',
            'topic' => 'Curhat',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
        ])
        ->assertOk()
        ->assertSee('showModal: true', false)
        ->assertSeeText('Sesi private harus dibuat untuk waktu yang akan datang.');

    expect(MentorSlot::count())->toBe(0);
});

it('limits the date pickers to today and later', function () {
    $this->travelTo(schedWib('2026-09-30 10:00'));

    $html = $this->actingAs(schedMentor())->get(route('mentor.teman-nalar.index'))->assertOk()->getContent();

    expect(substr_count($html, 'min="2026-09-30"'))->toBe(2)
        ->and($html)->toContain('showModal: false');
});

it('shows students only slots that have not started yet', function () {
    $this->travelTo(schedWib('2026-09-30 19:30'));
    $started = schedMentor(['name' => 'Mentor Sudah Mulai']);
    $later = schedMentor(['name' => 'Mentor Nanti Malam']);
    $tomorrow = schedMentor(['name' => 'Mentor Besok']);
    schedSlot($started, '2026-09-30', '19:00', '20:00');
    schedSlot($later, '2026-09-30', '21:00', '22:00');
    schedSlot($tomorrow, '2026-10-01', '10:00', '11:00');
    $student = schedStudent();

    $this->actingAs($student)->get(route('siswa.teman-nalar.index'))
        ->assertOk()
        ->assertDontSeeText('Mentor Sudah Mulai')
        ->assertSeeText('Mentor Nanti Malam')
        ->assertSeeText('Mentor Besok');

    $this->actingAs($student)->get(route('siswa.teman-nalar.booking.create', $started))
        ->assertOk()
        ->assertSeeText('Tidak ada sesi yang tersedia untuk mentor ini.');

    $this->actingAs($student)->get(route('siswa.teman-nalar.booking.create', $later))
        ->assertOk()
        ->assertSeeText('21:00 - 22:00 WIB');
});

// ── Dashboard mentor ───────────────────────────────────────────────────────

it('shows the real slot status on the mentor dashboard with active slots first', function () {
    $this->travelTo(schedWib('2026-09-30 10:00'));
    $mentor = schedMentor();
    $past = schedSlot($mentor, '2026-09-28', '19:00', '20:00');
    $upcoming = schedSlot($mentor, '2026-10-02', '19:00', '20:00');
    $today = schedSlot($mentor, '2026-09-30', '15:00', '16:00');

    $response = $this->actingAs($mentor)->get(route('dashboard.mentor'))
        ->assertOk()
        ->assertViewHas('mySlots', fn ($slots) => $slots->pluck('id')->all() === [$today->id, $upcoming->id, $past->id])
        ->assertSeeText('Kedaluwarsa')
        ->assertSeeText('Slot 1-on-1');

    expect($past->fresh()->status)->toBe('expired');

    // Kartu HP ikut dirender: tiap slot punya status & aksinya sendiri.
    $html = $response->getContent();
    expect(substr_count($html, 'Kosong (Tersedia)'))->toBe(4)   // 2 slot aktif × (tabel + kartu)
        ->and(substr_count($html, 'Hapus Slot'))->toBe(4)
        ->and($html)->toContain('md:hidden');
});

// ── Admin: KTM bisa direview dari HP ──────────────────────────────────────

it('shows the KTM and WhatsApp links on both desktop table and mobile cards', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    $pending = schedMentor(['status' => 'pending', 'name' => 'Calon Mentor'], ['ktm_path' => 'mentor-ktm/contoh.jpg', 'whatsapp' => '0812-3456-7890']);
    $noKtm = schedMentor(['status' => 'pending', 'name' => 'Tanpa KTM']);

    $html = $this->actingAs($admin)->get(route('admin.verification.index'))->assertOk()->getContent();

    expect(substr_count($html, route('admin.mentors.ktm', $pending->id)))->toBe(2)
        ->and(substr_count($html, 'https://wa.me/081234567890'))->toBeGreaterThanOrEqual(2)
        ->and($html)->toContain('KTM tidak ada');

    $this->actingAs($admin)->get(route('dashboard.admin'))
        ->assertOk()
        ->assertSee('href="'.route('admin.mentors.ktm', $pending->id).'"', false)
        ->assertSeeText('Review Berkas');
});

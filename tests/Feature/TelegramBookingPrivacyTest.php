<?php

use App\Models\LiveClass;
use App\Models\MentoringBooking;
use App\Models\MentorProfile;
use App\Models\MentorSlot;
use App\Models\StudentProfile;
use App\Models\User;
use App\Notifications\BookingApprovedMailNotification;
use App\Notifications\BookingRejectedMailNotification;
use Illuminate\Contracts\Mail\Factory as MailFactory;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

// Grup Telegram = pengumuman kelas (publik). Email siswa + website = feedback booking Private 1-on-1 (privat).

const TBP_GROUP_CHAT_ID = '-1000000000000'; // TELEGRAM_GROUP_CHAT_ID di phpunit.xml
const TBP_GROUP_URL = 'https://t.me/+SlpIKZ3RUisyODBl';

function tbpMentor(): User
{
    $mentor = User::factory()->create(['role' => 'mentor', 'status' => 'active', 'name' => 'Mentor Publik']);
    MentorProfile::create(['user_id' => $mentor->id, 'whatsapp' => '0812', 'university' => 'Universitas Airlangga', 'major' => 'Kedokteran', 'high_school' => 'SMAN 1 Magetan']);

    return $mentor;
}

function tbpStudent(string $name, string $email): User
{
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'name' => $name, 'email' => $email]);
    StudentProfile::create(['user_id' => $student->id, 'school' => 'SMAN 2 Magetan']);

    return $student->fresh();
}

/** Slot dibuat langsung (tanpa form), jadi tidak ada pengumuman Telegram. */
function tbpSlot(User $mentor, string $link = 'https://meet.google.com/private-abc'): MentorSlot
{
    return MentorSlot::create([
        'mentor_id' => $mentor->id, 'date' => now()->addDays(2)->toDateString(), 'start_time' => '19:00', 'end_time' => '20:00',
        'topic' => 'Strategi UTBK', 'meeting_link' => $link, 'status' => 'kosong',
    ]);
}

/** Mentor membuat slot Private 1-on-1 lewat form (memicu pengumuman ke grup). */
function tbpCreatePrivateSlot($test, User $mentor, string $link, string $start = '19:00'): MentorSlot
{
    $test->actingAs($mentor)->post(route('mentor.teman-nalar.slot.store'), [
        'session_type' => '1on1', 'date' => now()->addDays(2)->toDateString(), 'start_time' => $start,
        'end_time' => date('H:i', strtotime($start) + 3600), 'topic' => 'Strategi UTBK', 'meeting_link' => $link,
    ])->assertSessionHasNoErrors()->assertSessionHas('success');

    return MentorSlot::latest('id')->first();
}

function tbpBook($test, User $student, MentorSlot $slot): MentoringBooking
{
    $test->actingAs($student)
        ->post(route('siswa.teman-nalar.booking.store'), ['mentor_slot_id' => $slot->id, 'topic' => 'Curhat'])
        ->assertSessionHasNoErrors();

    return MentoringBooking::where('student_id', $student->id)->latest('id')->first();
}

/** Teks semua pesan yang dikirim ke Telegram selama test. */
function tbpTelegramTexts(): Collection
{
    return collect(Http::recorded())
        ->filter(fn ($pair) => str_contains($pair[0]->url(), 'api.telegram.org'))
        ->map(fn ($pair) => $pair[0]['text'])
        ->values();
}

/** Email yang benar-benar terkirim (mailer "array" di phpunit.xml) ke satu alamat. */
function tbpMailsTo(string $email): Collection
{
    return app('mailer')->getSymfonyTransport()->messages()
        ->map(fn ($sent) => $sent->getOriginalMessage())
        ->filter(fn ($message) => collect($message->getTo())->contains(fn ($address) => $address->getAddress() === $email))
        ->values();
}

// ── Publikasi kelas ke grup Telegram ──────────────────────────────────────

it('announces a new Private 1-on-1 class to the Telegram group', function () {
    Http::fake();
    $mentor = tbpMentor();

    tbpCreatePrivateSlot($this, $mentor, 'https://meet.google.com/private-abc');

    Http::assertSentCount(1);
    Http::assertSent(fn (Request $request) => str_contains($request->url(), 'api.telegram.org')
        && $request['chat_id'] === TBP_GROUP_CHAT_ID
        && str_contains($request['text'], 'SESI PRIVATE 1-ON-1 TERSEDIA')
        && str_contains($request['text'], 'Mentor Publik')
        && str_contains($request['text'], 'Strategi UTBK')
        && str_contains($request['text'], 'Universitas Airlangga')
        && str_contains($request['text'], route('siswa.teman-nalar.booking.create', $mentor->id)));
});

it('announces a new Belajar Bersama class to the Telegram group', function () {
    Http::fake();

    $this->actingAs(tbpMentor())->post(route('mentor.teman-nalar.slot.store'), [
        'session_type' => 'live_class', 'title' => 'Bedah Soal Penalaran Umum', 'description' => 'Latihan bersama',
        'live_date' => now()->addDays(3)->toDateString(), 'live_time' => '19:30', 'meeting_link' => 'https://meet.google.com/open-class',
    ])->assertSessionHasNoErrors()->assertSessionHas('success');

    expect(LiveClass::count())->toBe(1);
    Http::assertSentCount(1);
    Http::assertSent(fn (Request $request) => $request['chat_id'] === TBP_GROUP_CHAT_ID
        && str_contains($request['text'], 'BELAJAR BERSAMA BARU TERSEDIA')
        && str_contains($request['text'], 'Bedah Soal Penalaran Umum')
        && str_contains($request['text'], 'Mentor Publik')
        // Belajar Bersama kelas terbuka: tautan Meet-nya tetap diumumkan seperti sebelumnya.
        && str_contains($request['text'], 'https://meet.google.com/open-class'));
});

// ── Booking Private 1-on-1: privat ────────────────────────────────────────

it('saves a Private 1-on-1 booking as pending without any Telegram message', function () {
    Http::fake();

    $booking = tbpBook($this, tbpStudent('Siswa Rahasia', 'siswa.rahasia@gmail.com'), tbpSlot(tbpMentor()));

    expect($booking->status)->toBe('pending');
    Http::assertNothingSent();
});

it('emails only the booking student when the mentor approves, without any Telegram message', function () {
    Http::fake();
    $mentor = tbpMentor();
    $student = tbpStudent('Siswa Rahasia', 'siswa.rahasia@gmail.com');
    $booking = tbpBook($this, $student, tbpSlot($mentor, 'https://meet.google.com/private-abc'));

    $this->actingAs($mentor)->patch(route('mentor.teman-nalar.booking.approve', $booking->id))
        ->assertSessionHas('success', 'Booking berhasil disetujui!');

    expect($booking->fresh()->status)->toBe('approved');
    Http::assertNothingSent();

    $mails = tbpMailsTo('siswa.rahasia@gmail.com');
    expect($mails)->toHaveCount(1)
        ->and($mails[0]->getSubject())->toBe('Booking Private 1-on-1 Disetujui — KawanNalar')
        ->and($mails[0]->getHtmlBody())
        ->toContain('Kak Mentor Publik')
        ->toContain('Curhat')
        ->toContain(now()->addDays(2)->locale('id')->translatedFormat('l, d F Y'))
        ->toContain('19:00 - 20:00 WIB')
        ->toContain('Disetujui')
        ->toContain(route('siswa.teman-nalar.booking.meeting', $booking->id))
        ->not->toContain('meet.google.com');
});

it('emails the student the rejection reason, without any Telegram message', function () {
    Http::fake();
    Notification::fake();
    $mentor = tbpMentor();
    $student = tbpStudent('Siswa Rahasia', 'siswa.rahasia@gmail.com');
    $booking = MentoringBooking::create(['student_id' => $student->id, 'mentor_id' => $mentor->id, 'mentor_slot_id' => tbpSlot($mentor)->id, 'topic' => 'Curhat', 'status' => 'pending']);

    $this->actingAs($mentor)->patch(route('mentor.teman-nalar.booking.reject', $booking->id), ['reason' => 'Jadwal bentrok dengan ujian kampus.'])
        ->assertSessionHas('success');

    expect($booking->fresh()->status)->toBe('rejected');
    Http::assertNothingSent();
    Notification::assertSentToTimes($student, BookingRejectedMailNotification::class, 1);
    Notification::assertSentTo($student, BookingRejectedMailNotification::class, fn ($notification, array $channels) => $channels === ['mail']
        && $notification->reason === 'Jadwal bentrok dengan ujian kampus.');
    Notification::assertNotSentTo($student, BookingApprovedMailNotification::class);
});

it('keeps the approval successful when the student email cannot be sent', function () {
    Http::fake();
    Exceptions::fake();
    $mentor = tbpMentor();
    $student = tbpStudent('Siswa Rahasia', 'siswa.rahasia@gmail.com');
    $booking = MentoringBooking::create(['student_id' => $student->id, 'mentor_id' => $mentor->id, 'mentor_slot_id' => tbpSlot($mentor)->id, 'topic' => 'Curhat', 'status' => 'pending']);
    $this->mock(MailFactory::class, fn ($mock) => $mock->shouldReceive('mailer')->andThrow(new RuntimeException('SMTP tidak tersedia')));

    $this->actingAs($mentor)->patch(route('mentor.teman-nalar.booking.approve', $booking->id))
        ->assertSessionHas('success', 'Booking berhasil disetujui!');

    expect($booking->fresh()->status)->toBe('approved')
        ->and($student->notifications()->count())->toBe(1);
    Exceptions::assertReported(RuntimeException::class);
    Http::assertNothingSent();
});

// ── Tautan meeting privat ─────────────────────────────────────────────────

it('lets only the booking student and their mentor open the private meeting link', function () {
    $mentor = tbpMentor();
    $owner = tbpStudent('Siswa A', 'siswa.a@gmail.com');
    $booking = MentoringBooking::create(['student_id' => $owner->id, 'mentor_id' => $mentor->id, 'mentor_slot_id' => tbpSlot($mentor, 'https://meet.google.com/private-a')->id, 'topic' => 'Curhat', 'status' => 'approved']);

    $this->get(route('siswa.teman-nalar.booking.meeting', $booking->id))->assertRedirect(route('login'));
    $this->actingAs($owner)->get(route('siswa.teman-nalar.booking.meeting', $booking->id))->assertRedirect('https://meet.google.com/private-a');
    $this->actingAs($mentor)->get(route('mentor.teman-nalar.booking.meeting', $booking->id))->assertRedirect('https://meet.google.com/private-a');
    $this->actingAs(tbpMentor())->get(route('mentor.teman-nalar.booking.meeting', $booking->id))->assertForbidden();
});

it('blocks student A from the private meeting and booking data of student B', function () {
    $mentor = tbpMentor();
    $studentA = tbpStudent('Siswa A', 'siswa.a@gmail.com');
    $studentB = tbpStudent('Siswa B', 'siswa.b@gmail.com');
    $bookingB = MentoringBooking::create(['student_id' => $studentB->id, 'mentor_id' => $mentor->id, 'mentor_slot_id' => tbpSlot($mentor, 'https://meet.google.com/private-b')->id, 'topic' => 'Curhat', 'status' => 'approved']);

    $this->actingAs($studentA)->get(route('siswa.teman-nalar.booking.meeting', $bookingB->id))->assertForbidden();

    $this->actingAs($studentA)->get(route('siswa.teman-nalar.index'))
        ->assertOk()
        ->assertDontSee('private-b')
        ->assertDontSee(route('siswa.teman-nalar.booking.meeting', $bookingB->id))
        ->assertDontSee('siswa.b@gmail.com');
});

// ── Isi pesan grup Telegram untuk Private 1-on-1 ──────────────────────────

it('keeps every Telegram message of the Private 1-on-1 flow free of student data and meeting links', function () {
    Http::fake();
    $mentor = tbpMentor();
    $approved = tbpStudent('Siswa Disetujui', 'disetujui@gmail.com');
    $rejected = tbpStudent('Siswa Ditolak', 'ditolak@gmail.com');

    $bookingA = tbpBook($this, $approved, tbpCreatePrivateSlot($this, $mentor, 'https://meet.google.com/private-a', '19:00'));
    $bookingB = tbpBook($this, $rejected, tbpCreatePrivateSlot($this, $mentor, 'https://meet.google.com/private-b', '20:30'));
    $this->actingAs($mentor)->patch(route('mentor.teman-nalar.booking.approve', $bookingA->id))->assertSessionHas('success');
    $this->actingAs($mentor)->patch(route('mentor.teman-nalar.booking.reject', $bookingB->id), ['reason' => 'Jadwal bentrok.'])->assertSessionHas('success');

    $texts = tbpTelegramTexts();

    // Hanya 2 pengumuman kelas; booking, persetujuan, dan penolakan tidak menghasilkan pesan.
    expect($texts)->toHaveCount(2);
    foreach ($texts as $text) {
        expect($text)->toContain('SESI PRIVATE 1-ON-1 TERSEDIA')
            ->not->toContain('Siswa Disetujui')->not->toContain('Siswa Ditolak')
            ->not->toContain('disetujui@gmail.com')->not->toContain('ditolak@gmail.com')
            ->not->toContain('SMAN 2 Magetan')
            ->not->toContain('DISETUJUI')->not->toContain('DITOLAK')->not->toContain('Jadwal bentrok')
            ->not->toContain('meet.google.com')
            ->not->toContain('/meeting');
    }
});

// ── Email persetujuan ─────────────────────────────────────────────────────

it('renders the approval email with the session details and a protected meeting link', function () {
    $student = tbpStudent('Siswa Rahasia', 'siswa.rahasia@gmail.com');
    $mail = (new BookingApprovedMailNotification('Mentor Publik', 'Curhat', 'Senin, 12 Oktober 2026', '19:00 - 20:00 WIB', 'http://localhost/teman-nalar/booking/7/meeting'))
        ->toMail($student);

    expect($mail->subject)->toBe('Booking Private 1-on-1 Disetujui — KawanNalar')
        ->and((string) $mail->render())
        ->toContain('Halo, Siswa Rahasia')
        ->toContain('Kak Mentor Publik')
        ->toContain('Curhat')
        ->toContain('Senin, 12 Oktober 2026')
        ->toContain('19:00 - 20:00 WIB')
        ->toContain('Disetujui')
        ->toContain('Masuk ke Sesi')
        ->toContain('http://localhost/teman-nalar/booking/7/meeting');
});

it('renders the rejection email with the reason and a link to pick another schedule', function () {
    $mail = (new BookingRejectedMailNotification('Mentor Publik', 'Curhat', 'Senin, 12 Oktober 2026', '19:00 - 20:00 WIB', 'Jadwal bentrok dengan ujian kampus.'))
        ->toMail(tbpStudent('Siswa Rahasia', 'siswa.rahasia@gmail.com'));

    expect($mail->subject)->toBe('Booking Private 1-on-1 Ditolak — KawanNalar')
        ->and((string) $mail->render())
        ->toContain('Ditolak')
        ->toContain('Jadwal bentrok dengan ujian kampus.')
        ->toContain('Cari Jadwal Lain')
        ->toContain(route('siswa.teman-nalar.index'));
});

// ── Landing Page: CTA grup Telegram ───────────────────────────────────────

it('shows the Telegram community CTA on the landing page and opens it in a new tab', function () {
    expect(config('services.telegram.group_url'))->toBe(TBP_GROUP_URL);

    $html = $this->get(route('landing'))->assertOk()->assertSeeText('Gabung Telegram')->getContent();

    preg_match_all('/<a href="'.preg_quote(TBP_GROUP_URL, '/').'"[^>]*>/', $html, $links);
    expect($links[0])->toHaveCount(1)
        ->and($links[0][0])->toContain('target="_blank"')->toContain('rel="noopener noreferrer"');
});

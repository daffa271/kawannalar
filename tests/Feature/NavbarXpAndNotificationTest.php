<?php

use App\Models\MentorProfile;
use App\Models\MentorSlot;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\BookingApprovedNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

function navbarMentor(array $attributes = []): User
{
    $mentor = User::factory()->create(array_merge(['role' => 'mentor', 'status' => 'active'], $attributes));

    MentorProfile::create([
        'user_id' => $mentor->id,
        'whatsapp' => '08123456789',
        'university' => 'PTN KawanNalar',
        'major' => 'Informatika',
        'high_school' => 'SMA Magetan',
    ]);

    return $mentor;
}

it('shows the real xp_points on the navbar and the dashboard', function (int $xp) {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'xp_points' => $xp]);

    $response = $this->actingAs($student)->get(route('dashboard.siswa'));

    $response->assertOk()
        ->assertViewHas('xp', $xp)
        ->assertDontSeeText('1,250 XP')
        ->assertDontSeeText('1250 XP');

    // Pill navbar, badge di menu profil, dan kartu "Poin" dashboard.
    expect(substr_count($response->getContent(), "{$xp} XP"))->toBeGreaterThanOrEqual(3);
})->with([40, 30, 10]);

it('keeps navbar, dashboard, and Uji Nalar XP in sync after a quiz', function () {
    $mentor = navbarMentor();
    $subject = Subject::create(['name' => 'Fisika', 'code' => 'FIS']);
    $quiz = Quiz::create([
        'mentor_id' => $mentor->id,
        'subject_id' => $subject->id,
        'class_level' => '12',
        'title' => 'Paket Uji XP',
        'total_questions' => 5,
        'status' => 'approved',
    ]);
    $questions = collect(range(1, 5))->map(fn ($i) => Question::create([
        'quiz_id' => $quiz->id,
        'question_text' => "Soal {$i}",
        'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E',
        'correct_answer' => 'C',
        'order' => $i,
    ]));
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'xp_points' => 10]);

    $this->actingAs($student)
        ->post(route('siswa.uji-nalar.submit', $quiz), ['answers' => $questions->mapWithKeys(fn ($q) => [$q->id => 'C'])->all()])
        ->assertOk()
        ->assertViewHas('xpGained', 50);

    $this->actingAs($student->fresh())->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertViewHas('xp', 60)
        ->assertSeeText('60 XP');

    $this->actingAs($student->fresh())->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertViewHas('currentXp', 60)
        ->assertSeeText('60 XP');
});

it('shows the mentor XP card from xp_points instead of the hardcoded 1,250', function () {
    $this->actingAs(navbarMentor(['xp_points' => 0]))->get(route('dashboard.mentor'))
        ->assertOk()
        ->assertSeeText('Level 1 · Inspirator Aktif')
        ->assertSeeText('0 XP')
        ->assertDontSeeText('1,250 XP')
        ->assertDontSeeText('Level 4');

    $this->actingAs(navbarMentor(['xp_points' => 1500]))->get(route('dashboard.mentor'))
        ->assertOk()
        ->assertSeeText('Level 2 · Inspirator Aktif')
        ->assertSeeText('1,500 XP')
        ->assertSee('width: 50%', false);
});

it('shows only the authenticated user notifications, newest first, with the unread count', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $other = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->travelTo(Carbon::parse('2026-09-28 18:30', 'Asia/Jakarta'));
    $student->notify(new BookingApprovedNotification('Mentor Pertama', 'Strategi UTBK', '28 Sep 2026 19:00 WIB'));
    $this->travelTo(Carbon::parse('2026-09-28 18:45', 'Asia/Jakarta'));
    $student->notify(new BookingApprovedNotification('Mentor Kedua', 'Curhat', '30 Sep 2026 19:00 WIB'));
    $this->travelTo(Carbon::parse('2026-09-28 18:50', 'Asia/Jakarta'));
    $other->notify(new BookingApprovedNotification('Mentor Rahasia', 'Rasionalisasi SNBP', '01 Okt 2026 10:00 WIB'));

    $html = $this->actingAs($student)->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertSee('aria-label="Notifikasi (2 belum dibaca)"', false)
        ->assertSeeText('2 baru')
        ->assertSeeText('Booking Mentoring Disetujui')
        ->assertSeeText('28 Sep 2026, 18:45 WIB')
        ->assertSeeText('28 Sep 2026, 18:30 WIB')
        ->assertDontSeeText('Mentor Rahasia')
        ->getContent();

    expect(strpos($html, 'Kak Mentor Kedua'))->toBeLessThan(strpos($html, 'Kak Mentor Pertama'));

    // Hanya notifikasi yang belum dibaca yang dihitung.
    $student->notifications()->get()->last()->markAsRead();

    $this->actingAs($student)->get(route('dashboard.siswa'))
        ->assertSee('aria-label="Notifikasi (1 belum dibaca)"', false)
        ->assertSeeText('1 baru')
        ->assertSeeText('Kak Mentor Pertama');
});

it('shows an empty state and no mock notifications when the user has none', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertSee('aria-label="Notifikasi"', false)
        ->assertDontSee('belum dibaca')
        ->assertSeeText('Belum ada notifikasi.')
        ->assertDontSeeText('Mentor baru di Ruang Nalar')
        ->assertDontSeeText('Tryout UTBK tersedia!')
        ->assertDontSeeText('+50 XP dari quiz hari ini');
});

it('shows the booking notification created by the existing flow in the mentor navbar only', function () {
    Http::fake();
    $mentor = navbarMentor();
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'name' => 'Siswa Pemesan']);
    StudentProfile::create(['user_id' => $student->id, 'school' => 'SMAN 2 Magetan']);
    $slot = MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '19:00',
        'end_time' => '20:00',
        'topic' => 'Strategi UTBK',
        'status' => 'kosong',
    ]);

    $this->actingAs($student)
        ->post(route('siswa.teman-nalar.booking.store'), ['mentor_slot_id' => $slot->id, 'topic' => 'Strategi UTBK'])
        ->assertSessionHasNoErrors();

    $this->actingAs($mentor)->get(route('dashboard.mentor'))
        ->assertOk()
        ->assertSee('aria-label="Notifikasi (1 belum dibaca)"', false)
        ->assertSeeText('Booking Bimbingan Baru')
        ->assertSeeText('Siswa Siswa Pemesan (SMAN 2 Magetan)');

    $this->actingAs($student)->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertSeeText('Belum ada notifikasi.');
});

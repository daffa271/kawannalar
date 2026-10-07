<?php

use App\Models\MentoringBooking;
use App\Models\MentorProfile;
use App\Models\MentorSlot;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Carbon;

const MD_EMOJI = '/[\x{1F000}-\x{1FAFF}\x{2300}-\x{23FF}\x{2600}-\x{27BF}\x{25A0}-\x{25FF}]/u';

function mdMentor(array $attributes = [], ?array $profile = []): User
{
    $mentor = User::factory()->create(array_merge(['role' => 'mentor', 'status' => 'active', 'name' => 'Raka Mentor'], $attributes));

    if ($profile !== null) {
        MentorProfile::create(array_merge([
            'user_id' => $mentor->id,
            'whatsapp' => '081234567890',
            'university' => 'Universitas Brawijaya',
            'major' => 'Teknik Elektro',
            'high_school' => 'SMAN 2 Magetan',
        ], $profile));
    }

    return $mentor->fresh();
}

function mdApprovedSession(User $mentor, string $studentName, string $date): MentoringBooking
{
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'name' => $studentName]);
    StudentProfile::create(['user_id' => $student->id, 'school' => 'SMAN 1 Magetan']);
    $slot = MentorSlot::create([
        'mentor_id' => $mentor->id, 'date' => $date, 'start_time' => '19:00', 'end_time' => '20:00',
        'topic' => 'Curhat', 'meeting_link' => 'https://meet.google.com/abc-defg-hij', 'status' => 'terisi',
    ]);

    return MentoringBooking::create([
        'student_id' => $student->id, 'mentor_id' => $mentor->id, 'mentor_slot_id' => $slot->id,
        'topic' => 'Curhat', 'status' => 'approved',
    ]);
}

/** Isi halaman tanpa navbar, sidebar, dan footer aplikasi. */
function mdContent(string $html): string
{
    $start = strpos($html, '<main');

    return substr($html, $start, strrpos($html, '<footer') - $start);
}

it('renders the mentor dashboard without emoji and with vector icons', function () {
    $mentor = mdMentor();
    mdApprovedSession($mentor, 'Siswa Terjadwal', now()->addDays(2)->toDateString());
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $slot = MentorSlot::create(['mentor_id' => $mentor->id, 'date' => now()->addDays(3)->toDateString(), 'start_time' => '10:00', 'end_time' => '11:00', 'topic' => 'Strategi UTBK', 'status' => 'kosong']);
    MentoringBooking::create(['student_id' => $student->id, 'mentor_id' => $mentor->id, 'mentor_slot_id' => $slot->id, 'topic' => 'Strategi UTBK', 'status' => 'pending']);

    $content = mdContent($this->actingAs($mentor)->get(route('dashboard.mentor'))->assertOk()->getContent());

    expect(preg_match(MD_EMOJI, $content))->toBe(0)
        ->and(substr_count($content, 'viewBox="0 -960 960 960"'))->toBeGreaterThan(20)
        ->and($content)->not->toContain('href="#"')
        ->and(preg_match('/class="[^"]*bg-cta\b[^"]*text-white|class="[^"]*text-white[^"]*bg-cta\b/', $content))->toBe(0);
});

it('shows only the real mentor profile in the hero, never sample campus text', function () {
    $this->actingAs(mdMentor())->get(route('dashboard.mentor'))
        ->assertOk()
        ->assertSeeText('Selamat Datang, Kak Raka!')
        ->assertSeeText('Teknik Elektro · Universitas Brawijaya · Alumni SMAN 2 Magetan');

    $this->actingAs(mdMentor(['name' => 'Tanpa Profil'], null))->get(route('dashboard.mentor'))
        ->assertOk()
        ->assertSeeText('Lengkapi profil mentor kamu')
        ->assertSee('href="'.route('profile.edit').'"', false)
        ->assertDontSeeText('PENS Surabaya')
        ->assertDontSeeText('D4 Teknik Informatika')
        ->assertDontSeeText('Alumni SMAN 1 Magetan');
});

it('lists upcoming sessions by schedule, nearest first, with the protected meeting link', function () {
    Carbon::setTestNow(Carbon::parse('2026-10-03 08:00', 'Asia/Jakarta'));
    $mentor = mdMentor();
    // Dibuat urut: yang jadwalnya paling jauh dibooking paling akhir.
    mdApprovedSession($mentor, 'Siswa Minggu Depan', '2026-10-10');
    $nearest = mdApprovedSession($mentor, 'Siswa Besok', '2026-10-04');
    mdApprovedSession($mentor, 'Siswa Bulan Depan', '2026-11-01');

    $this->actingAs($mentor)->get(route('dashboard.mentor'))
        ->assertOk()
        ->assertViewHas('statApproved', 3)
        ->assertSeeInOrder(['Siswa Besok', 'Siswa Minggu Depan', 'Siswa Bulan Depan'])
        ->assertSeeText('3 Terjadwal')
        ->assertSee('href="'.route('mentor.teman-nalar.booking.meeting', $nearest->id).'"', false);

    Carbon::setTestNow();
});

it('disables every create action, including empty states, for a suspended mentor', function () {
    $content = mdContent($this->actingAs(mdMentor(['is_suspended' => true]))->get(route('dashboard.mentor'))->assertOk()->getContent());

    expect($content)->toContain('Akun Anda sedang ditangguhkan')
        ->not->toContain('href="'.route('mentor.uji-nalar.create').'"')
        ->not->toContain('href="'.route('mentor.ruang-nalar.create').'"')
        ->not->toContain('@click="showModal = true"');
});

it('keeps the dashboard split into section partials', function () {
    $index = file_get_contents(resource_path('views/pages/mentor/dashboard/index.blade.php'));

    foreach (['hero', 'stats', 'tabs', 'tab-slot', 'tab-quizzes', 'tab-modules'] as $partial) {
        expect($index)->toContain("@include('pages.mentor.dashboard.partials.{$partial}')");
    }
    expect(substr_count($index, "\n"))->toBeLessThan(50);
});

it('uses icons instead of emoji in the shared mentoring components on the Sesi Mentoring page', function () {
    $mentor = mdMentor();
    mdApprovedSession($mentor, 'Siswa Terjadwal', now()->addDays(2)->toDateString());

    $content = mdContent($this->actingAs($mentor)->get(route('mentor.teman-nalar.index'))->assertOk()->getContent());

    expect($content)->toContain('Link Meet')
        ->toContain('Bimbingan 1-on-1')
        ->and(preg_match('/🎥|👤|✅|❌/u', $content))->toBe(0);
});

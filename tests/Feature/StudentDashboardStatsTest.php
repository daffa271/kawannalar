<?php

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\User;

it('shows no stale UTBK countdown, fake focus hours, or fake streak on the student dashboard', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertViewMissing('focusHours')
        ->assertViewMissing('streakDays')
        ->assertSeeText('Tetap semangat, terus melangkah menuju kampus impianmu!')
        ->assertDontSeeText('120 Hari')
        ->assertDontSeeText('UTBK SNBT 2026')
        ->assertDontSeeText('12.5 Jam')
        ->assertDontSeeText('Streak');
});

it('shows the real number of Uji Nalar attempts instead of focus hours', function () {
    $mentor = User::factory()->create(['role' => 'mentor', 'status' => 'active']);
    $subject = Subject::create(['name' => 'Matematika', 'code' => 'MTK']);
    $quiz = Quiz::create([
        'mentor_id' => $mentor->id,
        'subject_id' => $subject->id,
        'class_level' => '11',
        'title' => 'Paket Uji Dashboard',
        'total_questions' => 5,
        'status' => 'approved',
    ]);
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $other = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    foreach ([$student, $student, $other] as $user) {
        QuizAttempt::create(['user_id' => $user->id, 'quiz_id' => $quiz->id, 'score' => 60, 'correct_count' => 3, 'total_xp_gained' => 30]);
    }

    $this->actingAs($student)->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertViewHas('quizCount', 2)
        ->assertSeeText('Latihan Uji Nalar')
        ->assertSeeText('2 Kuis');
});

it('computes the sidebar profile completion from the filled student profile fields', function (array $profile, int $percent, string $label) {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    if ($profile) {
        StudentProfile::create(['user_id' => $student->id] + $profile);
    }

    $this->actingAs($student)->get(route('dashboard.siswa'))
        ->assertOk()
        ->assertSeeText($label)
        ->assertSeeText("{$percent}%")
        ->assertSee("width: {$percent}%", false)
        ->assertDontSeeText('80%');
})->with([
    'profil lengkap' => [[
        'whatsapp' => '081234567890', 'school' => 'SMAN 1 Magetan', 'grade' => 'Kelas 12',
        'target_major' => 'Kedokteran', 'target_university' => 'UNAIR',
    ], 100, 'Profil lengkap'],
    'profil sebagian (3 dari 5)' => [[
        'whatsapp' => '081234567890', 'school' => 'SMAN 1 Magetan', 'grade' => 'Kelas 12',
    ], 60, 'Lengkapi profilmu'],
    'tanpa profil' => [[], 0, 'Lengkapi profilmu'],
]);

it('opens Nalar Kilat 5/10/15 from the dashboard links and shows that many approved questions', function () {
    $mentor = User::factory()->create(['role' => 'mentor', 'status' => 'active']);
    $subject = Subject::create(['name' => 'Matematika', 'code' => 'MTK']);
    foreach (['approved' => 15, 'pending' => 5] as $status => $count) {
        $quiz = Quiz::create([
            'mentor_id' => $mentor->id,
            'subject_id' => $subject->id,
            'class_level' => '11',
            'title' => "Paket {$status}",
            'total_questions' => $count,
            'status' => $status,
        ]);
        foreach (range(1, $count) as $i) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Soal {$status} {$i}",
                'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E',
                'correct_answer' => 'A',
                'order' => $i,
            ]);
        }
    }
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active'])->fresh();

    $html = $this->actingAs($student)->get(route('dashboard.siswa'))->assertOk()->getContent();

    // Bagian Nalar Kilat di dashboard: tidak boleh ada href="#" lagi.
    $section = substr($html, strpos($html, 'Nalar Kilat</h2>'));
    $section = substr($section, 0, strpos($section, '</section>'));
    expect($section)->not->toContain('href="#"')
        ->toContain('href="'.route('siswa.uji-nalar.index').'#nalar-kilat"');

    foreach ([5, 10, 15] as $jumlah) {
        $href = route('siswa.uji-nalar.kilat', ['jumlah' => $jumlah]);
        expect($section)->toContain('href="'.$href.'"');

        $this->actingAs($student)->get($href)
            ->assertOk()
            ->assertViewHas('questions', fn ($questions) => $questions->count() === $jumlah
                && $questions->every(fn ($question) => $question->quiz->status === 'approved'))
            ->assertSeeText("Nalar Kilat — {$jumlah} Soal")
            ->assertSeeText("Soal 1 dari {$jumlah}")
            ->assertSeeText("Soal {$jumlah}")
            ->assertDontSee('Soal pending');
    }
});

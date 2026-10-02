<?php

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('excludes dummy accounts, uses the profile school, and ranks students globally', function () {
    $dummy = User::factory()->create([
        'name' => 'Akun Dummy Leaderboard',
        'email' => 'dummy@kawannalar.test',
        'role' => 'siswa',
        'status' => 'active',
        'xp_points' => 9999,
    ]);

    $top = User::factory()
        ->count(10)
        ->state(new Sequence(fn (Sequence $sequence) => ['xp_points' => 1000 - $sequence->index]))
        ->create(['role' => 'siswa', 'status' => 'active']);
    StudentProfile::create(['user_id' => $top[4]->id, 'school' => 'SMAN 3 Magetan']);

    $me = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'xp_points' => 50]);

    $this->actingAs($me)
        ->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertViewHas('userRank', 11)
        ->assertViewHas('leaderboard', fn ($board) => $board->count() === 10
            && ! $board->contains('id', $dummy->id)
            && $board->first()->is($top->first()))
        ->assertSeeText('SMAN 3 Magetan')
        ->assertDontSeeText('Akun Dummy Leaderboard');

    expect($dummy->leaderboardRank())->toBeNull();
});

it('adds XP after a quiz submission and updates the leaderboard rank', function () {
    $mentor = User::factory()->create(['role' => 'mentor', 'status' => 'active']);
    $subject = Subject::create(['name' => 'Matematika', 'code' => 'MTK']);
    $quiz = Quiz::create([
        'mentor_id' => $mentor->id,
        'subject_id' => $subject->id,
        'class_level' => '11',
        'title' => 'Paket Uji Turunan',
        'total_questions' => 5,
        'status' => 'approved',
    ]);
    $questions = collect(range(1, 5))->map(fn ($i) => Question::create([
        'quiz_id' => $quiz->id,
        'question_text' => "Soal {$i}",
        'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E',
        'correct_answer' => 'B',
        'order' => $i,
    ]));

    User::factory()->create(['role' => 'siswa', 'status' => 'active', 'xp_points' => 20]);
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active', 'xp_points' => 0]);
    expect($student->leaderboardRank())->toBe(2);

    $answers = $questions->values()->mapWithKeys(fn ($q, $i) => [$q->id => $i < 3 ? 'B' : 'A'])->all();

    $this->actingAs($student)
        ->post(route('siswa.uji-nalar.submit', $quiz), ['answers' => $answers])
        ->assertOk()
        ->assertViewHas('xpGained', 30);

    expect($student->fresh()->xp_points)->toEqual(30);
    expect(QuizAttempt::where('user_id', $student->id)->value('correct_count'))->toEqual(3);
    expect($student->fresh()->leaderboardRank())->toBe(1);
});

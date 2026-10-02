<?php

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Subject;
use App\Models\User;

function moderationQuiz(User $mentor, string $prefix, int $count): Quiz
{
    $subject = Subject::firstOrCreate(['code' => 'MTK'], ['name' => 'Matematika']);
    $quiz = Quiz::create([
        'mentor_id' => $mentor->id,
        'subject_id' => $subject->id,
        'class_level' => '11',
        'title' => "Paket {$prefix}",
        'total_questions' => $count,
        'status' => 'approved',
    ]);

    foreach (range(1, $count) as $i) {
        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => "{$prefix} soal {$i}",
            'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E',
            'correct_answer' => 'A',
            'order' => $i,
        ]);
    }

    return $quiz;
}

/**
 * Kondisi mirip data nyata: dua paket layak (5 & 10 soal) dan satu paket uji (5 soal) yang sudah approved + 1 attempt.
 */
function moderationSetup(): array
{
    $mentor = User::factory()->create(['role' => 'mentor', 'status' => 'active']);

    return [
        'admin' => User::factory()->create(['role' => 'admin', 'status' => 'active']),
        'mentor' => $mentor,
        'student' => User::factory()->create(['role' => 'siswa', 'status' => 'active'])->fresh(),
        'valid1' => moderationQuiz($mentor, 'TURUNAN', 5),
        'valid2' => moderationQuiz($mentor, 'FISIKA', 10),
        'testing' => tap(moderationQuiz($mentor, 'UJICOBA', 5), function (Quiz $quiz) {
            QuizAttempt::create(['user_id' => User::factory()->create(['role' => 'siswa'])->id, 'quiz_id' => $quiz->id, 'score' => 20, 'correct_count' => 1, 'total_xp_gained' => 10]);
        }),
    ];
}

const UNAPPROVE_REASON = 'Data paket masih berupa soal uji/testing dan belum layak dipublikasikan.';

it('shows a Batalkan Persetujuan action for approved packages on the admin page', function () {
    ['admin' => $admin, 'testing' => $testing] = moderationSetup();

    $this->actingAs($admin)->get(route('admin.quizzes.index'))
        ->assertOk()
        ->assertSeeText('Batalkan Persetujuan')
        ->assertSee("targetQuizId = {$testing->id}", false)
        ->assertSeeText('Soal dan riwayat pengerjaan siswa tidak dihapus.');
});

it('lets an admin unapprove an approved package through the existing reject workflow', function () {
    ['admin' => $admin, 'mentor' => $mentor, 'valid1' => $valid1, 'valid2' => $valid2, 'testing' => $testing] = moderationSetup();

    $this->actingAs($admin)
        ->from(route('admin.quizzes.index'))
        ->patch(route('admin.quizzes.reject', $testing), ['reason' => UNAPPROVE_REASON])
        ->assertRedirect(route('admin.quizzes.index'))
        ->assertSessionHas('status');

    $testing->refresh();
    expect($testing->status)->toBe('rejected')
        ->and($testing->rejection_reason)->toBe(UNAPPROVE_REASON)
        ->and($testing->title)->toBe('Paket UJICOBA')
        ->and($testing->mentor_id)->toBe($mentor->id)
        ->and($testing->questions()->count())->toBe(5)
        ->and(QuizAttempt::where('quiz_id', $testing->id)->count())->toBe(1)
        ->and($valid1->fresh()->status)->toBe('approved')
        ->and($valid2->fresh()->status)->toBe('approved');

    // Tidak lagi tercantum di daftar paket disetujui milik admin.
    $this->actingAs($admin)->get(route('admin.quizzes.index'))
        ->assertOk()
        ->assertViewHas('approvedQuizzes', fn ($quizzes) => ! $quizzes->contains('id', $testing->id));

    // Mentor pemilik melihat alasan penolakan (perilaku existing).
    $this->actingAs($mentor)->get(route('mentor.uji-nalar.show', $testing))
        ->assertOk()
        ->assertSeeText(UNAPPROVE_REASON);
});

it('removes a rejected package from Bank Soal, Flashcard, and Nalar Kilat', function () {
    ['admin' => $admin, 'student' => $student, 'testing' => $testing] = moderationSetup();

    $this->actingAs($admin)->patch(route('admin.quizzes.reject', $testing), ['reason' => UNAPPROVE_REASON]);

    $this->actingAs($student)->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertViewHas('bankSoalQuizzes', fn ($quizzes) => $quizzes->count() === 2 && ! $quizzes->contains('id', $testing->id))
        ->assertViewHas('approvedQuestionCount', 15)
        ->assertViewHas('flashcardQuestions', fn ($cards) => $cards->count() === 10
            && $cards->every(fn ($card) => ! str_starts_with($card['question_text'], 'UJICOBA')))
        ->assertDontSee('UJICOBA soal');

    foreach ([5, 10, 15] as $jumlah) {
        $this->actingAs($student)->get(route('siswa.uji-nalar.kilat', ['jumlah' => $jumlah]))
            ->assertOk()
            ->assertViewHas('questions', fn ($questions) => $questions->count() === $jumlah
                && $questions->every(fn ($question) => $question->quiz_id !== $testing->id && $question->quiz->status === 'approved'))
            ->assertDontSee('UJICOBA soal');
    }

    $this->actingAs($student)->get(route('siswa.uji-nalar.show', $testing))->assertForbidden();
});

it('forbids non-admin users from unapproving a package', function (string $role) {
    ['mentor' => $mentor, 'student' => $student, 'testing' => $testing] = moderationSetup();
    $user = $role === 'mentor' ? $mentor : $student;

    $this->actingAs($user)
        ->patch(route('admin.quizzes.reject', $testing), ['reason' => UNAPPROVE_REASON])
        ->assertForbidden();

    expect($testing->fresh()->status)->toBe('approved')
        ->and($testing->fresh()->rejection_reason)->toBeNull();
})->with(['mentor pemilik paket' => 'mentor', 'siswa' => 'siswa']);

<?php

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

function practiceMentor(): User
{
    return User::factory()->create(['role' => 'mentor', 'status' => 'active']);
}

function practiceStudent(array $attributes = []): User
{
    // fresh(): seperti user login sungguhan, atribut default DB (xp_points = 0) ikut dimuat.
    return User::factory()->create(array_merge(['role' => 'siswa', 'status' => 'active'], $attributes))->fresh();
}

function practiceQuiz(User $mentor, string $status, int $count, string $prefix): Quiz
{
    $subject = Subject::firstOrCreate(['code' => 'MTK'], ['name' => 'Matematika']);
    $quiz = Quiz::create([
        'mentor_id' => $mentor->id,
        'subject_id' => $subject->id,
        'class_level' => '11',
        'title' => "Paket {$prefix}",
        'total_questions' => $count,
        'status' => $status,
    ]);

    foreach (range(1, $count) as $i) {
        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => "{$prefix} soal {$i}",
            'option_a' => "{$prefix}-{$i} opsi A",
            'option_b' => "{$prefix}-{$i} opsi B",
            'option_c' => "{$prefix}-{$i} opsi C",
            'option_d' => "{$prefix}-{$i} opsi D",
            'option_e' => "{$prefix}-{$i} opsi E",
            'correct_answer' => 'B',
            'explanation' => "Pembahasan {$prefix} {$i}",
            'order' => $i,
        ]);
    }

    return $quiz;
}

it('builds the flashcards from 10 random approved questions with answer and explanation', function () {
    $mentor = practiceMentor();
    practiceQuiz($mentor, 'approved', 12, 'APPROVED');
    practiceQuiz($mentor, 'pending', 5, 'PENDING');
    practiceQuiz($mentor, 'rejected', 5, 'REJECTED');

    $this->actingAs(practiceStudent())->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertViewHas('approvedQuestionCount', 12)
        ->assertViewHas('flashcardQuestions', function ($cards) {
            return $cards->count() === 10 && $cards->every(function ($card) {
                return preg_match('/^APPROVED soal (\d+)$/', $card['question_text'], $m)
                    && $card['answer'] === "B. APPROVED-{$m[1]} opsi B"
                    && $card['explanation'] === "Pembahasan APPROVED {$m[1]}";
            });
        })
        ->assertSeeText('Mulai Flashcard')
        ->assertSeeText('Belajar cepat dari soal-soal yang telah diverifikasi.')
        ->assertDontSeeText('+5 XP')
        ->assertDontSee('PENDING soal')
        ->assertDontSee('REJECTED soal');
});

it('shows a flashcard empty state when no question is approved yet', function () {
    practiceQuiz(practiceMentor(), 'pending', 5, 'PENDING');

    $this->actingAs(practiceStudent())->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertViewHas('approvedQuestionCount', 0)
        ->assertSeeText('Belum ada soal yang disetujui.')
        ->assertDontSeeText('Mulai Flashcard');
});

it('starts Nalar Kilat with the requested number of approved questions', function (int $jumlah) {
    $mentor = practiceMentor();
    practiceQuiz($mentor, 'approved', 15, 'APPROVED');
    practiceQuiz($mentor, 'pending', 5, 'PENDING');
    practiceQuiz($mentor, 'rejected', 5, 'REJECTED');

    $this->actingAs(practiceStudent())->get(route('siswa.uji-nalar.kilat', $jumlah))
        ->assertOk()
        ->assertViewHas('questions', fn ($questions) => $questions->count() === $jumlah
            && $questions->every(fn ($question) => $question->quiz->status === 'approved'))
        ->assertSeeText("Nalar Kilat — {$jumlah} Soal")
        ->assertSee(route('siswa.uji-nalar.kilat.submit'), false)
        ->assertSee('timeLeft: '.($jumlah * 60), false)
        ->assertDontSee('PENDING soal')
        ->assertDontSee('REJECTED soal');

    expect(session('uji_nalar.kilat_question_ids'))->toHaveCount($jumlah);
})->with([5, 10, 15]);

it('links the Nalar Kilat start button to the practice route', function () {
    practiceQuiz(practiceMentor(), 'approved', 5, 'APPROVED');

    $this->actingAs(practiceStudent())->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertSeeText('Latihan singkat 5, 10, atau 15 soal.')
        ->assertSeeText('Latihan berdasarkan paket soal.')
        ->assertSee('href="'.route('siswa.uji-nalar.kilat', 5).'"', false)
        ->assertDontSee('?kilat=', false);
});

it('shows a clear message when there are not enough approved questions', function () {
    $mentor = practiceMentor();
    practiceQuiz($mentor, 'approved', 7, 'APPROVED');
    practiceQuiz($mentor, 'pending', 10, 'PENDING');
    $student = practiceStudent();

    $this->actingAs($student)->get(route('siswa.uji-nalar.kilat', 10))
        ->assertRedirect(route('siswa.uji-nalar.index').'#nalar-kilat')
        ->assertSessionHas('kilat_error');
    expect(session('uji_nalar.kilat_question_ids'))->toBeNull();

    $this->actingAs($student)->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertSeeText('Belum tersedia cukup soal untuk latihan 10 soal.');

    $this->actingAs($student)->get(route('siswa.uji-nalar.kilat', 5))->assertOk();
    $this->actingAs($student)->get('/uji-nalar/kilat/7')->assertNotFound();
});

it('grades Nalar Kilat with the existing scoring and XP rule, once per session', function () {
    $mentor = practiceMentor();
    practiceQuiz($mentor, 'approved', 10, 'APPROVED');
    $pendingQuestionId = practiceQuiz($mentor, 'pending', 3, 'PENDING')->questions()->value('id');
    $student = practiceStudent(['xp_points' => 20]);

    $this->actingAs($student)->get(route('siswa.uji-nalar.kilat', 5))->assertOk();
    $served = session('uji_nalar.kilat_question_ids');

    // 3 benar, 2 salah; jawaban untuk soal yang tidak ditampilkan diabaikan.
    $answers = collect($served)->mapWithKeys(fn ($id, $i) => [$id => $i < 3 ? 'B' : 'A'])->all();
    $answers[$pendingQuestionId] = 'B';

    $this->actingAs($student)->post(route('siswa.uji-nalar.kilat.submit'), ['answers' => $answers])
        ->assertOk()
        ->assertViewHas('total', 5)
        ->assertViewHas('correctCount', 3)
        ->assertViewHas('score', 60)
        ->assertViewHas('xpGained', 30)
        ->assertSeeText('Nalar Kilat — 5 Soal')
        ->assertSee(route('siswa.uji-nalar.kilat', 5), false);

    expect($student->fresh()->xp_points)->toEqual(50)
        ->and(QuizAttempt::count())->toBe(0)
        ->and(session('uji_nalar.kilat_question_ids'))->toBeNull();

    // Mengumpulkan ulang sesi yang sama tidak menambah XP.
    $this->actingAs($student->fresh())->post(route('siswa.uji-nalar.kilat.submit'), ['answers' => $answers])
        ->assertRedirect(route('siswa.uji-nalar.index').'#nalar-kilat')
        ->assertSessionHas('kilat_error');

    expect($student->fresh()->xp_points)->toEqual(50);
});

it('keeps the existing Bank Soal quiz flow: attempt, XP, and result page', function () {
    $quiz = practiceQuiz(practiceMentor(), 'approved', 5, 'PAKET');
    $student = practiceStudent();

    $this->actingAs($student)->get(route('siswa.uji-nalar.show', $quiz))
        ->assertOk()
        ->assertSeeText('Paket PAKET')
        ->assertSeeText('Matematika · Kelas 11')
        ->assertSee('action="'.route('siswa.uji-nalar.submit', $quiz).'"', false)
        ->assertSee('timeLeft: 300', false);

    $answers = $quiz->questions->mapWithKeys(fn ($question) => [$question->id => 'B'])->all();

    $this->actingAs($student)->post(route('siswa.uji-nalar.submit', $quiz), ['answers' => $answers])
        ->assertOk()
        ->assertViewHas('xpGained', 50)
        ->assertSeeText('Paket PAKET')
        ->assertSeeText('Ulangi Quiz')
        ->assertSee(route('siswa.uji-nalar.show', $quiz), false);

    $attempt = QuizAttempt::sole();
    expect($attempt->quiz_id)->toBe($quiz->id)
        ->and($attempt->score)->toEqual(100)
        ->and($attempt->correct_count)->toEqual(5)
        ->and($attempt->total_xp_gained)->toEqual(50)
        ->and($student->fresh()->xp_points)->toEqual(50);
});

it('uses mentor question packages for Flashcard and Nalar Kilat after approval, without separate flashcards', function () {
    $subject = Subject::firstOrCreate(['code' => 'MTK'], ['name' => 'Matematika']);
    $mentor = practiceMentor();
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    $student = practiceStudent();

    $this->actingAs($mentor)->get(route('mentor.uji-nalar.index'))
        ->assertOk()
        ->assertSeeText('Bank Soal adalah sumber utama soal. Setelah disetujui Admin, soal dapat digunakan secara otomatis untuk Bank Soal, Flashcard, dan Nalar Kilat.')
        ->assertSeeText('Flashcard dibuat otomatis dari soal yang telah disetujui.')
        ->assertSeeText('Nalar Kilat mengambil soal secara acak dari Bank Soal yang telah disetujui.');

    $this->actingAs($mentor)->get(route('mentor.uji-nalar.create'))
        ->assertOk()
        ->assertSeeText('tidak perlu membuat flashcard terpisah');

    $this->actingAs($mentor)->post(route('mentor.uji-nalar.store'), [
        'title' => 'Paket Mentor Baru',
        'subject_id' => $subject->id,
        'class_level' => '10',
        'total_questions' => '5',
        'questions' => collect(range(1, 5))->map(fn ($i) => [
            'question_text' => "Soal mentor {$i}",
            'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E',
            'correct_answer' => 'C',
            'explanation' => "Pembahasan mentor {$i}",
        ])->all(),
    ])->assertRedirect(route('mentor.uji-nalar.index'));

    $quiz = Quiz::sole();
    expect($quiz->status)->toBe('pending');

    // Belum disetujui → belum dipakai.
    $this->actingAs($student)->get(route('siswa.uji-nalar.index'))
        ->assertViewHas('approvedQuestionCount', 0)
        ->assertViewHas('flashcardQuestions', fn ($cards) => $cards->isEmpty());
    $this->actingAs($student)->get(route('siswa.uji-nalar.kilat', 5))->assertSessionHas('kilat_error');

    $this->actingAs($admin)->patch(route('admin.quizzes.approve', $quiz))->assertRedirect();

    // Setelah disetujui → otomatis menjadi Flashcard dan sumber Nalar Kilat.
    $this->actingAs($student)->get(route('siswa.uji-nalar.index'))
        ->assertViewHas('approvedQuestionCount', 5)
        ->assertViewHas('flashcardQuestions', fn ($cards) => $cards->count() === 5
            && $cards->every(fn ($card) => str_starts_with($card['question_text'], 'Soal mentor') && $card['answer'] === 'C. C'));
    $this->actingAs($student)->get(route('siswa.uji-nalar.kilat', 5))
        ->assertOk()
        ->assertSeeText('Soal mentor 1');

    expect(Schema::hasTable('flashcards'))->toBeFalse();
});

it('marks Simulasi UTBK as coming soon without fake claims', function () {
    $this->actingAs(practiceStudent())->get(route('siswa.uji-nalar.index'))
        ->assertOk()
        ->assertSeeText('Simulasi UTBK')
        ->assertSeeText('Segera Hadir')
        ->assertDontSeeText('Simulasi Riil')
        ->assertDontSeeText('195 Menit')
        ->assertDontSeeText('IRT')
        ->assertDontSeeText('+100 XP')
        ->assertDontSeeText('tryout UTBK riil');
});

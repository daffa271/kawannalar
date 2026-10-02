<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UjiNalarController extends Controller
{
    /**
     * ID soal Nalar Kilat yang sedang dikerjakan — hanya soal ini yang dinilai saat submit.
     */
    private const KILAT_SESSION_KEY = 'uji_nalar.kilat_question_ids';

    /**
     * Halaman utama Uji Nalar untuk siswa.
     */
    public function index()
    {
        $user = Auth::user();

        // ── Leaderboard (top 10 by xp_points, tanpa akun dummy) ─────────────
        $leaderboard = User::query()
            ->leaderboard()
            ->with('studentProfile:id,user_id,school')
            ->take(10)
            ->get(['id', 'name', 'xp_points']);

        // Peringkat global, termasuk bila siswa berada di luar top 10.
        $userRank = $user->leaderboardRank();

        // ── Performa Saya ────────────────────────────────────────────────────
        $attempts = QuizAttempt::where('user_id', $user->id)->get();
        $totalAnswered = $attempts->sum(fn($a) => $a->quiz ? $a->quiz->total_questions : 0);
        $totalCorrect  = $attempts->sum('correct_count');
        $accuracy      = $totalAnswered > 0
            ? round(($totalCorrect / $totalAnswered) * 100)
            : 0;

        // XP Progress ke level berikutnya (tiap 1000 XP = 1 level)
        $currentXp  = $user->xp_points;
        $level       = intdiv($currentXp, 1000) + 1;
        $xpThisLevel = $currentXp % 1000;

        // ── Flashcard: 10 soal approved acak dari Bank Soal (tanpa tabel flashcard) ──
        $flashcardQuestions = $this->approvedQuestions()
            ->inRandomOrder()
            ->take(10)
            ->get()
            ->map(fn (Question $question) => [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'answer' => $question->correct_answer.'. '.$question->{'option_'.strtolower($question->correct_answer)},
                'explanation' => $question->explanation,
            ]);

        // ── Nalar Kilat: jumlah soal approved yang bisa diambil acak ─────────
        $approvedQuestionCount = $this->approvedQuestions()->count();

        // ── Subjects & Kelas untuk filter ────────────────────────────────────
        $subjects = Subject::orderBy('name')->get();
        $classes  = ['10', '11', '12'];

        // ── Approved quizzes untuk Bank Soal Sekolah ─────────────────────────
        $bankSoalQuizzes = Quiz::approved()
            ->with('subject')
            ->latest()
            ->take(20)
            ->get();

        return view('pages.siswa.uji-nalar.index', compact(
            'user',
            'leaderboard',
            'userRank',
            'accuracy',
            'totalAnswered',
            'currentXp',
            'level',
            'xpThisLevel',
            'flashcardQuestions',
            'approvedQuestionCount',
            'subjects',
            'classes',
            'bankSoalQuizzes',
        ));
    }

    /**
     * Halaman quiz aktif (mengerjakan soal).
     */
    public function show(Quiz $quiz)
    {
        abort_unless($quiz->status === 'approved', 403, 'Paket soal belum tersedia.');

        $questions = $quiz->questions()->orderBy('order')->get();

        return view('pages.siswa.uji-nalar.show', compact('quiz', 'questions'));
    }

    /**
     * Submit jawaban dan hitung XP.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        abort_unless($quiz->status === 'approved', 403);

        $answers  = (array) $request->input('answers', []);   // ['question_id' => 'A']
        $questions = $quiz->questions()->orderBy('order')->get();

        $grading = $this->gradeAnswers($questions, $answers);

        // Simpan attempt
        QuizAttempt::create([
            'user_id'       => Auth::id(),
            'quiz_id'       => $quiz->id,
            'score'         => $grading['score'],
            'correct_count' => $grading['correctCount'],
            'total_xp_gained' => $grading['xpGained'],
        ]);

        // Update user XP
        Auth::user()->increment('xp_points', $grading['xpGained']);

        return view('pages.siswa.uji-nalar.result', ['quiz' => $quiz] + $grading);
    }

    /**
     * Nalar Kilat: latihan 5/10/15 soal acak dari soal yang paketnya sudah approved.
     */
    public function kilat(Request $request, int $jumlah)
    {
        $questions = $this->approvedQuestions()
            ->inRandomOrder()
            ->take($jumlah)
            ->get();

        if ($questions->count() < $jumlah) {
            return redirect()->to(route('siswa.uji-nalar.index').'#nalar-kilat')
                ->with('kilat_error', "Belum tersedia cukup soal untuk latihan {$jumlah} soal. Saat ini baru ada {$questions->count()} soal terverifikasi.");
        }

        $request->session()->put(self::KILAT_SESSION_KEY, $questions->pluck('id')->all());

        return view('pages.siswa.uji-nalar.show', [
            'questions' => $questions->values(),
            'pageTitle' => "Nalar Kilat — {$jumlah} Soal",
            'pageMeta' => 'Soal acak dari Bank Soal yang telah disetujui',
            'timeLimit' => $jumlah * 60,
            'submitUrl' => route('siswa.uji-nalar.kilat.submit'),
        ]);
    }

    /**
     * Submit Nalar Kilat — penilaian & XP sama dengan paket soal.
     */
    public function submitKilat(Request $request)
    {
        // pull(): satu sesi latihan hanya bisa dikumpulkan sekali.
        $questionIds = $request->session()->pull(self::KILAT_SESSION_KEY, []);

        if (empty($questionIds)) {
            return redirect()->to(route('siswa.uji-nalar.index').'#nalar-kilat')
                ->with('kilat_error', 'Sesi Nalar Kilat sudah berakhir atau sudah dikumpulkan. Silakan mulai latihan baru.');
        }

        $questions = $this->approvedQuestions()
            ->whereIn('id', $questionIds)
            ->get()
            ->sortBy(fn (Question $question) => array_search($question->id, $questionIds))
            ->values();

        $grading = $this->gradeAnswers($questions, (array) $request->input('answers', []));

        // quiz_attempts tidak dicatat: soal berasal dari beberapa paket, sedangkan
        // quiz_attempts.quiz_id wajib menunjuk satu paket.
        Auth::user()->increment('xp_points', $grading['xpGained']);

        return view('pages.siswa.uji-nalar.result', $grading + [
            'pageTitle' => 'Nalar Kilat — '.count($questionIds).' Soal',
            'pageMeta' => 'Soal acak dari Bank Soal yang telah disetujui',
            'retryUrl' => route('siswa.uji-nalar.kilat', count($questionIds)),
            'retryLabel' => '⚡ Latihan Kilat Lagi',
        ]);
    }

    /**
     * Soal yang paket soalnya sudah disetujui admin — sumber Flashcard & Nalar Kilat.
     */
    private function approvedQuestions(): Builder
    {
        return Question::query()->whereHas('quiz', fn ($quiz) => $quiz->approved());
    }

    /**
     * Penilaian jawaban untuk paket soal dan Nalar Kilat (10 XP per soal benar).
     */
    private function gradeAnswers(Collection $questions, array $answers): array
    {
        $correctCount = 0;
        $results      = [];

        foreach ($questions as $question) {
            $given   = strtoupper($answers[$question->id] ?? '');
            $correct = strtoupper($question->correct_answer);
            $isRight = $given === $correct;

            if ($isRight) {
                $correctCount++;
            }

            $results[] = [
                'question'   => $question,
                'given'      => $given,
                'correct'    => $correct,
                'is_correct' => $isRight,
            ];
        }

        $total     = $questions->count();
        $score     = $total > 0 ? round(($correctCount / $total) * 100) : 0;
        $xpGained  = $correctCount * 10; // 10 XP per soal benar

        return compact('results', 'score', 'correctCount', 'total', 'xpGained');
    }
}

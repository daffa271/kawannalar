<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjiNalarController extends Controller
{
    /**
     * Halaman utama Uji Nalar untuk siswa.
     */
    public function index()
    {
        $user = Auth::user();

        // ── Leaderboard (top 10 by xp_points) ───────────────────────────────
        $leaderboard = User::where('role', 'siswa')
            ->orderByDesc('xp_points')
            ->take(10)
            ->get(['id', 'name', 'school_name', 'xp_points']);

        $userRank = $leaderboard->search(fn($u) => $u->id === $user->id);
        $userRank = $userRank !== false ? $userRank + 1 : null;

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

        // ── Flashcard Data (soal approved acak) ─────────────────────────────
        $flashcardQuestions = \App\Models\Question::whereHas('quiz', fn($q) => $q->where('status', 'approved'))
            ->inRandomOrder()
            ->take(10)
            ->get(['id', 'question_text', 'correct_answer', 'explanation']);

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

        $answers  = $request->input('answers', []);   // ['question_id' => 'A']
        $questions = $quiz->questions()->orderBy('order')->get();

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

        // Simpan attempt
        QuizAttempt::create([
            'user_id'       => Auth::id(),
            'quiz_id'       => $quiz->id,
            'score'         => $score,
            'correct_count' => $correctCount,
            'total_xp_gained' => $xpGained,
        ]);

        // Update user XP
        Auth::user()->increment('xp_points', $xpGained);

        return view('pages.siswa.uji-nalar.result', compact(
            'quiz', 'results', 'score', 'correctCount', 'total', 'xpGained'
        ));
    }
}

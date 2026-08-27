<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UjiNalarController extends Controller
{
    /**
     * Dashboard Mentor Uji Nalar — list semua paket soal milik mentor.
     */
    public function index()
    {
        $mentor = Auth::user();

        $quizzes = Quiz::where('mentor_id', $mentor->id)
            ->with('subject')
            ->latest()
            ->get();

        $subjects = Subject::orderBy('name')->get();

        return view('pages.mentor.uji-nalar.index', compact('quizzes', 'subjects'));
    }

    /**
     * Form buat paket soal baru.
     */
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('pages.mentor.uji-nalar.create', compact('subjects'));
    }

    /**
     * Simpan paket soal beserta semua pertanyaannya.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'subject_id'      => ['required', 'exists:subjects,id'],
            'class_level'     => ['required', 'in:10,11,12'],
            'total_questions' => ['required', 'in:5,10,15'],
            'questions'               => ['required', 'array'],
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.option_a'    => ['required', 'string'],
            'questions.*.option_b'    => ['required', 'string'],
            'questions.*.option_c'    => ['required', 'string'],
            'questions.*.option_d'    => ['required', 'string'],
            'questions.*.option_e'    => ['required', 'string'],
            'questions.*.correct_answer' => ['required', 'in:A,B,C,D,E'],
            'questions.*.explanation' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $quiz = Quiz::create([
                'mentor_id'       => Auth::id(),
                'subject_id'      => $validated['subject_id'],
                'class_level'     => $validated['class_level'],
                'title'           => $validated['title'],
                'total_questions' => $validated['total_questions'],
                'status'          => 'pending',
            ]);

            foreach ($validated['questions'] as $i => $q) {
                Question::create(array_merge($q, [
                    'quiz_id' => $quiz->id,
                    'order'   => $i + 1,
                ]));
            }
        });

        return redirect()->route('mentor.uji-nalar.index')
            ->with('status', 'Paket soal berhasil dikirim untuk ditinjau admin!');
    }

    /**
     * Detail paket soal.
     */
    public function show(Quiz $quiz)
    {
        abort_unless($quiz->mentor_id === Auth::id(), 403);
        $quiz->load('questions', 'subject');
        return view('pages.mentor.uji-nalar.show', compact('quiz'));
    }
}

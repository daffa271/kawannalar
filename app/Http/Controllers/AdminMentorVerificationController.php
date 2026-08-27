<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminMentorVerificationController extends Controller
{
    public function dashboard(): View
    {
        return view('pages.admin.dashboard.index', [
            'totalStudents'      => User::where('role', 'siswa')->count(),
            'activeMentors'      => User::where('role', 'mentor')->where('status', 'active')->count(),
            'pendingMentorCount' => User::where('role', 'mentor')->where('status', 'pending')->count(),
            'pendingModuleCount' => Module::where('status', 'pending')->count(),
            'approvedModuleCount'=> Module::where('status', 'approved')->count(),
            'pendingQuizCount'   => Quiz::where('status', 'pending')->count(),
            'approvedQuizCount'  => Quiz::where('status', 'approved')->count(),
            'recentMentors'      => User::where('role', 'mentor')->where('status', 'pending')->with('mentorProfile')->latest()->take(3)->get(),
            'recentQuizzes'      => Quiz::where('status', 'pending')->with('mentor:id,name', 'subject')->latest()->take(3)->get(),
        ]);
    }

    public function index(): View
    {
        $pendingMentors = User::query()
            ->where('role', 'mentor')
            ->where('status', 'pending')
            ->with('mentorProfile')
            ->latest()
            ->get();

        $pendingModules = Module::query()
            ->where('status', 'pending')
            ->with('uploader:id,name,role')
            ->latest()
            ->get();

        return view('pages.admin.verification.index', [
            'pendingMentors' => $pendingMentors,
            'pendingModules' => $pendingModules,
            'totalStudents' => User::where('role', 'siswa')->count(),
            'activeMentors' => User::where('role', 'mentor')->where('status', 'active')->count(),
            'pendingMentorCount' => $pendingMentors->count(),
            'pendingModuleCount' => $pendingModules->count(),
        ]);
    }

    public function approve(Request $request, int $id): RedirectResponse
    {
        $mentor = $this->pendingMentor($id);
        $mentor->update([
            'status' => 'active',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Mentor berhasil disetujui! Mentor kini sudah bisa login.');
    }

    public function reject(int $id): RedirectResponse
    {
        $mentor = $this->pendingMentor($id);
        $mentor->update([
            'status' => 'rejected',
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('status', 'Pendaftaran mentor berhasil ditolak.');
    }

    public function approveModule(Request $request, Module $module): RedirectResponse
    {
        $module->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Modul berhasil disetujui dan dapat dilihat siswa.');
    }

    public function rejectModule(Request $request, Module $module): RedirectResponse
    {
        $module->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Modul berhasil ditolak dan tidak akan tampil di ruang berbagi.');
    }

    public function moduleFile(Module $module): BinaryFileResponse
    {
        abort_unless(Storage::disk('public')->exists($module->file_path), 404);

        return response()->file(Storage::disk('public')->path($module->file_path));
    }

    public function ktm(int $id): BinaryFileResponse
    {
        $mentor = $this->pendingMentor($id);
        $path = $mentor->mentorProfile?->ktm_path;

        abort_unless($path && Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path));
    }

    // ─── Quiz Moderation ──────────────────────────────────────────────────────

    public function quizzes(): View
    {
        $pendingQuizzes = Quiz::where('status', 'pending')
            ->with(['mentor:id,name', 'subject', 'questions'])
            ->latest()
            ->get();

        $approvedQuizzes = Quiz::where('status', 'approved')
            ->with(['mentor:id,name', 'subject'])
            ->latest()
            ->take(10)
            ->get();

        return view('pages.admin.quizzes.index', compact('pendingQuizzes', 'approvedQuizzes'));
    }

    public function approveQuiz(Request $request, Quiz $quiz): RedirectResponse
    {
        $quiz->update(['status' => 'approved']);
        return back()->with('status', "Paket soal \"{$quiz->title}\" berhasil disetujui dan kini tersedia untuk siswa.");
    }

    public function rejectQuiz(Request $request, Quiz $quiz): RedirectResponse
    {
        $quiz->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->input('reason'),
        ]);
        return back()->with('status', "Paket soal \"{$quiz->title}\" ditolak.");
    }

    private function pendingMentor(int $id): User
    {
        return User::query()
            ->whereKey($id)
            ->where('role', 'mentor')
            ->where('status', 'pending')
            ->with('mentorProfile')
            ->firstOrFail();
    }
}

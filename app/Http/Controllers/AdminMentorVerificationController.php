<?php

namespace App\Http\Controllers;

use App\Models\Module;
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
            'totalStudents' => User::where('role', 'siswa')->count(),
            'activeMentors' => User::where('role', 'mentor')->where('status', 'active')->count(),
            'pendingMentorCount' => User::where('role', 'mentor')->where('status', 'pending')->count(),
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

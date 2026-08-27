<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Module;

class AdminUserController extends Controller
{
    public function siswaIndex()
    {
        $students = User::where('role', 'siswa')
            ->with('studentProfile')
            ->latest()
            ->paginate(15);

        return view('pages.admin.users.siswa.index', compact('students'));
    }

    public function mentorIndex()
    {
        $mentors = User::where('role', 'mentor')
            ->with(['mentorProfile', 'modules'])
            ->latest()
            ->paginate(15);

        return view('pages.admin.users.mentor.index', compact('mentors'));
    }

    public function toggleSuspend($id)
    {
        $user = User::findOrFail($id);
        $user->is_suspended = !$user->is_suspended;
        $user->save();

        $status = $user->is_suspended ? 'ditangguhkan' : 'diaktifkan kembali';
        return redirect()->back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }
}

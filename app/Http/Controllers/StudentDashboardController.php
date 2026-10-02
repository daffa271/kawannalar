<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user()->load('studentProfile');
        // Hanya modul yang sudah disetujui Admin (pending/ditolak tidak boleh tampil ke siswa lain).
        $popularModules = Module::query()
            ->where('status', 'approved')
            ->with('uploader:id,name')
            ->latest('download_count')
            ->latest()
            ->limit(2)
            ->get();

        return view('pages.siswa.dashboard.index', [
            'student' => $student,
            'popularModules' => $popularModules,
            'totalDownloads' => Module::sum('download_count'),
            'quizCount' => QuizAttempt::where('user_id', $student->id)->count(),
            'xp' => (int) $student->xp_points,
            'upcomingMentoring' => $this->upcomingMentoring($student),
            'leaderboard' => $this->leaderboard(),
        ]);
    }

    private function upcomingMentoring(User $student): ?\App\Models\MentoringBooking
    {
        return \App\Models\MentoringBooking::where('mentoring_bookings.student_id', $student->id)
            ->whereIn('mentoring_bookings.status', ['approved', 'pending'])
            ->whereHas('slot', function ($query) {
                $today = now()->toDateString();
                $currentTime = now()->toTimeString();
                $query->where('date', '>', $today)
                      ->orWhere(function ($q) use ($today, $currentTime) {
                          $q->where('date', '=', $today)
                            ->where('start_time', '>=', $currentTime);
                      });
            })
            ->with(['mentor.mentorProfile', 'slot'])
            ->join('mentor_slots', 'mentoring_bookings.mentor_slot_id', '=', 'mentor_slots.id')
            ->orderBy('mentor_slots.date')
            ->orderBy('mentor_slots.start_time')
            ->select('mentoring_bookings.*')
            ->first();
    }

    private function leaderboard(): array
    {
        return User::query()
            ->leaderboard()
            ->with('studentProfile:id,user_id,school')
            ->limit(3)
            ->get(['id', 'name', 'xp_points'])
            ->map(fn(User $user): array => [
                'name' => $user->name,
                'school' => $user->studentProfile?->school ?? '-',
                'xp' => (int) $user->xp_points,
            ])->all();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user()->load('studentProfile');
        $popularModules = Module::query()->with('uploader:id,name')->latest('download_count')->limit(2)->get();

        return view('pages.siswa.dashboard.index', [
            'student' => $student,
            'popularModules' => $popularModules,
            'totalDownloads' => Module::sum('download_count'),
            'focusHours' => $this->focusHours($student),
            'streakDays' => $student->streak_days ?? 7,
            'xp' => $student->xp ?? 1250,
            'upcomingMentoring' => $this->upcomingMentoring($student),
            'leaderboard' => $this->leaderboard(),
        ]);
    }

    private function focusHours(User $student): string
    {
        if (Schema::hasColumn('users', 'focus_minutes')) {
            return number_format(((int) $student->focus_minutes) / 60, 1);
        }

        return '12.5';
    }

    private function upcomingMentoring(User $student): ?array
    {
        if (! Schema::hasTable('bookings') || ! method_exists($student, 'bookings')) {
            return null;
        }

        $booking = $student->bookings()
            ->with('mentor.mentorProfile')
            ->where('starts_at', '>=', now())
            ->whereIn('status', ['confirmed', 'scheduled'])
            ->oldest('starts_at')
            ->first();

        return $booking?->toArray();
    }

    private function leaderboard(): array
    {
        if (Schema::hasColumn('users', 'xp')) {
            return User::query()->where('role', 'siswa')->orderByDesc('xp')->limit(3)->get(['name', 'xp'])->map(fn(User $user): array => [
                'name' => $user->name,
                'school' => $user->studentProfile?->school ?? 'Sekolah Magetan',
                'xp' => (int) $user->xp,
            ])->all();
        }

        return [
            ['name' => 'Nadia Putri', 'school' => 'SMAN 1 Magetan', 'xp' => 1860],
            ['name' => 'Rizky Maulana', 'school' => 'SMAN 2 Magetan', 'xp' => 1540],
            ['name' => 'Dimas Ardiansyah', 'school' => 'SMAN 1 Magetan', 'xp' => 1320],
        ];
    }
}

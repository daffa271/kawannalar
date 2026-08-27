<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LiveClass;
use App\Models\MentorSlot;

class TemanNalarController extends Controller
{
    public function index()
    {
        $mentors = User::where('role', 'mentor')
            ->where('status', 'active')
            ->with(['mentorProfile'])
            ->get()
            ->each(function ($mentor) {
                $mentor->available_slots = MentorSlot::where('mentor_id', $mentor->id)
                    ->where('status', 'kosong')
                    ->where('date', '>=', now()->toDateString())
                    ->orderBy('date')
                    ->orderBy('start_time')
                    ->get(['id', 'date', 'start_time', 'end_time']);
            });

        $liveClasses = LiveClass::with('mentor.mentorProfile')
            ->where('schedule_time', '>=', now())
            ->orderBy('schedule_time')
            ->get();

        $myBookings = \App\Models\MentoringBooking::where('student_id', auth()->id())
            ->with(['mentor', 'slot'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.siswa.teman-nalar.index', compact('mentors', 'liveClasses', 'myBookings'));
    }
}

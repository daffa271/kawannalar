<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\MentoringBooking;
use App\Models\MentorSlot;
use App\Models\Module;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mentor  = Auth::user();
        $profile = $mentor->mentorProfile;

        // ── Quiz stats ──────────────────────────────────────────────────
        $myQuizzes     = Quiz::where('mentor_id', $mentor->id)->with('subject')->latest()->get();
        $pendingCount  = $myQuizzes->where('status', 'pending')->count();
        $approvedCount = $myQuizzes->where('status', 'approved')->count();
        $rejectedCount = $myQuizzes->where('status', 'rejected')->count();

        // ── Module stats ─────────────────────────────────────────────────
        $myModules    = Module::where('uploaded_by', $mentor->id)->latest()->get();
        $moduleTayang = $myModules->where('status', 'approved')->count();

        // ── Real slot data (dashboard table) ────────────────────────────
        $mySlots = MentorSlot::where('mentor_id', $mentor->id)
            ->with(['booking.student'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // ── Pending booking requests (sidebar widget) ────────────────────
        $pendingBookings = MentoringBooking::with(['student.studentProfile', 'slot'])
            ->where('mentor_id', $mentor->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        // ── Upcoming / approved sessions (sidebar widget) ────────────────
        $upcomingSessions = MentoringBooking::with(['student', 'slot'])
            ->where('mentor_id', $mentor->id)
            ->where('status', 'approved')
            ->latest()
            ->take(5)
            ->get();

        // ── Quick-stats for stat cards ────────────────────────────────────
        $statPending   = $pendingBookings->count();
        $statApproved  = MentoringBooking::where('mentor_id', $mentor->id)->where('status', 'approved')->count();
        $statSlotFree  = MentorSlot::where('mentor_id', $mentor->id)->where('status', 'kosong')->count();

        return view('pages.mentor.dashboard.index', compact(
            'mentor', 'profile',
            'myQuizzes', 'pendingCount', 'approvedCount', 'rejectedCount',
            'myModules', 'moduleTayang',
            'mySlots',
            'pendingBookings',
            'upcomingSessions',
            'statPending', 'statApproved', 'statSlotFree',
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use App\Models\MentoringBooking;
use App\Models\MentorSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TemanNalarController extends Controller
{
    public function createBooking(User $mentor)
    {
        abort_unless($mentor->role === 'mentor' && $mentor->status === 'active', 404);

        $this->expirePastSessions();

        $mentor->load('mentorProfile');
        $slots = MentorSlot::where('mentor_id', $mentor->id)
            ->where('status', 'kosong')
            ->where('date', '>=', now()->toDateString())
            ->whereDoesntHave('bookings', function ($query) {
                $query->whereIn('status', ['pending', 'approved']);
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('pages.siswa.teman-nalar.booking', compact('mentor', 'slots'));
    }

    public function index(Request $request)
    {
        $this->expirePastSessions();

        $search = trim((string) $request->query('q', ''));
        $university = trim((string) $request->query('university', ''));
        $topic = trim((string) $request->query('topic', ''));

        $mentors = User::where('role', 'mentor')
            ->where('status', 'active')
            ->when($university, fn ($query) => $query->whereHas('mentorProfile', fn ($profile) => $profile->where('university', $university)))
            ->with(['mentorProfile'])
            ->get()
            ->each(function ($mentor) use ($topic) {
                $mentor->available_slots = MentorSlot::where('mentor_id', $mentor->id)
                    ->where('status', 'kosong')
                    ->where('date', '>=', now()->toDateString())
                    ->whereDoesntHave('bookings', function ($query) {
                        $query->whereIn('status', ['pending', 'approved']);
                    })
                    ->when($topic, function ($query) use ($topic) {
                        $standardTopics = ['Rasionalisasi SNBP', 'Strategi UTBK', 'Curhat'];

                        return $topic === 'Lainnya'
                            ? $query->whereNotIn('topic', $standardTopics)
                            : $query->where('topic', 'like', "%{$topic}%");
                    })
                    ->orderBy('date')
                    ->orderBy('start_time')
                    ->get(['id', 'date', 'start_time', 'end_time', 'topic']);
            })
            ->filter(function ($mentor) use ($search) {
                if ($mentor->available_slots->isEmpty()) {
                    return false;
                }

                if ($search === '') {
                    return true;
                }

                $haystack = collect([
                    $mentor->name,
                    $mentor->mentorProfile?->major,
                    $mentor->mentorProfile?->university,
                ])->merge($mentor->available_slots->pluck('topic'));

                return $haystack->contains(fn ($value) => Str::contains((string) $value, $search, true));
            })
            ->values();

        $universities = User::where('role', 'mentor')
            ->where('status', 'active')
            ->whereHas('mentorProfile')
            ->with('mentorProfile:id,user_id,university')
            ->get()
            ->pluck('mentorProfile.university')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $liveClasses = LiveClass::with('mentor.mentorProfile')
            ->where('schedule_time', '>=', now())
            ->orderBy('schedule_time')
            ->get();

        $myBookings = MentoringBooking::where('student_id', Auth::id())
            ->with(['mentor', 'slot'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.siswa.teman-nalar.index', compact('mentors', 'liveClasses', 'myBookings', 'universities', 'search', 'university', 'topic'));
    }

    private function expirePastSessions(): void
    {
        $slots = MentorSlot::whereIn('status', ['kosong', 'terisi'])->get();

        foreach ($slots as $slot) {
            if (Carbon::parse($slot->date.' '.$slot->end_time)->isPast()) {
                $slot->update(['status' => 'expired']);
                $slot->bookings()->whereIn('status', ['pending', 'approved'])->update(['status' => 'expired']);
            }
        }
    }
}

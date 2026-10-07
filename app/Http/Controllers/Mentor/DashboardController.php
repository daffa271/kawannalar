<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\MentoringBooking;
use App\Models\MentorSlot;
use App\Models\Module;
use App\Models\Quiz;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Dashboard mentor = ringkasan. Setiap bagian hanya menampilkan PREVIEW_LIMIT data teratas;
 * daftar lengkapnya ada di halaman fitur (Sesi Mentoring, Buat Soal, Upload Modul).
 * View dipecah per bagian di resources/views/pages/mentor/dashboard/partials.
 */
class DashboardController extends Controller
{
    public const PREVIEW_LIMIT = 5;

    public function index(): View
    {
        $mentor = Auth::user()->load('mentorProfile');

        // Slot yang sudah lewat ditandai expired dulu (sama seperti halaman Sesi Mentoring).
        MentorSlot::expirePastSessions($mentor->id);

        $mySlots = $this->slots($mentor->id);
        $approvedSessions = $this->approvedSessions($mentor->id);
        $pendingBookings = MentoringBooking::with(['student.studentProfile', 'slot'])
            ->where('mentor_id', $mentor->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $myQuizzes = Quiz::where('mentor_id', $mentor->id)->with('subject')->latest()->get();
        $myModules = Module::where('uploaded_by', $mentor->id)->latest()->get();

        return view('pages.mentor.dashboard.index', [
            'mentor' => $mentor,
            'profile' => $mentor->mentorProfile,

            // Kartu statistik
            'statApproved' => $approvedSessions->count(),
            'statPending' => $pendingBookings->count(),
            'moduleTayang' => $myModules->where('status', 'approved')->count(),
            'statSlotFree' => $mySlots->where('status', 'kosong')->count(),

            // Tab Slot 1-on-1 (ringkasan; total untuk tautan "Lihat semua")
            'mySlots' => $mySlots->take(self::PREVIEW_LIMIT),
            'slotTotal' => $mySlots->count(),
            'upcomingSessions' => $approvedSessions->take(self::PREVIEW_LIMIT),
            'pendingBookings' => $pendingBookings->take(self::PREVIEW_LIMIT),

            // Tab Paket Soal & Modul
            'myQuizzes' => $myQuizzes->take(self::PREVIEW_LIMIT),
            'quizTotal' => $myQuizzes->count(),
            'pendingCount' => $myQuizzes->where('status', 'pending')->count(),
            'myModules' => $myModules->take(self::PREVIEW_LIMIT),
            'moduleTotal' => $myModules->count(),
        ]);
    }

    /**
     * Slot aktif (kosong/terisi) terdekat di atas, lalu riwayat terbaru.
     */
    private function slots(int $mentorId): Collection
    {
        return MentorSlot::where('mentor_id', $mentorId)
            ->with(['booking.student'])
            ->orderByRaw("CASE WHEN status IN ('kosong', 'terisi') THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN status IN ('kosong', 'terisi') THEN date END")
            ->orderByRaw("CASE WHEN status IN ('kosong', 'terisi') THEN start_time END")
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get();
    }

    /**
     * Booking yang sudah disetujui, diurutkan dari jadwal sesi terdekat.
     * (Booking yang jadwalnya lewat sudah berstatus expired lewat expirePastSessions.)
     */
    private function approvedSessions(int $mentorId): Collection
    {
        return MentoringBooking::with(['student.studentProfile', 'slot'])
            ->where('mentor_id', $mentorId)
            ->where('status', 'approved')
            ->get()
            ->sortBy(fn (MentoringBooking $booking) => $booking->slot
                ? $booking->slot->date.' '.$booking->slot->start_time
                : '9999-12-31')
            ->values();
    }
}

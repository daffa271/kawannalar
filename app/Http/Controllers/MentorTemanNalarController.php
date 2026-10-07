<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectionReasonRequest;
use App\Models\LiveClass;
use App\Models\MentoringBooking;
use App\Models\MentorSlot;
use App\Models\User;
use App\Notifications\BookingApprovedMailNotification;
use App\Notifications\BookingApprovedNotification;
use App\Notifications\BookingRejectedMailNotification;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorTemanNalarController extends Controller
{
    public function index()
    {
        $mentorId = Auth::id();

        MentorSlot::expirePastSessions($mentorId);

        $slots = MentorSlot::where('mentor_id', $mentorId)
            ->with(['booking.student'])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->orderByDesc('created_at')
            ->get();

        $bookings = MentoringBooking::where('mentor_id', $mentorId)
            ->where('status', 'pending')
            ->with(['student.studentProfile', 'slot'])
            ->get()
            ->sortByDesc(function (MentoringBooking $booking) {
                $sessionTimestamp = $booking->slot
                    ? Carbon::parse($booking->slot->date.' '.$booking->slot->start_time)->timestamp
                    : 0;

                return ($sessionTimestamp * 10000000000) + ($booking->created_at?->timestamp ?? 0);
            })
            ->values();

        // Belajar Bersama mendatang dulu (terdekat), lalu riwayat yang sudah lewat (terbaru).
        [$upcomingClasses, $pastClasses] = LiveClass::where('mentor_id', $mentorId)
            ->orderBy('schedule_time')
            ->get()
            ->partition(fn (LiveClass $class) => ! Carbon::parse($class->schedule_time)->isPast());
        $liveClasses = $upcomingClasses->concat($pastClasses->reverse())->values();

        return view('pages.mentor.teman-nalar.index', compact('slots', 'bookings', 'liveClasses'));
    }

    public function storeSlot(Request $request)
    {
        $request->validate([
            'session_type' => 'required|in:1on1,live_class',
            'meeting_link' => 'required|string|max:500',

            // 1-on-1 fields
            'date' => 'required_if:session_type,1on1|nullable|date',
            'start_time' => 'required_if:session_type,1on1|nullable',
            'end_time' => 'required_if:session_type,1on1|nullable|after:start_time',
            'topic' => 'required_if:session_type,1on1|nullable|in:Rasionalisasi SNBP,Strategi UTBK,Curhat,Lainnya',
            'custom_topic' => 'required_if:topic,Lainnya|nullable|string|max:255',

            // Live class fields
            'title' => 'required_if:session_type,live_class|nullable|string|max:255',
            'description' => 'nullable|string',
            'live_date' => 'required_if:session_type,live_class|nullable|date',
            'live_time' => 'required_if:session_type,live_class|nullable',
        ], [
            'meeting_link.required' => 'Tautan meeting wajib diisi.',
            'meeting_link.max' => 'Tautan meeting terlalu panjang.',
            'date.required_if' => 'Tanggal bimbingan wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'start_time.required_if' => 'Jam mulai wajib diisi.',
            'end_time.required_if' => 'Jam selesai wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'title.required_if' => 'Judul kelas wajib diisi.',
            'live_date.required_if' => 'Tanggal live class wajib diisi.',
            'live_time.required_if' => 'Jam live class wajib diisi.',
            'topic.in' => 'Topik bimbingan tidak valid.',
            'custom_topic.required_if' => 'Topik custom wajib diisi jika memilih Lainnya.',
        ]);

        $telegram = new TelegramService;

        if ($request->session_type === '1on1') {
            // Calculate duration automatically in backend
            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);
            $duration = $startTime->diffInMinutes($endTime);
            $sessionStart = Carbon::parse($request->date.' '.$request->start_time);

            if ($sessionStart->isPast()) {
                return redirect()->back()->withErrors(['date' => 'Sesi private harus dibuat untuk waktu yang akan datang.'])->withInput();
            }

            $topic = $request->topic === 'Lainnya' ? $request->custom_topic : $request->topic;
            $mentor = User::query()
                ->with('mentorProfile')
                ->findOrFail(Auth::id());

            MentorSlot::create([
                'mentor_id' => Auth::id(),
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'meeting_link' => $request->meeting_link,
                'duration' => $duration,
                'topic' => $topic,
                'status' => 'kosong',
            ]);

            // Umumkan ketersediaan sesi ke grup Telegram (info kelas saja; tanpa tautan meeting)
            $telegram->sendMentoringNotification([
                'type' => '1on1',
                'topic' => $topic,
                'mentor_name' => $mentor->name,
                'university' => $mentor->mentorProfile?->university,
                'school' => $mentor->mentorProfile?->high_school,
                'date' => Carbon::parse($request->date)->translatedFormat('d F Y'),
                'time' => substr($request->start_time, 0, 5),
                'booking_url' => route('siswa.teman-nalar.booking.create', $mentor->id),
            ]);

            return redirect()->back()->with('success', 'Sesi Bimbingan Private berhasil ditambahkan!');
        } else {
            $schedule_time = Carbon::parse($request->live_date.' '.$request->live_time);

            if ($schedule_time->isPast()) {
                return redirect()->back()->withErrors(['live_date' => 'Belajar Bersama harus dijadwalkan untuk waktu yang akan datang.'])->withInput();
            }

            LiveClass::create([
                'mentor_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'schedule_time' => $schedule_time,
                'quota' => 0,
                'meet_link' => $request->meeting_link,
                'registered_count' => 0,
            ]);

            // Kirim notifikasi Telegram ke grup KawanNalar
            $telegram->sendMentoringNotification([
                'type' => 'live_class',
                'topic' => $request->title,
                'mentor_name' => Auth::user()->name,
                'university' => Auth::user()->mentorProfile?->university,
                'date' => Carbon::parse($request->live_date)->translatedFormat('d F Y'),
                'time' => substr($request->live_time, 0, 5),
                'link' => $request->meeting_link,
            ]);

            return redirect()->back()->with('success', 'Kelas Belajar Bersama berhasil ditambahkan!');
        }
    }

    public function approveBooking($id): RedirectResponse
    {
        $booking = MentoringBooking::where('mentor_id', Auth::id())
            ->where('status', 'pending')
            ->with(['slot', 'student'])
            ->findOrFail($id);

        if (! $booking->slot || Carbon::parse($booking->slot->date.' '.$booking->slot->start_time)->isPast()) {
            $booking->update(['status' => 'expired']);

            return redirect()->back()->with('error', 'Booking sudah kedaluwarsa dan tidak dapat disetujui.');
        }

        $booking->update(['status' => 'approved']);

        $booking->slot->update(['status' => 'terisi']);
        $student = $booking->student;
        $schedule = Carbon::parse($booking->slot->date)->translatedFormat('d M Y').' '.substr($booking->slot->start_time, 0, 5).' WIB';

        // Feedback booking Private 1-on-1 bersifat privat: notifikasi website + email ke siswa, tidak ke grup Telegram.
        if ($student) {
            $student->notify(new BookingApprovedNotification(
                Auth::user()->name,
                $booking->topic,
                $schedule,
            ));

            // Gagal kirim email (mis. SMTP tidak tersedia) tidak boleh menggagalkan persetujuan.
            [$date, $time] = $this->sessionDateTime($booking->slot);
            rescue(fn () => $student->notify(new BookingApprovedMailNotification(
                Auth::user()->name,
                $booking->topic,
                $date,
                $time,
                route('siswa.teman-nalar.booking.meeting', $booking->id),
            )), report: true);
        }

        return redirect()->back()->with('success', 'Booking berhasil disetujui!');
    }

    public function rejectBooking(RejectionReasonRequest $request, $id): RedirectResponse
    {
        $booking = MentoringBooking::where('mentor_id', Auth::id())
            ->where('status', 'pending')
            ->with('slot')
            ->findOrFail($id);
        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $request->validated('reason'),
        ]);

        $student = $booking->student;

        if ($booking->slot && ! MentoringBooking::where('mentor_slot_id', $booking->slot->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists()) {
            $booking->slot->update(['status' => 'kosong']);
        }

        // Alasan penolakan hanya untuk siswa terkait (email + halaman Booking Saya), tidak ke grup Telegram.
        if ($student) {
            [$date, $time] = $this->sessionDateTime($booking->slot);
            rescue(fn () => $student->notify(new BookingRejectedMailNotification(
                Auth::user()->name,
                $booking->topic,
                $date,
                $time,
                $booking->rejection_reason,
            )), report: true);
        }

        return redirect()->back()->with('success', 'Booking telah ditolak/dibatalkan.');
    }

    public function completeBooking($id): RedirectResponse
    {
        $booking = MentoringBooking::where('mentor_id', Auth::id())
            ->where('status', 'approved')
            ->with('slot')
            ->findOrFail($id);

        $booking->update(['status' => 'completed']);
        $booking->slot?->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Mentoring ditandai selesai.');
    }

    public function meeting($id)
    {
        $booking = MentoringBooking::where('status', 'approved')
            ->with('slot')
            ->findOrFail($id);

        abort_unless(
            $booking->student_id === Auth::id() || $booking->mentor_id === Auth::id(),
            403
        );
        abort_unless($booking->slot?->meeting_link, 404);

        return redirect()->away($booking->slot->meeting_link);
    }

    public function destroySlot($id)
    {
        $slot = MentorSlot::where('mentor_id', Auth::id())->findOrFail($id);

        if ($slot->status === 'terisi' || $slot->booking()->whereIn('status', ['pending', 'approved'])->exists()) {
            return redirect()->back()->with('error', 'Slot yang sudah terisi tidak bisa dihapus.');
        }

        $slot->delete();

        return redirect()->back()->with('success', 'Slot berhasil dihapus.');
    }

    /**
     * Tanggal & jam sesi untuk email siswa, mis. ['Senin, 12 Oktober 2026', '19:00 - 20:00 WIB'].
     * Locale 'id' dipasang di sini karena APP_LOCALE aplikasi masih 'en'.
     */
    private function sessionDateTime(?MentorSlot $slot): array
    {
        if (! $slot) {
            return ['-', '-'];
        }

        return [
            Carbon::parse($slot->date)->locale('id')->translatedFormat('l, d F Y'),
            substr($slot->start_time, 0, 5).' - '.substr($slot->end_time, 0, 5).' WIB',
        ];
    }
}

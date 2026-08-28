<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MentorSlot;
use App\Models\MentoringBooking;
use App\Models\LiveClass;
use App\Services\TelegramNotificationService;
use App\Services\TelegramService;

class MentorTemanNalarController extends Controller
{
    public function index()
    {
        $mentorId = auth()->id();
        
        $slots = MentorSlot::where('mentor_id', $mentorId)
            ->with(['booking.student'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        $bookings = MentoringBooking::where('mentor_id', $mentorId)
            ->where('status', 'pending')
            ->with(['student.studentProfile', 'slot'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $liveClasses = LiveClass::where('mentor_id', $mentorId)
            ->orderBy('schedule_time')
            ->get();

        return view('pages.mentor.teman-nalar.index', compact('slots', 'bookings', 'liveClasses'));
    }

    public function storeSlot(Request $request)
    {
        $request->validate([
            'session_type' => 'required|in:1on1,live_class',
            'meeting_link' => 'required|string|max:500',

            // 1-on-1 fields
            'date'       => 'required_if:session_type,1on1|nullable|date',
            'start_time' => 'required_if:session_type,1on1|nullable',
            'end_time'   => 'required_if:session_type,1on1|nullable|after:start_time',

            // Live class fields
            'title'       => 'required_if:session_type,live_class|nullable|string|max:255',
            'description' => 'nullable|string',
            'live_date'   => 'required_if:session_type,live_class|nullable|date',
            'live_time'   => 'required_if:session_type,live_class|nullable',
            'quota'       => 'required_if:session_type,live_class|nullable|integer|min:1',
        ], [
            'meeting_link.required'  => 'Tautan meeting wajib diisi.',
            'meeting_link.max'       => 'Tautan meeting terlalu panjang.',
            'date.required_if'       => 'Tanggal bimbingan wajib diisi.',
            'date.date'              => 'Format tanggal tidak valid.',
            'start_time.required_if' => 'Jam mulai wajib diisi.',
            'end_time.required_if'   => 'Jam selesai wajib diisi.',
            'end_time.after'         => 'Waktu selesai harus setelah waktu mulai.',
            'title.required_if'      => 'Judul kelas wajib diisi.',
            'live_date.required_if'  => 'Tanggal live class wajib diisi.',
            'live_time.required_if'  => 'Jam live class wajib diisi.',
            'quota.required_if'      => 'Kuota maksimal wajib diisi.',
            'quota.min'              => 'Kuota minimal adalah 1.',
        ]);

        $telegram = new TelegramService();

        if ($request->session_type === '1on1') {
            // Calculate duration automatically in backend
            $startTime = \Carbon\Carbon::parse($request->start_time);
            $endTime = \Carbon\Carbon::parse($request->end_time);
            $duration = $startTime->diffInMinutes($endTime);

            MentorSlot::create([
                'mentor_id'    => auth()->id(),
                'date'         => $request->date,
                'start_time'   => $request->start_time,
                'end_time'     => $request->end_time,
                'meeting_link' => $request->meeting_link,
                'duration'     => $duration,
                'status'       => 'kosong',
            ]);

            // Kirim notifikasi Telegram ke grup KawanNalar
            $telegram->sendMentoringNotification([
                'type'        => '1on1',
                'topic'       => 'Sesi 1-on-1 Mentoring',
                'mentor_name' => auth()->user()->name,
                'date'        => \Carbon\Carbon::parse($request->date)->translatedFormat('d F Y'),
                'time'        => substr($request->start_time, 0, 5),
                'link'        => $request->meeting_link,
            ]);

            return redirect()->back()->with('success', 'Slot 1-on-1 berhasil ditambahkan!');
        } else {
            $schedule_time = \Carbon\Carbon::parse($request->live_date . ' ' . $request->live_time);

            LiveClass::create([
                'mentor_id'        => auth()->id(),
                'title'            => $request->title,
                'description'      => $request->description,
                'schedule_time'    => $schedule_time,
                'quota'            => $request->quota,
                'meet_link'        => $request->meeting_link,
                'registered_count' => 0,
            ]);

            // Kirim notifikasi Telegram ke grup KawanNalar
            $telegram->sendMentoringNotification([
                'type'        => 'live_class',
                'topic'       => $request->title,
                'mentor_name' => auth()->user()->name,
                'date'        => \Carbon\Carbon::parse($request->live_date)->translatedFormat('d F Y'),
                'time'        => substr($request->live_time, 0, 5),
                'link'        => $request->meeting_link,
            ]);

            return redirect()->back()->with('success', 'Live Class berhasil ditambahkan!');
        }
    }

    public function approveBooking($id)
    {
        $booking = MentoringBooking::where('mentor_id', auth()->id())->findOrFail($id);
        $booking->update(['status' => 'approved']);
        
        if ($booking->slot) {
            $booking->slot->update(['status' => 'terisi']);
        }
        
        $student = $booking->student;
        $schedule = '';
        if ($booking->slot) {
            $schedule = \Carbon\Carbon::parse($booking->slot->date)->translatedFormat('d M Y') . ' ' . substr($booking->slot->start_time, 0, 5) . ' WIB';
        }
        
        // Send Database Notification to Student
        if ($student) {
            $student->notify(new \App\Notifications\BookingApprovedNotification(
                auth()->user()->name,
                $booking->topic,
                $schedule,
                $booking->slot?->meeting_link
            ));
        }
        
        if ($student && $student->studentProfile && $student->studentProfile->telegram_chat_id) {
            $message = "✅ <b>Booking Disetujui!</b>\nKak " . auth()->user()->name . " telah menyetujui jadwal bimbinganmu pada {$schedule}.\n🔗 Link: " . $booking->slot?->meeting_link;
            TelegramNotificationService::send($student->studentProfile->telegram_chat_id, $message);
        }
        
        return redirect()->back()->with('success', 'Booking berhasil disetujui!');
    }

    public function rejectBooking($id)
    {
        $booking = MentoringBooking::where('mentor_id', auth()->id())->findOrFail($id);
        $booking->update(['status' => 'rejected']);
        
        if ($booking->slot) {
            $booking->slot->update(['status' => 'kosong']);
        }
        
        return redirect()->back()->with('success', 'Booking telah ditolak/dibatalkan.');
    }

    public function destroySlot($id)
    {
        $slot = MentorSlot::where('mentor_id', auth()->id())->findOrFail($id);
        
        if ($slot->status === 'terisi') {
            return redirect()->back()->with('error', 'Slot yang sudah terisi tidak bisa dihapus.');
        }
        
        $slot->delete();
        return redirect()->back()->with('success', 'Slot berhasil dihapus.');
    }
}

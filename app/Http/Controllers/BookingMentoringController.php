<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MentoringBooking;
use App\Models\MentorSlot;
use App\Notifications\NewBookingNotification;
use App\Services\TelegramNotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingMentoringController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic'          => 'required|in:Rasionalisasi SNBP,Strategi UTBK,Curhat,Lainnya',
            'custom_topic'   => 'required_if:topic,Lainnya|nullable|string|max:255',
            'mentor_slot_id' => 'required|exists:mentor_slots,id',
            'message'        => 'nullable|string|max:200',
        ]);

        $booking = DB::transaction(function () use ($validated) {
            $slot = MentorSlot::query()->lockForUpdate()->findOrFail($validated['mentor_slot_id']);
            $slotStart = Carbon::parse($slot->date . ' ' . $slot->start_time);

            if ($slotStart->isPast() || in_array($slot->status, ['completed', 'expired'], true)) {
                $slot->update(['status' => 'expired']);
                throw ValidationException::withMessages(['mentor_slot_id' => 'Sesi ini sudah lewat dan tidak dapat dibooking.']);
            }

            $activeBooking = MentoringBooking::query()
                ->where('mentor_slot_id', $slot->id)
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($activeBooking) {
                throw ValidationException::withMessages(['mentor_slot_id' => 'Sesi ini sudah dibooking siswa lain.']);
            }

            $topic = $validated['topic'] === 'Lainnya'
                ? $validated['custom_topic']
                : $validated['topic'];

            return MentoringBooking::create([
                'student_id'     => Auth::id(),
                'mentor_id'      => $slot->mentor_id,
                'mentor_slot_id' => $slot->id,
                'topic'          => $topic,
                'message'        => $validated['message'] ?? null,
                'status'         => 'pending',
            ]);
        });

        $slot = $booking->slot()->with('mentor.mentorProfile')->first();
        $schedule = Carbon::parse($slot->date)->translatedFormat('D, d M Y') . ' ' . substr($slot->start_time, 0, 5) . ' WIB';

        $student      = Auth::user();
        $studentName  = $student->name;
        $studentSchool = $student->studentProfile?->school ?? 'Sekolah';
        $mentor        = $slot->mentor;

        // 1) Database Notification → Mentor
        $mentor->notify(new NewBookingNotification($studentName, $studentSchool, $request->topic, $schedule));

        // 2) Telegram Notification (requires TELEGRAM_BOT_TOKEN in .env and chat_ids stored in profiles)
        $mentorMsg  = "🔔 Ada Booking Baru dari <b>{$studentName}</b> ({$studentSchool})\n📚 Topik: {$booking->topic}\n📅 Jadwal: {$schedule}\nStatus: Menunggu konfirmasi mentor.";
        $studentMsg = "🕒 <b>Booking Diterima</b>\nBooking kamu dengan <b>{$mentor->name}</b> masih menunggu konfirmasi mentor.\n📅 {$schedule}";

        TelegramNotificationService::send($mentor->mentorProfile->telegram_chat_id ?? null, $mentorMsg);
        TelegramNotificationService::send($student->studentProfile->telegram_chat_id ?? null, $studentMsg);

        return redirect()->route('siswa.teman-nalar.index', ['tab' => 'my-bookings'])
            ->with('success', "🚀 Booking berhasil! Kak {$mentor->name} akan segera mengkonfirmasi jadwal kamu.");
    }
}

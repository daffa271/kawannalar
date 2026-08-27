<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MentoringBooking;
use App\Models\MentorSlot;
use App\Notifications\NewBookingNotification;
use App\Services\TelegramNotificationService;
use Carbon\Carbon;

class BookingMentoringController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mentor_id'      => 'required|exists:users,id',
            'topic'          => 'required|string',
            'mentor_slot_id' => 'required|exists:mentor_slots,id',
            'message'        => 'nullable|string|max:200',
        ]);

        $slot = MentorSlot::findOrFail($request->mentor_slot_id);
        $schedule = Carbon::parse($slot->date)->translatedFormat('D, d M Y') . ' ' . substr($slot->start_time, 0, 5) . ' WIB';

        $booking = MentoringBooking::create([
            'student_id'     => auth()->id(),
            'mentor_id'      => $request->mentor_id,
            'mentor_slot_id' => $request->mentor_slot_id,
            'topic'          => $request->topic,
            'message'        => $request->message,
            'status'         => 'pending',
        ]);

        // Mark slot as terisi
        $slot->update(['status' => 'terisi']);

        $student      = auth()->user();
        $studentName  = $student->name;
        $studentSchool = $student->studentProfile->school ?? 'Sekolah';
        $mentor       = User::find($request->mentor_id);

        // 1) Database Notification → Mentor
        $mentor->notify(new NewBookingNotification($studentName, $studentSchool, $request->topic, $schedule));

        // 2) Telegram Notification (requires TELEGRAM_BOT_TOKEN in .env and chat_ids stored in profiles)
        $mentorMsg  = "🔔 Ada Booking Baru dari <b>{$studentName}</b> ({$studentSchool})\n📚 Topik: {$request->topic}\n📅 Jadwal: {$schedule}";
        $studentMsg = "✅ <b>Booking Berhasil!</b>\nJadwal bimbingan kamu dengan <b>{$mentor->name}</b> untuk topik \"{$request->topic}\" telah terkonfirmasi.\n📅 {$schedule}";

        TelegramNotificationService::send($mentor->mentorProfile->telegram_chat_id ?? null, $mentorMsg);
        TelegramNotificationService::send($student->studentProfile->telegram_chat_id ?? null, $studentMsg);

        return redirect()->back()->with('success', "🚀 Booking berhasil! Kak {$mentor->name} akan segera mengkonfirmasi jadwal kamu.");
    }
}

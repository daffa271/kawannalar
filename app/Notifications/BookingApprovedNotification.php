<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $mentorName,
        public string $topic,
        public string $schedule,
        public ?string $meetLink,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Booking Mentoring Disetujui',
            'body' => "✅ Bimbinganmu dengan Kak {$this->mentorName} untuk topik \"{$this->topic}\" pada {$this->schedule} telah disetujui. Link: " . ($this->meetLink ?? '-'),
            'type' => 'booking_approved',
        ];
    }
}

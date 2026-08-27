<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewBookingNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $studentName,
        public string $studentSchool,
        public string $topic,
        public string $schedule,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Booking Bimbingan Baru',
            'body' => "🔔 Siswa {$this->studentName} ({$this->studentSchool}) baru saja booking bimbingan topik \"{$this->topic}\" pada {$this->schedule}.",
            'type' => 'booking',
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingApprovedMailNotification extends Notification
{
    use Queueable;

    /**
     * Email ke siswa saat mentor menyetujui booking Private 1-on-1. Feedback booking bersifat privat:
     * hanya lewat email ini dan website, tidak pernah ke grup Telegram.
     * $meetingUrl adalah rute KawanNalar yang dilindungi login, bukan tautan Google Meet mentah.
     */
    public function __construct(
        public string $mentorName,
        public string $topic,
        public string $date,
        public string $time,
        public string $meetingUrl,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Private 1-on-1 Disetujui — KawanNalar')
            ->view('emails.booking-status', [
                'approved' => true,
                'studentName' => $notifiable->name,
                'mentorName' => $this->mentorName,
                'topic' => $this->topic,
                'date' => $this->date,
                'time' => $this->time,
                'reason' => null,
                'url' => $this->meetingUrl,
            ]);
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRejectedMailNotification extends Notification
{
    use Queueable;

    /**
     * Email ke siswa saat mentor menolak booking Private 1-on-1, berisi alasan penolakan.
     * Sama seperti persetujuan, feedback ini privat dan tidak pernah dikirim ke grup Telegram.
     */
    public function __construct(
        public string $mentorName,
        public string $topic,
        public string $date,
        public string $time,
        public string $reason,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Private 1-on-1 Ditolak — KawanNalar')
            ->view('emails.booking-status', [
                'approved' => false,
                'studentName' => $notifiable->name,
                'mentorName' => $this->mentorName,
                'topic' => $this->topic,
                'date' => $this->date,
                'time' => $this->time,
                'reason' => $this->reason,
                'url' => route('siswa.teman-nalar.index'),
            ]);
    }
}

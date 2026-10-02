<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRequestMailNotification extends Notification
{
    use Queueable;

    /**
     * Email ke mentor saat ada booking baru. Tidak di-queue (belum ada queue worker),
     * sehingga dikirim langsung lewat SMTP seperti email reset password.
     */
    public function __construct(
        public string $studentName,
        public string $studentSchool,
        public string $topic,
        public string $schedule,
        public ?string $studentMessage = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Bimbingan Baru — KawanNalar')
            ->view('emails.new-booking', [
                'mentorName' => $notifiable->name,
                'studentName' => $this->studentName,
                'studentSchool' => $this->studentSchool,
                'topic' => $this->topic,
                'schedule' => $this->schedule,
                'studentMessage' => $this->studentMessage,
                'url' => route('mentor.teman-nalar.index'),
            ]);
    }
}

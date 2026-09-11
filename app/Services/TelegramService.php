<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token', '');
        $this->chatId   = config('services.telegram.chat_id', '');
    }

    /**
     * Kirim notifikasi ke grup/channel Telegram saat sesi mentoring baru dibuat.
     *
     * @param  array  $sessionData
     *   - type        : '1on1' | 'live_class'
     *   - topic       : string   (topik / judul sesi)
     *   - mentor_name : string
     *   - date        : string   (tanggal tampil, sudah diformat)
     *   - time        : string   (jam mulai, sudah diformat)
     *   - link        : string   (meeting link, only for live_class)
     * @return bool
     */
    public function sendMentoringNotification(array $sessionData): bool
    {
        if (empty($this->botToken) || empty($this->chatId)) {
            Log::warning('TelegramService: BOT_TOKEN atau CHAT_ID belum dikonfigurasi di .env');
            return false;
        }

        $isLiveClass = ($sessionData['type'] ?? '1on1') === 'live_class';
        $typeLabel = $isLiveClass
            ? '🎓 <b>BELAJAR BERSAMA BARU TERSEDIA!</b>'
            : '📢 <b>SESI BIMBINGAN PRIVATE TERSEDIA!</b>';

        $messageLines = [
            $typeLabel,
            '',
            "📚 <b>Topik:</b> " . htmlspecialchars($sessionData['topic'] ?? '-', ENT_XML1),
            "👨🏫 <b>Mentor:</b> " . htmlspecialchars($sessionData['mentor_name'] ?? '-', ENT_XML1),
        ];

        if (!empty($sessionData['university'])) {
            $messageLines[] = "🏫 <b>PTN:</b> " . htmlspecialchars($sessionData['university'], ENT_XML1);
        }

        if (!$isLiveClass && !empty($sessionData['school'])) {
            $messageLines[] = "🎓 <b>Alumni:</b> " . htmlspecialchars($sessionData['school'], ENT_XML1);
        }

        $messageLines[] = "📅 <b>Tanggal:</b> " . htmlspecialchars($sessionData['date'] ?? '-', ENT_XML1);
        $messageLines[] = "⏰ <b>Waktu:</b> " . htmlspecialchars($sessionData['time'] ?? '-', ENT_XML1) . " WIB";

        if ($isLiveClass) {
            $messageLines[] = "🔗 <b>Link Google Meet:</b> " . htmlspecialchars($sessionData['link'] ?? '-', ENT_XML1);
            $messageLines[] = '';
            $messageLines[] = '✨ Yuk ikut Belajar Bersama di KawanNalar.';
        } else {
            $messageLines[] = '';
            $messageLines[] = '✨ Sesi tersedia untuk dibooking melalui KawanNalar.';
        }

        $message = implode("\n", $messageLines);

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        try {
            $response = Http::timeout(10)->post($url, [
                'chat_id'    => $this->chatId,
                'text'       => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if (!$response->successful()) {
                Log::error('TelegramService: Gagal mengirim notifikasi.', [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('TelegramService: Exception saat mengirim notifikasi.', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function sendBookingStatusNotification(array $bookingData): bool
    {
        $status = $bookingData['status'] ?? 'approved';
        $statusLabel = $status === 'approved' ? '✅ DISETUJUI' : '❌ DITOLAK';

        return $this->sendGroupMessage(implode("\n", [
            "<b>Booking Bimbingan Private {$statusLabel}</b>",
            '',
            '<b>Nama siswa:</b> ' . htmlspecialchars($bookingData['student_name'] ?? '-', ENT_XML1),
            '<b>Mentor:</b> Kak ' . htmlspecialchars($bookingData['mentor_name'] ?? '-', ENT_XML1),
            '<b>PTN:</b> ' . htmlspecialchars($bookingData['university'] ?? '-', ENT_XML1),
            '<b>Jadwal:</b> ' . htmlspecialchars($bookingData['schedule'] ?? '-', ENT_XML1),
            '<b>Topik:</b> ' . htmlspecialchars($bookingData['topic'] ?? '-', ENT_XML1),
            '',
            $status === 'approved'
                ? 'Silakan buka KawanNalar untuk mengikuti sesi sesuai jadwal.'
                : 'Sesi ini tidak dapat dilanjutkan. Silakan pilih sesi lain di KawanNalar.',
        ]));
    }

    private function sendGroupMessage(string $message): bool
    {
        if (empty($this->botToken) || empty($this->chatId)) {
            Log::warning('TelegramService: BOT_TOKEN atau CHAT_ID belum dikonfigurasi di .env');
            return false;
        }

        try {
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('TelegramService: Exception saat mengirim status booking.', ['message' => $e->getMessage()]);
            return false;
        }
    }
}

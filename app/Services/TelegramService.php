<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramService
{
    protected ?string $botToken;
    protected ?string $chatId;

    public function __construct()
    {
        $this->botToken = $this->normalize(config('services.telegram.bot_token'));
        $this->chatId   = $this->normalize(config('services.telegram.chat_id'));
    }

    /**
     * Apakah bot token tersedia. Tanpa token, tidak ada request ke Telegram.
     */
    public function isConfigured(): bool
    {
        return $this->botToken !== null;
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
     *   - link        : string   (meeting link, hanya untuk live_class — link sesi private tidak pernah dikirim)
     * @return bool
     */
    public function sendMentoringNotification(array $sessionData): bool
    {
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

        return $this->sendGroupMessage(implode("\n", $messageLines));
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

    /**
     * Kirim pesan ke chat tertentu. Mengembalikan false (tanpa exception) bila
     * token/chat_id belum dikonfigurasi atau Telegram gagal dihubungi.
     */
    public function sendMessage(int|string|null $chatId, string $message): bool
    {
        $chatId = $this->normalize($chatId);

        if ($this->botToken === null || $chatId === null) {
            return false;
        }

        try {
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if (!$response->successful()) {
                Log::error('TelegramService: Gagal mengirim pesan.', [
                    'status'   => $response->status(),
                    'response' => $this->redact($response->body()),
                ]);

                return false;
            }

            return true;
        } catch (Throwable $e) {
            Log::error('TelegramService: Exception saat mengirim pesan.', [
                'message' => $this->redact($e->getMessage()),
            ]);

            return false;
        }
    }

    private function sendGroupMessage(string $message): bool
    {
        if ($this->botToken === null || $this->chatId === null) {
            Log::warning('TelegramService: TELEGRAM_BOT_TOKEN atau TELEGRAM_GROUP_CHAT_ID belum dikonfigurasi di .env');

            return false;
        }

        return $this->sendMessage($this->chatId, $message);
    }

    private function normalize(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * Pesan exception HTTP dapat memuat URL lengkap (termasuk token) — jangan sampai tertulis ke log.
     */
    private function redact(string $text): string
    {
        return $this->botToken === null ? $text : str_replace($this->botToken, '[REDACTED]', $text);
    }
}

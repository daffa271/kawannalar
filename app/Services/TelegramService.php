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
     *   - link        : string   (meeting link)
     * @return bool
     */
    public function sendMentoringNotification(array $sessionData): bool
    {
        if (empty($this->botToken) || empty($this->chatId)) {
            Log::warning('TelegramService: BOT_TOKEN atau CHAT_ID belum dikonfigurasi di .env');
            return false;
        }

        $typeLabel = ($sessionData['type'] ?? '1on1') === 'live_class'
            ? '🎓 <b>LIVE CLASS BARU TERSEDIA!</b>'
            : '📢 <b>SESI MENTORING BARU TERSEDIA!</b>';

        $message = implode("\n", [
            $typeLabel,
            '',
            "📚 <b>Topik:</b> " . htmlspecialchars($sessionData['topic'] ?? '-', ENT_XML1),
            "👨🏫 <b>Mentor:</b> " . htmlspecialchars($sessionData['mentor_name'] ?? '-', ENT_XML1),
            "📅 <b>Tanggal:</b> " . htmlspecialchars($sessionData['date'] ?? '-', ENT_XML1),
            "⏰ <b>Waktu:</b> " . htmlspecialchars($sessionData['time'] ?? '-', ENT_XML1) . " WIB",
            "🔗 <b>Link Mentoring:</b> " . ($sessionData['link'] ?? '-'),
            '',
            "✨ Segera daftar dan manfaatkan sesi ini!",
        ]);

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
}

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
     * Umumkan kelas baru ke grup Telegram KawanNalar (Private 1-on-1 maupun Belajar Bersama).
     *
     * Grup Telegram hanya papan pengumuman kelas. Data booking/siswa dan tautan meeting
     * Private 1-on-1 tidak pernah dikirim ke grup; feedback booking lewat website + email siswa.
     *
     * @param  array  $sessionData
     *   - type        : '1on1' | 'live_class'
     *   - topic       : string   (topik / judul sesi)
     *   - mentor_name : string
     *   - date        : string   (tanggal tampil, sudah diformat)
     *   - time        : string   (jam mulai, sudah diformat)
     *   - link        : string   (meeting link, hanya untuk live_class — link sesi private tidak pernah dikirim)
     *   - booking_url : string   (halaman booking di website, hanya untuk 1on1)
     * @return bool
     */
    public function sendMentoringNotification(array $sessionData): bool
    {
        $isLiveClass = ($sessionData['type'] ?? '1on1') === 'live_class';
        $typeLabel = $isLiveClass
            ? '🎓 <b>BELAJAR BERSAMA BARU TERSEDIA!</b>'
            : '📢 <b>SESI PRIVATE 1-ON-1 TERSEDIA!</b>';

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
            $messageLines[] = '✨ Booking sesi ini melalui website KawanNalar.';

            if (!empty($sessionData['booking_url'])) {
                $messageLines[] = '👉 <a href="' . htmlspecialchars($sessionData['booking_url'], ENT_QUOTES) . '">Lihat jadwal &amp; booking</a>';
            }
        }

        return $this->sendGroupMessage(implode("\n", $messageLines));
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

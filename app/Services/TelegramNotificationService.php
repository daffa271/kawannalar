<?php

namespace App\Services;

class TelegramNotificationService
{
    /**
     * Kirim pesan Telegram langsung ke satu chat (DM).
     *
     * Catatan: profil siswa/mentor belum memiliki kolom telegram_chat_id, sehingga
     * pemanggil saat ini selalu mengirim null dan tidak ada DM yang terkirim.
     *
     * @param  int|string|null  $chatId
     * @param  string  $message
     * @return bool
     */
    public static function send($chatId, $message): bool
    {
        return app(TelegramService::class)->sendMessage($chatId, (string) $message);
    }
}

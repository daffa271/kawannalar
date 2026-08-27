<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramNotificationService
{
    /**
     * Send a message via Telegram Bot
     * 
     * @param string $chatId
     * @param string $message
     * @return bool
     */
    public static function send($chatId, $message)
    {
        if (!$chatId) return false;
        
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) return false;

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        
        try {
            $response = Http::post($url, [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
            
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}

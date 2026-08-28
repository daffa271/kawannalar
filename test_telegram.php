<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$telegram = new \App\Services\TelegramService();
$result = $telegram->sendMentoringNotification([
    'type' => '1on1',
    'topic' => 'Test',
    'mentor_name' => 'Dimas',
    'date' => '28 Agustus 2026',
    'time' => '10:00',
    'link' => 'http://test.com',
]);
var_dump($result);

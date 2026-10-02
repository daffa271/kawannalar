<?php

use App\Models\MentoringBooking;
use App\Models\MentorProfile;
use App\Models\MentorSlot;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

function telegramSafetyMentor(): User
{
    $mentor = User::factory()->create(['role' => 'mentor', 'status' => 'active']);

    MentorProfile::create([
        'user_id' => $mentor->id,
        'whatsapp' => '08123456789',
        'university' => 'PTN KawanNalar',
        'major' => 'Informatika',
        'high_school' => 'SMA Magetan',
    ]);

    return $mentor;
}

it('keeps the mentoring flow working when Telegram is not configured', function () {
    config(['services.telegram.bot_token' => null, 'services.telegram.chat_id' => null]);
    Http::fake();

    $mentor = telegramSafetyMentor();

    $this->actingAs($mentor)
        ->post(route('mentor.teman-nalar.slot.store'), [
            'session_type' => '1on1',
            'date' => now()->addDay()->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'topic' => 'Strategi UTBK',
            'meeting_link' => 'https://meet.google.com/private-room',
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

    $this->actingAs($mentor)
        ->post(route('mentor.teman-nalar.slot.store'), [
            'session_type' => 'live_class',
            'title' => 'Belajar Bersama',
            'live_date' => now()->addDay()->toDateString(),
            'live_time' => '19:00',
            'meeting_link' => 'https://meet.google.com/open-room',
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $this->actingAs($student)
        ->post(route('siswa.teman-nalar.booking.store'), [
            'mentor_slot_id' => MentorSlot::first()->id,
            'topic' => 'Curhat',
        ])->assertSessionHasNoErrors();

    $booking = MentoringBooking::first();
    $this->actingAs($mentor)
        ->patch(route('mentor.teman-nalar.booking.approve', $booking->id))
        ->assertSessionHas('success');

    expect($booking->fresh()->status)->toBe('approved');
    Http::assertNothingSent();
});

it('never sends the private meeting link to the Telegram group on approval', function () {
    Http::fake();

    $mentor = telegramSafetyMentor();
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $slot = MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'topic' => 'Curhat',
        'meeting_link' => 'https://meet.google.com/private-room',
        'status' => 'kosong',
    ]);
    $booking = MentoringBooking::create([
        'student_id' => $student->id,
        'mentor_id' => $mentor->id,
        'mentor_slot_id' => $slot->id,
        'topic' => 'Curhat',
        'status' => 'pending',
    ]);

    $this->actingAs($mentor)
        ->patch(route('mentor.teman-nalar.booking.approve', $booking->id))
        ->assertSessionHas('success');

    Http::assertSent(fn ($request) => str_contains($request['text'], 'DISETUJUI'));
    Http::assertNotSent(fn ($request) => str_contains($request['text'], 'private-room'));
});

it('does not write the bot token to the log when Telegram is unreachable', function () {
    config(['services.telegram.bot_token' => 'secret-test-token']);
    Http::fake(fn () => throw new RuntimeException('Could not resolve host for https://api.telegram.org/botsecret-test-token/sendMessage'));
    Log::spy();

    expect(app(TelegramService::class)->sendMessage('-100123', 'halo'))->toBeFalse();

    Log::shouldHaveReceived('error')
        ->withArgs(fn ($message, $context) => ! str_contains(json_encode($context), 'secret-test-token'))
        ->once();
});

it('keeps .env.example free of real secrets', function () {
    $example = file_get_contents(base_path('.env.example'));

    expect($example)->not->toMatch('/\d{8,10}:[A-Za-z0-9_-]{30,}/');

    foreach (['TELEGRAM_BOT_TOKEN', 'TELEGRAM_GROUP_CHAT_ID', 'GEMINI_API_KEY', 'ADMIN_SEED_PASSWORD', 'APP_KEY'] as $key) {
        expect($example)->toMatch("/^{$key}=\\r?$/m");
    }
});

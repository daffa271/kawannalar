<?php

use App\Models\LiveClass;
use App\Models\MentoringBooking;
use App\Models\MentorProfile;
use App\Models\MentorSlot;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

function timezoneMentor(): User
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

function wib(string $datetime): Carbon
{
    return Carbon::parse($datetime, 'Asia/Jakarta');
}

it('runs the application in WIB', function () {
    expect(config('app.timezone'))->toBe('Asia/Jakarta');

    $this->travelTo(wib('2026-09-28 19:30'));
    expect(now()->format('Y-m-d H:i T'))->toBe('2026-09-28 19:30 WIB');
});

it('expires a 19:00-20:00 WIB slot only after 20:00 WIB', function () {
    $mentor = timezoneMentor();
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $slot = MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => '2026-09-28',
        'start_time' => '19:00',
        'end_time' => '20:00',
        'topic' => 'Strategi UTBK',
        'status' => 'kosong',
    ]);

    // 19:59 WIB = 12:59 UTC — sesi belum selesai.
    $this->travelTo(wib('2026-09-28 19:59'));
    $this->actingAs($student)->get(route('siswa.teman-nalar.index'))->assertOk();
    expect($slot->fresh()->status)->toBe('kosong');

    // 20:01 WIB — dengan config UTC lama slot ini baru expired pukul 03:00 WIB.
    $this->travelTo(wib('2026-09-28 20:01'));
    $this->actingAs($student)->get(route('siswa.teman-nalar.index'))->assertOk();
    expect($slot->fresh()->status)->toBe('expired');
});

it('accepts bookings before the WIB start time and rejects them after it', function () {
    Http::fake();
    $mentor = timezoneMentor();
    $slotA = MentorSlot::create([
        'mentor_id' => $mentor->id, 'date' => '2026-09-28', 'start_time' => '19:00', 'end_time' => '20:00',
        'topic' => 'Strategi UTBK', 'status' => 'kosong',
    ]);
    $slotB = MentorSlot::create([
        'mentor_id' => $mentor->id, 'date' => '2026-09-28', 'start_time' => '19:00', 'end_time' => '20:00',
        'topic' => 'Curhat', 'status' => 'kosong',
    ]);

    $this->travelTo(wib('2026-09-28 18:30'));
    $this->actingAs(User::factory()->create(['role' => 'siswa', 'status' => 'active']))
        ->post(route('siswa.teman-nalar.booking.store'), ['mentor_slot_id' => $slotA->id, 'topic' => 'Strategi UTBK'])
        ->assertSessionHasNoErrors();

    $booking = MentoringBooking::where('mentor_slot_id', $slotA->id)->first();
    expect($booking->status)->toBe('pending')
        ->and($booking->getRawOriginal('created_at'))->toBe('2026-09-28 18:30:00');

    // 19:05 WIB — sesi sudah dimulai. Dengan config UTC lama booking ini masih diterima.
    $this->travelTo(wib('2026-09-28 19:05'));
    $this->actingAs(User::factory()->create(['role' => 'siswa', 'status' => 'active']))
        ->post(route('siswa.teman-nalar.booking.store'), ['mentor_slot_id' => $slotB->id, 'topic' => 'Curhat'])
        ->assertSessionHasErrors('mentor_slot_id');
});

it('creates slots in WIB and shows the same wall-clock time', function () {
    Http::fake();
    $mentor = timezoneMentor();
    $this->travelTo(wib('2026-09-28 10:00'));

    // 09:30 WIB sudah lewat — dengan config UTC lama (03:00 UTC) slot ini lolos validasi.
    $this->actingAs($mentor)->post(route('mentor.teman-nalar.slot.store'), [
        'session_type' => '1on1', 'date' => '2026-09-28', 'start_time' => '09:30', 'end_time' => '10:30',
        'topic' => 'Curhat', 'meeting_link' => 'https://meet.google.com/abc-defg-hij',
    ])->assertSessionHasErrors('date');

    $this->actingAs($mentor)->post(route('mentor.teman-nalar.slot.store'), [
        'session_type' => '1on1', 'date' => '2026-09-28', 'start_time' => '19:00', 'end_time' => '20:00',
        'topic' => 'Strategi UTBK', 'meeting_link' => 'https://meet.google.com/abc-defg-hij',
    ])->assertSessionHasNoErrors();

    $slot = MentorSlot::sole();
    expect($slot->start_time)->toBe('19:00')->and($slot->end_time)->toBe('20:00');

    $this->actingAs($mentor)->get(route('mentor.teman-nalar.index'))
        ->assertOk()
        ->assertSeeText('19:00 - 20:00 WIB');

    $this->actingAs(User::factory()->create(['role' => 'siswa', 'status' => 'active']))
        ->get(route('siswa.teman-nalar.booking.create', $mentor))
        ->assertOk()
        ->assertSeeText('28 September 2026')
        ->assertSeeText('19:00 - 20:00 WIB');
});

it('lists Belajar Bersama until its WIB start time', function () {
    $mentor = timezoneMentor();
    LiveClass::create([
        'mentor_id' => $mentor->id,
        'title' => 'Strategi Belajar Menuju UTBK SNBT 2027',
        'schedule_time' => '2026-10-01 19:00:00',
        'quota' => 0,
        'registered_count' => 0,
        'meet_link' => 'https://meet.google.com/abc-defg-hij',
    ]);
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->travelTo(wib('2026-10-01 18:30'));
    $this->actingAs($student)->get(route('siswa.teman-nalar.index'))
        ->assertOk()
        ->assertSeeText('Strategi Belajar Menuju UTBK SNBT 2027')
        ->assertSeeText('19:00 WIB');

    // 19:30 WIB — dengan config UTC lama kelas ini masih tampil sampai 02:00 WIB.
    $this->travelTo(wib('2026-10-01 19:30'));
    $this->actingAs($student)->get(route('siswa.teman-nalar.index'))
        ->assertOk()
        ->assertDontSeeText('Strategi Belajar Menuju UTBK SNBT 2027');
});

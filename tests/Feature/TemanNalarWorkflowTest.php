<?php

use App\Models\MentorProfile;
use App\Models\MentorSlot;
use App\Models\MentoringBooking;
use App\Models\User;
use App\Models\LiveClass;
use Illuminate\Support\Facades\Http;

function temanMentor(array $attributes = []): User
{
    $mentor = User::factory()->create(array_merge([
        'role' => 'mentor',
        'status' => 'active',
    ], $attributes));

    MentorProfile::create([
        'user_id' => $mentor->id,
        'whatsapp' => '08123456789',
        'university' => 'PTN KawanNalar',
        'major' => 'Informatika',
        'high_school' => 'SMA Magetan',
    ]);

    return $mentor;
}

it('keeps private booking pending and blocks duplicate or expired bookings', function () {
    Http::fake();
    $mentor = temanMentor();
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $slot = MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'topic' => 'Strategi UTBK',
        'status' => 'kosong',
    ]);

    $this->actingAs($student)->post(route('siswa.teman-nalar.booking.store'), [
        'mentor_slot_id' => $slot->id,
        'topic' => 'Lainnya',
        'custom_topic' => 'Strategi belajar personal',
    ])->assertSessionHasNoErrors()
        ->assertRedirect(route('siswa.teman-nalar.index', ['tab' => 'my-bookings']));

    expect($slot->fresh()->status)->toBe('kosong');
    expect(MentoringBooking::first()->status)->toBe('pending');

    $this->actingAs(User::factory()->create(['role' => 'siswa', 'status' => 'active']))
        ->post(route('siswa.teman-nalar.booking.store'), [
            'mentor_slot_id' => $slot->id,
            'topic' => 'Curhat',
        ])->assertSessionHasErrors('mentor_slot_id');

    $expired = MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => now()->subDay()->toDateString(),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'topic' => 'Curhat',
        'status' => 'kosong',
    ]);

    $this->actingAs($student)->post(route('siswa.teman-nalar.booking.store'), [
        'mentor_slot_id' => $expired->id,
        'topic' => 'Curhat',
    ])->assertSessionHasErrors('mentor_slot_id');
});

it('protects private meeting access and completes approved mentoring', function () {
    $mentor = temanMentor();
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $otherStudent = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
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
        'status' => 'approved',
    ]);
    $otherMentor = temanMentor();

    $this->actingAs($otherMentor)
        ->get(route('mentor.teman-nalar.booking.meeting', $booking))
        ->assertForbidden();

    $pending = MentoringBooking::create([
        'student_id' => $student->id,
        'mentor_id' => $mentor->id,
        'mentor_slot_id' => $slot->id,
        'topic' => 'Curhat',
        'status' => 'pending',
    ]);

    $this->actingAs($student)
        ->get(route('siswa.teman-nalar.booking.meeting', $pending))
        ->assertNotFound();

    $this->actingAs($otherStudent)
        ->get(route('siswa.teman-nalar.booking.meeting', $booking))
        ->assertForbidden();

    $this->actingAs($student)
        ->get(route('siswa.teman-nalar.booking.meeting', $booking))
        ->assertRedirect('https://meet.google.com/private-room');

    $this->actingAs($mentor)
        ->patch(route('mentor.teman-nalar.booking.complete', $booking))
        ->assertSessionHasNoErrors();

    expect($booking->fresh()->status)->toBe('completed');
    expect($slot->fresh()->status)->toBe('completed');

    $this->actingAs($otherStudent)
        ->post(route('siswa.teman-nalar.booking.store'), [
            'mentor_slot_id' => $slot->id,
            'topic' => 'Curhat',
        ])->assertSessionHasErrors('mentor_slot_id');
});

it('filters mentors by PTN, topic, and search together', function () {
    $mentorA = temanMentor();
    $mentorA->mentorProfile->update(['university' => 'PTN Alpha']);
    $slotA = MentorSlot::create([
        'mentor_id' => $mentorA->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'topic' => 'Strategi UTBK',
        'status' => 'kosong',
    ]);

    $mentorB = temanMentor();
    $mentorB->mentorProfile->update(['university' => 'PTN Beta']);
    MentorSlot::create([
        'mentor_id' => $mentorB->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '12:00',
        'end_time' => '13:00',
        'topic' => 'Curhat',
        'status' => 'kosong',
    ]);

    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);
    $response = $this->actingAs($student)->get(route('siswa.teman-nalar.index', [
        'university' => 'PTN Alpha',
        'topic' => 'Strategi UTBK',
        'q' => 'Informatika',
    ]));

    $response->assertOk()->assertSeeText($mentorA->name)->assertDontSeeText($mentorB->name);
    expect($slotA->fresh()->topic)->toBe('Strategi UTBK');

    $customMentor = temanMentor();
    $customMentor->mentorProfile->update(['university' => 'PTN Custom']);
    MentorSlot::create([
        'mentor_id' => $customMentor->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '14:00',
        'end_time' => '15:00',
        'topic' => 'Strategi belajar personal',
        'status' => 'kosong',
    ]);

    $this->actingAs($student)
        ->get(route('siswa.teman-nalar.index', ['topic' => 'Lainnya']))
        ->assertSeeText($customMentor->name);
});

it('requires custom topic and creates open Belajar Bersama without quota', function () {
    Http::fake();
    $mentor = temanMentor();

    $this->actingAs($mentor)
        ->post(route('mentor.teman-nalar.slot.store'), [
            'session_type' => '1on1',
            'date' => now()->addDay()->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'topic' => 'Strategi UTBK',
            'meeting_link' => 'https://meet.google.com/private-room',
        ])->assertSessionHasNoErrors();

    Http::assertSent(fn($request) => !str_contains($request['text'], 'https://meet.google.com/private-room'));

    $this->actingAs($mentor)
        ->post(route('mentor.teman-nalar.slot.store'), [
            'session_type' => '1on1',
            'date' => now()->addDay()->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'topic' => 'Lainnya',
            'meeting_link' => 'https://meet.google.com/private-room',
        ])->assertSessionHasErrors('custom_topic');

    $this->actingAs($mentor)
        ->post(route('mentor.teman-nalar.slot.store'), [
            'session_type' => 'live_class',
            'title' => 'Strategi UTBK',
            'live_date' => now()->addDay()->toDateString(),
            'live_time' => '19:00',
            'meeting_link' => 'https://meet.google.com/open-room',
        ])->assertSessionHasNoErrors();

    $class = App\Models\LiveClass::first();
    expect($class->quota)->toBe(0);
    expect($class->registered_count)->toBe(0);

    Http::assertSent(fn($request) => str_contains($request['text'], 'https://meet.google.com/open-room'));
    Http::assertSent(fn($request) => !str_contains($request['text'], 'https://meet.google.com/private-room'));
});

it('renders the booking payload and upcoming Belajar Bersama for students', function () {
    $mentor = temanMentor(['name' => 'Mentor Tombol']);
    MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '10:30',
        'end_time' => '11:30',
        'topic' => 'Strategi UTBK',
        'status' => 'kosong',
    ]);
    LiveClass::create([
        'mentor_id' => $mentor->id,
        'title' => 'Belajar UTBK Bersama',
        'schedule_time' => now()->addDay()->setTime(19, 0),
        'quota' => 0,
        'registered_count' => 0,
        'meet_link' => 'https://meet.google.com/open-room',
    ]);

    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)
        ->get(route('siswa.teman-nalar.index'))
        ->assertOk()
        ->assertDontSee('127.0.0.1:5173', false)
        ->assertSee('/build/assets/app-', false)
        ->assertSeeText('Mentor Tombol')
        ->assertDontSee('openModal', false)
        ->assertSee(route('siswa.teman-nalar.booking.create', $mentor), false)
        ->assertSeeText('Belajar UTBK Bersama')
        ->assertSee('https://meet.google.com/open-room');

    $this->actingAs($student)
        ->get(route('siswa.teman-nalar.booking.create', $mentor))
        ->assertOk()
        ->assertSeeText('Booking Bimbingan 1-on-1')
        ->assertSeeText('Mentor Tombol')
        ->assertSeeText('Strategi UTBK');
});

it('orders mentor slots with the latest session first', function () {
    $mentor = temanMentor();
    MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => now()->addDays(2)->toDateString(),
        'start_time' => '09:00',
        'end_time' => '10:00',
        'topic' => 'Curhat',
        'status' => 'kosong',
    ]);
    MentorSlot::create([
        'mentor_id' => $mentor->id,
        'date' => now()->addDay()->toDateString(),
        'start_time' => '18:00',
        'end_time' => '19:00',
        'topic' => 'Curhat',
        'status' => 'kosong',
    ]);

    $response = $this->actingAs($mentor)->get(route('mentor.teman-nalar.index'));
    $response->assertOk();
    expect($response->getContent())->toContain(now()->addDays(2)->format('d'));
    expect(strpos($response->getContent(), now()->addDays(2)->format('d')))
        ->toBeLessThan(strpos($response->getContent(), now()->addDay()->format('d')));
});

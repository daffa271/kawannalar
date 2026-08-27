<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MentorSlot;
use App\Models\LiveClass;
use Carbon\Carbon;

class TemanNalarSeeder extends Seeder
{
    public function run(): void
    {
        $mentors = User::where('role', 'mentor')->get();
        
        foreach ($mentors as $mentor) {
            // Create slots
            for ($i = 0; $i < 3; $i++) {
                MentorSlot::create([
                    'mentor_id' => $mentor->id,
                    'date' => Carbon::now()->addDays($i + 1)->toDateString(),
                    'start_time' => '15:00:00',
                    'end_time' => '16:00:00',
                    'status' => 'kosong'
                ]);
            }
            
            // Create Live Class
            LiveClass::create([
                'mentor_id' => $mentor->id,
                'title' => 'Strategi Belajar ' . ($mentor->mentorProfile->major ?? 'Jurusan Impian'),
                'description' => 'Tips dan trik memilih jurusan yang tepat dan persiapan yang harus dilakukan.',
                'schedule_time' => Carbon::now()->addDays(2)->setTime(19, 0, 0),
                'quota' => 30,
                'registered_count' => 8,
                'meet_link' => 'https://meet.google.com/abc-def-ghi'
            ]);
        }
    }
}

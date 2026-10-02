<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorSlot extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Tandai slot (dan booking aktifnya) yang jam selesainya sudah lewat sebagai expired.
     * Tanpa $mentorId: semua mentor.
     */
    public static function expirePastSessions(?int $mentorId = null): void
    {
        static::query()
            ->when($mentorId !== null, fn ($query) => $query->where('mentor_id', $mentorId))
            ->whereIn('status', ['kosong', 'terisi'])
            ->get()
            ->each(function (MentorSlot $slot) {
                if (Carbon::parse($slot->date.' '.$slot->end_time)->isPast()) {
                    $slot->update(['status' => 'expired']);
                    $slot->bookings()->whereIn('status', ['pending', 'approved'])->update(['status' => 'expired']);
                }
            });
    }

    /**
     * Slot yang belum dimulai (jam mulai masih di depan, WIB) — hanya ini yang boleh dibooking.
     */
    public function scopeNotStarted(Builder $query): Builder
    {
        $now = now();

        return $query->where(fn ($slot) => $slot
            ->where('date', '>', $now->toDateString())
            ->orWhere(fn ($today) => $today
                ->where('date', $now->toDateString())
                ->where('start_time', '>', $now->format('H:i'))));
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function booking()
    {
        return $this->hasOne(MentoringBooking::class, 'mentor_slot_id')->latestOfMany();
    }

    public function bookings()
    {
        return $this->hasMany(MentoringBooking::class, 'mentor_slot_id');
    }
}

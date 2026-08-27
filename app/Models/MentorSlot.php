<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorSlot extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function booking()
    {
        return $this->hasOne(MentoringBooking::class, 'mentor_slot_id');
    }
}

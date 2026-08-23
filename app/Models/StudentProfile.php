<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'whatsapp',
        'school',
        'grade',
        'target_major',
        'target_university',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'quiz_id', 'question_text',
    'option_a', 'option_b', 'option_c', 'option_d', 'option_e',
    'correct_answer', 'explanation', 'order',
])]
class Question extends Model
{
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}

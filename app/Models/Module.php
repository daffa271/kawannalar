<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Module extends Model
{
    protected $fillable = [
        'title',
        'description',
        'subject',
        'grade',
        'file_path',
        'uploaded_by',
        'status',
        'approved_by',
        'approved_at',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'download_count' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

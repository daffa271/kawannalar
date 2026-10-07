<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Module extends Model
{
    /** Satu daftar mapel & kelas untuk semua form unggah Ruang Nalar (siswa dan mentor). */
    public const SUBJECTS = ['Matematika', 'Fisika', 'Kimia', 'Biologi', 'Bahasa Indonesia', 'Bahasa Inggris', 'UTBK', 'Lainnya'];

    public const GRADES = ['Kelas 10', 'Kelas 11', 'Kelas 12', 'UTBK'];

    /** XP untuk pengunggah saat modulnya pertama kali disetujui Admin. */
    public const APPROVAL_XP = 10;

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
        'rejection_reason',
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

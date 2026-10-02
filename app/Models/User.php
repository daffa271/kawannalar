<?php

namespace App\Models;

use Database\Factories\UserFactory;

use App\Models\Module;
use App\Notifications\ResetPasswordNotification;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'status',
    'approved_by',
    'approved_at',
    'school_name',
    'xp_points',
    'streak_days',
    'is_suspended',
    'rejection_reason',
])]
#[Hidden([
    'password',
    'remember_token'
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
            'is_suspended' => 'boolean',
        ];
    }

    /**
     * Kirim notifikasi reset password menggunakan
     * template email KawanNalar.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function mentorProfile(): HasOne
    {
        return $this->hasOne(MentorProfile::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'approved_by');
    }

    public function approvedMentors(): HasMany
    {
        return $this->hasMany(self::class, 'approved_by');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class, 'uploaded_by');
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'mentor_id');
    }

    /**
     * Domain email akun seed/testing — tidak boleh masuk leaderboard.
     */
    public const DUMMY_EMAIL_DOMAIN = '@kawannalar.test';

    /**
     * Siswa yang berhak tampil di leaderboard (tanpa akun dummy).
     */
    public function scopeLeaderboardEligible(Builder $query): Builder
    {
        return $query->where('role', 'siswa')
            ->where('email', 'not like', '%'.self::DUMMY_EMAIL_DOMAIN);
    }

    /**
     * Urutan leaderboard: XP terbanyak, lalu yang lebih dulu terdaftar.
     */
    public function scopeLeaderboard(Builder $query): Builder
    {
        return $query->leaderboardEligible()
            ->orderByDesc('xp_points')
            ->orderBy('id');
    }

    /**
     * Peringkat global siswa ini dengan urutan yang sama seperti scopeLeaderboard.
     */
    public function leaderboardRank(): ?int
    {
        if ($this->role !== 'siswa' || str_ends_with((string) $this->email, self::DUMMY_EMAIL_DOMAIN)) {
            return null;
        }

        return static::query()
            ->leaderboardEligible()
            ->where(function (Builder $query) {
                $query->where('xp_points', '>', $this->xp_points)
                    ->orWhere(fn (Builder $tie) => $tie->where('xp_points', $this->xp_points)->where('id', '<', $this->id));
            })
            ->count() + 1;
    }
}
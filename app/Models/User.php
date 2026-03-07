<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    public const XP_PER_LEVEL = 250;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_CLIENT = 'client';

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'role',
        'experience_points',
        'daily_streak',
        'last_studied_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_studied_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role,
        ];
    }

    public function studyProgress(): HasMany
    {
        return $this->hasMany(StudyProgress::class);
    }

    public function decks(): HasMany
    {
        return $this->hasMany(Deck::class);
    }

    public function deckReviews(): HasMany
    {
        return $this->hasMany(DeckReview::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function level(): int
    {
        return max(1, (int) floor($this->experience_points / self::XP_PER_LEVEL) + 1);
    }

    public function levelProgress(): array
    {
        $level = $this->level();
        $currentLevelFloor = max(0, ($level - 1) * self::XP_PER_LEVEL);
        $nextLevelAt = $level * self::XP_PER_LEVEL;
        $progressWithinLevel = max(0, $this->experience_points - $currentLevelFloor);

        return [
            'level' => $level,
            'current_xp' => (int) $this->experience_points,
            'current_level_floor' => $currentLevelFloor,
            'next_level_at' => $nextLevelAt,
            'progress_within_level' => $progressWithinLevel,
            'xp_per_level' => self::XP_PER_LEVEL,
            'remaining_xp' => max(0, $nextLevelAt - (int) $this->experience_points),
            'percent' => min(100, (int) round(($progressWithinLevel / self::XP_PER_LEVEL) * 100)),
        ];
    }
}

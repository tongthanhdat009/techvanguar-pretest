<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyProgress extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';

    public const STATUS_LEARNING = 'learning';

    public const STATUS_MASTERED = 'mastered';

    protected $table = 'study_progress';

    protected $fillable = [
        'user_id',
        'flashcard_id',
        'status',
        'last_reviewed_at',
        'next_review_at',
        'review_count',
        'correct_streak',
    ];

    protected function casts(): array
    {
        return [
            'last_reviewed_at' => 'datetime',
            'next_review_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function flashcard(): BelongsTo
    {
        return $this->belongsTo(Flashcard::class);
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_LEARNING,
            self::STATUS_MASTERED,
        ];
    }
}

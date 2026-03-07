<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'deck_id',
        'front_content',
        'back_content',
        'image_url',
        'audio_url',
        'hint',
    ];

    public function deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class);
    }

    public function studyProgress(): HasMany
    {
        return $this->hasMany(StudyProgress::class);
    }
}

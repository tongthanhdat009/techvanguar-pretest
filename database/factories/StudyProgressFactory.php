<?php

namespace Database\Factories;

use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudyProgress>
 */
class StudyProgressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'flashcard_id' => Flashcard::factory(),
            'status' => fake()->randomElement(StudyProgress::statuses()),
            'last_reviewed_at' => now(),
            'next_review_at' => now()->addDay(),
            'review_count' => fake()->numberBetween(0, 8),
            'correct_streak' => fake()->numberBetween(0, 5),
        ];
    }
}

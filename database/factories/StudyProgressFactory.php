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
        ];
    }
}

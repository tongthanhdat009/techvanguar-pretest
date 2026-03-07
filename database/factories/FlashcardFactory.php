<?php

namespace Database\Factories;

use App\Models\Deck;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flashcard>
 */
class FlashcardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'deck_id' => Deck::factory(),
            'front_content' => fake()->sentence(6),
            'back_content' => fake()->paragraph(2),
            'image_url' => fake()->optional()->imageUrl(640, 480, 'education'),
            'audio_url' => fake()->optional()->url(),
            'hint' => fake()->optional()->sentence(),
        ];
    }
}

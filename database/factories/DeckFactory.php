<?php

namespace Database\Factories;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deck>
 */
class DeckFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'user_id' => null,
            'visibility' => Deck::VISIBILITY_PUBLIC,
            'category' => fake()->randomElement(['Languages', 'Science', 'Programming']),
            'tags' => fake()->randomElements(['beginner', 'grammar', 'memory', 'quiz'], fake()->numberBetween(1, 3)),
            'source_deck_id' => null,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function shared(): static
    {
        return $this->state(fn () => ['visibility' => Deck::VISIBILITY_PUBLIC]);
    }

    public function privateOwned(?User $user = null): static
    {
        return $this->state(fn () => [
            'user_id' => $user?->id ?? User::factory(),
            'visibility' => Deck::VISIBILITY_PRIVATE,
        ]);
    }
}

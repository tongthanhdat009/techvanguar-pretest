<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Run seeders in order to respect foreign key constraints.
     */
    public function run(): void
    {
        // 1. Users must be created first
        $this->call(UserSeeder::class);

        // 2. Decks depend on users (for owned decks)
        $this->call(DeckSeeder::class);

        // 3. Flashcards depend on decks
        $this->call(FlashcardSeeder::class);

        // 4. StudyProgress depends on users and flashcards
        $this->call(StudyProgressSeeder::class);

        // 5. DeckReviews depend on users and decks
        $this->call(DeckReviewSeeder::class);
    }
}

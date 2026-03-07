<?php

namespace Database\Seeders;

use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $client = User::factory()->create([
            'name' => 'Client Learner',
            'email' => 'client@example.com',
            'password' => 'password',
        ]);

        $decks = collect([
            [
                'title' => 'Laravel Essentials',
                'description' => 'Core Laravel concepts for routing, middleware, and Eloquent.',
                'is_active' => true,
                'cards' => [
                    ['front' => 'What command creates a controller?', 'back' => 'Use php artisan make:controller ControllerName.'],
                    ['front' => 'What does Eloquent represent?', 'back' => 'Laravel\'s Active Record ORM for working with database models.'],
                    ['front' => 'Which file defines web routes?', 'back' => 'Routes for browser requests are usually defined in routes/web.php.'],
                ],
            ],
            [
                'title' => 'MySQL Basics',
                'description' => 'Practice data modeling, indexes, and query planning fundamentals.',
                'is_active' => true,
                'cards' => [
                    ['front' => 'What is a primary key?', 'back' => 'A unique identifier for each row in a table.'],
                    ['front' => 'Why add indexes?', 'back' => 'Indexes improve lookup speed for frequently queried columns.'],
                    ['front' => 'What is normalization?', 'back' => 'Organizing data to reduce redundancy and improve integrity.'],
                ],
            ],
            [
                'title' => 'Archived Practice Deck',
                'description' => 'An inactive deck that only admins should manage.',
                'is_active' => false,
                'cards' => [
                    ['front' => 'What is refactoring?', 'back' => 'Improving internal code structure without changing behavior.'],
                ],
            ],
        ])->map(function (array $deckData) {
            $deck = Deck::create([
                'title' => $deckData['title'],
                'description' => $deckData['description'],
                'is_active' => $deckData['is_active'],
            ]);

            collect($deckData['cards'])->each(function (array $card) use ($deck) {
                Flashcard::create([
                    'deck_id' => $deck->id,
                    'front_content' => $card['front'],
                    'back_content' => $card['back'],
                ]);
            });

            return $deck->load('flashcards');
        });

        $firstActiveDeck = $decks->firstWhere('is_active', true);

        if ($firstActiveDeck) {
            foreach ($firstActiveDeck->flashcards as $index => $flashcard) {
                StudyProgress::create([
                    'user_id' => $client->id,
                    'flashcard_id' => $flashcard->id,
                    'status' => [
                        StudyProgress::STATUS_NEW,
                        StudyProgress::STATUS_LEARNING,
                        StudyProgress::STATUS_MASTERED,
                    ][$index % 3],
                    'last_reviewed_at' => now()->subDays($index),
                ]);
            }
        }

        User::factory(3)->create();
    }
}

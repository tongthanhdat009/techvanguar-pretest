<?php

namespace Database\Seeders;

use App\Models\Deck;
use App\Models\DeckReview;
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
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Programming',
                'tags' => ['laravel', 'php', 'backend'],
                'is_active' => true,
                'cards' => [
                    ['front' => 'What command creates a controller?', 'back' => 'Use php artisan make:controller ControllerName.', 'hint' => 'artisan'],
                    ['front' => 'What does Eloquent represent?', 'back' => 'Laravel\'s Active Record ORM for working with database models.', 'hint' => 'ORM'],
                    ['front' => 'Which file defines web routes?', 'back' => 'Routes for browser requests are usually defined in routes/web.php.', 'hint' => 'web.php'],
                ],
            ],
            [
                'title' => 'MySQL Basics',
                'description' => 'Practice data modeling, indexes, and query planning fundamentals.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Databases',
                'tags' => ['mysql', 'sql', 'database'],
                'is_active' => true,
                'cards' => [
                    ['front' => 'What is a primary key?', 'back' => 'A unique identifier for each row in a table.', 'hint' => 'row id'],
                    ['front' => 'Why add indexes?', 'back' => 'Indexes improve lookup speed for frequently queried columns.', 'hint' => 'lookup speed'],
                    ['front' => 'What is normalization?', 'back' => 'Organizing data to reduce redundancy and improve integrity.', 'hint' => 'reduce redundancy'],
                ],
            ],
            [
                'title' => 'Archived Practice Deck',
                'description' => 'An inactive deck that only admins should manage.',
                'visibility' => Deck::VISIBILITY_PRIVATE,
                'category' => 'Archive',
                'tags' => ['archived'],
                'is_active' => false,
                'cards' => [
                    ['front' => 'What is refactoring?', 'back' => 'Improving internal code structure without changing behavior.', 'hint' => 'behavior'],
                ],
            ],
        ])->map(function (array $deckData) {
            $deck = Deck::create([
                'title' => $deckData['title'],
                'description' => $deckData['description'],
                'visibility' => $deckData['visibility'],
                'category' => $deckData['category'],
                'tags' => $deckData['tags'],
                'is_active' => $deckData['is_active'],
            ]);

            collect($deckData['cards'])->each(function (array $card) use ($deck) {
                Flashcard::create([
                    'deck_id' => $deck->id,
                    'front_content' => $card['front'],
                    'back_content' => $card['back'],
                    'hint' => $card['hint'] ?? null,
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
                    'next_review_at' => now()->subDays(max(0, $index - 1)),
                    'review_count' => $index + 1,
                    'correct_streak' => max(0, $index - 1),
                ]);
            }
        }

        $personalDeck = Deck::create([
            'user_id' => $client->id,
            'title' => 'Thai Vocabulary Sprint',
            'description' => 'Personal study deck for travel phrases.',
            'visibility' => Deck::VISIBILITY_PRIVATE,
            'category' => 'Languages',
            'tags' => ['thai', 'travel'],
            'is_active' => true,
        ]);

        collect([
            ['front' => 'Hello', 'back' => 'Sawasdee', 'hint' => 'greeting'],
            ['front' => 'Thank you', 'back' => 'Khob khun', 'hint' => 'gratitude'],
        ])->each(function (array $card) use ($personalDeck) {
            Flashcard::create([
                'deck_id' => $personalDeck->id,
                'front_content' => $card['front'],
                'back_content' => $card['back'],
                'hint' => $card['hint'],
            ]);
        });

        DeckReview::create([
            'deck_id' => $decks->first()->id,
            'user_id' => $client->id,
            'rating' => 5,
            'comment' => 'Clear prompts and useful explanations.',
        ]);

        User::factory(3)->create();
    }
}

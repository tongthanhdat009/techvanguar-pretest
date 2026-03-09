<?php

namespace Database\Seeders;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeckSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin for system decks
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        if (!$admin) {
            $admin = User::factory()->admin()->create();
        }

        // Get some clients for personal decks
        $clients = User::where('role', User::ROLE_CLIENT)->limit(3)->get();
        if ($clients->isEmpty()) {
            $clients = collect([User::factory()->create()]);
        }

        // Public/Community decks (no owner)
        $communityDecks = [
            [
                'title' => 'Laravel Essentials',
                'description' => 'Master the fundamentals of Laravel framework including routing, middleware, Eloquent ORM, and more.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Programming',
                'tags' => ['laravel', 'php', 'backend', 'web-development'],
                'is_active' => true,
            ],
            [
                'title' => 'JavaScript ES6+ Features',
                'description' => 'Learn modern JavaScript features including arrow functions, destructuring, async/await, and modules.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Programming',
                'tags' => ['javascript', 'es6', 'frontend', 'web-development'],
                'is_active' => true,
            ],
            [
                'title' => 'Japanese Hiragana Basics',
                'description' => 'Learn all 46 basic Hiragana characters with pronunciation guides.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Languages',
                'tags' => ['japanese', 'hiragana', 'beginner', 'writing'],
                'is_active' => true,
            ],
            [
                'title' => 'Spanish Vocabulary - Food',
                'description' => 'Essential Spanish vocabulary for food, cooking, and dining.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Languages',
                'tags' => ['spanish', 'vocabulary', 'food', 'beginner'],
                'is_active' => true,
            ],
            [
                'title' => 'MySQL Fundamentals',
                'description' => 'Database concepts, SQL queries, indexes, and optimization techniques.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Databases',
                'tags' => ['mysql', 'sql', 'database', 'backend'],
                'is_active' => true,
            ],
            [
                'title' => 'Vue.js Components',
                'description' => 'Build reactive UI components with Vue.js 3 including Composition API.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Programming',
                'tags' => ['vue', 'javascript', 'frontend', 'components'],
                'is_active' => true,
            ],
            [
                'title' => 'Python for Data Science',
                'description' => 'Python basics focused on data manipulation with pandas and numpy.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Programming',
                'tags' => ['python', 'data-science', 'pandas', 'numpy'],
                'is_active' => true,
            ],
            [
                'title' => 'French Verbs - Present Tense',
                'description' => 'Common French verbs conjugated in the present tense.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Languages',
                'tags' => ['french', 'verbs', 'grammar', 'conjugation'],
                'is_active' => true,
            ],
            [
                'title' => 'AWS Cloud Practitioner',
                'description' => 'AWS services overview for Cloud Practitioner certification preparation.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'Certifications',
                'tags' => ['aws', 'cloud', 'certification', 'devops'],
                'is_active' => true,
            ],
            [
                'title' => 'Docker & Kubernetes Basics',
                'description' => 'Container orchestration fundamentals for modern deployment.',
                'visibility' => Deck::VISIBILITY_PUBLIC,
                'category' => 'DevOps',
                'tags' => ['docker', 'kubernetes', 'containers', 'devops'],
                'is_active' => true,
            ],
        ];

        foreach ($communityDecks as $deck) {
            Deck::firstOrCreate(
                ['title' => $deck['title']],
                [...$deck, 'user_id' => null]
            );
        }

        // Private decks for first client
        if ($clients->isNotEmpty()) {
            $privateDecks = [
                [
                    'title' => 'Personal Study Notes',
                    'description' => 'My personal study notes and key concepts.',
                    'visibility' => Deck::VISIBILITY_PRIVATE,
                    'category' => 'Study Notes',
                    'tags' => ['personal', 'notes', 'study'],
                    'is_active' => true,
                    'user_id' => $clients->first()->id,
                ],
                [
                    'title' => 'Interview Prep',
                    'description' => 'Technical interview questions and answers.',
                    'visibility' => Deck::VISIBILITY_PRIVATE,
                    'category' => 'Career',
                    'tags' => ['interview', 'career', 'technical'],
                    'is_active' => true,
                    'user_id' => $clients->first()->id,
                ],
            ];

            foreach ($privateDecks as $deck) {
                Deck::firstOrCreate(
                    ['title' => $deck['title'], 'user_id' => $deck['user_id']],
                    $deck
                );
            }
        }

        // Inactive deck
        Deck::firstOrCreate(
            ['title' => 'Legacy Content (Archived)'],
            [
                'description' => 'Old content kept for reference but not active.',
                'visibility' => Deck::VISIBILITY_PRIVATE,
                'category' => 'Archive',
                'tags' => ['archived', 'legacy'],
                'is_active' => false,
                'user_id' => $admin->id,
            ]
        );
    }
}

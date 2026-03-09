<?php

namespace Database\Seeders;

use App\Models\Deck;
use App\Models\DeckReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeckReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Get clients who can review
        $clients = User::where('role', User::ROLE_CLIENT)
            ->where('status', User::STATUS_ACTIVE)
            ->limit(8)
            ->get();

        if ($clients->isEmpty()) {
            return;
        }

        // Get public decks to review
        $decks = Deck::where('visibility', Deck::VISIBILITY_PUBLIC)
            ->where('is_active', true)
            ->get();

        if ($decks->isEmpty()) {
            return;
        }

        $sampleReviews = [
            ['rating' => 5, 'comment' => 'Excellent deck! Really helped me understand the concepts.'],
            ['rating' => 5, 'comment' => 'Well-structured and comprehensive. Highly recommend!'],
            ['rating' => 4, 'comment' => 'Good content, but could use more examples.'],
            ['rating' => 4, 'comment' => 'Solid study material. Cleared up many doubts.'],
            ['rating' => 5, 'comment' => 'Perfect for beginners! Easy to follow.'],
            ['rating' => 3, 'comment' => 'Useful but some cards are too similar.'],
            ['rating' => 5, 'comment' => 'Exactly what I needed for my exam prep.'],
            ['rating' => 4, 'comment' => 'Great variety of questions. Good practice.'],
            ['rating' => 5, 'comment' => 'Love the format. Makes studying efficient.'],
            ['rating' => 4, 'comment' => 'Comprehensive coverage of the topic.'],
            ['rating' => 3, 'comment' => 'Helpful but could be more detailed.'],
            ['rating' => 5, 'comment' => 'Outstanding quality! Created my own similar deck after this.'],
        ];

        $reviewIndex = 0;

        foreach ($clients as $clientIndex => $client) {
            // Each client reviews different number of decks
            $decksToReview = $decks->random(rand(2, 5));

            foreach ($decksToReview as $deck) {
                // Skip if review already exists
                if (DeckReview::where('deck_id', $deck->id)
                    ->where('user_id', $client->id)
                    ->exists()) {
                    continue;
                }

                $review = $sampleReviews[$reviewIndex % count($sampleReviews)];
                $reviewIndex++;

                // Vary ratings slightly per client
                $ratingVariation = rand(-1, 0);
                $finalRating = max(1, min(5, $review['rating'] + $ratingVariation));

                DeckReview::create([
                    'deck_id' => $deck->id,
                    'user_id' => $client->id,
                    'rating' => $finalRating,
                    'comment' => $review['comment'],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}

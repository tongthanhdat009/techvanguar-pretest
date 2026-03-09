<?php

namespace Database\Seeders;

use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudyProgressSeeder extends Seeder
{
    public function run(): void
    {
        // Get users to create progress for
        $users = User::where('role', User::ROLE_CLIENT)
            ->where('status', User::STATUS_ACTIVE)
            ->limit(5)
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        // Get flashcards from public decks
        $flashcards = Flashcard::whereHas('deck', function ($query) {
            $query->where('visibility', 'public')
                ->where('is_active', true);
        })->limit(50)->get();

        if ($flashcards->isEmpty()) {
            return;
        }

        $statuses = [
            StudyProgress::STATUS_NEW,
            StudyProgress::STATUS_LEARNING,
            StudyProgress::STATUS_MASTERED,
        ];

        $now = now();

        // Create progress entries for each user
        foreach ($users as $userIndex => $user) {
            // Progress varies by user - some more advanced than others
            $flashcardCount = max(10, 30 - ($userIndex * 5));
            $userFlashcards = $flashcards->take($flashcardCount);

            foreach ($userFlashcards as $index => $flashcard) {
                // Skip if progress already exists
                if (StudyProgress::where('user_id', $user->id)
                    ->where('flashcard_id', $flashcard->id)
                    ->exists()) {
                    continue;
                }

                // Distribute statuses based on position
                // Earlier cards more likely to be mastered
                $statusWeights = [
                    StudyProgress::STATUS_NEW => max(0.1, 0.5 - ($index * 0.02)),
                    StudyProgress::STATUS_LEARNING => 0.3,
                    StudyProgress::STATUS_MASTERED => min(0.6, $index * 0.03),
                ];

                $status = $this->selectStatus($statusWeights);

                // Generate review dates
                $reviewCount = match ($status) {
                    StudyProgress::STATUS_MASTERED => rand(5, 15),
                    StudyProgress::STATUS_LEARNING => rand(2, 6),
                    default => 0,
                };

                $correctStreak = match ($status) {
                    StudyProgress::STATUS_MASTERED => rand(3, 8),
                    StudyProgress::STATUS_LEARNING => rand(1, 3),
                    default => 0,
                };

                $lastReviewedAt = match ($status) {
                    StudyProgress::STATUS_NEW => null,
                    StudyProgress::STATUS_LEARNING => $now->subHours(rand(1, 48)),
                    StudyProgress::STATUS_MASTERED => $now->subDays(rand(1, 7)),
                };

                $nextReviewAt = match ($status) {
                    StudyProgress::STATUS_NEW => $now,
                    StudyProgress::STATUS_LEARNING => $now->addHours(rand(4, 24)),
                    StudyProgress::STATUS_MASTERED => $now->addDays(rand(3, 14)),
                };

                StudyProgress::create([
                    'user_id' => $user->id,
                    'flashcard_id' => $flashcard->id,
                    'status' => $status,
                    'last_reviewed_at' => $lastReviewedAt,
                    'next_review_at' => $nextReviewAt,
                    'review_count' => $reviewCount,
                    'correct_streak' => $correctStreak,
                ]);
            }
        }
    }

    private function selectStatus(array $weights): string
    {
        $statuses = [
            StudyProgress::STATUS_NEW,
            StudyProgress::STATUS_LEARNING,
            StudyProgress::STATUS_MASTERED,
        ];

        $rand = mt_rand(0, 100);
        $cumulative = 0;

        foreach ($statuses as $status) {
            $cumulative += ($weights[$status] ?? 0) * 100;
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        return StudyProgress::STATUS_NEW;
    }
}

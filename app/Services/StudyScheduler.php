<?php

namespace App\Services;

use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use Carbon\CarbonInterface;

class StudyScheduler
{
    public function recordReview(User $user, Flashcard $flashcard, string $status, ?string $result = null): StudyProgress
    {
        $progress = StudyProgress::firstOrNew([
            'user_id' => $user->id,
            'flashcard_id' => $flashcard->id,
        ]);

        $reviewCount = (int) $progress->review_count + 1;
        $correctStreak = in_array($result, ['good', 'easy'], true)
            ? (int) $progress->correct_streak + 1
            : (in_array($status, [StudyProgress::STATUS_LEARNING, StudyProgress::STATUS_MASTERED], true)
                ? max(1, (int) $progress->correct_streak)
                : 0);

        $nextReviewAt = $this->nextReviewAt($status, $result, $correctStreak);

        $progress->fill([
            'status' => $status,
            'last_reviewed_at' => now(),
            'next_review_at' => $nextReviewAt,
            'review_count' => $reviewCount,
            'correct_streak' => $correctStreak,
        ])->save();

        $this->awardStudyXp($user, $status, $result);

        return $progress->fresh(['flashcard.deck']);
    }

    public function dueTodayCount(User $user): int
    {
        return $user->studyProgress()
            ->where(function ($query) {
                $query->whereNull('next_review_at')
                    ->orWhere('next_review_at', '<=', now());
            })
            ->where(function ($query) {
                // Exclude cards already reviewed today
                $query->whereNull('last_reviewed_at')
                    ->orWhere('last_reviewed_at', '<', now()->startOfDay());
            })
            ->count();
    }

    private function nextReviewAt(string $status, ?string $result, int $correctStreak): CarbonInterface
    {
        if ($result === 'again') {
            return now()->addMinutes(15);
        }

        if ($result === 'hard') {
            return now()->addDay();
        }

        $days = match ($status) {
            StudyProgress::STATUS_NEW => 1,
            StudyProgress::STATUS_LEARNING => max(1, min(7, 2 ** max(0, $correctStreak - 1))),
            StudyProgress::STATUS_MASTERED => max(3, min(30, 3 * max(1, $correctStreak))),
            default => 1,
        };

        if ($result === 'easy') {
            $days = min(45, $days + 3);
        }

        return now()->addDays($days)->endOfDay();
    }

    private function awardStudyXp(User $user, string $status, ?string $result): void
    {
        $xp = match (true) {
            $result === 'easy' => 25,
            $status === StudyProgress::STATUS_MASTERED => 20,
            $status === StudyProgress::STATUS_LEARNING => 12,
            default => 8,
        };

        $today = now()->startOfDay();
        $lastStudied = $user->last_studied_at?->startOfDay();
        $dailyStreak = $user->daily_streak;

        if (! $lastStudied || $lastStudied->lt($today)) {
            $dailyStreak = $lastStudied && $lastStudied->equalTo($today->copy()->subDay())
                ? $dailyStreak + 1
                : 1;
        }

        $user->forceFill([
            'experience_points' => $user->experience_points + $xp,
            'daily_streak' => $dailyStreak,
            'last_studied_at' => now(),
        ])->save();
    }
}

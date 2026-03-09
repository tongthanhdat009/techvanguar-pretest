<?php

namespace Tests\Unit\Services;

use App\Models\Flashcard;
use App\Models\Deck;
use App\Models\StudyProgress;
use App\Models\User;
use App\Services\StudyScheduler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudySchedulerTest extends TestCase
{
    use RefreshDatabase;

    public function test_again_result_resets_streak_and_schedules_quick_retry(): void
    {
        $this->travelTo(now()->setDate(2026, 3, 7)->setTime(9, 0));

        $user = User::factory()->create([
            'experience_points' => 0,
            'daily_streak' => 0,
            'last_studied_at' => null,
        ]);
        $flashcard = Flashcard::factory()->create();

        $progress = app(StudyScheduler::class)->recordReview(
            $user,
            $flashcard,
            StudyProgress::STATUS_NEW,
            'again'
        );

        $this->assertSame(0, $progress->correct_streak);
        $this->assertSame(1, $progress->review_count);
        $this->assertTrue($progress->next_review_at->equalTo(now()->addMinutes(15)));
        $this->assertSame(8, $user->fresh()->experience_points);
        $this->assertSame(1, $user->fresh()->daily_streak);
    }

    public function test_learning_good_result_increases_interval_from_current_streak(): void
    {
        $this->travelTo(now()->setDate(2026, 3, 7)->setTime(10, 30));

        $user = User::factory()->create([
            'experience_points' => 10,
            'daily_streak' => 2,
            'last_studied_at' => now(),
        ]);
        $flashcard = Flashcard::factory()->create();

        StudyProgress::factory()->create([
            'user_id' => $user->id,
            'flashcard_id' => $flashcard->id,
            'status' => StudyProgress::STATUS_LEARNING,
            'review_count' => 1,
            'correct_streak' => 1,
        ]);

        $progress = app(StudyScheduler::class)->recordReview(
            $user,
            $flashcard,
            StudyProgress::STATUS_LEARNING,
            'good'
        );

        $this->assertSame(2, $progress->correct_streak);
        $this->assertSame(2, $progress->review_count);
        $this->assertSame(now()->addDays(2)->endOfDay()->toDateTimeString(), $progress->next_review_at->toDateTimeString());
        $this->assertSame(22, $user->fresh()->experience_points);
        $this->assertSame(2, $user->fresh()->daily_streak);
    }

    public function test_mastered_easy_result_adds_bonus_days_and_keeps_streak_progression(): void
    {
        $this->travelTo(now()->setDate(2026, 3, 7)->setTime(14, 15));

        $user = User::factory()->create([
            'experience_points' => 30,
            'daily_streak' => 3,
            'last_studied_at' => now()->subDay(),
        ]);
        $flashcard = Flashcard::factory()->create();

        StudyProgress::factory()->create([
            'user_id' => $user->id,
            'flashcard_id' => $flashcard->id,
            'status' => StudyProgress::STATUS_MASTERED,
            'review_count' => 4,
            'correct_streak' => 2,
        ]);

        $progress = app(StudyScheduler::class)->recordReview(
            $user,
            $flashcard,
            StudyProgress::STATUS_MASTERED,
            'easy'
        );

        $this->assertSame(3, $progress->correct_streak);
        $this->assertSame(5, $progress->review_count);
        $this->assertSame(now()->addDays(12)->endOfDay()->toDateTimeString(), $progress->next_review_at->toDateTimeString());
        $this->assertSame(55, $user->fresh()->experience_points);
        $this->assertSame(4, $user->fresh()->daily_streak);
    }

    public function test_due_today_count_only_includes_unscheduled_or_due_progress(): void
    {
        $user = User::factory()->create();

        StudyProgress::factory()->count(2)->create([
            'user_id' => $user->id,
            'last_reviewed_at' => now()->subDay(),
            'next_review_at' => null,
        ]);
        StudyProgress::factory()->create([
            'user_id' => $user->id,
            'last_reviewed_at' => now()->subDay(),
            'next_review_at' => now()->subHour(),
        ]);
        StudyProgress::factory()->create([
            'user_id' => $user->id,
            'last_reviewed_at' => now()->subDay(),
            'next_review_at' => now()->addDay(),
        ]);

        $this->assertSame(3, app(StudyScheduler::class)->dueTodayCount($user));
    }

    public function test_due_today_count_does_not_fall_back_to_upcoming_cards_when_nothing_is_due(): void
    {
        $this->travelTo(now()->setDate(2026, 3, 8)->setTime(13, 0));

        $user = User::factory()->create();

        StudyProgress::factory()->count(2)->create([
            'user_id' => $user->id,
            'last_reviewed_at' => now(),
            'next_review_at' => now()->addDays(5),
        ]);

        $this->assertSame(0, app(StudyScheduler::class)->dueTodayCount($user));
    }

    public function test_due_today_count_ignores_untracked_public_flashcards_for_a_new_user(): void
    {
        $user = User::factory()->create();
        $publicDeck = Deck::factory()->create([
            'visibility' => Deck::VISIBILITY_PUBLIC,
            'is_active' => true,
        ]);

        Flashcard::factory()->count(3)->create([
            'deck_id' => $publicDeck->id,
        ]);

        $this->assertSame(0, app(StudyScheduler::class)->dueTodayCount($user));
    }
}
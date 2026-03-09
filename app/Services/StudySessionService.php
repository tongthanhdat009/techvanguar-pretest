<?php

namespace App\Services;

use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\User;
use App\Support\DeckAccess;
use Illuminate\Support\Collection;

class StudySessionService
{
    /**
     * Get study candidates for a user, optionally filtered by deck.
     *
     * @return \Illuminate\Support\Collection<int, \App\Models\Flashcard>
     */
    public function getStudyCandidates(User $user, ?Deck $deck = null, int $limit = 40): Collection
    {
        $deckIds = $this->getAccessibleDeckIds($user, $deck);

        $flashcards = $this->fetchFlashcardsWithProgress($user, $deckIds);

        $dueFlashcards = $this->filterDueFlashcards($user, $flashcards, $deck);

        return $this->prioritizeFlashcards($dueFlashcards, $flashcards, $limit);
    }

    /**
     * Prepare study cards with choices for multiple-choice mode.
     */
    public function prepareStudyCards(User $user, ?Deck $deck = null, int $cardLimit = 12, int $poolSize = 24): Collection
    {
        $flashcards = $this->getStudyCandidates($user, $deck, $cardLimit);

        return $this->mapStudyCards(
            $flashcards,
            $this->buildAnswerPool($user, $deck, $poolSize)
        );
    }

    /**
     * Prepare a full deck study session.
     *
     * Deck-specific study should keep the whole deck in one session so the
     * client can finish or restart the current deck predictably.
     */
    public function prepareDeckStudyCards(User $user, Deck $deck): Collection
    {
        $flashcards = $this->fetchFlashcardsWithProgress($user, collect([$deck->id]));

        return $this->mapStudyCards(
            $flashcards,
            $this->buildAnswerPoolFromFlashcards($flashcards)
        );
    }

    /**
     * Get deck IDs accessible to the user.
     *
     * @return \Illuminate\Support\Collection<int, int>
     */
    private function getAccessibleDeckIds(User $user, ?Deck $deck): Collection
    {
        $query = app(DeckAccess::class)->accessibleQuery($user);

        if ($deck) {
            $query->whereKey($deck->id);
        }

        return $query->pluck('id');
    }

    /**
     * Fetch flashcards with user's study progress.
     */
    private function fetchFlashcardsWithProgress(User $user, Collection $deckIds): Collection
    {
        return Flashcard::query()
            ->whereIn('deck_id', $deckIds)
            ->with([
                'deck',
                'studyProgress' => fn ($query) => $query->where('user_id', $user->id),
            ])
            ->get()
            ->sortBy(fn (Flashcard $flashcard) => $flashcard->studyProgress->first()?->next_review_at ?? now()->subYear())
            ->values();
    }

    /**
     * Filter flashcards that are due for review.
     */
    private function filterDueFlashcards(User $user, Collection $flashcards, ?Deck $deck = null): Collection
    {
        return $flashcards->filter(function (Flashcard $flashcard) use ($user, $deck) {
            $progress = $flashcard->studyProgress->first();

            if (! $progress) {
                return $deck !== null || (int) $flashcard->deck?->user_id === (int) $user->id;
            }

            if ($progress->last_reviewed_at && $progress->last_reviewed_at->gte(now()->startOfDay())) {
                return false;
            }

            return ! $progress
                || ! $progress->next_review_at
                || $progress->next_review_at->lte(now());
        })->values();
    }

    /**
     * Prioritize due cards over upcoming cards.
     */
    private function prioritizeFlashcards(Collection $dueFlashcards, Collection $allFlashcards, int $limit): Collection
    {
        return $dueFlashcards->take($limit)->values();
    }

    /**
     * Build a pool of answers for multiple-choice questions.
     *
    * @return \Illuminate\Support\Collection<int, string>
     */
    private function buildAnswerPool(User $user, ?Deck $deck, int $poolSize): Collection
    {
        return $this->buildAnswerPoolFromFlashcards(
            $this->getStudyCandidates($user, $deck, $poolSize),
            $poolSize
        );
    }

    /**
     * Convert flashcards into the frontend payload used by the study room.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Flashcard>  $flashcards
     * @param  \Illuminate\Support\Collection<int, string>  $answerPool
     */
    private function mapStudyCards(Collection $flashcards, Collection $answerPool): Collection
    {
        return $flashcards->map(function (Flashcard $flashcard) use ($answerPool) {
            return [
                'flashcard' => $flashcard,
                'progress' => $flashcard->studyProgress->first(),
                'choices' => $this->generateChoices($answerPool, $flashcard->back_content),
            ];
        })->values();
    }

    /**
     * Build an answer pool from an existing flashcard collection.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Flashcard>  $flashcards
     * @return \Illuminate\Support\Collection<int, string>
     */
    private function buildAnswerPoolFromFlashcards(Collection $flashcards, ?int $poolSize = null): Collection
    {
        $pool = $flashcards
            ->pluck('back_content')
            ->filter()
            ->unique()
            ->values();

        return $poolSize === null
            ? $pool
            : $pool->take($poolSize)->values();
    }

    /**
     * Generate multiple choice options with the correct answer.
     */
    private function generateChoices(Collection $answerPool, string $correctAnswer): Collection
    {
        return $answerPool
            ->reject(fn (string $answer) => $answer === $correctAnswer)
            ->shuffle()
            ->take(3)
            ->push($correctAnswer)
            ->shuffle()
            ->values();
    }
}

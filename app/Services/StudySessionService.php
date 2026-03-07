<?php

namespace App\Services;

use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\User;
use App\Support\DeckAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StudySessionService
{
    /**
     * Get study candidates for a user, optionally filtered by deck.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deck|null  $deck
     * @param  int  $limit
     * @return \Illuminate\Support\Collection<int, \App\Models\Flashcard>
     */
    public function getStudyCandidates(User $user, ?Deck $deck = null, int $limit = 40): Collection
    {
        $deckIds = $this->getAccessibleDeckIds($user, $deck);

        $flashcards = $this->fetchFlashcardsWithProgress($user, $deckIds);

        $dueFlashcards = $this->filterDueFlashcards($flashcards);

        return $this->prioritizeFlashcards($dueFlashcards, $flashcards, $limit);
    }

    /**
     * Prepare study cards with choices for multiple-choice mode.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deck|null  $deck
     * @param  int  $cardLimit
     * @param  int  $poolSize
     * @return \Illuminate\Support\Collection
     */
    public function prepareStudyCards(User $user, ?Deck $deck = null, int $cardLimit = 12, int $poolSize = 24): Collection
    {
        $flashcards = $this->getStudyCandidates($user, $deck, $cardLimit);
        $answerPool = $this->buildAnswerPool($user, $deck, $poolSize);

        return $flashcards->map(function (Flashcard $flashcard) use ($answerPool) {
            return [
                'flashcard' => $flashcard,
                'progress' => $flashcard->studyProgress->first(),
                'choices' => $this->generateChoices($answerPool, $flashcard->back_content),
            ];
        })->values();
    }

    /**
     * Get deck IDs accessible to the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deck|null  $deck
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
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Support\Collection  $deckIds
     * @return \Illuminate\Support\Collection
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
     *
     * @param  \Illuminate\Support\Collection  $flashcards
     * @return \Illuminate\Support\Collection
     */
    private function filterDueFlashcards(Collection $flashcards): Collection
    {
        return $flashcards->filter(function (Flashcard $flashcard) {
            $progress = $flashcard->studyProgress->first();

            return ! $progress
                || ! $progress->next_review_at
                || $progress->next_review_at->lte(now());
        })->values();
    }

    /**
     * Prioritize due cards over upcoming cards.
     *
     * @param  \Illuminate\Support\Collection  $dueFlashcards
     * @param  \Illuminate\Support\Collection  $allFlashcards
     * @param  int  $limit
     * @return \Illuminate\Support\Collection
     */
    private function prioritizeFlashcards(Collection $dueFlashcards, Collection $allFlashcards, int $limit): Collection
    {
        $source = $dueFlashcards->isNotEmpty() ? $dueFlashcards : $allFlashcards;

        return $source->take($limit)->values();
    }

    /**
     * Build a pool of answers for multiple-choice questions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deck|null  $deck
     * @param  int  $poolSize
     * @return \Illuminate\SupportCollection
     */
    private function buildAnswerPool(User $user, ?Deck $deck, int $poolSize): Collection
    {
        return $this->getStudyCandidates($user, $deck, $poolSize)
            ->pluck('back_content')
            ->filter()
            ->unique()
            ->values();
    }

    /**
     * Generate multiple choice options with the correct answer.
     *
     * @param  \Illuminate\Support\Collection  $answerPool
     * @param  string  $correctAnswer
     * @return \Illuminate\Support\Collection
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

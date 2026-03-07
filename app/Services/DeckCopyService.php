<?php

namespace App\Services;

use App\Models\Deck;
use App\Models\User;

class DeckCopyService
{
    /**
     * Copy a public deck to a user's library.
     *
     * @param  \App\Models\Deck  $deck  The deck to copy (must be public and active)
     * @param  \App\Models\User  $user  The user who will own the copied deck
     * @return \App\Models\Deck The newly created deck copy
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function copyToUser(Deck $deck, User $user): Deck
    {
        abort_unless($this->canCopy($deck), 403, 'This deck cannot be copied.');

        $copy = $user->decks()->create([
            'title' => $this->generateCopyTitle($deck->title),
            'description' => $deck->description,
            'visibility' => Deck::VISIBILITY_PRIVATE,
            'category' => $deck->category,
            'tags' => $deck->tags,
            'source_deck_id' => $deck->id,
            'is_active' => true,
        ]);

        $this->copyFlashcards($deck, $copy);

        return $copy;
    }

    /**
     * Check if a deck can be copied.
     *
     * @param  \App\Models\Deck  $deck
     * @return bool
     */
    public function canCopy(Deck $deck): bool
    {
        return $deck->visibility === Deck::VISIBILITY_PUBLIC
            && $deck->is_active;
    }

    /**
     * Generate a title for the copied deck.
     *
     * @param  string  $originalTitle
     * @return string
     */
    private function generateCopyTitle(string $originalTitle): string
    {
        return $originalTitle.' (Copy)';
    }

    /**
     * Copy all flashcards from source deck to destination deck.
     *
     * @param  \App\Models\Deck  $source
     * @param  \App\Models\Deck  $destination
     * @return void
     */
    private function copyFlashcards(Deck $source, Deck $destination): void
    {
        $source->load('flashcards');

        foreach ($source->flashcards as $flashcard) {
            $destination->flashcards()->create([
                'front_content' => $flashcard->front_content,
                'back_content' => $flashcard->back_content,
                'image_url' => $flashcard->image_url,
                'audio_url' => $flashcard->audio_url,
                'hint' => $flashcard->hint,
            ]);
        }
    }
}

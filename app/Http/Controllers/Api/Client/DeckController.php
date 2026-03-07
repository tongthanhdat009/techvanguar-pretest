<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use App\Models\User;
use App\Support\DeckAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeckController extends Controller
{
    public function index(DeckAccess $deckAccess): JsonResponse
    {
        /** @var User $user */
        $user = request()->user();

        $decks = $deckAccess->accessibleQuery($user)
            ->withCount('flashcards')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        return response()->json($decks);
    }

    public function show(Request $request, Deck $deck, DeckAccess $deckAccess): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($deckAccess->canAccess($user, $deck), Response::HTTP_NOT_FOUND);

        $deck->load(['flashcards.studyProgress' => fn ($query) => $query->where('user_id', $user->id)]);
        $deck->loadAvg('reviews', 'rating');

        return response()->json([
            'id' => $deck->id,
            'title' => $deck->title,
            'description' => $deck->description,
            'is_active' => $deck->is_active,
            'visibility' => $deck->visibility,
            'category' => $deck->category,
            'tags' => $deck->tags,
            'reviews_avg_rating' => $deck->reviews_avg_rating,
            'flashcards' => $deck->flashcards->map(fn ($flashcard) => [
                'id' => $flashcard->id,
                'front_content' => $flashcard->front_content,
                'back_content' => $flashcard->back_content,
                'image_url' => $flashcard->image_url,
                'audio_url' => $flashcard->audio_url,
                'hint' => $flashcard->hint,
                'progress_status' => optional($flashcard->studyProgress->first())->status,
                'last_reviewed_at' => optional($flashcard->studyProgress->first())->last_reviewed_at,
                'next_review_at' => optional($flashcard->studyProgress->first())->next_review_at,
            ]),
        ]);
    }
}

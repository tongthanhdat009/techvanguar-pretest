<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeckController extends Controller
{
    public function index(): JsonResponse
    {
        $decks = Deck::query()
            ->active()
            ->withCount('flashcards')
            ->latest()
            ->get();

        return response()->json($decks);
    }

    public function show(Request $request, Deck $deck): JsonResponse
    {
        abort_unless($deck->is_active, Response::HTTP_NOT_FOUND);

        /** @var User $user */
        $user = $request->user();

        $deck->load(['flashcards.studyProgress' => fn ($query) => $query->where('user_id', $user->id)]);

        return response()->json([
            'id' => $deck->id,
            'title' => $deck->title,
            'description' => $deck->description,
            'is_active' => $deck->is_active,
            'flashcards' => $deck->flashcards->map(fn ($flashcard) => [
                'id' => $flashcard->id,
                'front_content' => $flashcard->front_content,
                'back_content' => $flashcard->back_content,
                'progress_status' => optional($flashcard->studyProgress->first())->status,
                'last_reviewed_at' => optional($flashcard->studyProgress->first())->last_reviewed_at,
            ]),
        ]);
    }
}

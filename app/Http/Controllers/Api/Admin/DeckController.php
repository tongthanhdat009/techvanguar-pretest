<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class DeckController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Deck::query()->withCount('flashcards')->latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'visibility' => ['nullable', Rule::in([Deck::VISIBILITY_PRIVATE, Deck::VISIBILITY_PUBLIC])],
            'category' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'source_deck_id' => ['nullable', 'exists:decks,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        $validated['visibility'] = $validated['visibility'] ?? Deck::VISIBILITY_PUBLIC;
        $deck = Deck::create($validated);

        return response()->json($deck, Response::HTTP_CREATED);
    }

    public function show(Deck $deck): JsonResponse
    {
        return response()->json($deck->load('flashcards'));
    }

    public function update(Request $request, Deck $deck): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'visibility' => ['nullable', Rule::in([Deck::VISIBILITY_PRIVATE, Deck::VISIBILITY_PUBLIC])],
            'category' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'source_deck_id' => ['nullable', 'exists:decks,id'],
            'is_active' => ['sometimes', 'required', 'boolean'],
        ]);

        $deck->update($validated);

        return response()->json($deck->fresh()->load('flashcards'));
    }

    public function destroy(Deck $deck): JsonResponse
    {
        $deck->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}

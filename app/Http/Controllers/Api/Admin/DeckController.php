<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

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
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
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

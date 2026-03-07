<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FlashcardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Flashcard::query()->with('deck')->latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'deck_id' => ['required', 'exists:decks,id'],
            'front_content' => ['required', 'string'],
            'back_content' => ['required', 'string'],
        ]);

        $flashcard = Flashcard::create($validated);

        return response()->json($flashcard->load('deck'), Response::HTTP_CREATED);
    }

    public function show(Flashcard $flashcard): JsonResponse
    {
        return response()->json($flashcard->load('deck'));
    }

    public function update(Request $request, Flashcard $flashcard): JsonResponse
    {
        $validated = $request->validate([
            'deck_id' => ['sometimes', 'required', 'exists:decks,id'],
            'front_content' => ['sometimes', 'required', 'string'],
            'back_content' => ['sometimes', 'required', 'string'],
        ]);

        $flashcard->update($validated);

        return response()->json($flashcard->fresh()->load('deck'));
    }

    public function destroy(Flashcard $flashcard): JsonResponse
    {
        $flashcard->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}

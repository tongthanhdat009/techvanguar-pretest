<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StudyProgressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $progress = StudyProgress::query()
            ->where('user_id', $user->id)
            ->with('flashcard.deck')
            ->latest('last_reviewed_at')
            ->get();

        return response()->json($progress);
    }

    public function update(Request $request, Flashcard $flashcard): JsonResponse
    {
        abort_unless($flashcard->deck()->where('is_active', true)->exists(), Response::HTTP_NOT_FOUND);

        $validated = $request->validate([
            'status' => ['required', Rule::in(StudyProgress::statuses())],
        ]);

        /** @var User $user */
        $user = $request->user();

        $progress = StudyProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'flashcard_id' => $flashcard->id,
            ],
            [
                'status' => $validated['status'],
                'last_reviewed_at' => now(),
            ]
        );

        return response()->json($progress->load('flashcard.deck'));
    }
}

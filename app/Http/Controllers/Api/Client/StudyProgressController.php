<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordStudyProgressRequest;
use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use App\Services\StudyScheduler;
use App\Support\DeckAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function update(RecordStudyProgressRequest $request, Flashcard $flashcard, StudyScheduler $scheduler, DeckAccess $deckAccess): JsonResponse
    {
        $flashcard->load('deck');
        $validated = $request->validated();

        /** @var User $user */
        $user = $request->user();

        abort_unless($deckAccess->canAccess($user, $flashcard->deck), Response::HTTP_NOT_FOUND);

        $progress = $scheduler->recordReview(
            $user,
            $flashcard,
            $validated['status'],
            $validated['result'] ?? null
        );

        return response()->json($progress->load('flashcard.deck'));
    }
}

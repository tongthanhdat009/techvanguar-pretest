<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ClientPortalController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $decks = Deck::query()
            ->active()
            ->with(['flashcards.studyProgress' => fn ($query) => $query->where('user_id', $user->id)])
            ->get();

        $progressSummary = [
            'new' => $user->studyProgress()->where('status', StudyProgress::STATUS_NEW)->count(),
            'learning' => $user->studyProgress()->where('status', StudyProgress::STATUS_LEARNING)->count(),
            'mastered' => $user->studyProgress()->where('status', StudyProgress::STATUS_MASTERED)->count(),
        ];

        return view('client.portal', [
            'decks' => $decks,
            'progressSummary' => $progressSummary,
            'statuses' => StudyProgress::statuses(),
        ]);
    }

    public function updateProgress(Request $request, Flashcard $flashcard): RedirectResponse
    {
        abort_unless($flashcard->deck()->where('is_active', true)->exists(), Response::HTTP_NOT_FOUND);

        $validated = $request->validate([
            'status' => ['required', Rule::in(StudyProgress::statuses())],
        ]);

        StudyProgress::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'flashcard_id' => $flashcard->id,
            ],
            [
                'status' => $validated['status'],
                'last_reviewed_at' => now(),
            ]
        );

        return back()->with('status', 'Study progress updated successfully.');
    }
}

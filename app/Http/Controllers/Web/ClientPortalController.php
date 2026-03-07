<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientDeckImportRequest;
use App\Http\Requests\ClientDeckRequest;
use App\Http\Requests\ClientFlashcardRequest;
use App\Http\Requests\RecordStudyProgressRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Deck;
use App\Models\DeckReview;
use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use App\Services\CsvExportService;
use App\Services\CsvImportService;
use App\Services\DeckCopyService;
use App\Services\StudySessionService;
use App\Services\StudyScheduler;
use App\Support\DeckAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientPortalController extends Controller
{
    public function index(Request $request, StudyScheduler $scheduler): View
    {
        /** @var User $user */
        $user = $request->user();

        $ownedDecks = $user->decks()
            ->withCount('flashcards')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        $communityDecks = Deck::query()
            ->active()
            ->public()
            ->where(function (Builder $query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', '!=', $user->id);
            })
            ->withCount('flashcards')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(6)
            ->get();

        $dueCards = $this->studyCandidates($user)->take(8);
        $reviewTimeline = $user->studyProgress()
            ->with('flashcard.deck')
            ->whereNotNull('next_review_at')
            ->orderBy('next_review_at')
            ->take(6)
            ->get();

        return view('client.dashboard', [
            'ownedDecks' => $ownedDecks,
            'communityDecks' => $communityDecks,
            'dueCards' => $dueCards,
            'reviewTimeline' => $reviewTimeline,
            'leaderboard' => User::query()->orderByDesc('experience_points')->take(5)->get(),
            'progressSummary' => [
                'new' => $user->studyProgress()->where('status', StudyProgress::STATUS_NEW)->count(),
                'learning' => $user->studyProgress()->where('status', StudyProgress::STATUS_LEARNING)->count(),
                'mastered' => $user->studyProgress()->where('status', StudyProgress::STATUS_MASTERED)->count(),
                'due_today' => $scheduler->dueTodayCount($user),
            ],
            'deckDefaults' => [
                'visibility' => Deck::VISIBILITY_PRIVATE,
                'is_active' => true,
            ],
        ]);
    }

    public function showDeck(Request $request, Deck $deck): View
    {
        /** @var User $user */
        $user = $request->user();

        $this->ensureDeckAccessible($user, $deck);

        $deck->load([
            'owner',
            'flashcards.studyProgress' => fn ($query) => $query->where('user_id', $user->id),
            'reviews.user',
        ])->loadAvg('reviews', 'rating');

        return view('client.deck', [
            'deck' => $deck,
            'canManageDeck' => $deck->user_id === $user->id,
            'reviewForm' => $deck->reviews->firstWhere('user_id', $user->id),
        ]);
    }

    public function studyAll(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return $this->studyView($user, null, (string) $request->query('mode', 'flip'));
    }

    public function studyDeck(Request $request, Deck $deck): View
    {
        /** @var User $user */
        $user = $request->user();

        $this->ensureDeckAccessible($user, $deck);

        return $this->studyView($user, $deck, (string) $request->query('mode', 'flip'));
    }

    public function updateProgress(RecordStudyProgressRequest $request, Flashcard $flashcard, StudyScheduler $scheduler, DeckAccess $deckAccess): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $flashcard->load('deck');

        abort_unless($deckAccess->canAccess($user, $flashcard->deck), Response::HTTP_NOT_FOUND);

        $validated = $request->validated();

        $scheduler->recordReview(
            $user,
            $flashcard,
            $validated['status'],
            $validated['result'] ?? null
        );

        return back()->with('status', 'Study progress updated.');
    }

    public function storeDeck(ClientDeckRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->decks()->create($request->validatedPayload());

        return redirect()->route('client.portal')->with('status', 'Deck created.');
    }

    public function updateDeck(ClientDeckRequest $request, Deck $deck): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->ensureDeckOwner($user, $deck);

        $deck->update($request->validatedPayload());

        return redirect()->route('client.decks.show', $deck)->with('status', 'Deck updated.');
    }

    public function destroyDeck(Request $request, Deck $deck): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->ensureDeckOwner($user, $deck);

        $deck->delete();

        return redirect()->route('client.portal')->with('status', 'Deck removed.');
    }

    public function storeFlashcard(ClientFlashcardRequest $request, Deck $deck): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->ensureDeckOwner($user, $deck);

        $deck->flashcards()->create($request->validated());

        return redirect()->route('client.decks.show', $deck)->with('status', 'Flashcard added.');
    }

    public function updateFlashcard(ClientFlashcardRequest $request, Flashcard $flashcard): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $flashcard->load('deck');
        $this->ensureDeckOwner($user, $flashcard->deck);

        $flashcard->update($request->validated());

        return redirect()->route('client.decks.show', $flashcard->deck)->with('status', 'Flashcard updated.');
    }

    public function destroyFlashcard(Request $request, Flashcard $flashcard): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $flashcard->load('deck');
        $this->ensureDeckOwner($user, $flashcard->deck);
        $deck = $flashcard->deck;

        $flashcard->delete();

        return redirect()->route('client.decks.show', $deck)->with('status', 'Flashcard deleted.');
    }

    public function copyDeck(Request $request, Deck $deck, DeckCopyService $deckCopy): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $copy = $deckCopy->copyToUser($deck, $user);

        return redirect()->route('client.decks.show', $copy)->with('status', 'Deck copied to your library.');
    }

    public function storeReview(Request $request, Deck $deck, DeckAccess $deckAccess): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($deckAccess->canCloneOrReview($deck), Response::HTTP_NOT_FOUND);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1200'],
        ]);

        DeckReview::updateOrCreate(
            ['deck_id' => $deck->id, 'user_id' => $user->id],
            $validated
        );

        return redirect()->route('client.decks.show', $deck)->with('status', 'Review saved.');
    }

    public function profile(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('client.profile', [
            'user' => $user->loadCount('decks'),
            'stats' => [
                'reviews' => $user->studyProgress()->count(),
                'mastered' => $user->studyProgress()->where('status', StudyProgress::STATUS_MASTERED)->count(),
                'due_today' => $user->studyProgress()
                    ->where(function ($query) {
                        $query->whereNull('next_review_at')
                            ->orWhere('next_review_at', '<=', now());
                    })->count(),
            ],
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->update($request->validated());

        return redirect()->route('client.profile')->with('status', 'Profile updated.');
    }

    public function importDeck(ClientDeckImportRequest $request, CsvImportService $csvImport): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validatedPayload();

        $deck = $user->decks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'visibility' => $validated['visibility'],
            'tags' => $validated['tags'],
            'is_active' => true,
        ]);

        $importedCount = $csvImport->importFlashcards($deck, $request->file('csv_file'));

        return redirect()
            ->route('client.decks.show', $deck)
            ->with('status', "Deck imported from CSV. {$importedCount} flashcard(s) added.");
    }

    public function exportDeck(Request $request, Deck $deck, CsvExportService $csvExport): StreamedResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->ensureDeckAccessible($user, $deck);

        return $csvExport->exportFlashcards($deck);
    }

    private function studyView(User $user, ?Deck $deck, string $mode): View
    {
        abort_unless(in_array($mode, ['flip', 'multiple-choice', 'typed'], true), Response::HTTP_NOT_FOUND);

        $cards = app(StudySessionService::class)->prepareStudyCards($user, $deck, 12, 24);

        return view('client.study', [
            'mode' => $mode,
            'deck' => $deck,
            'cards' => $cards,
        ]);
    }

    private function studyCandidates(User $user, ?Deck $deck = null, int $limit = 40): Collection
    {
        return app(StudySessionService::class)->getStudyCandidates($user, $deck, $limit);
    }

    private function accessibleDecksQuery(User $user): Builder
    {
        return app(DeckAccess::class)->accessibleQuery($user);
    }

    private function ensureDeckAccessible(User $user, Deck $deck): void
    {
        abort_unless(app(DeckAccess::class)->canAccess($user, $deck), Response::HTTP_NOT_FOUND);
    }

    private function ensureDeckOwner(User $user, Deck $deck): void
    {
        abort_unless(app(DeckAccess::class)->canManage($user, $deck), Response::HTTP_FORBIDDEN);
    }
}

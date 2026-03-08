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
use App\Services\StudyScheduler;
use App\Services\StudySessionService;
use App\Support\DeckAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientPortalController extends Controller
{
    public function index(Request $request, StudyScheduler $scheduler): View
    {
        /** @var User $user */
        $user = $request->user();
        $dashboardSummary = $this->buildDashboardSummary($user, $scheduler);
        $masteredCount = $dashboardSummary['mastery']['count'];

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
            'dashboardSummary' => $dashboardSummary,
            'progressSummary' => [
                'new' => $user->studyProgress()->where('status', StudyProgress::STATUS_NEW)->count(),
                'learning' => $user->studyProgress()->where('status', StudyProgress::STATUS_LEARNING)->count(),
                'mastered' => $masteredCount,
                'due_today' => $dashboardSummary['due_today'],
            ],
            'deckDefaults' => [
                'visibility' => Deck::VISIBILITY_PRIVATE,
                'is_active' => true,
            ],
        ]);
    }

    public function createDeck(): View
    {
        return view('client.create-deck');
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

        return $this->studyView(
            $user,
            null,
            (string) $request->query('mode', 'flip'),
            (int) $request->query('card', 0)
        );
    }

    public function studyDeck(Request $request, Deck $deck): View
    {
        /** @var User $user */
        $user = $request->user();

        $this->ensureDeckAccessible($user, $deck);

        return $this->studyView(
            $user,
            $deck,
            (string) $request->query('mode', 'flip'),
            (int) $request->query('card', 0)
        );
    }

    public function updateProgress(RecordStudyProgressRequest $request, StudyScheduler $scheduler, DeckAccess $deckAccess): JsonResponse|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        $flashcard = Flashcard::query()
            ->with('deck')
            ->findOrFail($validated['flashcard_id']);
        $deck = $flashcard->deck;

        abort_unless($deckAccess->canAccess($user, $deck), Response::HTTP_NOT_FOUND);

        $scheduler->recordReview(
            $user,
            $flashcard,
            $validated['status'],
            $validated['result'] ?? null
        );

        $mode = (string) ($validated['study_mode'] ?? 'flip');
        $backUrl = $validated['deck_id'] ?? null
            ? route('client.decks.show', $deck)
            : route('client.dashboard');
        $restartUrl = $validated['deck_id'] ?? null
            ? route('client.decks.study', ['deck' => $deck, 'mode' => $mode])
            : route('client.study.all', ['mode' => $mode]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Progress saved!',
                'flashcard_id' => $flashcard->id,
                'deck_id' => $deck->id,
                'back_url' => $backUrl,
                'restart_url' => $restartUrl,
            ]);
        }

        if (in_array($mode, ['flip', 'multiple-choice', 'typed'], true)) {
            $nextCard = max(0, (int) ($validated['card_index'] ?? 0) + 1);

            if (($validated['deck_id'] ?? null) === $deck->id) {
                return redirect()
                    ->route('client.decks.study', [
                        'deck' => $deck,
                        'mode' => $mode,
                        'card' => $nextCard,
                    ])
                    ->with('status', 'Study progress updated.');
            }

            return redirect()
                ->route('client.study.all', [
                    'mode' => $mode,
                    'card' => $nextCard,
                ])
                ->with('status', 'Study progress updated.');
        }

        return back()->with('status', 'Study progress updated.');
    }

    public function storeDeck(ClientDeckRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $deck = $user->decks()->create($request->validatedPayload());

        // Create flashcards from cards array
        foreach ($request->cards() as $cardData) {
            $deck->flashcards()->create([
                'front_content' => $cardData['front'],
                'back_content' => $cardData['back'],
                'image_url' => $cardData['image_url'] ?? null,
                'audio_url' => $cardData['audio_url'] ?? null,
                'hint' => $cardData['hint'] ?? null,
            ]);
        }

        return redirect()->route('client.decks.show', $deck)->with('status', 'Deck created with '.count($request->cards()).' card(s).');
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

        return redirect()->route('client.dashboard')->with('status', 'Deck removed.');
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

    public function copyDeck(Request $request, Deck $deck, DeckCopyService $deckCopy, DeckAccess $deckAccess): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($deckAccess->canCloneOrReview($deck), Response::HTTP_NOT_FOUND);

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

    public function profile(Request $request, StudyScheduler $scheduler): View
    {
        /** @var User $user */
        $user = $request->user();
        $dashboardSummary = $this->buildDashboardSummary($user, $scheduler);

        return view('client.profile', [
            'user' => $user->loadCount('decks'),
            'dashboardSummary' => $dashboardSummary,
            'recentDecks' => $user->decks()
                ->withCount('flashcards')
                ->latest()
                ->take(6)
                ->get(),
            'stats' => [
                'reviews' => $user->studyProgress()->count(),
                'mastered' => $dashboardSummary['mastery']['count'],
                'due_today' => $dashboardSummary['due_today'],
                'completed_today' => $dashboardSummary['completed_today'],
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

    private function studyView(User $user, ?Deck $deck, string $mode, int $requestedIndex): View
    {
        abort_unless(in_array($mode, ['flip', 'multiple-choice', 'typed'], true), Response::HTTP_NOT_FOUND);

        $studySession = app(StudySessionService::class);
        $cards = ($deck
            ? $studySession->prepareDeckStudyCards($user, $deck)
            : $studySession->prepareStudyCards($user, null, 12, 24))
            ->values();

        $totalCards = $cards->count();
        $currentIndex = $totalCards === 0
            ? 0
            : min(max($requestedIndex, 0), $totalCards - 1);

        return view('client.study', [
            'mode' => $mode,
            'deck' => $deck,
            'cards' => $cards,
            'currentCard' => $cards->get($currentIndex),
            'currentIndex' => $currentIndex,
            'totalCards' => $totalCards,
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

    private function buildDashboardSummary(User $user, StudyScheduler $scheduler): array
    {
        $completedTodayCount = $user->studyProgress()
            ->whereDate('last_reviewed_at', now()->toDateString())
            ->count();
        $masteredCount = $user->studyProgress()
            ->where('status', StudyProgress::STATUS_MASTERED)
            ->count();

        return [
            'today_label' => now()->format('D, M j'),
            'due_today' => $scheduler->dueTodayCount($user),
            'completed_today' => $completedTodayCount,
            'level' => $user->levelProgress(),
            'streak_timeline' => $this->buildStreakTimeline($user),
            'mastery' => $this->buildMasterySummary($masteredCount, $user->studyProgress()->count()),
        ];
    }

    private function buildStreakTimeline(User $user, int $days = 7): Collection
    {
        $anchorDay = ($user->last_studied_at ?? now())->copy()->startOfDay();
        $windowStart = $anchorDay->copy()->subDays($days - 1);
        $activeDays = min($days, max(0, (int) $user->daily_streak));
        $activeWindowStart = $activeDays > 0
            ? $anchorDay->copy()->subDays($activeDays - 1)
            : null;

        return collect(range(0, $days - 1))->map(function (int $offset) use ($windowStart, $anchorDay, $activeWindowStart) {
            $date = $windowStart->copy()->addDays($offset);
            $isActive = $activeWindowStart
                ? $date->gte($activeWindowStart) && $date->lte($anchorDay)
                : false;

            return [
                'label' => $date->format('D'),
                'day' => $date->format('d'),
                'full' => $date->format('M d'),
                'is_active' => $isActive,
                'is_today' => $date->isToday(),
            ];
        });
    }

    private function buildMasterySummary(int $masteredCount, int $trackedCards): array
    {
        $milestones = [
            10 => 'Momentum',
            25 => 'Scholar',
            50 => 'Archivist',
            100 => 'Grand Archive',
        ];

        $tier = 'First Spark';

        foreach ($milestones as $threshold => $label) {
            if ($masteredCount >= $threshold) {
                $tier = $label;
            }
        }

        $nextThreshold = collect(array_keys($milestones))->first(fn (int $threshold) => $masteredCount < $threshold);
        $rate = $trackedCards === 0
            ? 0
            : min(100, (int) round(($masteredCount / $trackedCards) * 100));

        $message = match (true) {
            $masteredCount >= 100 => 'Your review base is turning into a reference library. Keep the cadence and protect the streak.',
            $masteredCount >= 50 => 'This is no longer casual practice. You are building durable recall across multiple decks.',
            $masteredCount >= 25 => 'A strong mastery core is forming. Push a few more cards each day to turn it into a moat.',
            $masteredCount >= 10 => 'You have real momentum now. Keep stacking clean reviews before the queue grows again.',
            default => 'Every mastered card reduces future friction. Start with the easiest wins and compound from there.',
        };

        $nextMilestone = $nextThreshold
            ? ($nextThreshold - $masteredCount).' cards to '.$milestones[$nextThreshold]
            : 'Top mastery tier unlocked';

        return [
            'count' => $masteredCount,
            'tier' => $tier,
            'rate' => $rate,
            'message' => $message,
            'next_milestone' => $nextMilestone,
        ];
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

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

    public function myDecks(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $ownedDecks = $user->decks()
            ->withCount('flashcards')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(12);

        return view('client.my-decks', [
            'ownedDecks' => $ownedDecks,
        ]);
    }

    public function community(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $query = Deck::query()
            ->active()
            ->public()
            ->where(function (Builder $query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', '!=', $user->id);
            });

        // Search by title or description
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function (Builder $q) use ($searchTerm) {
                $q->where('title', 'like', '%'.$searchTerm.'%')
                    ->orWhere('description', 'like', '%'.$searchTerm.'%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->input('tag'));
        }

        // Sort options
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'rating' => $query->orderByDesc('reviews_avg_rating'),
            'cards' => $query->orderByDesc('flashcards_count'),
            'popular' => $query->withCount('reviews')->orderByDesc('reviews_count'),
            default => $query->latest(),
        };

        $communityDecks = $query
            ->withCount('flashcards', 'reviews')
            ->withAvg('reviews', 'rating')
            ->with('owner')
            ->paginate(12)
            ->withQueryString();

        // Get available categories and tags for filters
        $categories = Deck::query()
            ->active()
            ->public()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        $allTags = Deck::query()
            ->active()
            ->public()
            ->whereNotNull('tags')
            ->select('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('client.community', [
            'communityDecks' => $communityDecks,
            'categories' => $categories,
            'allTags' => $allTags,
            'filters' => [
                'search' => $request->input('search', ''),
                'category' => $request->input('category', ''),
                'tag' => $request->input('tag', ''),
                'sort' => $sort,
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
                'message' => 'Đã lưu tiến độ học.',
                'flashcard_id' => $flashcard->id,
                'deck_id' => $deck->id,
                'due_count' => $scheduler->dueTodayCount($user),
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
                    ->with('status', 'Đã cập nhật tiến độ ôn tập.');
            }

            return redirect()
                ->route('client.study.all', [
                    'mode' => $mode,
                    'card' => $nextCard,
                ])
                ->with('status', 'Đã cập nhật tiến độ ôn tập.');
        }

        return back()->with('status', 'Đã cập nhật tiến độ ôn tập.');
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

        return redirect()->route('client.decks.show', $deck)->with('status', 'Đã tạo deck mới với '.count($request->cards()).' flashcard.');
    }

    public function updateDeck(ClientDeckRequest $request, Deck $deck): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->ensureDeckOwner($user, $deck);

        $deck->update($request->validatedPayload());

        return redirect()->route('client.decks.show', $deck)->with('status', 'Đã cập nhật deck.');
    }

    public function destroyDeck(Request $request, Deck $deck): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->ensureDeckOwner($user, $deck);

        $deck->delete();

        return redirect()->route('client.dashboard')->with('status', 'Đã xóa deck.');
    }

    public function storeFlashcard(ClientFlashcardRequest $request, Deck $deck): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->ensureDeckOwner($user, $deck);

        $deck->flashcards()->create($request->validated());

        return redirect()->route('client.decks.show', $deck)->with('status', 'Đã thêm flashcard mới.');
    }

    public function updateFlashcard(ClientFlashcardRequest $request, Flashcard $flashcard): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $flashcard->load('deck');
        $this->ensureDeckOwner($user, $flashcard->deck);

        $flashcard->update($request->validated());

        return redirect()->route('client.decks.show', $flashcard->deck)->with('status', 'Đã cập nhật flashcard.');
    }

    public function destroyFlashcard(Request $request, Flashcard $flashcard): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $flashcard->load('deck');
        $this->ensureDeckOwner($user, $flashcard->deck);
        $deck = $flashcard->deck;

        $flashcard->delete();

        return redirect()->route('client.decks.show', $deck)->with('status', 'Đã xóa flashcard.');
    }

    public function copyDeck(Request $request, Deck $deck, DeckCopyService $deckCopy, DeckAccess $deckAccess): JsonResponse|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($deckAccess->canCloneOrReview($deck), Response::HTTP_NOT_FOUND);

        $copy = $deckCopy->copyToUser($deck, $user);

        $redirectTarget = $request->string('redirect_to')->toString() === 'study'
            ? route('client.decks.study', $copy)
            : route('client.decks.show', $copy);

        $message = $request->string('redirect_to')->toString() === 'study'
            ? 'Deck đã được lưu vào thư viện của bạn. Bạn đang học trên bản sao riêng tư này.'
            : 'Deck đã được lưu vào thư viện của bạn. Bạn có thể chỉnh sửa và ôn tập trong thư viện cá nhân.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'deck_id' => $copy->id,
                'redirect_url' => $redirectTarget,
            ]);
        }

        return redirect($redirectTarget)->with('status', $message);
    }

    public function storeReview(Request $request, Deck $deck, DeckAccess $deckAccess): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($deckAccess->canCloneOrReview($deck), Response::HTTP_NOT_FOUND);
        abort_if((int) $deck->user_id === (int) $user->id, Response::HTTP_FORBIDDEN, 'Bạn không thể tự đánh giá deck của chính mình.');

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1200'],
        ]);

        DeckReview::updateOrCreate(
            ['deck_id' => $deck->id, 'user_id' => $user->id],
            $validated
        );

        return redirect()->route('client.decks.show', $deck)->with('status', 'Đã lưu đánh giá của bạn.');
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

        return redirect()->route('client.profile')->with('status', 'Đã cập nhật hồ sơ.');
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
            ->with('status', "Đã nhập deck từ CSV và thêm {$importedCount} flashcard.");
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
            'today_label' => ucfirst(now()->locale('vi')->translatedFormat('D, d/m')),
            'due_today' => $scheduler->dueTodayCount($user),
            'completed_today' => $completedTodayCount,
            'level' => $user->levelProgress(),
            'streak_timeline' => $this->buildStreakTimeline($user),
            'mastery' => $this->buildMasterySummary($masteredCount, $user->studyProgress()->count()),
            'study_chart' => $this->buildStudyActivityChart($user),
            'session_stats' => $this->buildSessionStats($user),
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
                'label' => ucfirst($date->locale('vi')->translatedFormat('D')),
                'day' => $date->format('d'),
                'full' => ucfirst($date->locale('vi')->translatedFormat('d/m')),
                'is_active' => $isActive,
                'is_today' => $date->isToday(),
            ];
        });
    }

    private function buildMasterySummary(int $masteredCount, int $trackedCards): array
    {
        $milestones = [
            10 => 'Đà học',
            25 => 'Học chắc',
            50 => 'Ghi nhớ sâu',
            100 => 'Kho tri thức',
        ];

        $tier = 'Khởi động';

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
            $masteredCount >= 100 => 'Bạn đã biến việc ôn tập thành một hệ thống thật sự bền. Giữ nhịp đều và bảo vệ streak hiện tại.',
            $masteredCount >= 50 => 'Việc học đã vượt qua mức thử nghiệm. Bạn đang xây trí nhớ dài hạn trên nhiều deck cùng lúc.',
            $masteredCount >= 25 => 'Một lõi ghi nhớ chắc đang hình thành. Chỉ cần thêm vài phiên ngắn mỗi ngày để tăng độ bền.',
            $masteredCount >= 10 => 'Bạn đã có đà học rõ ràng. Tiếp tục giữ các lượt review sạch trước khi hàng chờ dày lên.',
            default => 'Mỗi thẻ được mastery sẽ làm những phiên sau nhẹ hơn. Hãy bắt đầu từ các lượt ôn dễ thắng nhất.',
        };

        $nextMilestone = $nextThreshold
            ? 'Còn '.($nextThreshold - $masteredCount).' thẻ để đạt '.$milestones[$nextThreshold]
            : 'Đã mở khóa mốc mastery cao nhất';

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

    private function buildStudyActivityChart(User $user, int $days = 7): array
    {
        $chartData = [];
        $maxCount = 0;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = $user->studyProgress()
                ->whereDate('last_reviewed_at', $date->toDateString())
                ->count();

            $chartData[] = [
                'label' => ucfirst($date->locale('vi')->translatedFormat('D')),
                'date' => $date->format('d/m'),
                'count' => $count,
                'is_today' => $date->isToday(),
            ];

            $maxCount = max($maxCount, $count);
        }

        return [
            'data' => $chartData,
            'max' => max(1, $maxCount), // Avoid division by zero
            'total' => array_sum(array_column($chartData, 'count')),
        ];
    }

    private function buildSessionStats(User $user): array
    {
        $today = now()->toDateString();
        $todayProgress = $user->studyProgress()
            ->whereDate('last_reviewed_at', $today)
            ->get();

        $correctCount = $todayProgress->where('status', StudyProgress::STATUS_MASTERED)->count();
        $totalCount = $todayProgress->count();
        $accuracy = $totalCount > 0 ? (int) round(($correctCount / $totalCount) * 100) : 0;

        return [
            'correct' => $correctCount,
            'total' => $totalCount,
            'accuracy' => $accuracy,
            'learning' => $todayProgress->where('status', StudyProgress::STATUS_LEARNING)->count(),
            'new' => $todayProgress->where('status', StudyProgress::STATUS_NEW)->count(),
        ];
    }

    private function ensureDeckOwner(User $user, Deck $deck): void
    {
        abort_unless(app(DeckAccess::class)->canManage($user, $deck), Response::HTTP_FORBIDDEN);
    }
}

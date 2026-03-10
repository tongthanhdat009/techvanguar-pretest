<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDeckImportRequest;
use App\Http\Requests\AdminDeckRequest;
use App\Http\Requests\AdminFlashcardRequest;
use App\Models\Deck;
use App\Models\DeckReview;
use App\Models\Flashcard;
use App\Models\StudyProgress;
use App\Models\User;
use App\Services\CsvExportService;
use App\Services\CsvImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDashboardController extends Controller
{
    public function overview(): View
    {
        $stats = [
            'users' => User::count(),
            'clients' => User::where('role', User::ROLE_CLIENT)->count(),
            'admins' => User::where('role', User::ROLE_ADMIN)->count(),
            'decks' => Deck::count(),
            'public_decks' => Deck::where('visibility', Deck::VISIBILITY_PUBLIC)->count(),
            'flashcards' => Flashcard::count(),
            'mastered' => StudyProgress::where('status', StudyProgress::STATUS_MASTERED)->count(),
            'reviews' => DeckReview::count(),
        ];

        // Daily stats for today
        $today = now()->startOfDay();
        $dailyStats = [
            'new_users' => User::where('created_at', '>=', $today)->count(),
            'new_decks' => Deck::where('created_at', '>=', $today)->count(),
            'new_flashcards' => Flashcard::where('created_at', '>=', $today)->count(),
            'new_reviews' => DeckReview::where('created_at', '>=', $today)->count(),
            'study_sessions' => StudyProgress::where('updated_at', '>=', $today)->count(),
        ];

        // Time series data for charts (last 30 days)
        $days = [];
        $userGrowth = [];
        $deckGrowth = [];
        $flashcardGrowth = [];
        $reviewData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $nextDate = $date->copy()->addDay();

            $days[] = $date->format('d/m');
            $userGrowth[] = User::where('created_at', '<=', $nextDate)->count();
            $deckGrowth[] = Deck::where('created_at', '<=', $nextDate)->count();
            $flashcardGrowth[] = Flashcard::where('created_at', '<=', $nextDate)->count();
            $reviewData[] = DeckReview::whereBetween('created_at', [$date, $nextDate])->count();
        }

        // Weekly data (last 12 weeks)
        $weeks = [];
        $weeklyUsers = [];
        $weeklyDecks = [];

        for ($i = 11; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $weeks[] = $weekStart->format('d/m');
            $weeklyUsers[] = User::whereBetween('created_at', [$weekStart, $weekEnd])->count();
            $weeklyDecks[] = Deck::whereBetween('created_at', [$weekStart, $weekEnd])->count();
        }

        return view('admin.overview', [
            'stats' => $stats,
            'dailyStats' => $dailyStats,
            'chartData' => [
                'days' => $days,
                'userGrowth' => $userGrowth,
                'deckGrowth' => $deckGrowth,
                'flashcardGrowth' => $flashcardGrowth,
                'reviewData' => $reviewData,
                'weeks' => $weeks,
                'weeklyUsers' => $weeklyUsers,
                'weeklyDecks' => $weeklyDecks,
            ],
        ]);
    }

    public function users(Request $request): View
    {
        $query = User::query()->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = $request->input('per_page', 5);
        $users = $query->paginate($perPage)->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'filters' => [
                'search' => $request->input('search'),
                'role' => $request->input('role'),
                'status' => $request->input('status'),
                'per_page' => $perPage,
            ],
        ]);
    }

    public function createUser(): View
    {
        return view('admin.users.create');
    }

    public function editUser(User $user): View
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function decks(Request $request): View
    {
        $query = Deck::query()
            ->with('owner')
            ->withCount('flashcards')
            ->withAvg('reviews', 'rating')
            ->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by visibility
        if ($request->filled('visibility')) {
            $query->where('visibility', $request->input('visibility'));
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        // Filter by owner
        if ($request->filled('owner_id')) {
            $query->where('user_id', $request->input('owner_id'));
        }

        $perPage = $request->input('per_page', 5);
        $decks = $query->paginate($perPage)->withQueryString();

        return view('admin.decks', [
            'decks' => $decks,
            'users' => User::orderBy('name')->get(['id', 'name', 'role']),
            'filters' => [
                'search' => $request->input('search'),
                'visibility' => $request->input('visibility'),
                'is_active' => $request->input('is_active'),
                'owner_id' => $request->input('owner_id'),
                'per_page' => $perPage,
            ],
        ]);
    }

    public function createDeck(): View
    {
        return view('admin.decks.create', [
            'users' => User::orderBy('name')->get(['id', 'name', 'role']),
        ]);
    }

    public function showDeck(Deck $deck): View
    {
        $deck->load(['owner', 'flashcards']);

        return view('admin.decks.show', ['deck' => $deck]);
    }

    public function editDeck(Deck $deck): View
    {
        $deck->load(['owner', 'flashcards']);

        return view('admin.decks.edit', [
            'deck' => $deck,
            'users' => User::orderBy('name')->get(['id', 'name', 'role']),
        ]);
    }

    public function reviews(Request $request): View
    {
        $query = DeckReview::query()
            ->with(['deck', 'user'])
            ->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('deck', function ($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        // Filter by deck
        if ($request->filled('deck_id')) {
            $query->where('deck_id', $request->input('deck_id'));
        }

        $perPage = $request->input('per_page', 5);
        $reviews = $query->paginate($perPage)->withQueryString();

        return view('admin.reviews', [
            'reviews' => $reviews,
            'decks' => Deck::orderBy('title')->get(['id', 'title']),
            'filters' => [
                'search' => $request->input('search'),
                'rating' => $request->input('rating'),
                'deck_id' => $request->input('deck_id'),
                'per_page' => $perPage,
            ],
        ]);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_CLIENT])],
            'status'   => ['required', Rule::in([User::STATUS_ACTIVE, User::STATUS_INACTIVE])],
            'bio'      => ['nullable', 'string', 'max:1200'],
        ]);

        User::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Tài khoản \"{$validated['name']}\" đã được tạo.",
                'redirect' => route('admin.users'),
            ]);
        }

        return redirect()->route('admin.users')->with('status', "Tài khoản \"{$validated['name']}\" đã được tạo.");
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'          => ['nullable', 'string', 'min:8'],
            'role'              => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_CLIENT])],
            'status'            => ['required', Rule::in([User::STATUS_ACTIVE, User::STATUS_INACTIVE])],
            'bio'               => ['nullable', 'string', 'max:1200'],
            'experience_points' => ['required', 'integer', 'min:0'],
            'daily_streak'      => ['required', 'integer', 'min:0'],
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Tài khoản \"{$user->name}\" đã được cập nhật.",
                'redirect' => route('admin.users'),
            ]);
        }

        return redirect()->route('admin.users')->with('status', "Tài khoản \"{$user->name}\" đã được cập nhật.");
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()?->is($user), Response::HTTP_UNPROCESSABLE_ENTITY, 'You cannot delete your own account.');

        $user->delete();

        return back()->with('status', 'User deleted.');
    }

    public function storeDeck(AdminDeckRequest $request): RedirectResponse
    {
        $deck = Deck::create($request->validatedPayload());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Bộ thẻ \"{$deck->title}\" đã được tạo.",
                'redirect' => route('admin.decks'),
            ]);
        }

        return redirect()->route('admin.decks.show', $deck)->with('status', 'Bộ thẻ đã được tạo.');
    }

    public function updateDeck(AdminDeckRequest $request, Deck $deck): RedirectResponse
    {
        $deck->update($request->validatedPayload());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Bộ thẻ \"{$deck->title}\" đã được cập nhật.",
                'redirect' => route('admin.decks'),
            ]);
        }

        return redirect()->route('admin.decks.show', $deck)->with('status', 'Bộ thẻ đã được cập nhật.');
    }

    public function destroyDeck(Deck $deck): RedirectResponse
    {
        abort_if($deck->is_active, 422, 'Hãy vô hiệu hóa bộ thẻ trước khi xóa.');

        $deck->delete();

        return redirect()->route('admin.decks')->with('status', "Bộ thẻ \"{$deck->title}\" đã được xóa.");
    }

    public function storeFlashcard(AdminFlashcardRequest $request): RedirectResponse
    {
        Flashcard::create($request->validated());

        return back()->with('status', 'Flashcard created.');
    }

    public function updateFlashcard(AdminFlashcardRequest $request, Flashcard $flashcard): RedirectResponse
    {
        $flashcard->update($request->validated());

        return back()->with('status', 'Flashcard updated.');
    }

    public function destroyFlashcard(Flashcard $flashcard): RedirectResponse
    {
        $flashcard->delete();

        return back()->with('status', 'Flashcard deleted.');
    }

    public function importDeck(AdminDeckImportRequest $request, CsvImportService $csvImport): RedirectResponse
    {
        $validated = $request->validatedPayload();

        $deck = Deck::create([
            'user_id' => $validated['user_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'visibility' => $validated['visibility'],
            'category' => $validated['category'] ?? null,
            'tags' => $validated['tags'],
            'is_active' => (bool) $validated['is_active'],
        ]);

        $importedCount = $csvImport->importFlashcards($deck, $request->file('csv_file'));

        return back()->with("status", "Deck imported. {$importedCount} flashcard(s) added.");
    }

    public function exportDeck(Deck $deck, CsvExportService $csvExport): StreamedResponse
    {
        return $csvExport->exportFlashcards($deck);
    }

    public function destroyReview(DeckReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('status', 'Review removed.');
    }

    public function toggleDeckStatus(Deck $deck): RedirectResponse
    {
        $deck->update(['is_active' => ! $deck->is_active]);

        $label = $deck->is_active ? 'activated' : 'deactivated';

        return back()->with('status', "Deck \"{$deck->title}\" {$label}.");
    }

    public function toggleUserRole(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()?->is($user), 422, 'You cannot change your own role.');

        $newRole = $user->isAdmin() ? User::ROLE_CLIENT : User::ROLE_ADMIN;
        $user->update(['role' => $newRole]);

        return back()->with('status', "User \"{$user->name}\" role changed to {$newRole}.");
    }

    public function toggleUserStatus(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()?->is($user), 422, 'You cannot ban your own account.');

        $newStatus = $user->isActive() ? User::STATUS_INACTIVE : User::STATUS_ACTIVE;
        $user->update(['status' => $newStatus]);

        $label = $newStatus === User::STATUS_ACTIVE ? 'được kích hoạt' : 'bị vô hiệu hóa';

        return back()->with('status', "Tài khoản \"{$user->name}\" đã {$label}.");
    }

    // Profile, Account, Settings
    public function profile(): View
    {
        $user = auth('admin')->user();

        return view('admin.profile', [
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:1200'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return back()->with('status', 'Hồ sơ đã được cập nhật.');
    }

    public function account(): View
    {
        $user = auth('admin')->user();

        return view('admin.account', [
            'user' => $user,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth('admin')->user()->update([
            'password' => $validated['password'],
        ]);

        return back()->with('status', 'Mật khẩu đã được cập nhật.');
    }

    public function settings(): View
    {
        $user = auth('admin')->user();

        return view('admin.settings', [
            'user' => $user,
        ]);
    }

}

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

        return view('admin.overview', [
            'stats' => $stats,
        ]);
    }

    public function users(): View
    {
        return view('admin.users', [
            'users' => User::query()->latest()->get(),
        ]);
    }

    public function decks(): View
    {
        return view('admin.decks', [
            'decks' => Deck::query()
                ->with(['owner', 'flashcards'])
                ->withAvg('reviews', 'rating')
                ->latest()
                ->get(),
        ]);
    }

    public function reviews(): View
    {
        return view('admin.reviews', [
            'reviews' => DeckReview::query()
                ->with(['deck', 'user'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_CLIENT])],
            'bio' => ['nullable', 'string', 'max:1200'],
        ]);

        User::create($validated);

        return back()->with('status', 'User created.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_CLIENT])],
            'bio' => ['nullable', 'string', 'max:1200'],
            'experience_points' => ['required', 'integer', 'min:0'],
            'daily_streak' => ['required', 'integer', 'min:0'],
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('status', 'User updated.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()?->is($user), Response::HTTP_UNPROCESSABLE_ENTITY, 'You cannot delete your own account.');

        $user->delete();

        return back()->with('status', 'User deleted.');
    }

    public function storeDeck(AdminDeckRequest $request): RedirectResponse
    {
        Deck::create($request->validatedPayload());

        return back()->with('status', 'Deck created.');
    }

    public function updateDeck(AdminDeckRequest $request, Deck $deck): RedirectResponse
    {
        $deck->update($request->validatedPayload());

        return back()->with('status', 'Deck updated.');
    }

    public function destroyDeck(Deck $deck): RedirectResponse
    {
        $deck->delete();

        return back()->with('status', 'Deck deleted.');
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

}

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

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'decks' => Deck::count(),
            'active_decks' => Deck::where('is_active', true)->count(),
            'flashcards' => Flashcard::count(),
            'mastered' => StudyProgress::where('status', StudyProgress::STATUS_MASTERED)->count(),
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'users' => User::query()->latest()->get(),
            'decks' => Deck::query()->with('flashcards')->latest()->get(),
            'statuses' => StudyProgress::statuses(),
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_CLIENT])],
        ]);

        User::create($validated);

        return back()->with('status', 'User created successfully.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_CLIENT])],
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('status', 'User updated successfully.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()?->is($user), Response::HTTP_UNPROCESSABLE_ENTITY, 'You cannot delete your own account.');

        $user->delete();

        return back()->with('status', 'User deleted successfully.');
    }

    public function storeDeck(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        Deck::create($validated);

        return back()->with('status', 'Deck created successfully.');
    }

    public function updateDeck(Request $request, Deck $deck): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $deck->update($validated);

        return back()->with('status', 'Deck updated successfully.');
    }

    public function destroyDeck(Deck $deck): RedirectResponse
    {
        $deck->delete();

        return back()->with('status', 'Deck deleted successfully.');
    }

    public function storeFlashcard(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'deck_id' => ['required', 'exists:decks,id'],
            'front_content' => ['required', 'string'],
            'back_content' => ['required', 'string'],
        ]);

        Flashcard::create($validated);

        return back()->with('status', 'Flashcard created successfully.');
    }

    public function updateFlashcard(Request $request, Flashcard $flashcard): RedirectResponse
    {
        $validated = $request->validate([
            'deck_id' => ['required', 'exists:decks,id'],
            'front_content' => ['required', 'string'],
            'back_content' => ['required', 'string'],
        ]);

        $flashcard->update($validated);

        return back()->with('status', 'Flashcard updated successfully.');
    }

    public function destroyFlashcard(Flashcard $flashcard): RedirectResponse
    {
        $flashcard->delete();

        return back()->with('status', 'Flashcard deleted successfully.');
    }
}

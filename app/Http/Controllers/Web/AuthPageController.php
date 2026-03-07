<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use App\Models\DeckReview;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthPageController extends Controller
{
    public function landing(): View
    {
        return view('public.landing', [
            'publicDecks' => Deck::query()
                ->active()
                ->public()
                ->withCount('flashcards')
                ->withAvg('reviews', 'rating')
                ->latest()
                ->take(6)
                ->get(),
            'featuredReviews' => DeckReview::query()
                ->with(['deck', 'user'])
                ->latest()
                ->take(3)
                ->get(),
            'currentUser' => Auth::user(),
        ]);
    }

    public function showClientLogin(): View
    {
        return view('auth.client-login');
    }

    public function showAdminLogin(): View
    {
        return view('auth.admin-login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => User::ROLE_CLIENT,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('client.portal');
    }

    public function clientLogin(Request $request): RedirectResponse
    {
        return $this->attemptLogin($request, User::ROLE_CLIENT, 'client.portal');
    }

    public function adminLogin(Request $request): RedirectResponse
    {
        return $this->attemptLogin($request, User::ROLE_ADMIN, 'admin.overview');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function attemptLogin(Request $request, string $role, string $route): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'login' => 'The provided credentials are incorrect.',
            ])->onlyInput('email');
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->role !== $role) {
            Auth::logout();

            return back()->withErrors([
                'login' => sprintf('This portal is only available for %s accounts.', $role),
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route($route);
    }
}

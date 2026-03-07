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

        Auth::guard('client')->login($user);
        $request->session()->regenerate();

        return redirect()->route('client.portal');
    }

    public function clientLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::guard('client')->attempt($credentials)) {
            return back()->withErrors([
                'login' => 'The provided credentials are incorrect.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->route('client.portal');
    }

    public function adminLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::guard('admin')->attempt($credentials)) {
            return back()->withErrors([
                'login' => 'The provided credentials are incorrect.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->route('admin.overview');
    }

    public function logout(Request $request): RedirectResponse
    {
        // Determine which guard to logout from based on route name
        $guard = $request->route()->named('admin.*') ? 'admin' : 'client';

        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $route = $guard === 'admin' ? 'admin.login' : 'home';
        return redirect()->route($route);
    }
}

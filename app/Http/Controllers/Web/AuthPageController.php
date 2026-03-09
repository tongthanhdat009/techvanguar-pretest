<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\Deck;
use App\Models\DeckReview;
use App\Models\User;
use App\Services\PasswordResetService;
use Illuminate\Http\JsonResponse;
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

        return redirect()->route('client.dashboard');
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
        return redirect()->route('client.dashboard');
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

        $route = $guard === 'admin' ? 'admin.login' : 'client.login';
        return redirect()->route($route);
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(SendOtpRequest $request, PasswordResetService $passwordReset): JsonResponse
    {
        $validated = $request->validated();

        $result = $passwordReset->sendOtp($validated['email']);

        if (! $result['success']) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request, PasswordResetService $passwordReset): JsonResponse
    {
        $validated = $request->validated();

        $isValid = $passwordReset->verifyOtp($validated['email'], $validated['otp']);

        if (! $isValid) {
            return response()->json([
                'message' => 'Mã OTP không đúng hoặc đã hết hạn.',
            ], 422);
        }

        return response()->json([
            'message' => 'Xác thực OTP thành công.',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request, PasswordResetService $passwordReset): JsonResponse
    {
        $validated = $request->validated();

        $success = $passwordReset->resetPassword(
            $validated['email'],
            $validated['otp'],
            $validated['password']
        );

        if (! $success) {
            return response()->json([
                'message' => 'Không thể đặt lại mật khẩu. Vui lòng kiểm tra lại.',
            ], 422);
        }

        return response()->json([
            'message' => 'Đặt lại mật khẩu thành công.',
            'redirect' => route('client.login'),
        ]);
    }
}

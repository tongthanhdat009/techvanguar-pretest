<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterOtpSendRequest;
use App\Http\Requests\RegisterOtpVerifyRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\Deck;
use App\Models\DeckReview;
use App\Models\User;
use App\Services\PasswordResetService;
use App\Services\RegistrationOtpService;
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
        $registrationOtp = app(RegistrationOtpService::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $verifiedAt = $registrationOtp->verifiedAt($request->session(), $validated['name'], $validated['email']);

        if ($verifiedAt === null) {
            return back()
                ->withErrors([
                    'otp' => 'Bạn cần xác thực OTP cho email trước khi hoàn tất đăng ký.',
                ])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => User::ROLE_CLIENT,
        ]);

        $user->forceFill([
            'email_verified_at' => $verifiedAt,
        ])->save();

        $registrationOtp->clearVerification($request->session(), $user->email);

        return redirect()->route('client.login')->with('status', 'Đăng ký thành công. Bạn có thể đăng nhập ngay bây giờ.');
    }

    public function sendRegisterOtp(RegisterOtpSendRequest $request, RegistrationOtpService $registrationOtp): JsonResponse
    {
        $validated = $request->validated();

        $registrationOtp->forgetCurrentVerification($request->session());

        $result = $registrationOtp->sendOtp($validated['name'], $validated['email']);

        if (! $result['success']) {
            return response()->json([
                'code' => $result['code'] ?? 'otp_send_failed',
                'message' => $result['message'],
                'redirect' => ($result['code'] ?? null) === 'email_exists' ? route('client.login') : null,
            ], ($result['code'] ?? null) === 'email_exists' ? 409 : 422);
        }

        return response()->json([
            'code' => $result['code'] ?? 'otp_sent',
            'message' => $result['message'],
        ]);
    }

    public function verifyRegisterOtp(RegisterOtpVerifyRequest $request, RegistrationOtpService $registrationOtp): JsonResponse
    {
        $validated = $request->validated();

        $isValid = $registrationOtp->verifyOtp(
            $request->session(),
            $validated['name'],
            $validated['email'],
            $validated['otp']
        );

        if (! $isValid) {
            return response()->json([
                'message' => 'Mã OTP không đúng, đã hết hạn hoặc thông tin xác thực không khớp.',
            ], 422);
        }

        return response()->json([
            'message' => 'Xác thực OTP thành công. Bạn có thể thiết lập mật khẩu.',
        ]);
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

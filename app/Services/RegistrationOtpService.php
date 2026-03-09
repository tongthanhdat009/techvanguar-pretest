<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegistrationOtpService
{
    private const CACHE_PREFIX = 'registration_otp:';

    private const SESSION_KEY = 'registration.verified';

    private const TTL_MINUTES = 15;

    public function sendOtp(string $name, string $email): array
    {
        $existingUser = User::query()->where('email', $email)->first();

        if ($existingUser) {
            // Check if user is banned
            if ($existingUser->isBanned()) {
                return [
                    'success' => false,
                    'code' => 'account_banned',
                    'message' => 'Tài khoản liên kết với email này đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên nếu bạn nghĩ đây là sai lầm.',
                ];
            }

            // User exists but is active
            return [
                'success' => false,
                'code' => 'email_exists',
                'message' => 'Email này đã được sử dụng. Vui lòng đăng nhập hoặc chọn email khác.',
            ];
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put($this->cacheKey($email), [
            'name' => $name,
            'email' => $email,
            'otp' => Hash::make($otp),
            'verified_at' => null,
            'created_at' => now()->toIso8601String(),
        ], now()->addMinutes(self::TTL_MINUTES));

        try {
            Mail::raw("Xin chào {$name},\n\nMã OTP xác nhận tài khoản của bạn là: {$otp}\n\nMã có hiệu lực trong 15 phút.", function ($message) use ($email) {
                $message->to($email)
                    ->subject('Mã OTP xác nhận tài khoản');
            });
        } catch (\Exception $exception) {
            Cache::forget($this->cacheKey($email));
            Log::error("Failed to send registration OTP email to {$email}: {$exception->getMessage()}");

            return [
                'success' => false,
                'code' => 'mail_failed',
                'message' => 'Không thể gửi email xác nhận. Vui lòng thử lại sau.',
            ];
        }

        return [
            'success' => true,
            'code' => 'otp_sent',
            'message' => 'Đã gửi mã OTP xác nhận đến email của bạn.',
        ];
    }

    public function verifyOtp(Session $session, string $name, string $email, string $otp): bool
    {
        $payload = Cache::get($this->cacheKey($email));

        if (! is_array($payload)) {
            return false;
        }

        if (($payload['name'] ?? null) !== $name) {
            return false;
        }

        $createdAt = isset($payload['created_at']) ? Carbon::parse($payload['created_at']) : null;
        if (! $createdAt || $createdAt->lt(now()->subMinutes(self::TTL_MINUTES))) {
            Cache::forget($this->cacheKey($email));

            return false;
        }

        if (! Hash::check($otp, $payload['otp'] ?? '')) {
            return false;
        }

        Cache::put($this->cacheKey($email), [
            ...$payload,
            'verified_at' => now()->toIso8601String(),
        ], now()->addMinutes(self::TTL_MINUTES));

        $session->put(self::SESSION_KEY, [
            'name' => $name,
            'email' => $email,
            'verified_at' => now()->toIso8601String(),
        ]);

        return true;
    }

    public function hasVerifiedEmail(Session $session, string $name, string $email): bool
    {
        return $this->verifiedAt($session, $name, $email) !== null;
    }

    public function verifiedAt(Session $session, string $name, string $email): ?Carbon
    {
        $verified = $session->get(self::SESSION_KEY);

        if (! is_array($verified)) {
            return null;
        }

        if (($verified['name'] ?? null) !== $name || ($verified['email'] ?? null) !== $email) {
            return null;
        }

        $verifiedAt = isset($verified['verified_at']) ? Carbon::parse($verified['verified_at']) : null;

        if ($verifiedAt === null || $verifiedAt->lt(now()->subMinutes(self::TTL_MINUTES))) {
            return null;
        }

        return $verifiedAt;
    }

    public function clearVerification(Session $session, string $email): void
    {
        $session->forget(self::SESSION_KEY);
        Cache::forget($this->cacheKey($email));
    }

    public function forgetCurrentVerification(Session $session): void
    {
        $session->forget(self::SESSION_KEY);
    }

    private function cacheKey(string $email): string
    {
        return self::CACHE_PREFIX.md5(strtolower($email));
    }
}
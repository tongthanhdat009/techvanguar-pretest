<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PasswordResetService
{
    /**
     * Generate and store OTP for password reset.
     * Send OTP to user's email.
     */
    public function sendOtp(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            return [
                'success' => false,
                'message' => 'Email không tồn tại trong hệ thống.',
            ];
        }

        // Generate 6-digit OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        // Send OTP email
        try {
            Mail::raw("Mã OTP đặt lại mật khẩu của bạn là: {$otp}\n\nMã này có hiệu lực trong 15 phút.", function ($message) use ($email) {
                $message->to($email)
                    ->subject('Mã OTP đặt lại mật khẩu');
            });
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email to {$email}: {$e->getMessage()}");
            return [
                'success' => false,
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Đã gửi mã OTP đến email của bạn.',
        ];
    }

    /**
     * Verify OTP for password reset.
     */
    public function verifyOtp(string $email, string $otp): bool
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $resetRecord) {
            return false;
        }

        // Check if OTP is expired (15 minutes)
        if (Carbon::parse($resetRecord->created_at)->lt(now()->subMinutes(15))) {
            return false;
        }

        return Hash::check($otp, $resetRecord->token);
    }

    /**
     * Reset password using OTP.
     */
    public function resetPassword(string $email, string $otp, string $password): bool
    {
        if (! $this->verifyOtp($email, $otp)) {
            return false;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return false;
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        // Delete the reset token
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        return true;
    }

    /**
     * Get OTP for debugging (remove in production).
     */
    public function getLatestOtp(string $email): ?string
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $resetRecord || Carbon::parse($resetRecord->created_at)->lt(now()->subMinutes(15))) {
            return null;
        }

        // Return the original OTP (this is only for debugging)
        // In production, we cannot reverse the hash
        return $resetRecord->token; // hashed
    }
}

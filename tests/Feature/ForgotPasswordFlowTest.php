<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_renders_all_steps(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Quên mật khẩu?')
            ->assertSee('form-send-otp', false)
            ->assertSee('verify-otp-form', false)
            ->assertSee('form-reset-password', false);
    }

    public function test_user_can_request_an_otp_for_a_known_email(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'toiladat20041405@gmail.com',
            'role' => User::ROLE_CLIENT,
        ]);

        $this->postJson(route('password.send-otp'), [
            'email' => $user->email,
        ])
            ->assertOk()
            ->assertJson([
                'message' => 'Đã gửi mã OTP đến email của bạn.',
            ]);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_user_can_verify_otp_and_reset_password(): void
    {
        $user = User::factory()->create([
            'email' => 'toiladat20041405@gmail.com',
            'role' => User::ROLE_CLIENT,
            'password' => 'old-password',
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make('123456'),
            'created_at' => now(),
        ]);

        $this->postJson(route('password.verify-otp'), [
            'email' => $user->email,
            'otp' => '123456',
        ])
            ->assertOk()
            ->assertJson([
                'message' => 'Xác thực OTP thành công.',
            ]);

        $this->postJson(route('password.reset'), [
            'email' => $user->email,
            'otp' => '123456',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
            ->assertOk()
            ->assertJson([
                'message' => 'Đặt lại mật khẩu thành công.',
            ]);

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }
}
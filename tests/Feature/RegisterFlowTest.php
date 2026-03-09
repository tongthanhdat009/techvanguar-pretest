<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisterFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_renders_multi_step_flow(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Tạo tài khoản mới')
            ->assertSee('form-register-send-otp', false)
            ->assertSee('form-register-verify-otp', false)
            ->assertSee('form-register-complete', false);
    }

    public function test_guest_can_request_registration_otp_for_a_new_email(): void
    {
        Mail::fake();

        $email = 'new-user@example.com';

        $this->postJson(route('register.send-otp'), [
            'name' => 'New User',
            'email' => $email,
        ])
            ->assertOk()
            ->assertJson([
                'message' => 'Đã gửi mã OTP xác nhận đến email của bạn.',
            ]);

        $this->assertIsArray(Cache::get('registration_otp:'.md5($email)));
    }

    public function test_guest_can_verify_registration_otp(): void
    {
        $email = 'verify-user@example.com';

        Cache::put('registration_otp:'.md5($email), [
            'name' => 'Verify User',
            'email' => $email,
            'otp' => Hash::make('123456'),
            'verified_at' => null,
            'created_at' => now()->toIso8601String(),
        ], now()->addMinutes(15));

        $this->postJson(route('register.verify-otp'), [
            'name' => 'Verify User',
            'email' => $email,
            'otp' => '123456',
        ])
            ->assertOk()
            ->assertJson([
                'message' => 'Xác thực OTP thành công. Bạn có thể thiết lập mật khẩu.',
            ])
            ->assertSessionHas('registration.verified.email', $email);
    }

    public function test_registration_otp_request_returns_login_redirect_when_email_is_already_used(): void
    {
        $user = User::factory()->create([
            'email' => 'used-email@example.com',
            'role' => User::ROLE_CLIENT,
        ]);

        $this->postJson(route('register.send-otp'), [
            'name' => 'Existing User',
            'email' => $user->email,
        ])
            ->assertStatus(409)
            ->assertJson([
                'code' => 'email_exists',
                'message' => 'Email này đã được sử dụng. Vui lòng đăng nhập hoặc chọn email khác.',
                'redirect' => route('client.login'),
            ]);
    }

    public function test_register_requires_verified_otp_before_creating_user(): void
    {
        $this->from(route('register'))
            ->post(route('register.store'), [
                'name' => 'Plain User',
                'email' => 'plain-user@example.com',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('otp');

        $this->assertDatabaseMissing('users', [
            'email' => 'plain-user@example.com',
        ]);
    }

    public function test_verified_guest_can_complete_registration_and_is_redirected_to_login(): void
    {
        $verifiedAt = now()->subMinute()->startOfSecond();

        $response = $this->withSession([
            'registration.verified' => [
                'name' => 'Fresh User',
                'email' => 'fresh-user@example.com',
                'verified_at' => $verifiedAt->toIso8601String(),
            ],
        ])->post(route('register.store'), [
            'name' => 'Fresh User',
            'email' => 'fresh-user@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response
            ->assertRedirect(route('client.login'))
            ->assertSessionHas('status', 'Đăng ký thành công. Bạn có thể đăng nhập ngay bây giờ.');

        $this->assertDatabaseHas('users', [
            'email' => 'fresh-user@example.com',
            'role' => User::ROLE_CLIENT,
        ]);

        $user = User::query()->where('email', 'fresh-user@example.com')->firstOrFail();

        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue($verifiedAt->equalTo($user->email_verified_at));
        $this->assertGuest('client');
    }
}
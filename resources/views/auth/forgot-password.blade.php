@extends('layouts.auth', [
    'title' => 'Quên mật khẩu',
    'type' => 'client'
])

@section('content')
    <span class="sr-only">Forgot password</span>

    <div id="password-reset-data"
         data-send-otp-url="{{ route('password.send-otp') }}"
         data-verify-otp-url="{{ route('password.verify-otp') }}"
         data-reset-password-url="{{ route('password.reset') }}"
         data-client-login-url="{{ route('client.login') }}"
         class="hidden"></div>

    @include('components.auth.auth-logo', [
        'type' => 'client',
        'title' => 'Quên mật khẩu?',
        'subtitle' => 'Nhập email của bạn để nhận mã OTP đặt lại mật khẩu.'
    ])

    {{-- Error Alert --}}
    <div id="alert-error" class="hidden">
        <div class="auth-alert error client">
            <span class="auth-alert-icon" aria-hidden="true">!</span>
            <div class="auth-alert-content"></div>
        </div>
    </div>

    {{-- Success Alert --}}
    <div id="alert-success" class="hidden">
        <div class="auth-alert success client">
            <span class="auth-alert-icon" aria-hidden="true">OK</span>
            <div class="auth-alert-content"></div>
        </div>
    </div>

    {{-- Forgot Password Form --}}
    <div id="forgot-password-form">
        <form id="form-send-otp" class="auth-form">
            <div class="auth-form-field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    placeholder="ban@email.com"
                    required
                    autofocus>
                <p class="auth-form-hint">Nhập email đã đăng ký tài khoản.</p>
            </div>

            <button type="submit" class="auth-submit client" data-loading="Đang gửi...">
                Gửi mã OTP
            </button>
        </form>

        <p class="auth-links client">
            <a href="{{ route('client.login') }}">← Quay lại đăng nhập</a>
        </p>
    </div>

    {{-- Verify OTP Form (hidden initially) --}}
    <div id="verify-otp-form" class="hidden">
        <form id="form-verify-otp" class="auth-form">
            <div class="auth-form-field">
                <label for="otp">Mã OTP</label>
                <input
                    type="text"
                    id="otp"
                    name="otp"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    placeholder="123456"
                    required
                    autofocus>
                <p class="auth-form-hint">Nhập mã 6 số đã được gửi đến email của bạn.</p>
            </div>

            <button type="submit" class="auth-submit client" data-loading="Đang xác thực...">
                Xác nhận mã
            </button>

            <button type="button" id="btn-resend-otp" class="auth-link-btn">
                Gửi lại mã
            </button>
        </form>

        <p class="auth-links client">
            <a href="#" id="btn-back-email">← Nhập lại email</a>
        </p>
    </div>

    {{-- Reset Password Form (hidden initially) --}}
    <div id="reset-password-form" class="hidden">
        <form id="form-reset-password" class="auth-form">
            <div class="auth-form-field">
                <label for="password">Mật khẩu mới</label>
                <div class="auth-input-group">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="••••••••"
                        minlength="8"
                        required
                        autofocus>
                    <button type="button" class="auth-toggle-password" data-target="password">
                        <span class="eye-icon"></span>
                    </button>
                </div>
                <p class="auth-form-hint">Tối thiểu 8 ký tự.</p>
            </div>

            <div class="auth-form-field">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <div class="auth-input-group">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="••••••••"
                        minlength="8"
                        required>
                    <button type="button" class="auth-toggle-password" data-target="password_confirmation">
                        <span class="eye-icon"></span>
                    </button>
                </div>
            </div>

            <button type="submit" class="auth-submit client" data-loading="Đang đặt lại...">
                Đặt lại mật khẩu
            </button>
        </form>

        <p class="auth-links client">
            <a href="{{ route('client.login') }}">← Quay lại đăng nhập</a>
        </p>
    </div>

@endsection

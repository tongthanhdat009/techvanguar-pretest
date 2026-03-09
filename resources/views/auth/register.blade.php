@extends('layouts.auth', [
    'title' => 'Đăng ký',
    'type' => 'client'
])

@section('content')
    @php
        $initialStep = old('name') && old('email') && ($errors->has('password') || $errors->has('password_confirmation'))
            ? 'password'
            : 'details';
        $registerFlowUrls = json_encode([
            'sendOtp' => route('register.send-otp'),
            'verifyOtp' => route('register.verify-otp'),
            'login' => route('client.login'),
        ], JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
        $registerFlowState = json_encode([
            'initialStep' => $initialStep,
            'name' => old('name', ''),
            'email' => old('email', ''),
            'serverError' => $errors->any() ? $errors->all() : null,
        ], JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
    @endphp

    @include('components.auth.auth-logo', [
        'type' => 'client',
        'title' => 'Tạo tài khoản',
        'subtitle' => 'Xác thực email rồi đặt mật khẩu.'
    ])

    <div id="alert-error" class="{{ $errors->any() ? '' : 'hidden' }}">
        <div class="auth-alert error client">
            <span class="auth-alert-icon" aria-hidden="true">!</span>
            <div class="auth-alert-content">{{ $errors->any() ? implode(' ', $errors->all()) : '' }}</div>
        </div>
    </div>

    <div id="alert-success" class="hidden">
        <div class="auth-alert success client">
            <span class="auth-alert-icon" aria-hidden="true">OK</span>
            <div class="auth-alert-content"></div>
        </div>
    </div>

    <div
        id="register-stage-flow"
        class="register-flow"
        data-inline-register-flow="true"
        data-urls='{{ $registerFlowUrls }}'
        data-state='{{ $registerFlowState }}'>
        <div class="register-progress" aria-label="Tiến trình đăng ký">
            <div class="register-progress-step is-active" data-register-progress="details">
                <span>1</span>
                <strong>Thông tin</strong>
            </div>
            <div class="register-progress-step" data-register-progress="otp">
                <span>2</span>
                <strong>Xác thực OTP</strong>
            </div>
            <div class="register-progress-step" data-register-progress="password">
                <span>3</span>
                <strong>Tạo mật khẩu</strong>
            </div>
        </div>

        <div id="register-step-details">
            <form id="form-register-send-otp" class="auth-form client" data-validate>
                <div class="auth-form-field">
                    <label for="register_name">Họ và tên</label>
                    <input
                        type="text"
                        id="register_name"
                        name="name"
                        autocomplete="name"
                        placeholder="Nguyễn Văn A"
                        value="{{ old('name') }}"
                        data-validate="required"
                        required
                        autofocus>
                </div>

                <div class="auth-form-field">
                    <label for="register_email">Email</label>
                    <input
                        type="email"
                        id="register_email"
                        name="email"
                        autocomplete="email"
                        placeholder="ban@email.com"
                        value="{{ old('email') }}"
                        data-validate="required|email"
                        required>
                </div>

                <button type="button" id="btn-register-send-otp" class="auth-submit client" data-loading="Đang gửi OTP...">
                    Nhận mã OTP
                </button>
            </form>
        </div>

        <div id="register-step-otp" class="hidden">
            <div class="register-summary-card">
                <p class="register-summary-kicker">Email xác nhận</p>
                <strong id="register-summary-name">{{ old('name') ?: 'Chưa có thông tin' }}</strong>
                <span id="register-summary-email">{{ old('email') ?: 'Bạn sẽ nhận OTP tại đây' }}</span>
            </div>

            <form id="form-register-verify-otp" class="auth-form client">
                <div class="auth-form-field">
                    <label for="register_otp">Mã OTP</label>
                    <input
                        type="text"
                        id="register_otp"
                        name="otp"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        maxlength="6"
                        placeholder="123456"
                        required
                        autofocus>
                </div>

                <button type="button" id="btn-register-verify-otp" class="auth-submit client" data-loading="Đang xác thực OTP...">
                    Xác nhận mã
                </button>

                <button type="button" id="btn-register-resend-otp" class="auth-link-btn">
                    Gửi lại mã OTP
                </button>
            </form>

            <p class="auth-links client">
                <a href="#" id="btn-register-back-details">← Quay lại chỉnh họ tên hoặc email</a>
            </p>
        </div>

        <div id="register-step-password" class="hidden">
            <div class="register-summary-card register-summary-card-success">
                <p class="register-summary-kicker">Đã xác thực</p>
                <strong id="register-password-summary-name">{{ old('name') ?: 'Chưa có thông tin' }}</strong>
                <span id="register-password-summary-email">{{ old('email') ?: 'Bạn sẽ đăng nhập bằng email này' }}</span>
            </div>

            <form id="form-register-complete" method="POST" action="{{ route('register.store') }}" class="auth-form client" data-validate>
                @csrf
                <input type="hidden" name="name" id="register_hidden_name" value="{{ old('name') }}">
                <input type="hidden" name="email" id="register_hidden_email" value="{{ old('email') }}">

                <div class="auth-form-field">
                    <label for="register_password">Mật khẩu</label>
                    <div class="auth-input-group">
                        <input
                            type="password"
                            id="register_password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Tối thiểu 8 ký tự"
                            minlength="8"
                            data-validate="required|min:8"
                            required>
                        <button type="button" class="auth-toggle-password" data-target="register_password">
                            <span class="eye-icon"></span>
                        </button>
                    </div>
                </div>

                <div class="auth-form-field">
                    <label for="register_password_confirmation">Xác nhận mật khẩu</label>
                    <div class="auth-input-group">
                        <input
                            type="password"
                            id="register_password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Nhập lại mật khẩu"
                            minlength="8"
                            data-validate="required|min:8"
                            required>
                        <button type="button" class="auth-toggle-password" data-target="register_password_confirmation">
                            <span class="eye-icon"></span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="auth-submit client" data-loading="Đang tạo tài khoản...">
                    Hoàn tất đăng ký
                </button>
            </form>

            <p class="auth-links client">
                <a href="#" id="btn-register-back-otp">← Quay lại bước OTP</a>
            </p>
        </div>
    </div>

    @include('components.auth.auth-links', [
        'type' => 'client',
        'prompt' => 'Đã có tài khoản? <a href="' . route('client.login') . '">Đăng nhập</a>'
    ])

    @push('scripts')
    <script>
        (() => {
            const container = document.getElementById('register-stage-flow');

            if (!container) {
                return;
            }

            const urls = JSON.parse(container.dataset.urls || '{}');
            const state = JSON.parse(container.dataset.state || '{}');
            const detailsStep = document.getElementById('register-step-details');
            const otpStep = document.getElementById('register-step-otp');
            const passwordStep = document.getElementById('register-step-password');
            const nameInput = document.getElementById('register_name');
            const emailInput = document.getElementById('register_email');
            const otpInput = document.getElementById('register_otp');
            const hiddenNameInput = document.getElementById('register_hidden_name');
            const hiddenEmailInput = document.getElementById('register_hidden_email');
            const sendOtpButton = document.getElementById('btn-register-send-otp');
            const verifyOtpButton = document.getElementById('btn-register-verify-otp');
            const resendOtpButton = document.getElementById('btn-register-resend-otp');
            const backDetailsButton = document.getElementById('btn-register-back-details');
            const backOtpButton = document.getElementById('btn-register-back-otp');
            const errorAlert = document.getElementById('alert-error');
            const successAlert = document.getElementById('alert-success');

            const runtime = {
                name: state.name || nameInput?.value || '',
                email: state.email || emailInput?.value || '',
            };

            const parseResponse = async (response) => {
                const contentType = response.headers.get('content-type') || '';

                if (contentType.includes('application/json')) {
                    return await response.json();
                }

                return {
                    message: response.status === 419
                        ? 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại.'
                        : 'Máy chủ trả về phản hồi không hợp lệ. Vui lòng thử lại.',
                };
            };

            const postJson = async (url, payload) => {
                const response = await fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(payload),
                });

                return {
                    response,
                    data: await parseResponse(response),
                };
            };

            const setLoading = (button, active) => {
                if (!button) {
                    return;
                }

                if (active) {
                    button.disabled = true;
                    button.dataset.originalText = button.textContent;
                    button.textContent = button.dataset.loading || 'Đang xử lý...';
                    return;
                }

                button.disabled = false;
                button.textContent = button.dataset.originalText || button.textContent;
            };

            const syncSummary = () => {
                if (hiddenNameInput) hiddenNameInput.value = runtime.name;
                if (hiddenEmailInput) hiddenEmailInput.value = runtime.email;

                const nameTargets = [
                    document.getElementById('register-summary-name'),
                    document.getElementById('register-password-summary-name'),
                ];

                const emailTargets = [
                    document.getElementById('register-summary-email'),
                    document.getElementById('register-password-summary-email'),
                ];

                nameTargets.forEach((target) => {
                    if (target) target.textContent = runtime.name || 'Chưa có thông tin';
                });

                emailTargets.forEach((target) => {
                    if (target) target.textContent = runtime.email || 'Bạn sẽ nhận OTP tại đây';
                });
            };

            const showAlert = (type, message) => {
                if (errorAlert) {
                    errorAlert.classList.add('hidden');
                    const content = errorAlert.querySelector('.auth-alert-content');
                    if (content) content.textContent = message || '';
                }

                if (successAlert) {
                    successAlert.classList.add('hidden');
                    const content = successAlert.querySelector('.auth-alert-content');
                    if (content) content.textContent = message || '';
                }

                if (type === 'error' && errorAlert) {
                    errorAlert.classList.remove('hidden');
                }

                if (type === 'success' && successAlert) {
                    successAlert.classList.remove('hidden');
                }
            };

            const showStep = (step) => {
                detailsStep?.classList.add('hidden');
                otpStep?.classList.add('hidden');
                passwordStep?.classList.add('hidden');

                document.querySelectorAll('[data-register-progress]').forEach((node) => {
                    node.classList.remove('is-active', 'is-complete');
                });

                if (step === 'otp' || step === 'password') {
                    document.querySelector('[data-register-progress="details"]')?.classList.add('is-complete');
                }

                if (step === 'password') {
                    document.querySelector('[data-register-progress="otp"]')?.classList.add('is-complete');
                }

                document.querySelector(`[data-register-progress="${step}"]`)?.classList.add('is-active');

                if (step === 'otp') {
                    otpStep?.classList.remove('hidden');
                    otpInput?.focus();
                    return;
                }

                if (step === 'password') {
                    passwordStep?.classList.remove('hidden');
                    document.getElementById('register_password')?.focus();
                    return;
                }

                detailsStep?.classList.remove('hidden');
                nameInput?.focus();
            };

            const isValidEmail = (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

            const handleSendOtp = async () => {
                runtime.name = nameInput?.value.trim() || '';
                runtime.email = emailInput?.value.trim() || '';
                syncSummary();

                if (!runtime.name) {
                    showAlert('error', 'Vui lòng nhập họ và tên.');
                    return;
                }

                if (!isValidEmail(runtime.email)) {
                    showAlert('error', 'Vui lòng nhập email hợp lệ.');
                    return;
                }

                setLoading(sendOtpButton, true);

                try {
                    const { response, data } = await postJson(urls.sendOtp || '/register/send-otp', {
                        name: runtime.name,
                        email: runtime.email,
                    });

                    if (!response.ok) {
                        if (data.code === 'email_exists') {
                            const stayOnRegister = window.confirm('Email này đã được sử dụng. Bạn có muốn tiếp tục đăng ký với email khác không? Chọn Hủy để quay lại trang đăng nhập.');

                            if (!stayOnRegister) {
                                window.location.href = data.redirect || urls.login || '/login/client';
                                return;
                            }
                        }

                        showAlert('error', data.message || 'Không thể gửi OTP xác nhận.');
                        return;
                    }

                    showAlert('success', data.message || 'Đã gửi mã OTP xác nhận đến email của bạn.');
                    showStep('otp');
                } catch (error) {
                    showAlert('error', 'Có lỗi xảy ra khi gửi OTP. Vui lòng thử lại.');
                } finally {
                    setLoading(sendOtpButton, false);
                }
            };

            const handleVerifyOtp = async () => {
                const otp = otpInput?.value.trim() || '';

                if (!/^\d{6}$/.test(otp)) {
                    showAlert('error', 'Vui lòng nhập mã OTP gồm đúng 6 chữ số.');
                    return;
                }

                setLoading(verifyOtpButton, true);

                try {
                    const { response, data } = await postJson(urls.verifyOtp || '/register/verify-otp', {
                        name: runtime.name,
                        email: runtime.email,
                        otp,
                    });

                    if (!response.ok) {
                        showAlert('error', data.message || 'Mã OTP không hợp lệ.');
                        return;
                    }

                    showAlert('success', data.message || 'Xác thực OTP thành công.');
                    showStep('password');
                } catch (error) {
                    showAlert('error', 'Có lỗi xảy ra khi xác thực OTP.');
                } finally {
                    setLoading(verifyOtpButton, false);
                }
            };

            if (sendOtpButton) {
                sendOtpButton.onclick = () => handleSendOtp();
            }

            if (verifyOtpButton) {
                verifyOtpButton.onclick = () => handleVerifyOtp();
            }

            if (resendOtpButton) {
                resendOtpButton.onclick = () => handleSendOtp();
            }

            sendOtpButton?.addEventListener('click', handleSendOtp);
            verifyOtpButton?.addEventListener('click', handleVerifyOtp);
            resendOtpButton?.addEventListener('click', handleSendOtp);
            backDetailsButton?.addEventListener('click', (event) => {
                event.preventDefault();
                showStep('details');
            });
            backOtpButton?.addEventListener('click', (event) => {
                event.preventDefault();
                showStep('otp');
            });

            detailsStep?.querySelector('form')?.addEventListener('submit', (event) => {
                event.preventDefault();
                handleSendOtp();
            });
            otpStep?.querySelector('form')?.addEventListener('submit', (event) => {
                event.preventDefault();
                handleVerifyOtp();
            });

            syncSummary();
            showStep(state.initialStep || 'details');

            if (Array.isArray(state.serverError) && state.serverError.length > 0) {
                showAlert('error', state.serverError.join(' '));
            }
        })();
    </script>
    @endpush
@endsection

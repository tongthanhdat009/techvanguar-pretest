/**
 * Auth JavaScript Module
 * Handles authentication pages functionality
 */

// ───────────────────────────────────────────────────────────────────────────────
// Auth Form Handling
// ───────────────────────────────────────────────────────────────────────────────

const AuthForm = {
    init() {
        this.forms = document.querySelectorAll('.auth-form');
        this.bindEvents();
    },

    bindEvents() {
        this.forms.forEach(form => {
            // Add loading state on submit
            form.addEventListener('submit', (e) => {
                this.handleSubmit(e, form);
            });

            // Clear errors on input
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    input.classList.remove('border-red-400');
                    const errorMsg = input.parentElement.querySelector('.error-message');
                    if (errorMsg) errorMsg.remove();
                });
            });
        });
    },

    handleSubmit(e, form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        // Add loading state
        submitBtn.disabled = true;
        submitBtn.dataset.originalText = submitBtn.textContent;
        submitBtn.textContent = submitBtn.dataset.loadingText || 'Đang xử lý...';
        submitBtn.classList.add('opacity-75');

        // Form will submit normally, this just adds visual feedback
    }
};

async function postJson(url, payload) {
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

    const contentType = response.headers.get('content-type') || '';

    if (contentType.includes('application/json')) {
        return {
            response,
            data: await response.json(),
        };
    }

    const text = await response.text();

    return {
        response,
        data: {
            message: response.status === 419
                ? 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại.'
                : 'Máy chủ trả về phản hồi không hợp lệ. Vui lòng thử lại.',
            raw: text,
        },
    };
}

// ───────────────────────────────────────────────────────────────────────────────
// Password Visibility Toggle
// ───────────────────────────────────────────────────────────────────────────────

const PasswordToggle = {
    init() {
        this.toggles = document.querySelectorAll('[data-password-toggle]');
        this.bindEvents();
    },

    bindEvents() {
        this.toggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const input = toggle.parentElement.querySelector('input');
                if (!input) return;

                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                // Update icon
                const eyeIcon = toggle.querySelector('[data-eye-icon]');
                const eyeOffIcon = toggle.querySelector('[data-eye-off-icon]');

                if (eyeIcon && eyeOffIcon) {
                    eyeIcon.classList.toggle('hidden');
                    eyeOffIcon.classList.toggle('hidden');
                }
            });
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Form Validation
// ───────────────────────────────────────────────────────────────────────────────

const FormValidation = {
    init() {
        this.forms = document.querySelectorAll('[data-validate]');
        this.bindEvents();
    },

    bindEvents() {
        this.forms.forEach(form => {
            const inputs = form.querySelectorAll('input[data-validate]');
            inputs.forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => {
                    if (input.dataset.validated === 'true') {
                        this.validateField(input);
                    }
                });
            });
        });
    },

    validateField(input) {
        input.dataset.validated = 'true';
        const rules = input.dataset.validate?.split('|') || [];
        let isValid = true;
        let error = '';

        for (const rule of rules) {
            const [ruleName, ruleValue] = rule.split(':');

            switch (ruleName) {
                case 'required':
                    if (!input.value.trim()) {
                        isValid = false;
                        error = 'Truong nay la bat buoc';
                    }
                    break;
                case 'email':
                    if (input.value && !this.isValidEmail(input.value)) {
                        isValid = false;
                        error = 'Vui long nhap email hop le';
                    }
                    break;
                case 'min':
                    if (input.value.length < parseInt(ruleValue)) {
                        isValid = false;
                        error = `Can toi thieu ${ruleValue} ky tu`;
                    }
                    break;
            }

            if (!isValid) break;
        }

        this.updateFieldStatus(input, isValid, error);
        return isValid;
    },

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    updateFieldStatus(input, isValid, error) {
        // Remove previous error
        const prevError = input.parentElement.querySelector('.error-message');
        if (prevError) prevError.remove();

        if (!isValid) {
            input.classList.add('border-red-400');
            input.classList.remove('border-green-400');

            const errorEl = document.createElement('p');
            errorEl.className = 'error-message auth-live-error';
            errorEl.textContent = error;
            input.parentElement.appendChild(errorEl);
        } else {
            input.classList.remove('border-red-400');
            input.classList.add('border-green-400');
        }
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Remember Me
// ───────────────────────────────────────────────────────────────────────────────

const RememberMe = {
    init() {
        this.checkboxes = document.querySelectorAll('input[name="remember"]');
        this.loadState();
        this.bindEvents();
    },

    bindEvents() {
        this.checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => this.saveState(checkbox));
        });
    },

    saveState(checkbox) {
        const emailInput = checkbox.closest('form').querySelector('input[name="email"]');
        if (!emailInput) return;

        if (checkbox.checked) {
            localStorage.setItem('remember_email', emailInput.value);
        } else {
            localStorage.removeItem('remember_email');
        }
    },

    loadState() {
        const savedEmail = localStorage.getItem('remember_email');
        if (!savedEmail) return;

        this.checkboxes.forEach(checkbox => {
            const emailInput = checkbox.closest('form').querySelector('input[name="email"]');
            if (emailInput && !emailInput.value) {
                emailInput.value = savedEmail;
                checkbox.checked = true;
            }
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Forgot Password
// ───────────────────────────────────────────────────────────────────────────────

const ForgotPassword = {
    state: {
        email: '',
        otp: '',
    },

    init() {
        this.sendOtpForm = document.getElementById('form-send-otp');
        this.verifyOtpContainer = document.getElementById('verify-otp-form');
        this.verifyOtpForm = document.getElementById('form-verify-otp');
        this.resetPasswordContainer = document.getElementById('reset-password-form');
        this.resetPasswordForm = document.getElementById('form-reset-password');
        this.forgotPasswordContainer = document.getElementById('forgot-password-form');

        // Get URLs from data attributes
        const dataEl = document.getElementById('password-reset-data');
        if (dataEl) {
            this.urls = {
                sendOtp: dataEl.dataset.sendOtpUrl || '/forgot-password/send-otp',
                verifyOtp: dataEl.dataset.verifyOtpUrl || '/forgot-password/verify-otp',
                resetPassword: dataEl.dataset.resetPasswordUrl || '/forgot-password/reset',
                clientLogin: dataEl.dataset.clientLoginUrl || '/login/client'
            };
        }

        if (!this.sendOtpForm) return;

        this.bindEvents();
    },

    bindEvents() {
        // Send OTP form
        if (this.sendOtpForm) {
            this.sendOtpForm.addEventListener('submit', (e) => this.handleSendOtp(e));
        }

        // Verify OTP form
        if (this.verifyOtpForm) {
            this.verifyOtpForm.addEventListener('submit', (e) => this.handleVerifyOtp(e));
        }

        // Reset password form
        if (this.resetPasswordForm) {
            this.resetPasswordForm.addEventListener('submit', (e) => this.handleResetPassword(e));
        }

        // Back to email button
        const backBtn = document.getElementById('btn-back-email');
        if (backBtn) {
            backBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showStep('email');
            });
        }

        // Resend OTP button
        const resendBtn = document.getElementById('btn-resend-otp');
        if (resendBtn) {
            resendBtn.addEventListener('click', () => this.handleResendOtp());
        }

        // Password toggle buttons
        const toggleBtns = document.querySelectorAll('.auth-toggle-password');
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', () => this.togglePassword(btn));
        });
    },

    async handleSendOtp(e) {
        e.preventDefault();

        const form = this.sendOtpForm;
        const email = form.querySelector('#email').value;

        if (!email) {
            this.showAlert('error', 'Vui lòng nhập email.');
            return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        this.setLoading(submitBtn, true);

        try {
            const { response, data } = await postJson(
                this.urls?.sendOtp || '/forgot-password/send-otp',
                { email }
            );

            if (response.ok) {
                this.state.email = email;
                this.showAlert('success', 'Đã gửi mã OTP đến email của bạn. Kiểm tra email để lấy mã.');
                this.showStep('otp');
            } else {
                this.showAlert('error', data.message || 'Không thể gửi mã OTP. Vui lòng thử lại.');
            }
        } catch (error) {
            console.error('Error sending OTP:', error);
            this.showAlert('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        } finally {
            this.setLoading(submitBtn, false);
        }
    },

    async handleVerifyOtp(e) {
        e.preventDefault();

        const form = this.verifyOtpForm;
        const otp = form.querySelector('#otp').value;

        if (!otp || otp.length !== 6) {
            this.showAlert('error', 'Vui lòng nhập mã OTP 6 số.');
            return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        this.setLoading(submitBtn, true);

        try {
            const { response, data } = await postJson(
                this.urls?.verifyOtp || '/forgot-password/verify-otp',
                { email: this.state.email, otp }
            );

            if (response.ok) {
                this.state.otp = otp;
                this.showAlert('success', 'Xác thực OTP thành công.');
                this.showStep('reset');
            } else {
                this.showAlert('error', data.message || 'Mã OTP không đúng.');
            }
        } catch (error) {
            console.error('Error verifying OTP:', error);
            this.showAlert('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        } finally {
            this.setLoading(submitBtn, false);
        }
    },

    async handleResetPassword(e) {
        e.preventDefault();

        const form = this.resetPasswordForm;
        const password = form.querySelector('#password').value;
        const passwordConfirmation = form.querySelector('#password_confirmation').value;

        if (!password || password.length < 8) {
            this.showAlert('error', 'Mật khẩu phải có ít nhất 8 ký tự.');
            return;
        }

        if (password !== passwordConfirmation) {
            this.showAlert('error', 'Xác nhận mật khẩu không khớp.');
            return;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        this.setLoading(submitBtn, true);

        try {
            const { response, data } = await postJson(
                this.urls?.resetPassword || '/forgot-password/reset',
                {
                    email: this.state.email,
                    otp: this.state.otp,
                    password: password,
                    password_confirmation: passwordConfirmation,
                }
            );

            if (response.ok) {
                this.showAlert('success', 'Đặt lại mật khẩu thành công! Đang chuyển hướng...');
                setTimeout(() => {
                    window.location.href = data.redirect || this.urls?.clientLogin || '/login/client';
                }, 1500);
            } else {
                this.showAlert('error', data.message || 'Không thể đặt lại mật khẩu.');
            }
        } catch (error) {
            console.error('Error resetting password:', error);
            this.showAlert('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        } finally {
            this.setLoading(submitBtn, false);
        }
    },

    async handleResendOtp() {
        if (!this.state.email) return;

        const resendBtn = document.getElementById('btn-resend-otp');
        this.setLoading(resendBtn, true);

        try {
            const { response, data } = await postJson(
                this.urls?.sendOtp || '/forgot-password/send-otp',
                { email: this.state.email }
            );

            if (response.ok) {
                this.showAlert('success', 'Đã gửi lại mã OTP. Kiểm tra email để lấy mã.');
            } else {
                this.showAlert('error', data.message || 'Không thể gửi lại mã OTP.');
            }
        } catch (error) {
            console.error('Error resending OTP:', error);
            this.showAlert('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        } finally {
            this.setLoading(resendBtn, false);
        }
    },

    togglePassword(btn) {
        const targetId = btn.dataset.target;
        const input = document.getElementById(targetId);
        if (!input) return;

        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        // Update icon (simple visual feedback)
        btn.style.opacity = isPassword ? '1' : '0.7';
    },

    showStep(step) {
        // Hide all forms
        if (this.forgotPasswordContainer) this.forgotPasswordContainer.classList.add('hidden');
        if (this.verifyOtpContainer) this.verifyOtpContainer.classList.add('hidden');
        if (this.resetPasswordContainer) this.resetPasswordContainer.classList.add('hidden');

        // Show current step
        switch (step) {
            case 'email':
                if (this.forgotPasswordContainer) this.forgotPasswordContainer.classList.remove('hidden');
                break;
            case 'otp':
                if (this.verifyOtpContainer) {
                    this.verifyOtpContainer.classList.remove('hidden');
                    this.verifyOtpForm.querySelector('#otp')?.focus();
                }
                break;
            case 'reset':
                if (this.resetPasswordContainer) {
                    this.resetPasswordContainer.classList.remove('hidden');
                    this.resetPasswordForm.querySelector('#password')?.focus();
                }
                break;
        }
    },

    showAlert(type, message) {
        const errorAlert = document.getElementById('alert-error');
        const successAlert = document.getElementById('alert-success');

        if (errorAlert) {
            errorAlert.classList.add('hidden');
            const msgEl = errorAlert.querySelector('.auth-alert-content p');
            if (msgEl) msgEl.textContent = message;
        }

        if (successAlert) {
            successAlert.classList.add('hidden');
            const msgEl = successAlert.querySelector('.auth-alert-content p');
            if (msgEl) msgEl.textContent = message;
        }

        if (type === 'error' && errorAlert) {
            errorAlert.classList.remove('hidden');
        } else if (type === 'success' && successAlert) {
            successAlert.classList.remove('hidden');
        }

        // Auto-hide success alerts after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                if (successAlert) successAlert.classList.add('hidden');
            }, 5000);
        }
    },

    setLoading(btn, isLoading) {
        if (!btn) return;

        if (isLoading) {
            btn.disabled = true;
            btn.dataset.originalText = btn.textContent;
            btn.textContent = btn.dataset.loading || 'Đang xử lý...';
        } else {
            btn.disabled = false;
            btn.textContent = btn.dataset.originalText || btn.textContent;
        }
    },
};

const RegisterFlow = {
    state: {
        name: '',
        email: '',
        verified: false,
    },

    init() {
        this.container = document.getElementById('register-stage-flow');

        if (!this.container || this.container.dataset.inlineRegisterFlow === 'true') return;

        this.detailsForm = document.getElementById('form-register-send-otp');
        this.otpForm = document.getElementById('form-register-verify-otp');
        this.passwordForm = document.getElementById('form-register-complete');
        this.sendOtpButton = document.getElementById('btn-register-send-otp');
        this.verifyOtpButton = document.getElementById('btn-register-verify-otp');

        this.detailsStep = document.getElementById('register-step-details');
        this.otpStep = document.getElementById('register-step-otp');
        this.passwordStep = document.getElementById('register-step-password');

        this.nameInput = document.getElementById('register_name');
        this.emailInput = document.getElementById('register_email');
        this.otpInput = document.getElementById('register_otp');
        this.hiddenNameInput = document.getElementById('register_hidden_name');
        this.hiddenEmailInput = document.getElementById('register_hidden_email');

        const registerUrls = this.readJsonData(this.container.dataset.urls);
        const initialState = this.readJsonData(this.container.dataset.state);
        this.urls = registerUrls;
        this.state.name = initialState.name || this.nameInput?.value || '';
        this.state.email = initialState.email || this.emailInput?.value || '';
        this.state.verified = (initialState.initialStep || 'details') === 'password';

        this.bindEvents();
        this.syncStateToInputs();
        this.showStep(initialState.initialStep || this.container.dataset.initialStep || 'details');

        if (initialState.serverError?.length) {
            this.showAlert('error', initialState.serverError.join(' '));
        }
    },

    bindEvents() {
        this.sendOtpButton?.addEventListener('click', (event) => this.handleSendOtp(event));
        this.verifyOtpButton?.addEventListener('click', (event) => this.handleVerifyOtp(event));

        this.detailsForm?.addEventListener('submit', (event) => this.handleSendOtp(event));
        this.otpForm?.addEventListener('submit', (event) => this.handleVerifyOtp(event));

        document.getElementById('btn-register-resend-otp')?.addEventListener('click', () => this.handleResendOtp());
        document.getElementById('btn-register-back-details')?.addEventListener('click', (event) => {
            event.preventDefault();
            this.state.verified = false;
            this.showStep('details');
        });
        document.getElementById('btn-register-back-otp')?.addEventListener('click', (event) => {
            event.preventDefault();
            this.showStep('otp');
        });

        this.nameInput?.addEventListener('input', () => {
            this.state.name = this.nameInput.value.trim();
            this.state.verified = false;
            this.syncStateToInputs();
        });

        this.emailInput?.addEventListener('input', () => {
            this.state.email = this.emailInput.value.trim();
            this.state.verified = false;
            this.syncStateToInputs();
        });
    },

    async handleSendOtp(event) {
        event.preventDefault();

        this.state.name = this.nameInput?.value.trim() || '';
        this.state.email = this.emailInput?.value.trim() || '';
        this.state.verified = false;
        this.syncStateToInputs();

        if (!this.state.name) {
            this.showAlert('error', 'Vui lòng nhập họ và tên.');
            return;
        }

        if (!this.isValidEmail(this.state.email)) {
            this.showAlert('error', 'Vui lòng nhập email hợp lệ.');
            return;
        }

        const submitBtn = this.detailsForm?.querySelector('button[type="submit"]');
        this.setLoading(submitBtn, true);

        try {
            const { response, data } = await postJson(
                this.urls?.sendOtp || '/register/send-otp',
                {
                    name: this.state.name,
                    email: this.state.email,
                }
            );

            if (!response.ok) {
                if (data.code === 'email_exists') {
                    const shouldStayOnRegister = window.confirm(
                        'Email này đã được sử dụng. Bạn có muốn tiếp tục đăng ký với email khác không? Chọn Hủy để quay lại trang đăng nhập.'
                    );

                    if (!shouldStayOnRegister) {
                        window.location.href = data.redirect || this.urls?.login || '/login/client';
                        return;
                    }

                    this.showAlert('error', data.message || 'Email này đã được sử dụng.');
                    this.nameInput?.focus();
                    return;
                }

                this.showAlert('error', data.message || 'Không thể gửi OTP xác nhận.');
                return;
            }

            this.showAlert('success', data.message || 'Đã gửi mã OTP xác nhận đến email của bạn.');
            this.showStep('otp');
        } catch (error) {
            console.error('Error sending register OTP:', error);
            this.showAlert('error', 'Có lỗi xảy ra khi gửi OTP. Vui lòng thử lại.');
        } finally {
            this.setLoading(submitBtn, false);
        }
    },

    async handleVerifyOtp(event) {
        event.preventDefault();

        const otp = this.otpInput?.value.trim() || '';

        if (!this.state.name || !this.isValidEmail(this.state.email)) {
            this.showAlert('error', 'Thông tin đăng ký chưa đầy đủ. Vui lòng nhập lại họ tên và email.');
            this.showStep('details');
            return;
        }

        if (!/^\d{6}$/.test(otp)) {
            this.showAlert('error', 'Vui lòng nhập mã OTP gồm đúng 6 chữ số.');
            return;
        }

        const submitBtn = this.otpForm?.querySelector('button[type="submit"]');
        this.setLoading(submitBtn, true);

        try {
            const { response, data } = await postJson(
                this.urls?.verifyOtp || '/register/verify-otp',
                {
                    name: this.state.name,
                    email: this.state.email,
                    otp,
                }
            );

            if (!response.ok) {
                this.showAlert('error', data.message || 'Mã OTP không hợp lệ.');
                return;
            }

            this.state.verified = true;
            this.syncStateToInputs();
            this.showAlert('success', data.message || 'Xác thực OTP thành công.');
            this.showStep('password');
        } catch (error) {
            console.error('Error verifying register OTP:', error);
            this.showAlert('error', 'Có lỗi xảy ra khi xác thực OTP.');
        } finally {
            this.setLoading(submitBtn, false);
        }
    },

    async handleResendOtp() {
        if (!this.state.name || !this.isValidEmail(this.state.email)) {
            this.showAlert('error', 'Vui lòng nhập lại họ tên và email trước khi gửi lại OTP.');
            this.showStep('details');
            return;
        }

        const resendBtn = document.getElementById('btn-register-resend-otp');
        this.setLoading(resendBtn, true);

        try {
            const { response, data } = await postJson(
                this.urls?.sendOtp || '/register/send-otp',
                {
                    name: this.state.name,
                    email: this.state.email,
                }
            );

            if (!response.ok) {
                this.showAlert('error', data.message || 'Không thể gửi lại OTP.');
                return;
            }

            this.showAlert('success', data.message || 'Đã gửi lại mã OTP xác nhận.');
        } catch (error) {
            console.error('Error resending register OTP:', error);
            this.showAlert('error', 'Có lỗi xảy ra khi gửi lại OTP.');
        } finally {
            this.setLoading(resendBtn, false);
        }
    },

    syncStateToInputs() {
        if (this.hiddenNameInput) this.hiddenNameInput.value = this.state.name;
        if (this.hiddenEmailInput) this.hiddenEmailInput.value = this.state.email;

        const nameTargets = [
            document.getElementById('register-summary-name'),
            document.getElementById('register-password-summary-name'),
        ];

        const emailTargets = [
            document.getElementById('register-summary-email'),
            document.getElementById('register-password-summary-email'),
        ];

        nameTargets.forEach((target) => {
            if (target) target.textContent = this.state.name || 'Chưa có thông tin';
        });

        emailTargets.forEach((target) => {
            if (target) target.textContent = this.state.email || 'Bạn sẽ nhận OTP tại đây';
        });
    },

    showStep(step) {
        this.detailsStep?.classList.add('hidden');
        this.otpStep?.classList.add('hidden');
        this.passwordStep?.classList.add('hidden');

        document.querySelectorAll('[data-register-progress]').forEach((item) => {
            item.classList.remove('is-active', 'is-complete');

            if (item.dataset.registerProgress === step) {
                item.classList.add('is-active');
            }
        });

        if (step === 'otp' || step === 'password') {
            document.querySelector('[data-register-progress="details"]')?.classList.add('is-complete');
        }

        if (step === 'password') {
            document.querySelector('[data-register-progress="otp"]')?.classList.add('is-complete');
        }

        if (step === 'otp') {
            this.otpStep?.classList.remove('hidden');
            this.otpInput?.focus();
            return;
        }

        if (step === 'password') {
            this.passwordStep?.classList.remove('hidden');
            document.getElementById('register_password')?.focus();
            return;
        }

        this.detailsStep?.classList.remove('hidden');
        this.nameInput?.focus();
    },

    showAlert(type, message) {
        const errorAlert = document.getElementById('alert-error');
        const successAlert = document.getElementById('alert-success');

        if (errorAlert) {
            errorAlert.classList.add('hidden');
            const content = errorAlert.querySelector('.auth-alert-content');
            if (content) content.textContent = message;
        }

        if (successAlert) {
            successAlert.classList.add('hidden');
            const content = successAlert.querySelector('.auth-alert-content');
            if (content) content.textContent = message;
        }

        if (type === 'error' && errorAlert) {
            errorAlert.classList.remove('hidden');
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        if (type === 'success' && successAlert) {
            successAlert.classList.remove('hidden');
        }
    },

    setLoading(btn, isLoading) {
        if (!btn) return;

        if (isLoading) {
            btn.disabled = true;
            btn.dataset.originalText = btn.textContent;
            btn.textContent = btn.dataset.loading || 'Đang xử lý...';
            return;
        }

        btn.disabled = false;
        btn.textContent = btn.dataset.originalText || btn.textContent;
    },

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    readJsonData(value) {
        if (!value) return {};

        try {
            return JSON.parse(value);
        } catch (error) {
            console.error('Error parsing register flow data:', error);
            return {};
        }
    },
};

// ───────────────────────────────────────────────────────────────────────────────
// Typing Animation
// ───────────────────────────────────────────────────────────────────────────────

const TypingAnimation = {
    init() {
        this.elements = document.querySelectorAll('[data-typing]');
        if (this.elements.length === 0) return;

        this.elements.forEach(el => this.type(el));
    },

    type(element) {
        const texts = JSON.parse(element.dataset.typing || '[]');
        if (!texts.length) return;

        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typingSpeed = 80;
        let deletingSpeed = 40;
        let pauseDuration = 1500;
        let pauseBetweenTexts = 500;

        const type = () => {
            const currentText = texts[textIndex];

            if (isDeleting) {
                element.textContent = currentText.substring(0, charIndex - 1);
                charIndex--;
                typingSpeed = deletingSpeed;
            } else {
                element.textContent = currentText.substring(0, charIndex + 1);
                charIndex++;
                typingSpeed = 80 + Math.random() * 40; // Random typing speed
            }

            if (!isDeleting && charIndex === currentText.length) {
                // Finished typing current text
                isDeleting = true;
                typingSpeed = pauseDuration;
                element.classList.add('completed');
            } else if (isDeleting && charIndex === 0) {
                // Finished deleting
                isDeleting = false;
                textIndex = (textIndex + 1) % texts.length;
                typingSpeed = pauseBetweenTexts;
                element.classList.remove('completed');
            }

            setTimeout(type, typingSpeed);
        };

        // Start typing after a small delay
        setTimeout(type, 500);
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Initialize
// ───────────────────────────────────────────────────────────────────────────────

function initializeAuthPages() {
    AuthForm.init();
    PasswordToggle.init();
    FormValidation.init();
    RememberMe.init();
    ForgotPassword.init();
    RegisterFlow.init();
    TypingAnimation.init();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeAuthPages, { once: true });
} else {
    initializeAuthPages();
}

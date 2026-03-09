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
            const response = await fetch(window.passwordResetUrls?.sendOtp || '/forgot-password/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ email }),
            });

            const data = await response.json();

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
            const response = await fetch(window.passwordResetUrls?.verifyOtp || '/forgot-password/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ email: this.state.email, otp }),
            });

            const data = await response.json();

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
            const response = await fetch(window.passwordResetUrls?.resetPassword || '/forgot-password/reset', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    email: this.state.email,
                    otp: this.state.otp,
                    password: password,
                    password_confirmation: passwordConfirmation,
                }),
            });

            const data = await response.json();

            if (response.ok) {
                this.showAlert('success', 'Đặt lại mật khẩu thành công! Đang chuyển hướng...');
                setTimeout(() => {
                    window.location.href = data.redirect || window.passwordResetUrls?.clientLogin || '/login/client';
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
            const response = await fetch(window.passwordResetUrls?.sendOtp || '/forgot-password/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ email: this.state.email }),
            });

            const data = await response.json();

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

// ───────────────────────────────────────────────────────────────────────────────
// Initialize
// ───────────────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    AuthForm.init();
    PasswordToggle.init();
    FormValidation.init();
    RememberMe.init();
    ForgotPassword.init();
});

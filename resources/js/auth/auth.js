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
        submitBtn.textContent = submitBtn.dataset.loadingText || 'Dang xu ly...';
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
// Initialize
// ───────────────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    AuthForm.init();
    PasswordToggle.init();
    FormValidation.init();
    RememberMe.init();
});

/**
 * Admin JavaScript Module
 * Handles admin-specific functionality
 */

// ───────────────────────────────────────────────────────────────────────────────
// Admin Sidebar
// ───────────────────────────────────────────────────────────────────────────────

const AdminSidebar = {
    STORAGE_KEY: 'admin_sidebar_open',

    init() {
        this.sidebar = document.getElementById('admin-sidebar');
        this.mainWrapper = document.getElementById('admin-main-wrapper');
        if (!this.sidebar) return;

        this.overlay = document.querySelector('[data-admin-sidebar-overlay]');

        // Read state from localStorage, default to true if not set
        const storedState = localStorage.getItem(this.STORAGE_KEY);
        this.isOpen = storedState === null ? true : storedState === 'true';

        this.bindEvents();
        this.updateUI();
    },

    bindEvents() {
        // Toggle sidebar button
        const toggleBtn = document.querySelector('[data-admin-sidebar-toggle]');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggle());
        }

        // Close on overlay click
        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.close());
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            // Only apply this behavior on mobile
            if (window.innerWidth >= 1024) return;

            if (this.isOpen &&
                !this.sidebar.contains(e.target) &&
                !e.target.closest('[data-admin-sidebar-toggle]')) {
                this.close();
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                // On desktop, show overlay when sidebar is closed
                if (this.overlay && this.isOpen) {
                    this.overlay.classList.add('hidden');
                }
            } else {
                // On mobile, always hide overlay initially
                if (this.overlay && !this.isOpen) {
                    this.overlay.classList.add('hidden');
                }
            }
        });
    },

    toggle() {
        this.isOpen ? this.close() : this.open();
    },

    open() {
        this.isOpen = true;
        this.saveState();
        this.updateUI();
    },

    close() {
        this.isOpen = false;
        this.saveState();
        this.updateUI();
    },

    saveState() {
        localStorage.setItem(this.STORAGE_KEY, this.isOpen ? 'true' : 'false');
    },

    updateUI() {
        // Update sidebar attribute
        this.sidebar.setAttribute('admin-sidebar-open', this.isOpen ? 'true' : 'false');

        // Update main wrapper margin
        if (this.mainWrapper) {
            if (this.isOpen && window.innerWidth >= 1024) {
                this.mainWrapper.classList.add('sidebar-open');
            } else {
                this.mainWrapper.classList.remove('sidebar-open');
            }
        }

        // Update overlay visibility
        if (this.overlay) {
            if (this.isOpen && window.innerWidth < 1024) {
                this.overlay.classList.remove('hidden');
            } else {
                this.overlay.classList.add('hidden');
            }
        }
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Admin Toast Notifications
// ───────────────────────────────────────────────────────────────────────────────

const AdminToast = {
    init() {
        this.stack = document.querySelector('[data-admin-toast-stack]');
        if (!this.stack) return;

        // Auto-dismiss existing toasts from server-rendered HTML
        const existingToasts = this.stack.querySelectorAll('.toast');
        existingToasts.forEach(toast => {
            this.scheduleAutoDismiss(toast);
        });
    },

    show(message, type = 'success') {
        if (!this.stack) return;

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;

        this.stack.appendChild(toast);

        this.scheduleAutoDismiss(toast);
    },

    scheduleAutoDismiss(toast) {
        // Auto remove after 4 seconds
        setTimeout(() => {
            // Add hiding class for slide out animation
            toast.classList.add('hiding');

            // Remove after animation completes (300ms)
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 4000);
    },

    success(message) {
        this.show(message, 'success');
    },

    error(message) {
        this.show(message, 'error');
    },

    info(message) {
        this.show(message, 'info');
    },

    warning(message) {
        this.show(message, 'warning');
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Admin Confirm Modal
// ───────────────────────────────────────────────────────────────────────────────

const AdminConfirm = {
    init() {
        this.modal = document.getElementById('confirm-modal');
        if (!this.modal) return;
        this.bindEvents();
    },

    bindEvents() {
        // Intercept clicks on any element with data-admin-confirm (but never the modal itself)
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-admin-confirm]');
            if (!trigger) return;
            // Ignore clicks that originate inside the modal overlay
            if (this.modal && this.modal.contains(trigger)) return;
            e.preventDefault();
            this.show(trigger);
        });

        // Cancel button (uses stable ID so it never conflicts with trigger attributes)
        document.getElementById('confirm-modal-cancel')?.addEventListener('click', () => this.hide());

        // Close on backdrop click
        this.modal?.addEventListener('click', (e) => {
            if (e.target === this.modal) this.hide();
        });
    },

    show(trigger) {
        const message = trigger.getAttribute('data-confirm-message');
        const acceptText = trigger.getAttribute('data-confirm-accept');

        const messageEl = document.getElementById('confirm-modal-message');
        const acceptBtn = document.getElementById('confirm-modal-ok');

        if (messageEl) messageEl.textContent = message || 'Bạn có chắc chắn muốn tiếp tục?';
        if (acceptBtn) acceptBtn.textContent = acceptText || 'Xác nhận';

        const confirmBtn = document.getElementById('confirm-modal-ok');
        if (confirmBtn) {
            // Clone to remove previous onclick listener
            const fresh = confirmBtn.cloneNode(true);
            fresh.textContent = acceptText || 'Xác nhận';
            confirmBtn.replaceWith(fresh);
            fresh.addEventListener('click', () => {
                // Submit the associated form
                const form = trigger.tagName === 'FORM'
                    ? trigger
                    : trigger.closest('form');
                if (form) form.submit();
                this.hide();
            });
        }

        this.modal.setAttribute('open', 'true');
    },

    hide() {
        this.modal.removeAttribute('open');
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Theme Toggle
// ───────────────────────────────────────────────────────────────────────────────

const ThemeToggle = {
    STORAGE_KEY: 'theme',

    init() {
        // Check for saved theme preference or default to 'dark'
        const savedTheme = localStorage.getItem(this.STORAGE_KEY);
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        this.darkMode = savedTheme ? savedTheme === 'dark' : systemPrefersDark;

        this.applyTheme();

        // Make functions globally available for Alpine.js
        window.toggleTheme = () => this.toggle();
        window.setTheme = (mode) => this.setTheme(mode);
        window.darkMode = this.darkMode;
        window.theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
    },

    toggle() {
        this.darkMode = !this.darkMode;
        window.darkMode = this.darkMode;
        this.applyTheme();
        this.saveTheme();
    },

    setTheme(mode) {
        this.darkMode = mode === 'dark';
        window.darkMode = this.darkMode;
        window.theme = mode;
        this.applyTheme();
        this.saveTheme();
    },

    applyTheme() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },

    saveTheme() {
        localStorage.setItem(this.STORAGE_KEY, this.darkMode ? 'dark' : 'light');
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Language Manager
// ───────────────────────────────────────────────────────────────────────────────

const LanguageManager = {
    STORAGE_KEY: 'language',
    DEFAULT_LANG: 'vi',

    init() {
        // Get saved language or default to Vietnamese
        const savedLang = localStorage.getItem(this.STORAGE_KEY) || this.DEFAULT_LANG;
        this.currentLang = savedLang;

        // Make functions globally available for Alpine.js
        window.setLanguage = (lang) => this.setLanguage(lang);
        window.language = this.currentLang;
    },

    setLanguage(lang) {
        this.currentLang = lang;
        window.language = lang;
        localStorage.setItem(this.STORAGE_KEY, lang);

        // Optionally reload page to apply language changes
        // window.location.reload();

        // Show toast notification
        const langNames = { vi: 'Tiếng Việt', en: 'English' };
        if (window.AdminToast) {
            AdminToast.success(`Ngôn ngữ đã được thay đổi sang ${langNames[lang]}`);
        }
    },

    getLanguage() {
        return this.currentLang;
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Admin User Dropdown
// ───────────────────────────────────────────────────────────────────────────────

const AdminDropdown = {
    init() {
        // User dropdown
        const dropdown = document.querySelector('[data-user-dropdown]');
        const toggleBtn = dropdown?.querySelector('[data-dropdown-toggle]');
        const dropdownMenu = dropdown?.querySelector('.dropdown-menu');
        const dropdownBackdrop = dropdown?.querySelector('[data-dropdown-backdrop]');
        const mobileDropdownMediaQuery = window.matchMedia('(max-width: 640px)');

        const placeDropdownInViewport = () => {
            if (!mobileDropdownMediaQuery.matches || !dropdownMenu || !dropdownBackdrop) {
                return;
            }

            if (dropdownBackdrop.parentElement !== document.body) {
                document.body.appendChild(dropdownBackdrop);
            }

            if (dropdownMenu.parentElement !== document.body) {
                document.body.appendChild(dropdownMenu);
            }
        };

        const restoreDropdownPlacement = () => {
            if (!dropdown || !dropdownMenu || !dropdownBackdrop) {
                return;
            }

            if (dropdownBackdrop.parentElement !== dropdown) {
                dropdown.insertBefore(dropdownBackdrop, dropdownMenu.parentElement === dropdown ? dropdownMenu : null);
            }

            if (dropdownMenu.parentElement !== dropdown) {
                dropdown.appendChild(dropdownMenu);
            }
        };

        const syncDropdownPlacement = () => {
            if (mobileDropdownMediaQuery.matches) {
                placeDropdownInViewport();
                return;
            }

            restoreDropdownPlacement();
        };

        const syncMobileDropdownState = (isOpen) => {
            document.body.classList.toggle('mobile-dropdown-open', isOpen && mobileDropdownMediaQuery.matches);
            dropdownMenu?.classList.toggle('is-open', isOpen);
            dropdownBackdrop?.classList.toggle('is-open', isOpen);
        };

        const closeDropdown = () => {
            dropdown?.classList.remove('active');
            toggleBtn?.setAttribute('aria-expanded', 'false');
            syncMobileDropdownState(false);
        };

        const openDropdown = () => {
            dropdown?.classList.add('active');
            toggleBtn?.setAttribute('aria-expanded', 'true');
            syncMobileDropdownState(true);
        };

        toggleBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            const expanded = toggleBtn.getAttribute('aria-expanded') === 'true';
            if (expanded) {
                closeDropdown();
                return;
            }

            syncDropdownPlacement();
            openDropdown();
        });

        dropdownBackdrop?.addEventListener('click', closeDropdown);
        dropdownMenu?.addEventListener('click', (e) => e.stopPropagation());

        // Close dropdown on outside click
        document.addEventListener('click', closeDropdown);

        syncDropdownPlacement();

        mobileDropdownMediaQuery.addEventListener('change', () => {
            if (!mobileDropdownMediaQuery.matches) {
                document.body.classList.remove('mobile-dropdown-open');
            }

            syncDropdownPlacement();
            syncMobileDropdownState(dropdown?.classList.contains('active') ?? false);
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Initialize
// ───────────────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    AdminSidebar.init();
    AdminToast.init();
    AdminConfirm.init();
    ThemeToggle.init();
    LanguageManager.init();
    AdminDropdown.init();

    // Show flash messages as toasts
    const flashMessage = document.querySelector('[data-flash-message]');
    if (flashMessage) {
        AdminToast.show(flashMessage.textContent, flashMessage.dataset.type || 'success');
    }
});

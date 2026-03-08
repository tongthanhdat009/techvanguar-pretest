/**
 * Admin JavaScript Module
 * Handles admin-specific functionality
 */

// ───────────────────────────────────────────────────────────────────────────────
// Admin Sidebar
// ───────────────────────────────────────────────────────────────────────────────

const AdminSidebar = {
    init() {
        this.sidebar = document.getElementById('admin-sidebar');
        if (!this.sidebar) return;

        this.overlay = document.querySelector('[data-admin-sidebar-overlay]');
        this.isOpen = this.sidebar.getAttribute('admin-sidebar-open') === 'true';
        this.bindEvents();
    },

    bindEvents() {
        // Toggle sidebar button
        const toggleBtn = document.querySelector('[data-admin-sidebar-toggle]');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggle());
        }

        // Close on overlay click (mobile)
        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.close());
        }

        // Close sidebar when clicking outside (desktop fallback)
        document.addEventListener('click', (e) => {
            if (window.innerWidth >= 1024) return; // skip on desktop
            if (this.isOpen &&
                !this.sidebar.contains(e.target) &&
                !e.target.closest('[data-admin-sidebar-toggle]')) {
                this.close();
            }
        });
    },

    toggle() {
        this.isOpen ? this.close() : this.open();
    },

    open() {
        this.isOpen = true;
        this.sidebar.setAttribute('admin-sidebar-open', 'true');
        if (this.overlay) this.overlay.classList.remove('hidden');
    },

    close() {
        this.isOpen = false;
        this.sidebar.setAttribute('admin-sidebar-open', 'false');
        if (this.overlay) this.overlay.classList.add('hidden');
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Admin Toast Notifications
// ───────────────────────────────────────────────────────────────────────────────

const AdminToast = {
    init() {
        this.stack = document.querySelector('[data-admin-toast-stack]');
        if (!this.stack) return;
    },

    show(message, type = 'success') {
        if (!this.stack) return;

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;

        this.stack.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
    },

    success(message) {
        this.show(message, 'success');
    },

    error(message) {
        this.show(message, 'error');
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
        // Handle elements with data-admin-confirm attribute
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-admin-confirm]');
            if (trigger) {
                e.preventDefault();
                this.show(trigger);
            }
        });

        // Cancel button
        const cancelBtn = this.modal?.querySelector('[data-confirm-cancel]');
        cancelBtn?.addEventListener('click', () => this.hide());
    },

    show(trigger) {
        const message = trigger.getAttribute('data-confirm-message');
        const acceptText = trigger.getAttribute('data-confirm-accept');

        const messageEl = this.modal.querySelector('[data-confirm-message]');
        const acceptBtn = this.modal.querySelector('[data-confirm-accept]');

        if (messageEl) messageEl.textContent = message;
        if (acceptBtn) acceptBtn.textContent = acceptText || 'Confirm';

        // Set up confirm action
        const confirmBtn = this.modal.querySelector('[data-confirm-ok]');
        if (confirmBtn) {
            confirmBtn.onclick = () => {
                if (trigger.tagName === 'FORM') {
                    trigger.submit();
                } else if (trigger.tagName === 'BUTTON' && trigger.type === 'submit') {
                    trigger.closest('form').submit();
                }
                this.hide();
            };
        }

        this.modal.setAttribute('open', 'true');
    },

    hide() {
        this.modal.removeAttribute('open');
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Initialize
// ───────────────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    AdminSidebar.init();
    AdminToast.init();
    AdminConfirm.init();

    // Show flash messages as toasts
    const flashMessage = document.querySelector('[data-flash-message]');
    if (flashMessage) {
        AdminToast.show(flashMessage.textContent, flashMessage.dataset.type || 'success');
    }
});

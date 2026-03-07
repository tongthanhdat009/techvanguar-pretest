const SIDEBAR_STORAGE_KEY = 'admin-sidebar-open';

const getStoredSidebarState = () => {
    try {
        return window.localStorage.getItem(SIDEBAR_STORAGE_KEY) !== 'false';
    } catch (error) {
        return true;
    }
};

const persistSidebarState = (isOpen) => {
    try {
        window.localStorage.setItem(SIDEBAR_STORAGE_KEY, isOpen ? 'true' : 'false');
    } catch (error) {
        // Ignore storage access issues.
    }
};

const applySidebarState = (isOpen) => {
    document.documentElement.dataset.adminSidebar = isOpen ? 'open' : 'closed';

    if (window.matchMedia('(max-width: 1023px)').matches) {
        document.body.classList.toggle('overflow-hidden', isOpen);
    } else {
        document.body.classList.remove('overflow-hidden');
    }
};

const initializeSidebar = () => {
    if (!document.querySelector('[data-admin-sidebar]')) {
        return;
    }

    let isOpen = getStoredSidebarState();

    const syncSidebar = () => {
        persistSidebarState(isOpen);
        applySidebarState(isOpen);
    };

    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            isOpen = !isOpen;
            syncSidebar();
        });
    });

    document.querySelectorAll('[data-sidebar-close]').forEach((button) => {
        button.addEventListener('click', () => {
            isOpen = false;
            syncSidebar();
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen && window.matchMedia('(max-width: 1023px)').matches) {
            isOpen = false;
            syncSidebar();
        }
    });

    window.addEventListener('resize', () => {
        applySidebarState(isOpen);
    });

    syncSidebar();
};

const dismissToast = (toast) => {
    if (!toast || toast.dataset.dismissing === 'true') {
        return;
    }

    toast.dataset.dismissing = 'true';
    toast.classList.add('translate-y-2', 'opacity-0');

    window.setTimeout(() => {
        toast.remove();
    }, 200);
};

const createToastElement = (message, type = 'success') => {
    const toast = document.createElement('div');
    const tone = type === 'error'
        ? 'border-rose-200 bg-rose-50 text-rose-700'
        : 'border-emerald-200 bg-emerald-50 text-emerald-700';
    const content = document.createElement('div');
    const dismissButton = document.createElement('button');

    toast.className = `pointer-events-auto flex items-start gap-3 rounded-2xl border px-4 py-3 shadow-lg transition duration-200 ${tone}`;
    toast.dataset.adminToast = 'true';
    toast.dataset.timeout = type === 'error' ? '6000' : '4000';
    content.className = 'min-w-0 flex-1 text-sm font-medium';
    content.textContent = message;
    dismissButton.type = 'button';
    dismissButton.className = 'rounded-full p-1 transition hover:bg-black/5';
    dismissButton.dataset.toastDismiss = 'true';
    dismissButton.setAttribute('aria-label', 'Dismiss notification');
    dismissButton.innerHTML = `
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    `;
    toast.append(content, dismissButton);

    return toast;
};

const initializeToasts = () => {
    const toastStack = document.querySelector('[data-admin-toast-stack]');

    if (!toastStack) {
        return;
    }

    const queueDismissal = (toast) => {
        const timeout = Number.parseInt(toast.dataset.timeout ?? '4000', 10);

        if (timeout > 0) {
            window.setTimeout(() => dismissToast(toast), timeout);
        }
    };

    toastStack.querySelectorAll('[data-admin-toast]').forEach((toast) => {
        queueDismissal(toast);
    });

    toastStack.addEventListener('click', (event) => {
        const dismissButton = event.target.closest('[data-toast-dismiss]');

        if (dismissButton) {
            dismissToast(dismissButton.closest('[data-admin-toast]'));
        }
    });

    window.adminToast = {
        show(message, type = 'success') {
            const toast = createToastElement(message, type);
            toastStack.appendChild(toast);
            queueDismissal(toast);
        },
    };
};

const initializeConfirmModal = () => {
    const modal = document.querySelector('[data-admin-confirm]');

    if (!modal) {
        return;
    }

    const titleElement = modal.querySelector('[data-confirm-modal-title]');
    const messageElement = modal.querySelector('[data-confirm-modal-message]');
    const confirmButton = modal.querySelector('[data-confirm-accept]');
    let pendingForm = null;

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        pendingForm = null;
    };

    const openModal = (trigger, form) => {
        pendingForm = form;
        titleElement.textContent = trigger.dataset.confirmTitle ?? 'Confirm action';
        messageElement.textContent = trigger.dataset.confirmMessage ?? 'Are you sure you want to continue?';
        confirmButton.textContent = trigger.dataset.confirmAccept ?? 'Confirm';
        confirmButton.classList.remove('bg-slate-950', 'hover:bg-slate-800');
        confirmButton.classList.add('bg-rose-600', 'hover:bg-rose-700');
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        confirmButton.focus();
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-confirm-message]');

        if (trigger) {
            const form = trigger.form ?? trigger.closest('form');

            if (!form) {
                return;
            }

            event.preventDefault();
            openModal(trigger, form);
            return;
        }

        if (event.target.closest('[data-confirm-cancel]') || event.target === modal) {
            closeModal();
        }
    });

    confirmButton.addEventListener('click', () => {
        if (!pendingForm) {
            closeModal();
            return;
        }

        const form = pendingForm;
        closeModal();
        form.submit();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
            closeModal();
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initializeSidebar();
    initializeToasts();
    initializeConfirmModal();
});

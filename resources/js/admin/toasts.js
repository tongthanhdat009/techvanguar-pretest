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

    toast.className = `pointer-events-auto flex items-start gap-3 border px-4 py-3 transition duration-200 ${tone}`;
    toast.dataset.adminToast = 'true';
    toast.dataset.timeout = type === 'error' ? '6000' : '4000';
    content.className = 'min-w-0 flex-1 text-sm font-medium';
    content.textContent = message;
    dismissButton.type = 'button';
    dismissButton.className = 'p-1 transition hover:bg-black/5';
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

export const initializeAdminToasts = () => {
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

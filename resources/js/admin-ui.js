const DESKTOP_SIDEBAR_STORAGE_KEY = 'admin-sidebar-desktop';
const MOBILE_BREAKPOINT = '(max-width: 1023px)';
let activeAdminNavigationRequest = null;

const getStoredDesktopSidebarState = () => {
    try {
        return window.localStorage.getItem(DESKTOP_SIDEBAR_STORAGE_KEY) === 'collapsed'
            ? 'collapsed'
            : 'expanded';
    } catch (error) {
        return 'expanded';
    }
};

const persistDesktopSidebarState = (desktopState) => {
    try {
        window.localStorage.setItem(DESKTOP_SIDEBAR_STORAGE_KEY, desktopState);
    } catch (error) {
        // Ignore storage access issues.
    }
};

const setExpandedState = (buttons, isExpanded) => {
    buttons.forEach((button) => {
        button.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
    });
};

const applySidebarState = ({ desktopState, mobileState, desktopButtons, mobileButtons }) => {
    document.documentElement.dataset.adminSidebarDesktop = desktopState;
    document.documentElement.dataset.adminSidebarMobile = mobileState;

    if (window.matchMedia(MOBILE_BREAKPOINT).matches) {
        document.body.classList.toggle('overflow-hidden', mobileState === 'open');
    } else {
        document.body.classList.remove('overflow-hidden');
    }

    setExpandedState(desktopButtons, desktopState === 'expanded');
    setExpandedState(mobileButtons, mobileState === 'open');
};

const initializeSidebar = () => {
    if (!document.querySelector('[data-admin-sidebar]')) {
        return;
    }

    let desktopState = getStoredDesktopSidebarState();
    let mobileState = 'closed';
    const desktopButtons = Array.from(document.querySelectorAll('[data-sidebar-toggle-mode="desktop"]'));
    const mobileButtons = Array.from(document.querySelectorAll('[data-sidebar-toggle-mode="mobile"]'));
    const mobileMediaQuery = window.matchMedia(MOBILE_BREAKPOINT);

    const syncSidebar = () => {
        persistDesktopSidebarState(desktopState);
        applySidebarState({ desktopState, mobileState, desktopButtons, mobileButtons });
    };

    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            if (button.dataset.sidebarToggleMode === 'desktop') {
                desktopState = desktopState === 'expanded' ? 'collapsed' : 'expanded';
            } else {
                mobileState = mobileState === 'open' ? 'closed' : 'open';
            }

            syncSidebar();
        });
    });

    document.querySelectorAll('[data-sidebar-close]').forEach((button) => {
        button.addEventListener('click', () => {
            mobileState = 'closed';
            syncSidebar();
        });
    });

    document.addEventListener('admin:navigate', () => {
        if (mobileState === 'open') {
            mobileState = 'closed';
            syncSidebar();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && mobileState === 'open' && mobileMediaQuery.matches) {
            mobileState = 'closed';
            syncSidebar();
        }
    });

    const handleViewportChange = (event) => {
        if (!event.matches && mobileState === 'open') {
            mobileState = 'closed';
        }

        applySidebarState({ desktopState, mobileState, desktopButtons, mobileButtons });
    };

    if (typeof mobileMediaQuery.addEventListener === 'function') {
        mobileMediaQuery.addEventListener('change', handleViewportChange);
    } else {
        mobileMediaQuery.addListener(handleViewportChange);
    }

    window.addEventListener('resize', () => {
        applySidebarState({ desktopState, mobileState, desktopButtons, mobileButtons });
    });

    syncSidebar();
};

const getAdminBasePath = () => {
    const firstAdminLink = document.querySelector('[data-admin-sidebar-nav] a[href]');

    if (!firstAdminLink) {
        return null;
    }

    return new URL(firstAdminLink.href, window.location.origin).pathname.replace(/\/+$/, '');
};

const isAdminPageUrl = (url) => {
    const basePath = getAdminBasePath();

    if (!basePath || url.origin !== window.location.origin) {
        return false;
    }

    return url.pathname === basePath || url.pathname.startsWith(`${basePath}/`);
};

const setAdminNavigationLoading = (isLoading) => {
    const content = document.querySelector('[data-admin-page-content]');

    if (!content) {
        return;
    }

    content.classList.toggle('pointer-events-none', isLoading);
    content.classList.toggle('opacity-60', isLoading);
};

const replaceAdminPage = (nextDocument) => {
    const nextNav = nextDocument.querySelector('[data-admin-sidebar-nav]');
    const nextBreadcrumb = nextDocument.querySelector('[data-admin-breadcrumb]');
    const nextContent = nextDocument.querySelector('[data-admin-page-content]');
    const currentNav = document.querySelector('[data-admin-sidebar-nav]');
    const currentBreadcrumb = document.querySelector('[data-admin-breadcrumb]');
    const currentContent = document.querySelector('[data-admin-page-content]');

    if (!nextNav || !nextBreadcrumb || !nextContent || !currentNav || !currentBreadcrumb || !currentContent) {
        return false;
    }

    currentNav.innerHTML = nextNav.innerHTML;
    currentBreadcrumb.innerHTML = nextBreadcrumb.innerHTML;
    currentContent.innerHTML = nextContent.innerHTML;
    document.title = nextDocument.title;

    const adminMain = document.getElementById('admin-main');

    if (adminMain) {
        adminMain.scrollTo({ top: 0, behavior: 'auto' });
    }

    document.dispatchEvent(new CustomEvent('admin:navigate'));

    return true;
};

const navigateAdminPage = async (url, { pushState = true } = {}) => {
    if (activeAdminNavigationRequest) {
        activeAdminNavigationRequest.abort();
    }

    const controller = new AbortController();
    activeAdminNavigationRequest = controller;
    setAdminNavigationLoading(true);

    try {
        const response = await window.fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-Admin-Navigation': 'true',
            },
            signal: controller.signal,
        });

        if (response.redirected) {
            window.location.assign(response.url);
            return;
        }

        if (!response.ok) {
            window.location.assign(url);
            return;
        }

        const responseText = await response.text();
        const nextDocument = new DOMParser().parseFromString(responseText, 'text/html');

        if (!replaceAdminPage(nextDocument)) {
            window.location.assign(url);
            return;
        }

        if (pushState) {
            window.history.pushState({ adminNavigate: true }, '', url);
        }
    } catch (error) {
        if (error.name !== 'AbortError') {
            window.location.assign(url);
        }
    } finally {
        if (activeAdminNavigationRequest === controller) {
            activeAdminNavigationRequest = null;
        }

        setAdminNavigationLoading(false);
    }
};

const initializeAdminNavigation = () => {
    if (!document.querySelector('[data-admin-sidebar-nav]')) {
        return;
    }

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[data-admin-navigate]');

        if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        if (link.target && link.target !== '_self') {
            return;
        }

        const url = new URL(link.href, window.location.origin);

        if (!isAdminPageUrl(url)) {
            return;
        }

        if (url.href === window.location.href) {
            event.preventDefault();
            return;
        }

        event.preventDefault();
        navigateAdminPage(url.href);
    });

    window.addEventListener('popstate', () => {
        const url = new URL(window.location.href);

        if (!isAdminPageUrl(url)) {
            window.location.reload();
            return;
        }

        navigateAdminPage(url.href, { pushState: false });
    });
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
    initializeAdminNavigation();
    initializeToasts();
    initializeConfirmModal();
});

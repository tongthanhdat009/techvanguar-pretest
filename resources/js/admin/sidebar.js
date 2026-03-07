const DESKTOP_SIDEBAR_STORAGE_KEY = 'admin-sidebar-desktop';
const MOBILE_BREAKPOINT = '(max-width: 1023px)';

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

export const initializeAdminSidebar = () => {
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

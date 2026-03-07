const DESKTOP_SIDEBAR_STORAGE_KEY = 'admin-sidebar-desktop';

try {
    document.documentElement.dataset.adminSidebarDesktop = window.localStorage.getItem(DESKTOP_SIDEBAR_STORAGE_KEY) === 'collapsed'
        ? 'collapsed'
        : 'expanded';
} catch (error) {
    document.documentElement.dataset.adminSidebarDesktop = 'expanded';
}

document.documentElement.dataset.adminSidebarMobile = 'closed';

let activeAdminNavigationRequest = null;

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

export const initializeAdminNavigation = () => {
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

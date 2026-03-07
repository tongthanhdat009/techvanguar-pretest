const setDisclosureState = (container, isOpen) => {
    const toggle = container.querySelector('[data-disclosure-toggle]');
    const panel = container.querySelector('[data-disclosure-panel]');
    const openIcon = container.querySelector('[data-disclosure-icon-open]');
    const closeIcon = container.querySelector('[data-disclosure-icon-close]');

    if (!toggle || !panel) {
        return;
    }

    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    panel.classList.toggle('hidden', !isOpen);
    panel.dataset.disclosureState = isOpen ? 'open' : 'closed';

    if (openIcon) {
        openIcon.classList.toggle('hidden', isOpen);
    }

    if (closeIcon) {
        closeIcon.classList.toggle('hidden', !isOpen);
    }

    container.dataset.disclosureOpen = isOpen ? 'true' : 'false';
};

export const initializeDisclosureMenus = () => {
    document.querySelectorAll('[data-disclosure]').forEach((container) => {
        const toggle = container.querySelector('[data-disclosure-toggle]');
        const panel = container.querySelector('[data-disclosure-panel]');

        if (!toggle || !panel || container.dataset.disclosureBound === 'true') {
            return;
        }

        const isInitiallyOpen = container.dataset.disclosureDefault === 'open';
        setDisclosureState(container, isInitiallyOpen);
        container.dataset.disclosureBound = 'true';

        toggle.addEventListener('click', () => {
            const isOpen = container.dataset.disclosureOpen === 'true';
            setDisclosureState(container, !isOpen);
        });

        document.addEventListener('click', (event) => {
            if (container.dataset.disclosureOpen !== 'true' || container.contains(event.target)) {
                return;
            }

            setDisclosureState(container, false);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && container.dataset.disclosureOpen === 'true') {
                setDisclosureState(container, false);
            }
        });
    });
};

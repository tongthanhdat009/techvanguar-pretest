export const initializeAdminConfirmModal = () => {
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

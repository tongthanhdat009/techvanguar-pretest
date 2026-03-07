import { initializeAdminSidebar } from './sidebar';
import { initializeAdminNavigation } from './navigation';
import { initializeAdminToasts } from './toasts';
import { initializeAdminConfirmModal } from './confirm-modal';

export const initializeAdminUi = () => {
    initializeAdminSidebar();
    initializeAdminNavigation();
    initializeAdminToasts();
    initializeAdminConfirmModal();
};

import './bootstrap';
import './admin/sidebar-state';
import { initializeAdminUi } from './admin';
import { initializeDisclosureMenus } from './shared/disclosure';
import { initializeMetricProgress } from './shared/metric-progress';

document.addEventListener('DOMContentLoaded', () => {
    initializeAdminUi();
    initializeDisclosureMenus();
    initializeMetricProgress();
});

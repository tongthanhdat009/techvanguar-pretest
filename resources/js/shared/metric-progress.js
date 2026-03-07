export const initializeMetricProgress = () => {
    document.querySelectorAll('[data-metric-progress]').forEach((element) => {
        const rawValue = Number(element.getAttribute('data-metric-progress') ?? 0);
        const progress = Number.isFinite(rawValue)
            ? Math.max(0, Math.min(100, rawValue))
            : 0;

        element.style.setProperty('--metric-progress', `${progress}%`);
    });
};

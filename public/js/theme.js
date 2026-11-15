(() => {
    const root = document.documentElement;
    const storageKey = 'icu-shortener-theme';

    try {
        if (localStorage.getItem(storageKey) === 'dark') {
            root.dataset.theme = 'dark';
        }
    } catch {
        // Theme switching still works when storage is unavailable.
    }

    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('theme-toggle');
        toggle.setAttribute('aria-pressed', String(root.dataset.theme === 'dark'));
        toggle.hidden = false;

        toggle.addEventListener('click', () => {
            const theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
            root.dataset.theme = theme;
            toggle.setAttribute('aria-pressed', String(theme === 'dark'));

            try {
                localStorage.setItem(storageKey, theme);
            } catch {
                // Keep the selected theme for the current page.
            }
        });
    });
})();

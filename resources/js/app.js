import '@phosphor-icons/web/regular';
import '@phosphor-icons/web/fill';
import '@phosphor-icons/web/duotone';

/**
 * Theme manager.
 * Persists the user's light/dark preference, falls back to the system
 * preference, and exposes window.__setTheme for the inline nav toggle.
 */
(function () {
    const KEY = 'invms-theme';

    function apply(theme) {
        document.documentElement.classList.toggle('dark', theme === 'dark');
    }

    // Initial paint: explicit choice wins, otherwise follow the OS.
    const stored = (() => {
        try {
            return localStorage.getItem(KEY);
        } catch (e) {
            return null;
        }
    })();

    if (stored) {
        apply(stored);
    } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        apply('dark');
    }

    window.__setTheme = function (theme) {
        apply(theme);
        try {
            localStorage.setItem(KEY, theme);
        } catch (e) {
            /* private mode, ignore */
        }
    };

    // Keep the toggle in sync when the OS preference changes and no explicit
    // choice has been stored yet.
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            let stored;
            try {
                stored = localStorage.getItem(KEY);
            } catch (err) {
                stored = null;
            }
            if (!stored) {
                apply(e.matches ? 'dark' : 'light');
            }
        });
    }
})();

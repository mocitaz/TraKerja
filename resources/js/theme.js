// Theme Persistence and Livewire SPA Lock
export function initTheme() {
    function getPreferredTheme() {
        try {
            const stored = localStorage.getItem('theme');
            if (stored === 'dark' || stored === 'light') {
                return stored;
            }
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        } catch (e) {
            return 'light';
        }
    }

    function applyTheme(theme) {
        const isDark = theme === 'dark';
        if (isDark) {
            if (!document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.add('dark');
            }
            if (!document.cookie.includes('theme=dark')) {
                document.cookie = 'theme=dark; path=/; max-age=31536000; SameSite=Lax';
            }
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
            }
            if (!document.cookie.includes('theme=light')) {
                document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
            }
        }
    }

    function sync() {
        applyTheme(getPreferredTheme());
    }

    // Apply immediately
    sync();

    // Listen to Livewire v3 navigation events
    document.addEventListener('livewire:navigating', sync);
    document.addEventListener('livewire:navigated', sync);
    window.addEventListener('popstate', sync);

    // Listen to custom theme-changed event dispatched by toggles
    window.addEventListener('theme-changed', (event) => {
        const theme = event.detail?.isDark ? 'dark' : 'light';
        localStorage.setItem('theme', theme);
        applyTheme(theme);
    });

    // Guard against any framework stripping the class attribute
    if (window.MutationObserver) {
        const observer = new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                if (mutation.attributeName === 'class') {
                    const expectedTheme = getPreferredTheme();
                    const hasDark = document.documentElement.classList.contains('dark');
                    if (expectedTheme === 'dark' && !hasDark) {
                        document.documentElement.classList.add('dark');
                    } else if (expectedTheme === 'light' && hasDark) {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    }
}

// Auto-run when imported
initTheme();

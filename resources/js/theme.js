// Theme Persistence, Livewire SPA Lock & Ultra-Smooth Morphing
let isApplyingTheme = false;
let isThemeTransitioning = false;

export function getPreferredTheme() {
    try {
        const stored = localStorage.getItem('theme');
        if (stored === 'dark' || stored === 'light') {
            return stored;
        }
        // Default to clean light mode unless user explicitly selected dark
        return 'light';
    } catch (e) {
        return 'light';
    }
}

export function applyTheme(theme) {
    const isDark = theme === 'dark';
    isApplyingTheme = true;
    try {
        if (isDark) {
            if (!document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.add('dark');
            }
            document.cookie = 'theme=dark; path=/; max-age=31536000; SameSite=Lax';
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
            }
            document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
        }
    } finally {
        setTimeout(() => {
            isApplyingTheme = false;
        }, 50);
    }
}

export function toggleTheme() {
    if (isThemeTransitioning) {
        return document.documentElement.classList.contains('dark');
    }
    isThemeTransitioning = true;

    const isDark = !document.documentElement.classList.contains('dark');
    const theme = isDark ? 'dark' : 'light';

    const commitTheme = () => {
        applyTheme(theme);
        try {
            localStorage.setItem('theme', theme);
        } catch (e) {}
        document.cookie = 'theme=' + theme + '; path=/; max-age=31536000; SameSite=Lax';
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark } }));
    };

    const hasViewTransition = typeof document.startViewTransition === 'function' &&
        !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (hasViewTransition) {
        try {
            const transition = document.startViewTransition(() => {
                commitTheme();
            });
            transition.finished.finally(() => {
                isThemeTransitioning = false;
            });
        } catch (e) {
            fallbackTransition();
        }
    } else {
        fallbackTransition();
    }

    function fallbackTransition() {
        document.documentElement.classList.add('theme-transitioning');
        commitTheme();
        setTimeout(() => {
            document.documentElement.classList.remove('theme-transitioning');
            isThemeTransitioning = false;
        }, 400);
    }

    return isDark;
}

export function setTheme(theme) {
    const isDark = theme === 'dark';
    const currentlyDark = document.documentElement.classList.contains('dark');
    if (isDark === currentlyDark) {
        return;
    }
    toggleTheme();
}

// Make functions globally accessible
if (typeof window !== 'undefined') {
    window.setTheme = setTheme;
    window.toggleTheme = toggleTheme;
    window.getPreferredTheme = getPreferredTheme;
}

export function initTheme() {
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
        if (!isThemeTransitioning && event.detail && typeof event.detail.isDark === 'boolean') {
            const theme = event.detail.isDark ? 'dark' : 'light';
            try {
                localStorage.setItem('theme', theme);
            } catch (e) {}
            document.documentElement.classList.add('theme-transitioning');
            applyTheme(theme);
            setTimeout(() => {
                document.documentElement.classList.remove('theme-transitioning');
            }, 400);
        }
    });

    // Guard against any framework stripping the class attribute
    if (window.MutationObserver) {
        const observer = new MutationObserver((mutations) => {
            if (isApplyingTheme) return;
            for (const mutation of mutations) {
                if (mutation.attributeName === 'class') {
                    const expectedTheme = getPreferredTheme();
                    const hasDark = document.documentElement.classList.contains('dark');
                    if (expectedTheme === 'dark' && !hasDark) {
                        applyTheme('dark');
                    } else if (expectedTheme === 'light' && hasDark) {
                        applyTheme('light');
                    }
                }
            }
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    }
}

// Auto-run when imported
initTheme();

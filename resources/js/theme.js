// Theme Persistence and Livewire SPA Lock
let isApplyingTheme = false;

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

export function setTheme(theme) {
    const isDark = theme === 'dark';
    try {
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    } catch (e) {}
    applyTheme(isDark ? 'dark' : 'light');
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark } }));
}

// Make setTheme globally accessible
if (typeof window !== 'undefined') {
    window.setTheme = setTheme;
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
        if (event.detail && typeof event.detail.isDark === 'boolean') {
            const theme = event.detail.isDark ? 'dark' : 'light';
            try {
                localStorage.setItem('theme', theme);
            } catch (e) {}
            applyTheme(theme);
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

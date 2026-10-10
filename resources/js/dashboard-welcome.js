export function dashboardWelcome(environment = globalThis) {
    return {
        open: false, paused: false, reduced: false, media: null, mediaListener: null,
        init() {
            this.media = environment.matchMedia?.('(prefers-reduced-motion: reduce)');
            this.reduced = this.media?.matches ?? false;
            this.mediaListener = event => { this.reduced = event.matches; this.reset(); };
            this.media?.addEventListener('change', this.mediaListener);
        },
        move(event) {
            if (this.paused || this.reduced || event.pointerType !== 'mouse') return;
            const bounds = this.$el.getBoundingClientRect();
            if (!bounds.width || !bounds.height) return;
            const clamp = value => Math.max(-1, Math.min(1, value));
            this.$el.style.setProperty('--welcome-x', String(clamp((event.clientX - bounds.left) / bounds.width * 2 - 1)));
            this.$el.style.setProperty('--welcome-y', String(clamp((event.clientY - bounds.top) / bounds.height * 2 - 1)));
        },
        reset() {
            this.$el.style.setProperty('--welcome-x', '0');
            this.$el.style.setProperty('--welcome-y', '0');
        },
        toggleMotion() { this.paused = !this.paused; this.reset(); },
        close() { this.open = false; this.$refs.companion.focus({preventScroll: true}); },
        destroy() { this.media?.removeEventListener('change', this.mediaListener); },
    };
}

if (typeof window !== 'undefined') window.dashboardWelcome = dashboardWelcome;

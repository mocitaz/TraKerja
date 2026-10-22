window.adminSettingsWorkspace = function (initial = 'general') {
    return {
        section: 'general',
        init() {
            const sections = ['general', 'integrations', 'maintenance'];
            const hash = window.location.hash.slice(1);
            this.section = sections.includes(hash) ? hash : initial;
            this._scrollListener = () => {
                const visible = sections.filter(id => document.getElementById(id)?.getBoundingClientRect().top <= 160);
                if (Math.ceil(window.scrollY + window.innerHeight) >= document.documentElement.scrollHeight - 2) this.section = 'maintenance';
                else this.section = visible.at(-1) || 'general';
            };
            window.addEventListener('scroll', this._scrollListener, { passive: true });
            if (hash || initial !== 'general') this.$nextTick(() => this.navigate(this.section, false));
            this._hashListener = () => { const target = window.location.hash.slice(1); if (sections.includes(target)) this.section = target; };
            window.addEventListener('hashchange', this._hashListener);
        },
        navigate(section, smooth = true) {
            if (!['general', 'integrations', 'maintenance'].includes(section)) return;
            this.section = section;
            const url = new URL(window.location.href); url.searchParams.delete('tab'); url.hash = section;
            window.history.replaceState(null, '', url);
            const panel = document.getElementById(section);
            panel?.querySelector('h2')?.focus({ preventScroll: true });
            panel?.scrollIntoView({ block: 'start', behavior: smooth && !window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'smooth' : 'instant' });
        },
        destroy() {
            window.removeEventListener('scroll', this._scrollListener);
            window.removeEventListener('hashchange', this._hashListener);
        },
    };
};

export function jobDetailTabs(environment = globalThis) {
    return {
        tab: 'overview', requestedTab: 'overview', switching: false, focusContent: false,
        animations: [], disposed: false,
        async selectTab(next, focusContent = false) {
            if (!['overview', 'notes', 'record'].includes(next)) return;
            this.requestedTab = next;
            this.focusContent = focusContent;
            if (this.switching) return;
            this.switching = true;
            const viewport = this.$refs.panels;
            try {
                while (this.tab !== this.requestedTab && !this.disposed) {
                    const previous = this.$refs[`panel_${this.tab}`];
                    const before = previous.getBoundingClientRect().height;
                    const reduced = environment.matchMedia?.('(prefers-reduced-motion: reduce)').matches;
                    viewport.style.height = `${before}px`;
                    viewport.style.overflow = 'hidden';
                    if (!reduced && previous.animate) {
                        const exit = previous.animate([{ opacity: 1 }, { opacity: 0 }], { duration: 80, fill: 'forwards' });
                        this.animations.push(exit);
                        await exit.finished.catch(() => {});
                    }
                    if (this.disposed) break;
                    this.tab = this.requestedTab;
                    await this.$nextTick();
                    const current = this.$refs[`panel_${this.tab}`];
                    const after = current.getBoundingClientRect().height;
                    if (!reduced && current.animate && viewport.animate) {
                        const resize = viewport.animate([{ height: `${before}px` }, { height: `${after}px` }], { duration: 220, easing: 'cubic-bezier(.2,.7,.2,1)', fill: 'forwards' });
                        const enter = current.animate([{ opacity: 0, transform: 'translateY(3px)' }, { opacity: 1, transform: 'translateY(0)' }], { duration: 180, easing: 'ease-out' });
                        this.animations.push(resize, enter);
                        await Promise.all([resize.finished, enter.finished].map(p => p.catch(() => {})));
                    }
                    this.animations.splice(0).forEach(animation => animation.cancel());
                    viewport.style.height = '';
                    viewport.style.overflow = '';
                }
                if (!this.disposed && this.focusContent) this.$refs[`panel_${this.tab}`].focus({ preventScroll: true });
            } finally {
                this.animations.splice(0).forEach(animation => animation.cancel());
                viewport.style.height = '';
                viewport.style.overflow = '';
                this.switching = false;
            }
        },
        keyTab(event) {
            const keys = ['overview', 'notes', 'record'];
            const index = keys.indexOf(this.requestedTab);
            const next = event.key === 'Home' ? 0 : event.key === 'End' ? 2 : event.key === 'ArrowRight' ? (index + 1) % 3 : event.key === 'ArrowLeft' ? (index + 2) % 3 : null;
            if (next === null) return;
            event.preventDefault();
            this.$refs[`tab_${keys[next]}`].focus();
            this.selectTab(keys[next]);
        },
        destroy() { this.disposed = true; this.animations.splice(0).forEach(animation => animation.cancel()); },
    };
}

if (typeof window !== 'undefined') window.jobDetailTabs = jobDetailTabs;

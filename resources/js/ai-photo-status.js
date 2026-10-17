import { t } from './i18n.js';
window.photoRequestStatus = (url, initialStatus) => ({
    status: initialStatus,
    labels: {queued: 'Queued', processing: 'Processing', completed: 'Completed', failed: 'Failed'},
    error: '', timer: null, controller: null, stopped: false,
    init() {
        if (['queued', 'processing'].includes(this.status)) this.poll();
    },
    async poll() {
        if (this.stopped || this.controller) return;
        clearTimeout(this.timer);
        this.controller = new AbortController();
        const timeout = setTimeout(() => this.controller?.abort(), 10000);
        try {
            const response = await fetch(url, {headers: {Accept: 'application/json'}, credentials: 'same-origin', cache: 'no-store', signal: this.controller.signal});
            if (!response.ok) throw new Error(t("We couldn’t check the photo status. Please try again shortly."));
            const data = await response.json();
            if (this.stopped) return;
            this.error = '';
            const changed = data.status !== this.status;
            this.status = data.status;
            if (changed && ['completed', 'failed'].includes(data.status)) {
                this.stopped = true;
                window.location.reload();
            }
        } catch (error) {
            if (!this.stopped) this.error = t("We couldn’t check the photo status. Please try again shortly.");
        } finally {
            clearTimeout(timeout);
            this.controller = null;
            if (!this.stopped && ['queued', 'processing'].includes(this.status)) {
                this.timer = setTimeout(() => this.poll(), this.error ? 10000 : 4000);
            }
        }
    },
    destroy() {
        this.stopped = true;
        clearTimeout(this.timer);
        this.controller?.abort();
    },
});

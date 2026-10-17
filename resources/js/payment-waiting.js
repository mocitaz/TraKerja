import { t } from './i18n.js';
window.paymentWaitingStatus = ({statusUrl, successUrl, failedUrl, initialStatus}) => ({
    status: initialStatus,
    labels: {PENDING: 'Awaiting payment', WAITING: 'Awaiting payment', SUCCESS: 'Paid', FAILED: 'Failed', CANCELED: 'Canceled', EXPIRED: 'Expired'},
    error: '', copyMessage: '', busy: false, stopped: false, paused: false, failures: 0,
    timer: null, timeout: null, controller: null, cleanup: null, hide: null, restore: null,
    init() {
        this.cleanup = () => this.destroy();
        document.addEventListener('livewire:navigating', this.cleanup);
        this.hide = (event) => event.persisted ? this.pause() : this.destroy();
        this.restore = (event) => {
            if (!event.persisted || !this.paused || this.stopped) return;
            this.paused = false;
            return this.poll();
        };
        window.addEventListener('pagehide', this.hide);
        window.addEventListener('pageshow', this.restore);
        this.timer = setTimeout(() => this.poll(), 2000);
    },
    async poll() {
        if (this.stopped || this.paused || this.busy) return;
        clearTimeout(this.timer);
        this.timer = null;
        this.busy = true;
        const controller = new AbortController();
        this.controller = controller;
        let timedOut = false;
        let retry = true;
        this.timeout = setTimeout(() => { timedOut = true; controller.abort(); }, 12000);
        try {
            const response = await fetch(statusUrl, {
                headers: {Accept: 'application/json'}, credentials: 'same-origin', cache: 'no-store', signal: controller.signal,
            });
            if (this.stopped || this.controller !== controller) return;
            if (response.redirected || [401, 419].includes(response.status)) {
                retry = false;
                throw new Error(t("Your session has ended. Sign in again to check this payment."));
            }
            if ([403, 404].includes(response.status)) {
                retry = false;
                throw new Error(t("This payment is unavailable. Open your payment history to continue."));
            }
            if (!response.ok) throw new Error(t("We couldn’t verify the payment status. Please try again shortly."));
            const data = await response.json();
            if (this.stopped || this.controller !== controller) return;
            if (!data || data.success !== true || !Object.hasOwn(this.labels, data.status)) {
                throw new Error(t("We couldn’t verify the payment status. Please try again shortly."));
            }
            this.status = data.status;
            this.error = '';
            this.failures = 0;
            if (['SUCCESS', 'FAILED', 'CANCELED', 'EXPIRED'].includes(this.status)) {
                this.destroy();
                window.location.assign(this.status === 'SUCCESS' ? successUrl : failedUrl);
            }
        } catch (error) {
            if (!this.stopped && this.controller === controller) {
                this.failures++;
                this.error = timedOut
                    ? 'The status check timed out. Please try again.'
                    : (error.message.startsWith('Your session') || error.message.startsWith('This payment')
                        ? error.message : t("We couldn’t verify the payment status. Please try again shortly."));
            }
        } finally {
            if (this.controller !== controller) return;
            clearTimeout(this.timeout);
            this.timeout = null;
            this.controller = null;
            this.busy = false;
            if (!this.stopped && !this.paused && retry) {
                this.timer = setTimeout(() => this.poll(), this.failures ? Math.min(10000 * 2 ** Math.min(this.failures - 1, 3), 60000) : 10000);
            }
        }
    },
    async copyVA(value) {
        try {
            await navigator.clipboard.writeText(value);
            this.copyMessage = t("Virtual account number copied.");
        } catch {
            this.copyMessage = t("Couldn’t copy the number. Please select and copy it manually.");
        }
    },
    pause() {
        this.paused = true;
        clearTimeout(this.timer);
        clearTimeout(this.timeout);
        this.timer = null;
        this.timeout = null;
        this.controller?.abort();
        this.controller = null;
        this.busy = false;
    },
    destroy() {
        this.stopped = true;
        this.pause();
        if (this.cleanup) {
            document.removeEventListener('livewire:navigating', this.cleanup);
            window.removeEventListener('pagehide', this.hide);
            window.removeEventListener('pageshow', this.restore);
            this.hide = null;
            this.restore = null;
            this.cleanup = null;
        }
    },
});

window.adminPaymentsWorkspace = function () {
    return {
        open: false, loading: false, busy: false, confirming: false, error: '', payment: null, user: null, requestId: 0,
        money(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(value)); },
        date(value) { return value ? new Intl.DateTimeFormat('en-GB', { dateStyle: 'medium', timeStyle: 'short', timeZone: 'Asia/Jakarta' }).format(new Date(value)) + ' WIB' : '—'; },
        async view(id) {
            if (this.busy) return;
            const requestId = ++this.requestId;
            this.open = true; this.loading = true; this.error = ''; this.payment = null; this.user = null; this.confirming = false;
            try {
                const response = await fetch(`/admin/payments/${id}`, { headers: { Accept: 'application/json' }, signal: AbortSignal.timeout(15000) });
                const data = await response.json();
                if (!response.ok || !data.success) throw new Error(data.message || 'Unable to load this payment. Close and try again.');
                if (requestId !== this.requestId) return;
                this.payment = data.payment; this.user = data.user;
            } catch (error) { if (requestId === this.requestId) this.error = error.message; }
            finally { if (requestId === this.requestId) this.loading = false; }
        },
        async cancel() {
            if (!this.confirming || this.busy || !this.payment) return;
            this.busy = true; this.error = '';
            try {
                const response = await fetch(`/admin/payments/${this.payment.id}/cancel`, { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, signal: AbortSignal.timeout(30000) });
                const data = await response.json();
                if (!response.ok || !data.success) throw new Error(data.message || 'Cancellation was not confirmed. Review the payment before trying again.');
                window.location.reload();
            } catch (error) { this.error = ['TimeoutError', 'AbortError'].includes(error.name) || error instanceof TypeError ? 'Cancellation could not be confirmed. Close and review the payment before trying again.' : error.message; }
            finally { this.busy = false; }
        },
    };
};

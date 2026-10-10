window.paymentCheckout = (method = '') => ({
    method, submitting: false, restore: null,
    init() {
        this.restore = () => { this.submitting = false; };
        window.addEventListener('pageshow', this.restore);
    },
    submit(event) {
        if (this.submitting) { event.preventDefault(); return; }
        if (!event.target.checkValidity()) { event.preventDefault(); event.target.reportValidity(); return; }
        this.submitting = true;
    },
    destroy() { window.removeEventListener('pageshow', this.restore); },
});

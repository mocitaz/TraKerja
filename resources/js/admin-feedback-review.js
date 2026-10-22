window.adminFeedbackReview = function (initialOpen = false) {
    return {
        open: initialOpen, deleting: false, busy: false,
        review() { this.deleting = false; this.open = true; },
        close() { if (!this.busy) { this.open = false; this.deleting = false; } },
    };
};

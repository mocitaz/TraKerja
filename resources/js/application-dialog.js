export function installApplicationDialog(win, doc) {
    if (win.__applicationDialogInstalled) return;
    win.__applicationDialogInstalled = true;
    let closing = null;
    let afterClosing = null;
    let pendingNotice = null;
    let navigationTimer = null;
    let savedScroll = null;
    const modal = () => doc.getElementById('jobModal');
    const notify = () => {
        win.clearTimeout(navigationTimer);
        if (!pendingNotice) return;
        win.showToastNotification?.(pendingNotice);
        pendingNotice = null;
    };
    win.openJobModal = (isEdit = false, defaultStage = null) => {
        const dialog = modal();
        if (!dialog || closing) return;
        doc.getElementById('modalTitle').textContent = isEdit ? 'Edit application' : 'Add application';
        dialog.classList.remove('hidden', 'is-closing');
        dialog.querySelector('.job-dialog__body').scrollTop = 0;
        if (!isEdit) win.Livewire?.dispatch('resetFormForNewJob', { defaultStage });
    };
    win.closeJobModal = () => close();
    function close(afterClose) {
        const dialog = modal();
        if (!dialog || dialog.classList.contains('hidden')) {
            afterClose?.();
            return;
        }
        if (afterClose) afterClosing = afterClose;
        if (closing) return;
        dialog.classList.add('is-closing');
        const duration = win.matchMedia?.('(prefers-reduced-motion: reduce)').matches ? 0 : 180;
        closing = win.setTimeout(() => {
            closing = null;
            dialog.classList.add('hidden');
            dialog.classList.remove('is-closing');
            win.TraKerjaDialogs?.close(dialog);
            const completed = afterClosing;
            afterClosing = null;
            if (completed) completed();
            else win.Livewire?.dispatch('resetFormForNewJob');
        }, duration);
    }
    win.addEventListener('edit-job', () => win.openJobModal(true));
    win.addEventListener('application-saved', event => {
        pendingNotice = Array.isArray(event.detail) ? event.detail[0] : event.detail;
        const refresh = modal()?.dataset.refreshOnSave === 'true';
        close(() => {
            if (refresh && win.Livewire?.navigate) {
                savedScroll = win.scrollY;
                navigationTimer = win.setTimeout(notify, 10000);
                try { win.Livewire.navigate(win.location.href); }
                catch { savedScroll = null; notify(); win.Livewire.dispatch('resetFormForNewJob'); }
            } else {
                win.Livewire?.dispatch('resetFormForNewJob');
                notify();
            }
        });
    });
    const openRequestedEdit = () => {
        if (!modal()) return;
        const id = new URL(win.location.href).searchParams.get('edit');
        if (id && /^\d+$/.test(id)) win.Livewire?.dispatch('edit-job', { jobId: Number(id) });
    };
    doc.addEventListener('livewire:navigated', () => {
        if (savedScroll !== null) {
            win.scrollTo(0, savedScroll);
            savedScroll = null;
        }
        notify();
        openRequestedEdit();
    });
    doc.addEventListener('livewire:navigating', () => {
        if (closing) { win.clearTimeout(closing); closing = null; afterClosing = null; }
    });
}

export function cvWorkspace(importUrl) {
    return {
        previewOpen: false, previewLoading: true, previewVersion: 0,
        importModalOpen: false, importing: false, importFilename: '', importError: '', importSuccess: false,
        async uploadPdf(file) {
            if (!file || this.importing) return;
            this.importError = '';
            this.importSuccess = false;
            if (!file.name.toLowerCase().endsWith('.pdf') || (file.type && file.type !== 'application/pdf')) {
                this.importError = 'Choose a PDF file.';
                return;
            }
            if (file.size > 10 * 1024 * 1024) {
                this.importError = 'Your PDF must be 10 MB or smaller.';
                return;
            }
            this.importing = true;
            this.importFilename = file.name;
            const formData = new FormData();
            formData.append('resume', file);
            try {
                const response = await fetch(importUrl, {
                    method: 'POST', credentials: 'same-origin',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, Accept: 'application/json' },
                    body: formData,
                });
                const result = await response.json().catch(() => ({}));
                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'The PDF could not be imported. Please try again.');
                }
                this.importSuccess = true;
            } catch (error) {
                this.importError = error.message || 'Connection interrupted. Please try again.';
            } finally {
                this.importing = false;
            }
        },
    };
}
function register() { window.Alpine.data('cvWorkspace', cvWorkspace); }
if (window.Alpine) register();
else document.addEventListener('alpine:init', register, { once: true });

export function cvReview(template, previewUrl, checkUrl) {
    return {
        template, previewUrl, spacing: 'standard', fontSize: '9.5pt', loading: true,
        checkOpen: false, checking: false, checkError: '', result: null,
        get margin() {
            if (this.template === 'professional') return '0';
            const sizes = this.template === 'creative' ? { compact: '12mm', standard: '20mm', spacious: '28mm' } : { compact: '10mm', standard: '15mm', spacious: '22mm' };
            return sizes[this.spacing];
        },
        async runCheck() {
            if (this.checking) return;
            this.checkOpen = true;
            this.checking = true;
            this.checkError = '';
            this.result = null;
            try {
                const response = await fetch(checkUrl, {
                    method: 'POST', credentials: 'same-origin',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, Accept: 'application/json' },
                });
                const result = await response.json().catch(() => ({}));
                if (!response.ok || !result.success) throw new Error(result.message || 'The check could not be completed. Please try again.');
                this.result = result;
            } catch (error) {
                this.checkError = error.message || 'Connection interrupted. Please try again.';
            } finally { this.checking = false; }
        },
    };
}
function registerReview() { window.Alpine.data('cvReview', cvReview); }
if (window.Alpine) registerReview();
else document.addEventListener('alpine:init', registerReview, { once: true });

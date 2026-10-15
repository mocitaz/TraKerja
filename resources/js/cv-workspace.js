export function cvWorkspace(importUrl, confirmUrl = importUrl + '/confirm') {
    return {
        previewOpen: false, previewLoading: true, previewVersion: 0,
        importModalOpen: false, importing: false, importFilename: '', importError: '', importSuccess: false,
        importPreview: null,
        educationStartDates: {},
        get missingEducationDates() {
            return (this.importPreview?.data?.educations || [])
                .map((entry, index) => ({ entry, index })).filter(({ entry }) => !entry.start_date);
        },
        get importSections() {
            const labels = { experiences: 'Work experience', educations: 'Education', projects: 'Projects', skills: 'Skills' };
            return Object.entries(labels).map(([key, label]) => ({
                key, label, entries: this.importPreview?.data?.[key] || [],
                existing: this.importPreview?.existing_counts?.[key] || 0,
            }));
        },
        importFieldLabel(key) {
            const labels = { company_name: 'Company', position: 'Position', location: 'Location',
                start_date: 'Start date', end_date: 'End date', is_current: 'Current', description: 'Description',
                project_name: 'Project', role: 'Role', is_ongoing: 'Ongoing', institution_name: 'Institution',
                degree: 'Degree', major: 'Major', skill_name: 'Skill', category: 'Category',
                proficiency_level: 'Proficiency', years_of_experience: 'Years of experience' };
            return labels[key] || key;
        },
        async uploadPdf(file) {
            if (!file || this.importing) return;
            this.importError = '';
            this.importSuccess = false;
            this.importPreview = null;
            this.educationStartDates = {};
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
                if (!response.ok || !result.success || result.stage !== 'preview' || !result.token || !result.data) {
                    throw new Error(result.message || 'The PDF could not be imported. Please try again.');
                }
                this.importPreview = result;
            } catch (error) {
                this.importError = error.message || 'Connection interrupted. Please try again.';
            } finally {
                this.importing = false;
            }
        },
        async confirmImport() {
            if (!this.importPreview || this.importing) return;
            if (this.missingEducationDates.some(({ index }) => !this.educationStartDates[index])) {
                this.importError = 'Enter the missing education start dates before confirming. Use the dates from your records.';
                return;
            }
            this.importing = true;
            this.importError = '';
            try {
                const payload = { token: this.importPreview.token, confirm_replace: true };
                if (this.missingEducationDates.length) {
                    payload.education_start_dates = Object.fromEntries(this.missingEducationDates
                        .map(({ index }) => [index, this.educationStartDates[index]]));
                }
                const response = await fetch(confirmUrl, {
                    method: 'POST', credentials: 'same-origin',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        Accept: 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const result = await response.json().catch(() => ({}));
                if (!response.ok || !result.success || result.stage !== 'saved') {
                    if (response.status === 409 || (response.status === 422 && !result.requires_completion)) this.importPreview = null;
                    throw new Error(result.message || 'The import could not be saved. Reload to check your CV before trying again.');
                }
                this.importSuccess = true;
                this.importPreview = null;
            } catch (error) {
                this.importError = error.message || 'Connection interrupted. Reload to check whether the import was saved before trying again.';
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
            const sizes = { compact: '10mm', standard: '15mm', spacious: '20mm' };
            return sizes[this.spacing] || '15mm';
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

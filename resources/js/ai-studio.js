export function aiStudioPortal(config) {
    return {
        task: config.task, endpoints: config.endpoints, access: config.access,
        applicationId: config.draft?.application_id || '', resumeId: config.draft?.resume_id || '',
        requestKey: config.requestKey, applications: config.applications || [],
        values: { company_name: config.draft?.company_name || config.draft?.target_company || '', job_title: config.draft?.job_title || '', job_description: config.draft?.job_description || '' },
        fileName: '', busy: false, error: '', notice: '', historyOpen: false,
        photoType: 'enhance', photoBackground: 'studio_plain', showRequirements: !!config.draft?.job_description,
        stopped: false, controller: null,
        get taskInfo() {
            return {
                analyzer: { title: 'See where your CV stands.', description: 'Compare your CV with a role. Get evidence, gaps, and clear next steps.', action: 'Review my CV' },
                'cover-letter': { title: 'An introduction that fits the role.', description: 'Turn your experience and the job requirements into a focused cover letter.', action: 'Write cover letter' },
                outreach: { title: 'Find the right words to reach out.', description: 'Write an introduction, follow-up, or thank-you for your next opportunity.', action: 'Write message' },
                photo: { title: 'A portrait ready for your next step.', description: 'Enhance your portrait or remove its background for your CV and profile.', action: 'Process photo' },
            }[this.task];
        },
        init() {
            this.onPageShow = event => { if (event.persisted) this.busy = false; };
            window.addEventListener('pageshow', this.onPageShow);
        },
        applyApplication() {
            const application = this.applications.find(item => String(item.id) === String(this.applicationId));
            if (!application) return;
            if (this.values.company_name !== application.company_name || this.values.job_title !== application.position) this.values.job_description = '';
            this.values.company_name = application.company_name;
            this.values.job_title = application.position;
            this.error = '';
        },
        choose(task) {
            if (this.busy || task === this.task || !Object.hasOwn(this.endpoints, task)) return;
            this.task = task;
            this.showRequirements = !!this.values.job_description;
            this.requestKey = globalThis.crypto?.randomUUID?.() || this.requestKey;
            this.removeFile();
            this.error = ''; this.notice = '';
            const url = new URL(window.location.href);
            if (url.pathname.endsWith('/ai-studio')) {
                url.searchParams.delete('tab'); url.searchParams.delete('run'); url.searchParams.delete('id');
                url.searchParams.set('task', task); window.history.replaceState(null, '', url);
            }
        },
        removeFile() { this.fileName = ''; if (this.$refs.file) this.$refs.file.value = ''; },
        fileChanged(event, type) {
            const file = event.target.files?.[0];
            if (!file) return;
            const validType = type === 'pdf' ? (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) : ['image/jpeg', 'image/png', 'image/webp'].includes(file.type);
            const max = type === 'pdf' ? 10 : 20;
            if (!validType || file.size > max * 1024 * 1024) {
                this.error = `Choose ${type === 'pdf' ? 'a PDF' : 'a JPG, PNG, or WEBP image'} up to ${max} MB.`;
                event.target.value = ''; this.fileName = '';
            } else { this.error = ''; this.fileName = file.name; this.resumeId = ''; }
        },
        async submit(event) {
            event.preventDefault();
            if (this.busy || !this.access[this.task]) return;
            this.error = ''; this.notice = '';
            if ((this.task === 'photo' || (this.task === 'analyzer' && !this.resumeId)) && !this.fileName) {
                this.error = this.task === 'photo' ? 'Choose a photo to continue.' : 'Upload a PDF or choose a saved CV to continue.';
                this.$refs.file?.focus(); return;
            }
            if (['analyzer', 'cover-letter'].includes(this.task) && this.values.job_description.trim().length < 50) {
                this.error = 'Add at least 50 characters of job requirements.'; this.$refs.description?.focus(); return;
            }
            if (['cover-letter', 'outreach'].includes(this.task) && (!this.values.company_name.trim() || !this.values.job_title.trim())) {
                this.error = 'Add the company and role, or select an application.'; return;
            }
            const form = event.target;
            const body = new FormData(form);
            this.busy = true; this.controller = new AbortController();
            const timeout = setTimeout(() => this.controller?.abort(), 30000);
            try {
                const response = await fetch(this.endpoints[this.task], { method: 'POST', body, credentials: 'same-origin', signal: this.controller.signal, headers: { Accept: 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value } });
                if (!(response.headers.get('content-type') || '').includes('application/json')) throw new Error('Your session could not be verified. Refresh and sign in again if needed.');
                const data = await response.json();
                if (this.stopped) return;
                if (!response.ok || !data.success) throw new Error(data.errors ? Object.values(data.errors).flat().join(' ') : data.message || 'The request could not be saved. Your inputs are still here.');
                const url = new URL(data.url, window.location.origin);
                if (url.origin !== window.location.origin) throw new Error('The result link could not be verified. Check your history before trying again.');
                window.location.assign(url.href);
            } catch (error) {
                if (!this.stopped) this.error = error.name === 'AbortError' ? 'Saving timed out. Check History before retrying; your request may already be saved.' : error.message || 'Connection failed. Your inputs are still here.';
            } finally { clearTimeout(timeout); this.controller = null; if (!this.stopped) this.busy = false; }
        },
        destroy() { this.stopped = true; this.controller?.abort(); window.removeEventListener('pageshow', this.onPageShow); },
    };
}
function register() { window.Alpine.data('aiStudioPortal', aiStudioPortal); }
if (window.Alpine) register();
else document.addEventListener('alpine:init', register, { once: true });

export function studioRunStatus(endpoint, initialStatus, initialCredit = 'none') {
    return {
        status: initialStatus, credit: initialCredit, message: '', error: '', stopped: false, timer: null, controller: null,
        labels: { queued: 'Queued', processing: 'Processing', completed: 'Completed', failed: 'Failed' },
        init() { if (['queued', 'processing'].includes(this.status)) this.poll(); },
        async poll() {
            clearTimeout(this.timer);
            if (this.stopped || this.controller) return;
            this.error = ''; this.controller = new AbortController();
            const timeout = setTimeout(() => this.controller?.abort(), 12000);
            try {
                const response = await fetch(endpoint, { headers: { Accept: 'application/json' }, credentials: 'same-origin', signal: this.controller.signal });
                if (!response.ok || !(response.headers.get('content-type') || '').includes('application/json')) throw new Error('Status is unavailable. Refresh or sign in again to check this request.');
                const data = await response.json();
                if (this.stopped) return;
                if (!Object.hasOwn(this.labels, data.status)) throw new Error('Request status could not be verified.');
                this.status = data.status; this.credit = data.credit_state; this.message = data.message;
                if (data.status === 'completed') {
                    const url = new URL(data.url, window.location.origin);
                    if (url.origin !== window.location.origin) throw new Error('The result link could not be verified.');
                    window.location.assign(url.href);
                } else if (['queued', 'processing'].includes(data.status)) this.timer = setTimeout(() => this.poll(), 4000);
            } catch (error) { if (!this.stopped) this.error = error.name === 'AbortError' ? 'Status check timed out. Processing may still continue; check again below.' : error.message; }
            finally { clearTimeout(timeout); this.controller = null; }
        },
        destroy() { this.stopped = true; clearTimeout(this.timer); this.controller?.abort(); },
    };
}
function registerRunStatus() { window.Alpine.data('studioRunStatus', studioRunStatus); }
if (window.Alpine) registerRunStatus();
else document.addEventListener('alpine:init', registerRunStatus, { once: true });

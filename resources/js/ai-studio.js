export function aiStudioPortal(config) {
    return {
        task: config.task, endpoints: config.endpoints, access: config.access,
        answer: '', values: {}, turns: [], fileName: '', completedDetails: false, preparing: false,
        busy: false, error: '', notice: '', draftText: '', historyOpen: false, photoType: 'enhance', stopped: false, controller: null,
        get steps() {
            return {
                'cover-letter': [
                    { key: 'company_name', question: 'Which company are you applying to?', hint: 'Type the company name…', max: 255 },
                    { key: 'job_title', question: 'What role are you applying for?', hint: 'Type the position…', max: 255 },
                    { key: 'job_description', question: 'Paste the job description. I’ll use this with your saved CV profile.', hint: 'Paste the job description…', min: 50, max: 2500 },
                ],
                outreach: [
                    { key: 'target_company', question: 'Which company would you like to reach out to?', hint: 'Type the company name…', max: 100 },
                    { key: 'job_title', question: 'What role would you like to discuss?', hint: 'Type the role…', max: 100 },
                ],
                analyzer: [{ key: 'job_description', question: 'Attach your resume PDF and paste the job description you want to compare it with.', hint: 'Paste the job description…', min: 50, max: 2500 }],
                photo: [],
            }[this.task];
        },
        get currentStep() { return this.steps[this.turns.length]; },
        get question() { return this.completedDetails ? 'Your details are ready. You can send again if the previous request did not complete.' : this.currentStep?.question || 'Attach your photo. Choose enhance or remove background in the settings.'; },
        get placeholder() { return this.currentStep?.hint || 'Your details are ready to send…'; },
        editTurn(index) {
            if (this.busy) return;
            const removed = this.turns.splice(index);
            this.answer = removed[0]?.answer || '';
            for (const turn of removed) delete this.values[turn.key];
            this.completedDetails = false;
            this.error = '';
            this.$nextTick(() => this.$refs.answer?.focus());
        },
        async sendAnswer(event) {
            event.preventDefault();
            if (this.busy || this.preparing || !this.access[this.task]) return;
            this.error = '';
            if (['analyzer', 'photo'].includes(this.task) && !this.fileName) {
                this.error = this.task === 'analyzer' ? 'Attach your resume PDF first.' : 'Attach a photo first.';
                return;
            }
            if (this.currentStep) {
                const step = this.currentStep, answer = this.answer.trim();
                if (!answer || answer.length < (step.min || 1) || answer.length > step.max) {
                    this.error = `Please enter ${step.min || 1}–${step.max} characters for this detail.`;
                    return;
                }
                this.values[step.key] = answer;
                this.turns.push({ ...step, answer });
                this.answer = '';
                if (this.currentStep) {
                    this.$nextTick(() => { this.$refs.answer?.focus(); this.$refs.composer.scrollIntoView({ block: 'nearest' }); });
                    return;
                }
            }
            this.completedDetails = true;
            this.preparing = true;
            await this.$nextTick();
            this.preparing = false;
            if (['analyzer', 'photo'].includes(this.task)) {
                this.busy = true;
                HTMLFormElement.prototype.submit.call(this.$refs.form);
                return;
            }
            return this.submit(event);
        },
        init() {
            for (const step of this.steps) {
                const answer = config.draft?.[step.key];
                if (!answer) break;
                this.values[step.key] = answer; this.turns.push({ ...step, answer });
            }
            this.completedDetails = this.steps.length > 0 && this.turns.length === this.steps.length;
            this.onPageShow = event => { if (event.persisted) this.busy = false; };
            window.addEventListener('pageshow', this.onPageShow);
        },
        choose(task) {
            if (this.busy || !Object.hasOwn(this.endpoints, task)) return;
            this.task = task;
            this.values = {}; this.turns = []; this.answer = ''; this.fileName = ''; this.completedDetails = false;
            if (this.$refs.file) this.$refs.file.value = '';
            this.error = ''; this.notice = ''; this.draftText = '';
            const url = new URL(window.location.href);
            if (!url.searchParams.has('id') && url.pathname.endsWith('/ai-studio')) {
                url.searchParams.delete('tab'); url.searchParams.set('task', task);
                window.history.replaceState(null, '', url);
            }
            this.$nextTick(() => this.$refs.composer.scrollIntoView({ block: 'nearest', behavior: 'auto' }));
        },
        fileChanged(event, type) {
            const file = event.target.files?.[0];
            if (!file) return;
            const validType = type === 'pdf' ? (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) : file.type.startsWith('image/');
            const max = type === 'pdf' ? 10 : 20;
            if (!validType || file.size > max * 1024 * 1024) {
                this.error = `Choose ${type === 'pdf' ? 'a PDF' : 'an image'} up to ${max} MB.`;
                event.target.value = ''; this.fileName = '';
            } else { this.error = ''; this.fileName = file.name; }
        },
        async submit(event) {
            if (this.busy || !this.access[this.task]) { event.preventDefault(); return; }
            this.error = ''; this.notice = ''; this.draftText = '';
            if (['analyzer', 'photo'].includes(this.task)) { this.busy = true; return; }
            event.preventDefault();
            const form = event.target;
            const body = new FormData(form);
            const task = this.task;
            this.busy = true;
            this.controller = new AbortController();
            const timeout = setTimeout(() => this.controller?.abort(), 90000);
            try {
                const response = await fetch(this.endpoints[task], { method: 'POST', body, credentials: 'same-origin', signal: this.controller.signal, headers: { Accept: 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value } });
                if (!(response.headers.get('content-type') || '').includes('application/json')) throw new Error('Your session or request could not be verified. Refresh and sign in again if needed.');
                const data = await response.json();
                if (this.stopped) return;
                if (!response.ok || !data.success) throw new Error(data.errors ? Object.values(data.errors).flat().join(' ') : data.message || 'The request couldn’t be completed. Your inputs are still here.');
                if (data.is_fallback) { this.draftText = data.cover_letter || ''; return; }
                const url = task === 'cover-letter' ? new URL(`/cover-letters/${data.id}`, window.location.origin) : new URL(data.url, window.location.origin);
                if (url.origin !== window.location.origin) throw new Error('The saved result could not be opened. Check your history before trying again.');
                window.location.assign(url.href);
            } catch (error) {
                if (!this.stopped) this.error = error.name === 'AbortError' ? 'The request timed out. Check your history before sending again; it may still be processing.' : error.message || 'Connection failed. Your inputs are still here. Check history before retrying.';
            } finally { clearTimeout(timeout); this.controller = null; if (!this.stopped) this.busy = false; }
        },
        async copyDraft() {
            try { await navigator.clipboard.writeText(this.draftText); this.notice = 'Template copied.'; }
            catch { this.notice = 'Copy is unavailable. Select the template text and copy it manually.'; }
        },
        destroy() { this.stopped = true; this.controller?.abort(); window.removeEventListener('pageshow', this.onPageShow); },
    };
}
function register() { window.Alpine.data('aiStudioPortal', aiStudioPortal); }
if (window.Alpine) register();
else document.addEventListener('alpine:init', register, { once: true });

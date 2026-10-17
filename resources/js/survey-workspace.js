import { t } from './i18n.js';
export function surveyWorkspace(sections, initialSection = 0) {
    return {
        step: Math.min(Math.max(Number(initialSection) || 0, 0), sections.length - 1),
        answered: 0, feedbackLength: 0, submitting: false, error: '', restore: null,
        init() {
            this.sync();
            this.feedbackLength = this.$refs.feedback?.value.length || 0;
            this.restore = () => { this.submitting = false; };
            window.addEventListener('pageshow', this.restore);
        },
        sync() {
            const data = new FormData(this.$refs.form);
            this.answered = sections.flat().filter(name => /^[1-5]$/.test(String(data.get(name) ?? ''))).length;
        },
        go(index) {
            if (this.submitting) return;
            this.step = Math.min(Math.max(index, 0), sections.length - 1);
            this.error = '';
            this.$nextTick(() => this.$refs.form.querySelector(`[data-survey-heading="${this.step}"]`)?.focus({preventScroll: true}));
        },
        firstMissing(index) {
            const data = new FormData(this.$refs.form);
            return sections[index].find(name => !/^[1-5]$/.test(String(data.get(name) ?? '')));
        },
        async showMissing(index, name) {
            this.step = index;
            this.error = t("Choose a rating for each question in this section.");
            await this.$nextTick();
            const input = this.$refs.form.querySelector(`input[name="${name}"]`);
            input?.focus();
            input?.reportValidity();
        },
        async next() {
            if (this.submitting) return;
            const missing = this.firstMissing(this.step);
            if (missing) { await this.showMissing(this.step, missing); return; }
            this.go(this.step + 1);
        },
        async submit(event) {
            event.preventDefault();
            if (this.submitting) return;
            this.submitting = true;
            for (let index = 0; index < sections.length; index++) {
                const missing = this.firstMissing(index);
                if (missing) {
                    this.submitting = false;
                    await this.showMissing(index, missing);
                    return;
                }
            }
            this.step = sections.length - 1;
            await this.$nextTick();
            if (!this.$refs.form.checkValidity()) {
                this.submitting = false;
                this.$refs.form.reportValidity();
                return;
            }
            HTMLFormElement.prototype.submit.call(this.$refs.form);
        },
        reset() {
            this.$nextTick(() => { this.sync(); this.feedbackLength = this.$refs.feedback?.value.length || 0; });
        },
        destroy() { window.removeEventListener('pageshow', this.restore); },
    };
}
window.surveyWorkspace = surveyWorkspace;

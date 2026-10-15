// Render a deliberately small text format; provider HTML is always escaped first.
export function formatControlAnswer(value) {
    const escape = text => text.replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[char]);
    const inline = text => escape(text).replace(/\*\*([^*\n]+)\*\*/g, '<strong>$1</strong>').replace(/\*([^*\n]+)\*/g, '<em>$1</em>').replace(/`([^`\n]+)`/g, '<code>$1</code>');
    const lines = String(value ?? '').replace(/\r\n?/g, '\n').split('\n');
    const headings = /^(Pengamatan|Interpretasi|Langkah berikutnya|Keterbatasan|Ringkasan|Observation|Interpretation|Next steps|Limitations)$/i;
    const output = []; let paragraph = []; let list = null;
    const flush = () => { if (paragraph.length) { output.push('<p>' + inline(paragraph.join(' ')) + '</p>'); paragraph = []; } };
    const closeList = () => { if (list) { output.push('</' + list + '>'); list = null; } };
    for (const raw of lines) {
        const line = raw.trim();
        const title = line.replace(/^#{1,4}\s+/, '').replace(/^\*\*(.*?)\*\*$/, '$1').replace(/:$/, '');
        if (!line) { flush(); closeList(); continue; }
        if (headings.test(title) || /^#{1,4}\s+/.test(line)) { flush(); closeList(); output.push('<h3>' + inline(title) + '</h3>'); continue; }
        const item = line.match(/^(?:(\d+)[.)]|[-•])\s+(.+)$/);
        if (item) {
            flush(); const kind = item[1] ? 'ol' : 'ul';
            if (list !== kind) { closeList(); list = kind; output.push('<' + kind + '>'); }
            output.push('<li>' + inline(item[2]) + '</li>'); continue;
        }
        closeList(); paragraph.push(line);
    }
    flush(); closeList(); return output.join('');
}

export function adminControl(endpoints) {
    return {
        days: 7, loading: true, busy: false, error: '', prompt: '', snapshot: null, messages: [], focus: 'overview', copied: false,
        controller: null, epoch: 0,
        modes: [
            { key: 'overview', icon: 'ph ph-squares-four', label: 'Daily briefing', description: 'What needs your attention', question: 'Give me an operational and business briefing. Separate observations from hypotheses and recommend the next three actions.' },
            { key: 'growth', icon: 'ph ph-trend-up', label: 'Growth & engagement', description: 'Member momentum and adoption', question: 'Analyze registration momentum, recorded member activity and Premium share. What should we investigate and test next?' },
            { key: 'revenue', icon: 'ph ph-wallet', label: 'Revenue review', description: 'Settled payments and pending records', question: 'Review recorded settled revenue against the preceding period and pending payments. Explain what this data cannot establish, then recommend checks.' },
            { key: 'operations', icon: 'ph ph-sliders-horizontal', label: 'Operational triage', description: 'Support backlog and scraper signals', question: 'Prioritize support backlog and scraper failures using this snapshot. Give an investigation checklist without assuming root causes.' },
        ],
        async init() { await this.load(); },
        destroy() { this.epoch++; this.controller?.abort(); },
        async request(url, options = {}) {
            const response = await fetch(url, { credentials: 'same-origin', ...options,
                headers: { Accept: 'application/json', 'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, ...options.headers } });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(data.message || (response.status === 429 ? 'Usage limit reached. Please wait before retrying.' : 'The request could not be completed. Please retry.'));
            return data;
        },
        async load() {
            if (this.busy) return;
            const epoch = ++this.epoch;
            this.controller?.abort(); this.controller = new AbortController();
            this.loading = true; this.error = '';
            try {
                const data = await this.request(`${endpoints.snapshot}?days=${this.days}`, { signal: this.controller.signal });
                if (epoch !== this.epoch) return;
                this.snapshot = data.snapshot; this.messages = data.history || [];
            } catch (error) { if (epoch === this.epoch && error.name !== 'AbortError') this.error = error.message; }
            finally { if (epoch === this.epoch) this.loading = false; }
        },
        choose(mode) { this.focus = mode.key; this.prompt = mode.question; this.$nextTick?.(() => this.$refs?.composer?.focus()); },
        async ask() {
            const question = this.prompt.trim();
            if (this.busy || this.loading || question.length < 3 || question.length > 2000) return;
            this.busy = true; this.error = ''; this.copied = false;
            this.controller = new AbortController();
            try {
                const data = await this.request(endpoints.ask, { method: 'POST', signal: this.controller.signal,
                    body: JSON.stringify({ question, days: Number(this.days) }) });
                this.snapshot = data.snapshot;
                this.messages.push({ role: 'user', text: question, at: data.snapshot.generated_at },
                    { role: 'model', text: data.answer.text, mode: data.answer.mode, at: data.snapshot.generated_at });
                this.messages = this.messages.slice(-12); this.prompt = '';
                this.$nextTick?.(() => this.$refs?.latest?.scrollIntoView({ behavior: 'auto', block: 'nearest' }));
            } catch (error) { if (error.name !== 'AbortError') this.error = error.message; }
            finally { this.busy = false; }
        },
        async clear() {
            if (this.busy || this.loading) return;
            this.busy = true; this.error = '';
            try { await this.request(endpoints.clear, { method: 'POST', body: JSON.stringify({ days: Number(this.days) }) }); this.messages = []; this.copied = false; }
            catch (error) { this.error = error.message; }
            finally { this.busy = false; }
        },
        async copy() {
            const answer = [...this.messages].reverse().find(message => message.role === 'model');
            if (!answer) return;
            try { await navigator.clipboard.writeText(answer.text); this.copied = true; }
            catch { this.error = 'Clipboard access was denied. Select the analysis text to copy it manually.'; }
        },
        formatAnswer: formatControlAnswer,
        number(value) { return new Intl.NumberFormat('en-US').format(value ?? 0); },
        money(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value ?? 0); },
        time(value) { return value ? new Intl.DateTimeFormat('en-GB', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' }).format(new Date(value)) + ' WIB' : ''; },
        delta(current, previous) {
            if (!previous) return current ? 'No prior baseline' : 'No change';
            const value = Math.round((current - previous) / previous * 100);
            return `${value > 0 ? '+' : ''}${value}% vs previous period`;
        },
        get metrics() { return this.snapshot?.metrics || {}; },
        get highestRegistration() { return Math.max(1, ...(this.snapshot?.registrations || []).map(day => day.count)); },
        destination(route) { return endpoints.sources[route] || endpoints.sources['admin.analytics']; },
    };
}
if (typeof window !== 'undefined') {
    const register = () => window.Alpine.data('adminControl', adminControl);
    if (window.Alpine) register(); else document.addEventListener('alpine:init', register, { once: true });
}

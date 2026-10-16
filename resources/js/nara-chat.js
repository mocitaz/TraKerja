const modes = [
    {
        key: 'career', label: 'Career plan', icon: 'ph-path', heading: 'What’s your next move?',
        description: 'Turn your applications, priorities, and questions into a clear plan for what comes next.',
        placeholder: 'What would you like to work through?', thinking: 'Reviewing your question and career context…',
        starters: [
            { title: 'Find my next step', description: 'Prioritize your application pipeline', icon: 'ph-kanban', prompt: 'Review my tracked application pipeline. Summarize what needs attention and suggest up to three next steps, using only the data available.' },
            { title: 'Prepare for what’s coming', description: 'Plan around your next interview', icon: 'ph-calendar-check', prompt: 'Help me prepare for my nearest upcoming interview. Use my actual schedule if available; otherwise ask which role I am preparing for.' },
            { title: 'Make TraKerja work for me', description: 'Find the right tool for your task', icon: 'ph-compass', prompt: 'Help me choose a TraKerja feature for my next career task. Ask what I want to accomplish, then point me to the relevant tool.' },
        ],
        followups: ['Turn this into a short checklist', 'Which step should I prioritize?'],
    },
    {
        key: 'interview', label: 'Interview practice', icon: 'ph-microphone', heading: 'Practice with purpose.',
        description: 'One question at a time. Get specific feedback and build an answer that sounds like you.',
        placeholder: 'Share a target role or write your practice answer…', thinking: 'Preparing focused interview guidance…',
        starters: [
            { title: 'Start a mock interview', description: 'Practice for a role in your tracker', icon: 'ph-chats-circle', prompt: 'Start a mock interview for a relevant application in my tracker. Explain which role you selected, ask just one question, and wait for my answer. If no role is available, ask me for it.' },
            { title: 'Improve my answer', description: 'Feedback on clarity, evidence, and impact', icon: 'ph-target', prompt: 'I want to improve an interview answer. Ask me to paste the question and my answer first. Do not score anything until I share my answer.' },
            { title: 'Prepare questions for the interviewer', description: 'Show curiosity about the role and team', icon: 'ph-question', prompt: 'Help me prepare thoughtful questions for an interviewer. Use a relevant role in my tracked applications if possible; ask me for the target role if it is missing.' },
        ],
        followups: ['Ask me the next practice question', 'Help me make my answer more concise'],
    },
    {
        key: 'writing', label: 'Writing desk', icon: 'ph-pencil-simple-line', heading: 'Find the right words.',
        description: 'Draft a thoughtful follow-up, sharpen a CV bullet, or introduce yourself with confidence.',
        placeholder: 'Paste a draft or tell me what you need to write…', thinking: 'Shaping a draft around your request…',
        starters: [
            { title: 'Write a follow-up', description: 'A polite message for a recruiter', icon: 'ph-envelope-simple', prompt: 'Help me write a concise recruiter follow-up. Ask for the target application and timing if these are unclear. Use placeholders for facts we do not know.' },
            { title: 'Strengthen a CV bullet', description: 'Clear action, scope, and real impact', icon: 'ph-file-text', prompt: 'Help me improve a CV experience bullet. First ask me to paste the original bullet and any real results. Never invent metrics, tools, or achievements.' },
            { title: 'Craft my introduction', description: 'A focused professional introduction', icon: 'ph-user-sound', prompt: 'Help me draft a short professional introduction from my available profile. Ask for my target role if needed and use only verified experience and skills.' },
        ],
        followups: ['Make it shorter and more natural', 'Give me a more formal version'],
    },
];

const escape = value => String(value).replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[character]);
const inline = text => escape(text).replace(/`([^`\n]+)`/g, '<code>$1</code>').replace(/\*\*([^*\n]+)\*\*/g, '<strong>$1</strong>').replace(/\*([^*\n]+)\*/g, '<em>$1</em>');

export function cleanNaraResponse(raw) {
    return String(raw ?? '')
        .replace(/([0-9#*])\uFE0F?\u20E3/gu, '$1')
        .replace(/[\p{Extended_Pictographic}\p{Regional_Indicator}\p{Emoji_Modifier}\uFE0F\u200D\u{E0020}-\u{E007F}]/gu, '')
        .replace(/^[ \t]+/gm, match => match.length < 2 ? '' : match)
        .trim();
}

export function formatNaraText(raw, routes = {}, assistant = true) {
    const blocks = [];
    // Reserve structured blocks before parsing Markdown, so their contents cannot become HTML attributes.
    let text = (assistant ? cleanNaraResponse(raw) : String(raw ?? '')).replace(/\u0000/g, '').replace(/\r\n?/g, '\n');
    const reserve = html => { blocks.push(html); return `\n\n\u0000${blocks.length - 1}\u0000\n\n`; };
    text = text.replace(/```[^\n]*\n([\s\S]*?)```/g, (_, code) => reserve(`<pre><code>${escape(code.trim())}</code></pre>`));
    text = text.replace(/\[action:([a-z-]+)\|([^|\]]+)\|?([^\]]*)\]/g, (_, key, title, subtitle) => {
        const route = Object.hasOwn(routes, key) ? routes[key] : null;
        if (!route || typeof route.url !== 'string' || !/^(\/(?!\/)|https?:\/\/)/.test(route.url)) return escape(title);
        return reserve(`<a class="nara-action" href="${escape(route.url)}" wire:navigate><span><strong>${escape(title.trim())}</strong>${subtitle ? `<small>${escape(subtitle.trim())}</small>` : ''}</span><span aria-hidden="true">↗</span></a>`);
    });
    text = text.replace(/\[star_scorecard\]([\s\S]*?)\[\/star_scorecard\]/g, (_, content) => {
        const rows = content.trim().split('\n').map(line => line.match(/^\s*(Situation|Task|Action|Result|Overall):\s*(\d+(?:\.\d+)?)\/10\s*\|\s*(.*)$/i)).filter(Boolean).filter(row => Number(row[2]) >= 0 && Number(row[2]) <= 10);
        if (!rows.length) return reserve(`<p>${escape(content.trim())}</p>`);
        return reserve(`<section class="nara-scorecard" aria-label="Practice answer feedback"><h4>STAR feedback <small>Practice assessment</small></h4>${rows.map(([, label, score, note]) => `<div class="nara-score-row"><div><strong>${escape(label)}</strong><span>${escape(score)}<small>/10</small></span></div><p>${inline(note)}</p></div>`).join('')}</section>`);
    });
    const lines = text.trim().split('\n');
    const output = [];
    const marker = line => line.match(/^\s*(?:([-*•])|([0-9]+)[.)])\s+(.+)$/);
    const structural = line => /^\s*(?:#{1,6}\s|>\s?|\u0000\d+\u0000$)/.test(line) || marker(line);
    for (let index = 0; index < lines.length;) {
        const line = lines[index];
        if (!line.trim()) { index++; continue; }
        const token = line.trim().match(/^\u0000(\d+)\u0000$/);
        if (token) { output.push(blocks[Number(token[1])] ?? ''); index++; continue; }
        if (/^#{1,6}\s+/.test(line)) { output.push(`<h4>${inline(line.replace(/^#{1,6}\s+/, ''))}</h4>`); index++; continue; }
        if (/^>\s?/.test(line)) {
            const quote = [];
            while (index < lines.length && /^>\s?/.test(lines[index])) quote.push(inline(lines[index++].replace(/^>\s?/, '')));
            output.push(`<blockquote>${quote.join('<br>')}</blockquote>`);
            continue;
        }
        const first = marker(line);
        if (first) {
            const ordered = !!first[2];
            const items = [];
            while (index < lines.length) {
                const current = marker(lines[index]);
                if (!current || !!current[2] !== ordered) break;
                let content = inline(current[3]);
                index++;
                while (index < lines.length && /^\s{2,}\S/.test(lines[index]) && !structural(lines[index])) content += `<br>${inline(lines[index++].trim())}`;
                items.push(`<li>${content}</li>`);
            }
            const tag = ordered ? 'ol' : 'ul';
            output.push(`<${tag}>${items.join('')}</${tag}>`);
            continue;
        }
        const paragraph = [line.trim()];
        index++;
        while (index < lines.length && lines[index].trim() && !structural(lines[index])) paragraph.push(lines[index++].trim());
        output.push(`<p>${inline(paragraph.join(' '))}</p>`);
    }
    return output.join('');
}

export function naraChat(config, environment = globalThis) {
    return {
        open: false, expanded: false, mode: 'career', modes, input: '', messages: [], loading: false,
        historyLoading: true, historyError: '', error: '', failedText: '', clearing: false, confirmClear: false,
        atBottom: true, copied: null, failedMode: 'career', disposed: false, controllers: new Set(), openHandler: null, previousOverflow: null, copyTimer: null,
        get currentMode() { return this.modes.find(item => item.key === this.mode) ?? this.modes[0]; },
        get busy() { return this.loading || this.clearing || this.historyLoading; },
        get length() { return Array.from(this.input).length; },
        async init() {
            this.$watch('open', isOpen => {
                if (this.disposed) return;
                if (isOpen) {
                    this.previousOverflow = environment.document.body.style.overflow;
                    environment.document.body.style.overflow = 'hidden';
                    this.$refs.drawer.showModal();
                    if (this.messages.length) this.scrollToBottom(true);
                    else this.$refs.messages.scrollTop = 0;
                } else {
                    this.$refs.drawer.close();
                    environment.document.body.style.overflow = this.previousOverflow ?? '';
                    this.previousOverflow = null;
                    this.$nextTick(() => this.$refs.launcher?.focus());
                }
            });
            this.openHandler = event => {
                this.open = true;
                if (typeof event.detail?.prompt === 'string' && !this.loading && !this.clearing) {
                    this.input = event.detail.prompt;
                    this.$nextTick(() => this.resizeComposer());
                }
            };
            for (const name of ['open-nara', 'open-hiremate', 'open-kawankerja']) environment.addEventListener(name, this.openHandler);
            await this.loadHistory();
        },
        async request(url, options = {}) {
            const controller = new AbortController();
            this.controllers.add(controller);
            const timer = environment.setTimeout(() => controller.abort(), 55000);
            try {
                const response = await environment.fetch(url, { ...options, signal: controller.signal, headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': config.csrfToken, ...options.headers } });
                const data = await response.json().catch(() => null);
                if (!response.ok) {
                    const message = response.status === 429 ? 'Too many requests. Wait a moment before trying again.' : response.status === 401 || response.status === 419 ? 'Your session has expired. Reload the page to sign in again.' : response.status === 403 ? 'Chat is unavailable for this account. Check your account access and email verification.' : 'Could not complete the request. Please try again.';
                    throw new Error(message);
                }
                if (!data || typeof data !== 'object') throw new Error('An unreadable response was received. Please try again.');
                return data;
            } finally {
                environment.clearTimeout(timer);
                this.controllers.delete(controller);
            }
        },
        async loadHistory() {
            if (this.disposed || this.loading || this.clearing) return;
            this.historyLoading = true;
            this.historyError = '';
            try {
                const data = await this.request(config.historyUrl);
                if (!Array.isArray(data.history) || data.history.some(item => !item || !['user', 'model'].includes(item.role) || typeof item.content !== 'string')) throw new Error('Could not read your conversation. Please reload it.');
                if (this.disposed) return;
                this.messages = data.history;
                if (this.open && this.messages.length) this.scrollToBottom(true);
            } catch (error) {
                if (!this.disposed) this.historyError = error.name === 'AbortError' ? 'Loading timed out. Reload your conversation to continue.' : error.message;
            } finally { if (!this.disposed) this.historyLoading = false; }
        },
        selectMode(mode) {
            if (this.busy || !modes.some(item => item.key === mode)) return;
            this.mode = mode;
        },
        usePrompt(prompt) {
            if (this.busy || this.historyError) return;
            this.input = prompt;
            this.$nextTick(() => { this.resizeComposer(); this.$refs.composer?.focus(); });
        },
        async send(retryText = null) {
            const text = (typeof retryText === 'string' ? retryText : this.input).trim();
            if (!text || this.busy || this.historyError || this.disposed || (this.failedText && typeof retryText !== 'string')) return;
            if (Array.from(text).length > 1000) { this.error = 'Keep your message within 1,000 characters.'; return; }
            const requestMode = typeof retryText === 'string' ? this.failedMode : this.mode;
            this.error = ''; this.failedText = ''; this.loading = true;
            const retrying = typeof retryText === 'string';
            if (!retrying) {
                this.input = '';
                this.messages.push({ role: 'user', content: text });
                this.resizeComposer();
            }
            this.scrollToBottom(true);
            try {
                const data = await this.request(config.chatUrl, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ message: text, mode: requestMode }) });
                if (data.success !== true || typeof data.reply !== 'string' || !data.reply.trim()) throw new Error('Nara could not finish a response. Your message is saved here; try again.');
                if (this.disposed) return;
                // The server owns successful history; failed requests are never stored as assistant replies.
                this.messages.push({ role: 'model', content: data.reply });
                this.scrollToBottom();
            } catch (error) {
                if (!this.disposed) {
                    this.failedText = text;
                    this.failedMode = requestMode;
                    this.error = error.name === 'AbortError' ? 'The response timed out. Try again in a moment.' : error.message;
                }
            } finally { if (!this.disposed) this.loading = false; }
        },
        async retry() { if (this.failedText) await this.send(this.failedText); },
        editFailed() {
            if (this.busy || !this.failedText) return;
            const text = this.failedText;
            if (this.messages.at(-1)?.role === 'user') this.messages.pop();
            this.failedText = ''; this.error = '';
            this.usePrompt(text);
        },
        async clearChat() {
            if (this.busy || this.disposed) return;
            this.clearing = true; this.error = '';
            try {
                const data = await this.request(config.clearUrl, { method: 'POST' });
                if (data.success !== true) throw new Error('Could not clear this conversation. Please try again.');
                if (this.disposed) return;
                this.messages = []; this.failedText = ''; this.confirmClear = false; this.input = ''; this.copied = null;
                this.resizeComposer();
            } catch (error) { if (!this.disposed) this.error = error.name === 'AbortError' ? 'Clearing timed out. Please try again.' : error.message; }
            finally { if (!this.disposed) this.clearing = false; }
        },
        trackScroll() {
            const element = this.$refs.messages;
            if (element) this.atBottom = element.scrollHeight - element.scrollTop - element.clientHeight < 80;
        },
        scrollToBottom(force = false) {
            if (!force && !this.atBottom) return;
            this.$nextTick(() => { if (this.disposed) return; const element = this.$refs.messages; if (element) element.scrollTop = element.scrollHeight; this.atBottom = true; });
        },
        resizeComposer() {
            this.$nextTick(() => { const element = this.$refs.composer; if (!element || this.disposed) return; element.style.height = 'auto'; element.style.height = `${Math.min(160, element.scrollHeight)}px`; });
        },
        async copyMessage(raw, index) {
            const text = cleanNaraResponse(raw).replace(/\[action:[a-z-]+\|([^|\]]+)\|?([^\]]*)\]/g, (_, title, subtitle) => `${title}${subtitle ? ' — ' + subtitle : ''}`).replace(/\[\/?star_scorecard\]/g, '').trim();
            try {
                await environment.navigator.clipboard.writeText(text);
                if (this.disposed) return;
                this.copied = index;
                environment.clearTimeout(this.copyTimer);
                this.copyTimer = environment.setTimeout(() => { this.copied = null; }, 2000);
            } catch (_) { this.error = 'Copy was blocked. Select the response text and copy it manually.'; }
        },
        formatText(raw, assistant = true) { return formatNaraText(raw, config.routes, assistant); },
        destroy() {
            this.disposed = true;
            this.controllers.forEach(controller => controller.abort());
            environment.clearTimeout(this.copyTimer);
            for (const name of ['open-nara', 'open-hiremate', 'open-kawankerja']) environment.removeEventListener(name, this.openHandler);
            if (this.open) { this.$refs.drawer.close(); environment.document.body.style.overflow = this.previousOverflow ?? ''; }
        },
    };
}

if (typeof window !== 'undefined') window.naraChat = naraChat;

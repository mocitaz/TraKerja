import { t } from './i18n.js';
// One chat for everything: Nara reads the intent of each message (jobs, plans, interview practice, writing).
// Kept as a one-item list so currentMode, the server's mode field and older history keep working.
const modes = [
    {
        key: 'auto', label: t("Nara"), icon: 'ph-chat-circle', heading: t("What’s your next move?"),
        description: t("Ask about jobs, your applications, interviews, or anything you need to write."),
        placeholder: t("Ask Nara anything about your job search…"), thinking: t("Nara is thinking…"),
        starters: [
            { title: t("Find jobs that fit me"), description: t("Open jobs from the TraKerja catalog"), icon: 'ph-briefcase', prompt: t("Recommend open jobs from the TraKerja catalog that fit my profile and applications. Ask me for a role or city if you need one.") },
            { title: t("Find my next step"), description: t("Prioritize your application pipeline"), icon: 'ph-kanban', prompt: t("Review my tracked application pipeline. Summarize what needs attention and suggest up to three next steps, using only the data available.") },
            { title: t("Start a mock interview"), description: t("Practice for a role in your tracker"), icon: 'ph-chats-circle', prompt: t("Start a mock interview for a relevant application in my tracker. Explain which role you selected, ask just one question, and wait for my answer. If no role is available, ask me for it.") },
            { title: t("Write a follow-up"), description: t("A polite message for a recruiter"), icon: 'ph-envelope-simple', prompt: t("Help me write a concise recruiter follow-up. Ask for the target application and timing if these are unclear. Use placeholders for facts we do not know.") },
        ],
        followups: ['Show me more jobs like these', 'What should I do next?'].map(value => t(value)),
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

/** The follow-up messages Nara suggested at the end of a reply ([suggest:a|b|c]); up to 3, never from a half-written tag. */
export function parseSuggestions(raw) {
    const match = String(raw ?? '').match(/\[suggest:([^\]]*)\]/);
    if (!match) return [];
    return [...new Set(match[1].split('|').map(item => item.replace(/\s+/g, ' ').trim()).filter(item => item.length >= 3))].slice(0, 3);
}

/** [compare:...] carries base64url JSON built by the server; null when it cannot be read. */
export function decodeComparison(encoded) {
    try {
        const base64 = String(encoded).replace(/-/g, '+').replace(/_/g, '/');
        const bytes = Uint8Array.from(atob(base64 + '='.repeat((4 - base64.length % 4) % 4)), character => character.charCodeAt(0));
        const data = JSON.parse(new TextDecoder().decode(bytes));
        return Array.isArray(data.jobs) && Array.isArray(data.rows) ? data : null;
    } catch { return null; }
}

/** The text a draft card copies: subject (emails), a blank line, then the body. */
export function splitDraft(content) {
    const lines = String(content).replace(/^\n+|\n+$/g, '').split('\n');
    const subject = lines[0]?.match(/^\s*\**(?:subject|subjek|perihal)\**\s*:\**\s*(.+)$/i);
    const body = (subject ? lines.slice(1) : lines).join('\n').replace(/^\n+/, '').trim();
    return { subject: subject ? subject[1].replace(/\*+/g, '').trim() : '', body };
}

export function formatNaraText(raw, routes = {}, assistant = true, state = {}) {
    const blocks = [];
    // Reserve structured blocks before parsing Markdown, so their contents cannot become HTML attributes.
    let text = (assistant ? cleanNaraResponse(raw) : String(raw ?? '')).replace(/\u0000/g, '').replace(/\r\n?/g, '\n');
    const reserve = html => { blocks.push(html); return `\n\n\u0000${blocks.length - 1}\u0000\n\n`; };
    text = text.replace(/```[^\n]*\n([\s\S]*?)```/g, (_, code) => reserve(`<pre><code>${escape(code.trim())}</code></pre>`));
    // Half-written tags at the very end (while streaming) are hidden until they are complete.
    text = text.replace(/\[(?:job|suggest|savesearch|interview|compare|action|star_scorecard)[^\]]*$/u, '').replace(/\[\/(?:d[a-z]*|s[a-z_]*)?$/u, '');
    // Draft cards: a ready-to-send email, letter or message with a copy button. An unfinished draft (streaming) shows what is there so far.
    text = text.replace(/\[draft:(email|letter|message)\]([\s\S]*?)(?:\[\/draft\]|$)/g, (_, kind, content) => {
        const { subject, body } = splitDraft(content);
        const label = { email: t("Email draft"), letter: t("Cover letter"), message: t("Message draft") }[kind];
        const copyAll = [subject ? `${t("Subject")}: ${subject}` : '', body].filter(Boolean).join('\n\n');
        const paragraphs = body.split(/\n{2,}/).map(part => `<p>${inline(part).replace(/\n/g, '<br>')}</p>`).join('');
        const buttons = `<button type="button" class="nara-draft-btn" data-nara-copy data-nara-text="${escape(copyAll)}"><i class="ph ph-copy" aria-hidden="true"></i><span>${escape(kind === 'email' ? t("Copy email") : kind === 'letter' ? t("Copy letter") : t("Copy message"))}</span></button>`
            + (kind === 'email' && subject ? `<button type="button" class="nara-draft-btn" data-nara-copy data-nara-text="${escape(body)}"><span>${escape(t("Copy body"))}</span></button>` : '');
        return reserve(`<section class="nara-draft nara-draft--${kind}" aria-label="${escape(label)}"><header><span><i class="ph ph-envelope-simple" aria-hidden="true"></i>${escape(label)}</span><span class="nara-draft-actions">${buttons}</span></header>${subject ? `<div class="nara-draft-subject"><small>${escape(t("Subject"))}</small><strong>${escape(subject)}</strong></div>` : ''}<div class="nara-draft-body">${paragraphs}</div></section>`);
    });
    // Comparison table of 2-3 jobs, built by the server from the catalogue; scrolls sideways inside the panel.
    text = text.replace(/\[compare:([A-Za-z0-9_-]+)\]/g, (_, encoded) => {
        const data = decodeComparison(encoded);
        if (!data) return '';
        const head = data.jobs.map(job => { const href = typeof job.url === 'string' && /^\/(?!\/)/.test(job.url) ? job.url : null; const name = `<strong>${escape(job.title)}</strong>${job.company ? `<small>${escape(job.company)}</small>` : ''}`; return `<th scope="col">${href ? `<a href="${escape(href)}" wire:navigate>${name}</a>` : name}</th>`; }).join('');
        const rows = data.rows.map(([label, values]) => `<tr><th scope="row">${escape(label)}</th>${values.map(value => `<td>${value ? escape(value) : '<span class="nara-compare-empty">–</span>'}</td>`).join('')}</tr>`).join('');
        return reserve(`<div class="nara-compare" role="region" aria-label="${escape(t("Job comparison"))}" tabindex="0"><table><thead><tr><th></th>${head}</tr></thead><tbody>${rows}</tbody></table></div>`);
    });
    // Interview practice progress: question dots, current question and the average score so far.
    text = text.replace(/\[interview:(\d+)\|(\d+)\|([\d.]*)\|(active|done)\|([^\]]*)\]/g, (_, answered, total, average, status, role) => {
        const done = status === 'done', n = Number(answered), all = Math.max(1, Number(total));
        const dots = Array.from({ length: all }, (_, index) => `<i class="${index < n ? 'is-done' : index === n && !done ? 'is-current' : ''}"></i>`).join('');
        const label = done ? t("Completed · :answered of :total answered", { answered: n, total: all }) : t("Question :current of :total", { current: Math.min(all, n + 1), total: all });
        return reserve(`<div class="nara-interview${done ? ' is-done' : ''}" role="group" aria-label="${escape(t("Interview practice"))}"><div class="nara-interview-head"><i class="ph ph-microphone" aria-hidden="true"></i><strong>${escape(t("Interview practice"))}</strong>${role.trim() ? `<span>${escape(role.trim())}</span>` : ''}</div><div class="nara-interview-dots" aria-hidden="true">${dots}</div><div class="nara-interview-meta"><span>${escape(label)}</span>${average ? `<span>${escape(t("Average"))} ${escape(average)}/10</span>` : ''}</div></div>`);
    });
    // Follow-up suggestions are drawn as chips under the reply, not inside it.
    text = text.replace(/\[suggest:[^\]]*\]/g, '');
    // Job cards: the server expands [job:ID] into [job:ID|title|company|meta|url|saved] from the catalogue;
    // bare tags (still streaming) are hidden. Buttons are handled by naraChat.onMessageClick.
    const savedJobs = state.savedJobs instanceof Set ? state.savedJobs : new Set();
    text = text.replace(/\[job:(\d+)(?:\|([^|\]]*)\|([^|\]]*)\|([^|\]]*)\|([^|\]]*)(?:\|([01]))?(?:\|([^|\]]*))?)?\]/g, (_, id, title, company, meta, url, saved, logo) => {
        if (!title) return '';
        const href = typeof url === 'string' && /^\/(?!\/)/.test(url) ? url : null;
        const isSaved = saved === '1' || savedJobs.has(id);
        const text = `<span class="nara-job-text"><strong>${escape(title.trim())}</strong>${company ? `<small>${escape(company.trim())}</small>` : ''}${meta ? `<em>${escape(meta.trim())}</em>` : ''}</span>`;
        // Company logo when the catalogue has one (https only); the initial stays underneath if the image fails.
        const initial = (company ?? '').trim().charAt(0).toUpperCase();
        const logoUrl = typeof logo === 'string' && /^https:\/\/[^\s"'<>]+$/.test(logo.trim()) ? logo.trim() : null;
        const icon = `<span class="nara-job-icon${logoUrl ? ' has-logo' : ''}" aria-hidden="true">${initial ? `<b>${escape(initial)}</b>` : '<i class="ph ph-briefcase"></i>'}${logoUrl ? `<img src="${escape(logoUrl)}" alt="" width="36" height="36" loading="lazy" decoding="async" referrerpolicy="no-referrer" onload="this.parentNode.classList.add('is-loaded')" onerror="this.parentNode.classList.remove('has-logo');this.remove()">` : ''}</span>`;
        const main = href ? `<a class="nara-job-main" href="${escape(href)}" wire:navigate>${icon}${text}</a>` : `<span class="nara-job-main">${icon}${text}</span>`;
        const save = isSaved
            ? `<span class="nara-job-btn is-done"><i class="ph-fill ph-bookmark-simple" aria-hidden="true"></i>${escape(t("Saved"))}</span>`
            : `<button type="button" class="nara-job-btn" data-nara-save-job="${escape(id)}"><i class="ph ph-bookmark-simple" aria-hidden="true"></i>${escape(t("Save"))}</button>`;
        const details = href ? `<a class="nara-job-btn" href="${escape(href)}" wire:navigate>${escape(t("Details"))} ↗</a>` : '';
        return reserve(`<div class="nara-job">${main}<div class="nara-job-actions">${save}${details}</div></div>`);
    });
    text = text.replace(/\[savesearch:([^\]]*)\]/g, (_, label) => {
        if (state.savedSearch?.has?.(label.trim())) return reserve(`<p class="nara-save-search is-done"><i class="ph-fill ph-bell-ringing" aria-hidden="true"></i>${escape(t("Alerts on for this search."))}</p>`);
        return reserve(`<button type="button" class="nara-save-search" data-nara-save-search="${escape(label.trim())}"><i class="ph ph-bell" aria-hidden="true"></i><span><strong>${escape(t("Save this search"))}</strong><small>${escape(t("Get an email when new matching jobs appear."))}</small></span></button>`);
    });
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
        open: false, expanded: false, mode: 'auto', modes, input: '', messages: [], loading: false,
        historyLoading: true, historyError: '', error: '', failedText: '', clearing: false, confirmClear: false,
        atBottom: true, copied: null, failedMode: 'auto', disposed: false, controllers: new Set(), openHandler: null, previousOverflow: null, copyTimer: null,
        // waiting: the "Nara is thinking" dots. It only ever goes true -> false (request sent -> first word or reply),
        // so it cannot flicker back on when the stream ends.
        streaming: false, waiting: false, stopRequested: false, status: '', savedJobs: new Set(), savedSearches: new Set(), nudges: [], nudgesHidden: false,
        get currentMode() { return this.modes.find(item => item.key === this.mode) ?? this.modes[0]; },
        get busy() { return this.loading || this.clearing || this.historyLoading; },
        get visibleNudges() { return this.nudgesHidden ? [] : this.nudges; },
        /** Follow-ups Nara suggested for its last reply (none while it is still writing). */
        get suggestions() { const last = this.messages.at(-1); return last?.role === 'model' && !last.streaming ? parseSuggestions(last.content) : []; },
        /** Chips under the last reply: Nara's own suggestions, or the fixed ones when it gave none. */
        get followupChips() { const own = this.suggestions; return own.length ? own : this.currentMode.followups; },
        get lastUserIndex() { for (let index = this.messages.length - 1; index >= 0; index--) if (this.messages[index].role === 'user') return index; return -1; },
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
                    const message = response.status === 429 ? 'Too many requests. Wait a moment before trying again.' : response.status === 401 || response.status === 419 ? 'Your session has expired. Reload the page to sign in again.' : response.status === 403 ? 'Chat is unavailable for this account. Check your account access and email verification.' : t("Could not complete the request. Please try again.");
                    throw new Error(message);
                }
                if (!data || typeof data !== 'object') throw new Error(t("An unreadable response was received. Please try again."));
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
                if (!Array.isArray(data.history) || data.history.some(item => !item || !['user', 'model'].includes(item.role) || typeof item.content !== 'string')) throw new Error(t("Could not read your conversation. Please reload it."));
                if (this.disposed) return;
                this.messages = data.history;
                this.nudges = Array.isArray(data.nudges) ? data.nudges.filter(item => item && typeof item.text === 'string' && typeof item.prompt === 'string').slice(0, 3) : [];
                try { this.nudgesHidden = environment.sessionStorage?.getItem('nara-nudges-hidden') === new Date().toDateString(); } catch { this.nudgesHidden = false; }
                if (this.open && this.messages.length) this.scrollToBottom(true);
            } catch (error) {
                if (!this.disposed) this.historyError = error.name === t("AbortError") ? 'Loading timed out. Reload your conversation to continue.' : error.message;
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
            if (Array.from(text).length > 1000) { this.error = t("Keep your message within 1,000 characters."); return; }
            const requestMode = typeof retryText === 'string' ? this.failedMode : this.mode;
            this.error = ''; this.failedText = ''; this.loading = true; this.waiting = true;
            const retrying = typeof retryText === 'string';
            if (!retrying) {
                this.input = '';
                this.messages.push({ role: 'user', content: text });
                this.resizeComposer();
            }
            this.scrollToBottom(true);
            try {
                const streamed = config.streamUrl ? await this.streamReply(text, requestMode) : null;
                if (streamed === '') {
                    // Stopped before the first word: take the question back so it can be edited or sent again.
                    if (this.messages.at(-1)?.role === 'user') this.messages.pop();
                    this.input = text; this.resizeComposer();
                    return;
                }
                if (streamed === null && !this.stopRequested) {
                    // No streaming support (or it failed before the first word): one plain request instead.
                    const data = await this.request(config.chatUrl, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ message: text, mode: requestMode }) });
                    if (data.success !== true || typeof data.reply !== 'string' || !data.reply.trim()) throw new Error(t("Nara could not finish a response. Your message is saved here; try again."));
                    if (this.disposed) return;
                    // The server owns successful history; failed requests are never stored as assistant replies.
                    this.messages.push({ role: 'model', content: data.reply, id: data.message_id ?? null, rating: null });
                }
                this.scrollToBottom();
            } catch (error) {
                if (this.stopRequested) { // Stop pressed during the plain request: nothing to show
                    if (this.messages.at(-1)?.role === 'user') this.messages.pop();
                    this.input = text; this.resizeComposer();
                } else if (!this.disposed) {
                    this.failedText = text;
                    this.failedMode = requestMode;
                    this.error = error.name === t("AbortError") ? 'The response timed out. Try again in a moment.' : error.message;
                }
            } finally { this.stopRequested = false; if (!this.disposed) { this.waiting = false; this.streaming = false; this.loading = false; this.status = ''; } }
        },
        /**
         * Stream the reply word by word (Server-Sent Events). Returns null when streaming is unavailable before any
         * text arrived (the caller falls back to a plain request); throws when it breaks halfway.
         */
        async streamReply(text, mode) {
            const controller = new AbortController();
            this.controllers.add(controller);
            const timer = environment.setTimeout(() => controller.abort(), 90000);
            const every = environment.setInterval ?? globalThis.setInterval.bind(globalThis);
            const stopEvery = environment.clearInterval ?? globalThis.clearInterval.bind(globalThis);
            let message = null, ticker = null, target = '', shown = 0;
            // Typing effect: the model sends a few big pieces; show them letter by letter, catching up when behind.
            const reveal = () => {
                const behind = target.length - shown;
                if (!message || behind <= 0) return;
                shown += Math.max(1, Math.ceil(behind / 8));
                message.content = target.slice(0, shown);
                this.scrollToBottom();
            };
            const drain = async limit => { const until = Date.now() + limit; while (message && shown < target.length && Date.now() < until) await new Promise(resolve => environment.setTimeout(resolve, 20)); };
            try {
                const response = await environment.fetch(config.streamUrl, { method: 'POST', signal: controller.signal, headers: { 'Accept': 'text/event-stream', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': config.csrfToken }, body: JSON.stringify({ message: text, mode }) });
                if (response.status === 429 || response.status === 401 || response.status === 419 || response.status === 403) {
                    throw new Error(response.status === 429 ? t("Too many requests. Wait a moment before trying again.") : response.status === 403 ? t("Chat is unavailable for this account. Check your account access and email verification.") : t("Your session has expired. Reload the page to sign in again."));
                }
                if (!response.ok || !response.body?.getReader) return null;
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '', done = false, failed = false, final = null, finalId = null;
                while (!done) {
                    // Nothing for 30 s (a stalled connection): stop waiting instead of hanging forever.
                    const read = await Promise.race([reader.read(), new Promise(resolve => environment.setTimeout(() => resolve({ stalled: true }), 30000))]);
                    if (read.stalled) { try { reader.cancel(); } catch { /* already closed */ } if (!final) throw new Error(t("Response interrupted. Please try again.")); break; }
                    const { value, done: ended } = read;
                    if (ended) break;
                    buffer += decoder.decode(value, { stream: true });
                    const events = buffer.split('\n\n');
                    buffer = events.pop();
                    for (const event of events) {
                        const isError = /^event:\s*error/m.test(event);
                        const data = event.split('\n').filter(line => line.startsWith('data:')).map(line => line.slice(5).trim()).join('');
                        if (data === '[DONE]') { done = true; break; }
                        if (isError) { failed = true; continue; }
                        let payload; try { payload = JSON.parse(data); } catch { continue; }
                        if (typeof payload.status === 'string' && payload.status && !message) { this.status = payload.status; continue; }
                        // The final reply is the last thing the server sends: no need to wait for the connection to close.
                        if (typeof payload.final === 'string') { final = payload.final; finalId = payload.id ?? null; done = true; break; }
                        if (typeof payload.chunk === 'string' && payload.chunk && !this.disposed) {
                            if (!message) {
                                this.messages.push({ role: 'model', content: '', streaming: true });
                                message = this.messages[this.messages.length - 1];
                                this.streaming = true;
                                this.waiting = false;
                                ticker = every(reveal, 28);
                            }
                            target += payload.chunk;
                        }
                    }
                }
                try { reader.cancel(); } catch { /* already closed */ }
                this.waiting = false;
                if (failed || (!final && !target.trim())) {
                    if (!message) return null;
                    throw new Error(t("Response interrupted. Please try again."));
                }
                await drain(900); // let the typing catch up, but never keep the user waiting long
                if (!message) { this.messages.push({ role: 'model', content: final, id: finalId, rating: null }); return final; }
                // The final reply carries the job cards and tables the streamed text cannot show yet.
                message.content = final ?? target;
                message.id = finalId;
                message.rating = null;
                message.streaming = false;
                return message.content;
            } catch (error) {
                if (this.stopRequested && error.name === 'AbortError') {
                    // Stop pressed: keep what was written (the server saved it too); nothing written means an empty result.
                    if (!message) return '';
                    message.content = target;
                    message.streaming = false;
                    message.stopped = true;
                    return message.content;
                }
                if (message) { const index = this.messages.indexOf(message); if (index >= 0) this.messages.splice(index, 1); }
                if (error.name === 'AbortError' && !message) return null;
                throw error;
            } finally {
                if (ticker !== null) stopEvery(ticker);
                environment.clearTimeout(timer);
                this.controllers.delete(controller);
            }
        },
        /** Stop button: end the reply that is being written. */
        stop() {
            if (!this.loading || this.disposed) return;
            this.stopRequested = true;
            this.controllers.forEach(controller => controller.abort());
        },
        /** Take back the last exchange on the server and return the user's text (null when there is nothing to take back). */
        async takeBackLast() {
            try {
                const data = await this.request(config.undoUrl, { method: 'POST' });
                if (data.success !== true || typeof data.text !== 'string') return null;
                const index = this.lastUserIndex;
                if (index >= 0) this.messages.splice(index);
                return data.text;
            } catch (error) { if (!this.disposed) this.error = error.message; return null; }
        },
        /** Regenerate: ask the same question again and replace the last reply. */
        async regenerate() {
            if (this.busy || this.historyError || this.messages.at(-1)?.role !== 'model') return;
            const text = await this.takeBackLast();
            if (!text) return;
            this.input = text;
            await this.send();
        },
        /** Edit the last message: take it back into the composer. */
        async editLast() {
            if (this.busy || this.historyError || this.lastUserIndex < 0) return;
            const text = await this.takeBackLast();
            if (text === null) return;
            this.usePrompt(text);
        },
        /** A suggestion chip written by Nara is sent straight away. */
        useSuggestion(text) {
            if (this.busy || this.historyError || !text) return;
            this.input = text;
            this.send();
        },
        /** 👍 / 👎 under a reply; pressing the same one again takes the rating back. */
        async rate(message, value) {
            if (!message?.id || this.disposed) return;
            const previous = message.rating ?? null;
            const next = previous === value ? 0 : value;
            message.rating = next || null;
            try {
                const data = await this.request(config.feedbackUrl, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ message_id: message.id, rating: next }) });
                if (data.success !== true) throw new Error(t("Could not complete the request. Please try again."));
            } catch (error) {
                if (!this.disposed) { message.rating = previous; this.error = error.message; }
            }
        },
        useNudge(nudge) {
            if (this.busy || this.historyError || !nudge?.prompt) return;
            this.nudgesHidden = true;
            this.input = nudge.prompt;
            this.send();
        },
        dismissNudges() {
            this.nudgesHidden = true;
            try { environment.sessionStorage?.setItem('nara-nudges-hidden', new Date().toDateString()); } catch { /* private mode */ }
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
                if (data.success !== true) throw new Error(t("Could not clear this conversation. Please try again."));
                if (this.disposed) return;
                this.messages = []; this.failedText = ''; this.confirmClear = false; this.input = ''; this.copied = null;
                this.resizeComposer();
            } catch (error) { if (!this.disposed) this.error = error.name === t("AbortError") ? 'Clearing timed out. Please try again.' : error.message; }
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
            const text = cleanNaraResponse(raw).replace(/\[job:\d+\|([^|\]]*)\|([^|\]]*)\|([^|\]]*)\|[^\]]*\]/g, (_, title, company, meta) => `- ${title}${company ? ' — ' + company : ''}${meta ? ' (' + meta + ')' : ''}`).replace(/\[job:\d+\]/g, '').replace(/\[savesearch:[^\]]*\]/g, '').replace(/\[(?:compare|interview|suggest):[^\]]*\]/g, '').replace(/\[draft:(?:email|letter|message)\]|\[\/draft\]/g, '').replace(/\[action:[a-z-]+\|([^|\]]+)\|?([^\]]*)\]/g, (_, title, subtitle) => `${title}${subtitle ? ' — ' + subtitle : ''}`).replace(/\[\/?star_scorecard\]/g, '').trim();
            try {
                await environment.navigator.clipboard.writeText(text);
                if (this.disposed) return;
                this.copied = index;
                environment.clearTimeout(this.copyTimer);
                this.copyTimer = environment.setTimeout(() => { this.copied = null; }, 2000);
            } catch (_) { this.error = t("Copy was blocked. Select the response text and copy it manually."); }
        },
        formatText(raw, assistant = true) { return formatNaraText(raw, config.routes, assistant, { savedJobs: this.savedJobs, savedSearch: this.savedSearches }); },
        // Buttons inside rendered replies (job cards, save search) are delegated from the transcript.
        async onMessageClick(event) {
            const copy = event.target.closest('[data-nara-copy]');
            if (copy) {
                event.preventDefault();
                try {
                    await environment.navigator.clipboard.writeText(copy.dataset.naraText ?? '');
                    const label = copy.querySelector('span');
                    const original = label?.textContent;
                    copy.classList.add('is-copied');
                    if (label) label.textContent = t("Copied");
                    environment.setTimeout(() => { copy.classList.remove('is-copied'); if (label) label.textContent = original; }, 1600);
                } catch (_) { this.error = t("Copy was blocked. Select the response text and copy it manually."); }
                return;
            }
            const button = event.target.closest('[data-nara-save-job], [data-nara-save-search]');
            if (!button || button.disabled || this.disposed) return;
            event.preventDefault();
            button.disabled = true;
            const job = button.dataset.naraSaveJob;
            try {
                const data = await this.request(job ? config.saveJobUrl : config.saveSearchUrl, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(job ? { vacancy_id: Number(job) } : {}) });
                if (data.success !== true) throw new Error(data.message || t("Could not complete the request. Please try again."));
                if (this.disposed) return;
                // Reassign the sets so Alpine re-renders the cards.
                if (job) this.savedJobs = new Set([...this.savedJobs, job]);
                else this.savedSearches = new Set([...this.savedSearches, button.dataset.naraSaveSearch]);
                this.error = '';
            } catch (error) {
                if (!this.disposed) { button.disabled = false; this.error = error.message; }
            }
        },
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

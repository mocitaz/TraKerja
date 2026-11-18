// Nara Admin: the operations assistant panel on every admin page.
// The server streams SSE events: {status}, {chunk}, {final, id, actions}, then [DONE]. Approval cards are drawn from
// [adminaction:ID] tags; the card data (status, details) comes from the server, never from the model's text.

const escapeHtml = value => String(value).replace(/[&<>"']/g, ch => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch]));

/** Inline Markdown on already-escaped text: code, bold, italics, and links to /admin pages only. */
function inline(escaped) {
    return escaped
        .replace(/`([^`]+)`/g, '<code>$1</code>')
        .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
        .replace(/(^|[\s(])\*([^*\s][^*]*)\*(?=$|[\s).,;:!?])/g, '$1<em>$2</em>')
        .replace(/\[([^\]]+)\]\((\/admin[^)\s]*)\)/g, (match, label, href) => `<a href="${href}" wire:navigate>${label}</a>`);
}

const isRow = line => /^\s*\|.*\|\s*$/.test(line);
const cells = line => line.trim().replace(/^\||\|$/g, '').split('|').map(cell => cell.trim());

/** The follow-up chips Nara wrote at the end ([suggest:a|b|c]); up to three. */
export function parseSuggestions(raw) {
    const match = String(raw ?? '').match(/\[suggest:([^\]]*)\]/);
    if (!match) return [];
    return [...new Set(match[1].split('|').map(item => item.replace(/\s+/g, ' ').trim()).filter(item => item.length >= 3))].slice(0, 3);
}

/** Split a reply into text and approval-card segments: [{type:'html', html}, {type:'action', id}]. */
export function formatAdminText(raw) {
    let text = String(raw ?? '').replace(/\[suggest:[^\]]*\]?/g, '').replace(/\[(?:adminaction|suggest)[^\]]*$/g, '').trim();
    const segments = [];
    const parts = text.split(/\[adminaction:(\d+)\]/);
    parts.forEach((part, index) => {
        if (index % 2 === 1) { segments.push({ type: 'action', id: Number(part) }); return; }
        const html = blocks(part);
        if (html) segments.push({ type: 'html', html });
    });
    return segments;
}

function blocks(text) {
    const lines = text.replace(/\r/g, '').split('\n');
    const out = [];
    let i = 0;
    while (i < lines.length) {
        const line = lines[i];
        if (/^```/.test(line)) {
            const code = [];
            i++;
            while (i < lines.length && !/^```/.test(lines[i])) code.push(lines[i++]);
            i++;
            out.push(`<pre><code>${escapeHtml(code.join('\n'))}</code></pre>`);
        } else if (isRow(line) && i + 1 < lines.length && /^\s*\|[\s:|-]+\|\s*$/.test(lines[i + 1])) {
            const head = cells(line);
            i += 2;
            const body = [];
            while (i < lines.length && isRow(lines[i])) body.push(cells(lines[i++]));
            out.push('<div class="admin-nara__table"><table><thead><tr>' + head.map(c => `<th>${inline(escapeHtml(c))}</th>`).join('') + '</tr></thead><tbody>'
                + body.map(row => '<tr>' + row.map(c => `<td>${inline(escapeHtml(c))}</td>`).join('') + '</tr>').join('') + '</tbody></table></div>');
        } else if (/^\s*([-*•]|\d+[.)])\s+/.test(line)) {
            const ordered = /^\s*\d+[.)]/.test(line);
            const items = [];
            while (i < lines.length && /^\s*([-*•]|\d+[.)])\s+/.test(lines[i])) items.push(lines[i++].replace(/^\s*([-*•]|\d+[.)])\s+/, ''));
            const tag = ordered ? 'ol' : 'ul';
            out.push(`<${tag}>` + items.map(item => `<li>${inline(escapeHtml(item))}</li>`).join('') + `</${tag}>`);
        } else if (/^#{1,4}\s+/.test(line)) {
            out.push(`<p class="admin-nara__heading">${inline(escapeHtml(line.replace(/^#{1,4}\s+/, '')))}</p>`);
            i++;
        } else if (line.trim() === '') {
            i++;
        } else {
            const paragraph = [];
            while (i < lines.length && lines[i].trim() !== '' && !/^```/.test(lines[i]) && !isRow(lines[i]) && !/^\s*([-*•]|\d+[.)])\s+/.test(lines[i]) && !/^#{1,4}\s+/.test(lines[i])) paragraph.push(lines[i++]);
            out.push(`<p>${inline(escapeHtml(paragraph.join(' ')))}</p>`);
        }
    }

    return out.join('');
}

export function adminNara(config, environment = globalThis) {
    return {
        open: false,
        busy: false,
        waiting: false,
        status: '',
        input: '',
        messages: [],
        actions: {},
        starters: [],
        error: '',
        controller: null,
        stopped: false,

        init() {
            this.load();
        },

        toggle() {
            this.open = !this.open;
            if (this.open) this.$nextTick?.(() => { this.scrollDown(); this.$refs.input?.focus(); });
        },

        csrf() {
            return environment.document?.querySelector('meta[name="csrf-token"]')?.content ?? '';
        },

        async request(url, body = null) {
            const response = await environment.fetch(url, {
                method: body === null ? 'GET' : 'POST',
                headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                body: body === null ? undefined : JSON.stringify(body),
            });
            const data = await response.json().catch(() => ({}));

            return { ok: response.ok, status: response.status, data };
        },

        async load() {
            try {
                const { ok, data } = await this.request(`${config.historyUrl}?page=${encodeURIComponent(config.page ?? '')}`);
                if (!ok) return;
                this.messages = (data.history ?? []).map(item => ({ id: item.id, role: item.role, content: item.content }));
                this.actions = data.actions ?? {};
                this.starters = data.starters ?? [];
            } catch { /* the panel still works without history */ }
        },

        segments(message) {
            return formatAdminText(message.content);
        },

        chips(message) {
            return parseSuggestions(message.content);
        },

        get lastModelIndex() {
            for (let i = this.messages.length - 1; i >= 0; i--) if (this.messages[i].role === 'model') return i;

            return -1;
        },

        scrollDown() {
            this.$nextTick?.(() => { const box = this.$refs.scroller; if (box) box.scrollTop = box.scrollHeight; });
        },

        async send(text = null) {
            const message = String(text ?? this.input).trim();
            if (!message || this.busy) return;
            this.input = '';
            this.error = '';
            this.busy = true;
            this.waiting = true;
            this.stopped = false;
            this.status = '';
            this.messages.push({ role: 'user', content: message });
            this.messages.push({ role: 'model', content: '' });
            const reply = this.messages[this.messages.length - 1]; // read back through Alpine's proxy so updates redraw
            this.scrollDown();
            this.controller = new environment.AbortController();
            let finished = false;
            try {
                const response = await environment.fetch(config.streamUrl, {
                    method: 'POST',
                    headers: { Accept: 'text/event-stream', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf(), 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ message, page: config.page ?? null }),
                    signal: this.controller.signal,
                });
                if (!response.ok || !response.body) throw new Error(`HTTP ${response.status}`);
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';
                while (!finished) {
                    const { done, value } = await reader.read();
                    if (done) break;
                    buffer += decoder.decode(value, { stream: true });
                    let at;
                    while ((at = buffer.indexOf('\n\n')) !== -1) {
                        const block = buffer.slice(0, at);
                        buffer = buffer.slice(at + 2);
                        const isError = /^event: error/m.test(block);
                        const line = block.split('\n').find(l => l.startsWith('data: '));
                        if (!line) continue;
                        const payload = line.slice(6);
                        if (payload === '[DONE]') { finished = true; break; }
                        let event;
                        try { event = JSON.parse(payload); } catch { continue; }
                        if (isError) { this.error = event.message ?? ''; continue; }
                        if (event.status) this.status = event.status;
                        if (event.chunk) { this.waiting = false; reply.content += event.chunk; this.scrollDown(); }
                        if (event.final !== undefined) {
                            reply.content = event.final;
                            reply.id = event.id;
                            this.actions = { ...this.actions, ...(event.actions ?? {}) };
                            finished = true;
                        }
                    }
                }
            } catch (error) {
                if (!this.stopped) this.error = config.errorText ?? 'Something went wrong. Please try again.';
            } finally {
                this.busy = false;
                this.waiting = false;
                this.status = '';
                this.controller = null;
                if (!reply.content.trim()) {
                    this.messages = this.messages.filter(item => item !== reply);
                    if (this.stopped) this.input = message;
                }
                this.scrollDown();
            }
        },

        stop() {
            this.stopped = true;
            this.controller?.abort();
        },

        async clear() {
            if (this.busy) return;
            try { await this.request(config.clearUrl, {}); } catch { return; }
            this.messages = [];
            this.actions = {};
            this.load();
        },

        async decide(id, approve) {
            const url = (approve ? config.approveUrl : config.rejectUrl).replace('__ID__', id);
            this.actions = { ...this.actions, [id]: { ...this.actions[id], busy: true } };
            try {
                const { data } = await this.request(url, {});
                this.actions = { ...this.actions, [id]: { ...(data.action ?? this.actions[id]), busy: false, message: data.message ?? data.action?.message } };
            } catch {
                this.actions = { ...this.actions, [id]: { ...this.actions[id], busy: false, message: config.errorText } };
            }
        },

        onKey(event) {
            if (event.key === 'Enter' && !event.shiftKey && !event.isComposing) { event.preventDefault(); this.send(); }
        },
    };
}

if (typeof window !== 'undefined') window.adminNara = adminNara;

@if(auth()->check() && !auth()->user()->isAdmin())
<div class="kawankerja-widget" x-data="kawankerjaChat()" x-cloak>
    <!-- Floating Launcher Pill -->
    <button type="button"
            class="kawankerja-launcher"
            @click="open = !open; if(open) scrollToBottom()"
            aria-label="Open HireMate AI Assistant"
            :aria-expanded="open.toString()">
        <div class="kawankerja-launcher-avatar-wrap">
            <div class="kawankerja-launcher-avatar">
                <img src="{{ asset('images/kawankerja.png') }}" alt="HireMate Avatar">
            </div>
            <span class="kawankerja-online-dot"></span>
        </div>
        <div class="kawankerja-launcher-label">
            <strong>HireMate</strong>
            <small>AI Career Coach</small>
        </div>
    </button>

    <!-- Chat Pop-up Window -->
    <div class="kawankerja-window"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         @click.outside="open = false">

        <!-- Header -->
        <header class="kawankerja-header">
            <div class="kawankerja-header-info">
                <div class="kawankerja-header-avatar-wrap">
                    <div class="kawankerja-header-avatar">
                        <img src="{{ asset('images/kawankerja.png') }}" alt="HireMate Avatar">
                    </div>
                    <span class="kawankerja-status-indicator"></span>
                </div>
                <div class="kawankerja-header-text">
                    <h3>HireMate</h3>
                    <p>TraKerja Career Assistant</p>
                </div>
            </div>
            <div class="kawankerja-header-actions">
                <button type="button"
                        class="kawankerja-header-btn"
                        @click="clearChat()"
                        title="Clear chat history">
                    <i class="ph ph-trash" aria-hidden="true"></i>
                </button>
                <button type="button"
                        class="kawankerja-header-btn"
                        @click="open = false"
                        title="Close">
                    <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            </div>
        </header>

        <!-- Message Body -->
        <div class="kawankerja-messages" x-ref="msgContainer">
            <div class="kawankerja-welcome" x-show="messages.length === 0">
                <div class="kawankerja-welcome-intro">
                    <h4 class="kawankerja-welcome-heading">Hi, {{ auth()->user()->name }}</h4>
                    <p class="kawankerja-welcome-sub">Choose a career prep topic below or ask anything directly.</p>
                </div>

                <div class="kawankerja-chips">
                    <button type="button" class="kawankerja-chip" @click="input = 'Help me practice an interview for my latest application'; send()">
                        <span>Practice latest interview</span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'What is the status of my application pipeline and what are the best next steps?'; send()">
                        <span>Check application pipeline</span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'What TraKerja features can help accelerate my job search?'; send()">
                        <span>TraKerja workflow & features</span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Please draft a polite post-interview follow-up message to HR'; send()">
                        <span>Draft follow-up message to HR</span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- Chat History -->
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'kawankerja-row kawankerja-row--user' : 'kawankerja-row kawankerja-row--bot'">
                    <template x-if="msg.role !== 'user'">
                        <div class="kawankerja-row-avatar">
                            <img src="{{ asset('images/kawankerja.png') }}" alt="HireMate">
                        </div>
                    </template>
                    <div :class="msg.role === 'user' ? 'kawankerja-bubble kawankerja-bubble--user' : 'kawankerja-bubble kawankerja-bubble--bot'">
                        <div class="kawankerja-bubble-content" x-html="formatText(msg.content)"></div>
                        <div class="kawankerja-bubble-footer" x-show="msg.role !== 'user' && msg.content && msg.content.trim().length > 0">
                            <button type="button"
                                    class="kawankerja-copy-btn"
                                    @click="copyMessage(msg.content, $el)"
                                    title="Copy message">
                                <i class="ph ph-copy" aria-hidden="true"></i>
                                <span>Copy</span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div class="kawankerja-row kawankerja-row--bot" x-show="loading">
                <div class="kawankerja-row-avatar">
                    <img src="{{ asset('images/kawankerja.png') }}" alt="HireMate">
                </div>
                <div class="kawankerja-bubble kawankerja-bubble--bot kawankerja-bubble--typing">
                    <div class="kawankerja-typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Bar -->
        <footer class="kawankerja-footer">
            <div class="kawankerja-input-row">
                <textarea class="kawankerja-textarea"
                          rows="1"
                          placeholder="Ask HireMate anything..."
                          x-model="input"
                          @keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); send(); }"
                          :disabled="loading"></textarea>
                <button type="button"
                        class="kawankerja-send-btn"
                        @click="send()"
                        :disabled="!input.trim() || loading"
                        aria-label="Send message">
                    <i class="ph-fill ph-paper-plane-tilt" aria-hidden="true"></i>
                </button>
            </div>
            <div class="kawankerja-input-hint">Enter to send · Shift+Enter for new line</div>
        </footer>
    </div>
</div>

<script>
    function kawankerjaChat() {
        return {
            open: false,
            loading: false,
            input: '',
            messages: [],
            streamUrl: '{{ route('kawankerja.stream') }}',
            chatUrl: '{{ route('kawankerja.chat') }}',
            historyUrl: '{{ route('kawankerja.history') }}',
            clearUrl: '{{ route('kawankerja.clear') }}',
            csrfToken: '{{ csrf_token() }}',
            actionRoutes: {
                'tracker': { url: '{{ route('tracker') }}', icon: 'ph-kanban' },
                'interviews': { url: '{{ route('interviews') }}', icon: 'ph-calendar' },
                'ai-studio': { url: '{{ route('ai-studio.index') }}', icon: 'ph-sparkle' },
                'cv-builder': { url: '{{ route('cv.builder') }}', icon: 'ph-file-text' },
                'explore': { url: '{{ route('jobs.explore') }}', icon: 'ph-compass' },
                'profile': { url: '{{ route('profile.edit') }}', icon: 'ph-user' },
            },

            async init() {
                const openHandler = (e) => {
                    this.open = true;
                    if (e.detail?.prompt) {
                        this.input = e.detail.prompt;
                        this.$nextTick(() => this.send());
                    }
                };
                window.addEventListener('open-hiremate', openHandler);
                window.addEventListener('open-kawankerja', openHandler);

                try {
                    const res = await fetch(this.historyUrl, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.messages = data.history || [];
                    }
                } catch (_) {}
            },

            async send() {
                const text = this.input.trim();
                if (!text || this.loading) return;

                this.input = '';
                this.messages.push({ role: 'user', content: text });
                this.loading = true;
                this.scrollToBottom();

                let assistantMsg = null;
                let streamedSuccessfully = false;

                try {
                    const response = await fetch(this.streamUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'text/event-stream',
                        },
                        body: JSON.stringify({ message: text })
                    });

                    if (response.ok && response.body && response.headers.get('content-type')?.includes('text/event-stream')) {
                        const reader = response.body.getReader();
                        const decoder = new TextDecoder('utf-8');
                        let buffer = '';

                        while (true) {
                            const { done, value } = await reader.read();
                            if (done) break;

                            buffer += decoder.decode(value, { stream: true });
                            const lines = buffer.split('\n');
                            buffer = lines.pop();

                            for (const line of lines) {
                                const trimmed = line.trim();
                                if (!trimmed || !trimmed.startsWith('data: ')) continue;
                                const dataStr = trimmed.substring(6);
                                if (dataStr === '[DONE]') {
                                    streamedSuccessfully = true;
                                    break;
                                }
                                try {
                                    const parsed = JSON.parse(dataStr);
                                    if (parsed.chunk) {
                                        if (!assistantMsg) {
                                            assistantMsg = { role: 'model', content: '' };
                                            this.messages.push(assistantMsg);
                                            this.loading = false;
                                        }
                                        assistantMsg.content += parsed.chunk;
                                        streamedSuccessfully = true;
                                        this.scrollToBottom();
                                    }
                                } catch (_) {}
                            }
                        }
                    }
                } catch (e) {
                    console.warn('HireMate stream encountered an issue, checking fallback...', e);
                }

                // If streaming produced no content or failed, fallback to synchronous chatUrl
                if (!streamedSuccessfully || !assistantMsg || !assistantMsg.content.trim()) {
                    try {
                        const res = await fetch(this.chatUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ message: text })
                        });
                        const data = await res.json();
                        const replyText = (data.success && data.reply) ? data.reply : (data.reply || 'Maaf, layanan HireMate sedang tidak tersedia saat ini.');
                        if (!assistantMsg) {
                            assistantMsg = { role: 'model', content: replyText };
                            this.messages.push(assistantMsg);
                        } else {
                            assistantMsg.content = replyText;
                        }
                    } catch (e) {
                        const errMsg = 'Maaf, layanan HireMate sedang tidak tersedia saat ini.';
                        if (!assistantMsg) {
                            assistantMsg = { role: 'model', content: errMsg };
                            this.messages.push(assistantMsg);
                        } else {
                            assistantMsg.content = errMsg;
                        }
                    }
                }

                this.loading = false;
                this.scrollToBottom();
            },

            async clearChat() {
                if (!confirm('Clear chat history with HireMate?')) return;
                try {
                    await fetch(this.clearUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                        }
                    });
                    this.messages = [];
                } catch (_) {}
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const el = this.$refs.msgContainer;
                    if (el) el.scrollTop = el.scrollHeight;
                });
            },

            cleanTextForCopy(raw) {
                if (!raw) return '';
                return raw
                    .replace(/\[action:[^\]]+\]/g, '')
                    .replace(/\[\/?star_scorecard\]/g, '')
                    .trim();
            },

            async copyMessage(raw, btnEl) {
                const text = this.cleanTextForCopy(raw);
                if (!text) return;
                try {
                    await navigator.clipboard.writeText(text);
                    const icon = btnEl.querySelector('i');
                    const label = btnEl.querySelector('span');
                    if (icon) icon.className = 'ph-bold ph-check text-emerald-600';
                    if (label) label.textContent = 'Copied!';
                    btnEl.classList.add('is-copied');
                    setTimeout(() => {
                        if (icon) icon.className = 'ph ph-copy';
                        if (label) label.textContent = 'Copy';
                        btnEl.classList.remove('is-copied');
                    }, 2000);
                } catch (_) {}
            },

            formatText(raw) {
                if (!raw) return '';
                let text = raw.trim();

                // Strip dangling markdown asterisks on their own lines
                text = text.replace(/^\s*\*+\s*$/gm, '');

                // Escape HTML
                let esc = text
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');

                // Code blocks: ``` ... ```
                esc = esc.replace(/```(?:[a-zA-Z0-9_-]+)?\n?([\s\S]*?)```/g, function(match, code) {
                    return '<pre class="kawankerja-code"><code>' + code.trim() + '</code></pre>';
                });

                // Inline code: `code`
                esc = esc.replace(/`([^`\n]+)`/g, '<code class="kawankerja-inline-code">$1</code>');

                // Blockquotes: consecutive lines starting with &gt;
                esc = esc.replace(/(?:^|\n)((?:[ \t]*&gt;[^\n]*(?:\n|$))+)/g, function(match, bqBlock) {
                    const rawLines = bqBlock.split('\n');
                    const lines = rawLines.filter((l, idx) => !(idx === rawLines.length - 1 && l === ''));
                    const cleanLines = lines.map(line => line.replace(/^[ \t]*&gt;[ \t]?/, ''));
                    const content = cleanLines.join('\n').trim();
                    if (!content) return '';
                    return '\n<blockquote class="kawankerja-quote">' + content + '</blockquote>\n';
                });

                // Headers: ### Title or ## Title
                esc = esc.replace(/(?:^|\n)###[ \t]+([^\n]+)/g, '<h4 class="kawankerja-h4">$1</h4>');
                esc = esc.replace(/(?:^|\n)##[ \t]+([^\n]+)/g, '<h3 class="kawankerja-h3">$1</h3>');

                // Bold: **content**
                esc = esc.replace(/\*\*(.+?)\*\*/gs, '<strong>$1</strong>');

                // Italic: *content*
                esc = esc.replace(/(?<!\*)\*([^\*\n]+?)\*(?!\*)/g, '<em>$1</em>');

                // Bullet points: - or • or *
                esc = esc.replace(/(?:^|\n)[-•*][ \t]+([^\n]+)/g, '<div class="kawankerja-bullet"><span class="kawankerja-bullet-dot">•</span><span class="kawankerja-bullet-text">$1</span></div>');

                // Numbered lists: 1. Item
                esc = esc.replace(/(?:^|\n)(\d+)\.[ \t]+([^\n]+)/g, '<div class="kawankerja-bullet"><span class="kawankerja-bullet-num">$1.</span><span class="kawankerja-bullet-text">$2</span></div>');

                // Action cards: [action:key|Title|Subtitle]
                esc = esc.replace(/\[action:([a-z-]+)\|([^|\]]+)\|?([^\]]*)\]/g, (match, key, title, sub) => {
                    const routeInfo = this.actionRoutes[key] || { url: '#', icon: 'ph-arrow-up-right' };
                    const subText = sub ? sub.trim() : '';
                    const subHtml = subText ? `<small class="kawankerja-action-sub">${subText}</small>` : '';
                    return `\n<div class="kawankerja-action-wrap"><a href="${routeInfo.url}" class="kawankerja-action-card" wire:navigate><div class="kawankerja-action-icon"><i class="ph ${routeInfo.icon}" aria-hidden="true"></i></div><div class="kawankerja-action-text"><strong class="kawankerja-action-title">${title.trim()}</strong>${subHtml}</div><i class="ph ph-arrow-up-right kawankerja-action-arrow" aria-hidden="true"></i></a></div>\n`;
                });

                // STAR Scorecard: [star_scorecard]...[/star_scorecard]
                esc = esc.replace(/\[star_scorecard\]([\s\S]*?)\[\/star_scorecard\]/g, (match, inner) => {
                    const lines = inner.trim().split('\n');
                    let overallBadge = '';
                    let itemsHtml = '';

                    lines.forEach(line => {
                        const trimmedLine = line.trim();
                        if (!trimmedLine) return;
                        const parts = trimmedLine.split('|');
                        if (parts.length < 2) return;
                        const left = parts[0].trim();
                        const note = parts.slice(1).join('|').trim();
                        const colonIdx = left.indexOf(':');
                        if (colonIdx === -1) return;
                        const label = left.substring(0, colonIdx).trim();
                        const scoreStr = left.substring(colonIdx + 1).trim();
                        const numMatch = scoreStr.match(/([0-9]+(?:\.[0-9]+)?)/);
                        const scoreNum = numMatch ? parseFloat(numMatch[1]) : 7.0;
                        const pct = Math.min(100, Math.max(10, Math.round(scoreNum * 10)));

                        if (label.toLowerCase().includes('overall')) {
                            overallBadge = `<div class="kawankerja-star-overall"><span class="kawankerja-star-overall-pill">${scoreStr}</span><span class="kawankerja-star-overall-note">${note}</span></div>`;
                        } else {
                            const letter = label.charAt(0).toUpperCase();
                            itemsHtml += `
                                <div class="kawankerja-star-item">
                                    <div class="kawankerja-star-item-head">
                                        <div class="kawankerja-star-item-label">
                                            <span class="kawankerja-star-letter">${letter}</span>
                                            <strong>${label}</strong>
                                        </div>
                                        <span class="kawankerja-star-score">${scoreStr}</span>
                                    </div>
                                    <div class="kawankerja-star-track">
                                        <div class="kawankerja-star-fill" style="width: ${pct}%"></div>
                                    </div>
                                    <div class="kawankerja-star-note">${note}</div>
                                </div>`;
                        }
                    });

                    return `\n<div class="kawankerja-star-card"><div class="kawankerja-star-head"><div class="kawankerja-star-title"><i class="ph ph-target" aria-hidden="true"></i> STAR Assessment</div>${overallBadge}</div><div class="kawankerja-star-items">${itemsHtml}</div></div>\n`;
                });

                // Paragraph spacing
                esc = esc.replace(/\n{2,}/g, '<div class="kawankerja-gap"></div>');
                esc = esc.replace(/\n/g, '<br>');

                // Clean up excess <br> around block containers
                esc = esc.replace(/<br>\s*(<(?:blockquote|div class="kawankerja-(?:gap|bullet|action-wrap|star-card)|h[34]|pre))/g, '$1');
                esc = esc.replace(/(<\/(?:blockquote|h[34]|pre)>|<div class="kawankerja-gap"><\/div>|<\/div>)\s*<br>/g, '$1');

                return esc.trim();
            }
        };
    }

    if (window.Alpine) {
        window.Alpine.data('kawankerjaChat', kawankerjaChat);
    } else {
        document.addEventListener('alpine:init', () => {
            window.Alpine.data('kawankerjaChat', kawankerjaChat);
        });
    }
</script>
@endif

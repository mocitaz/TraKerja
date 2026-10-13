@if(auth()->check() && !auth()->user()->isAdmin())
<div class="kawankerja-widget" x-data="kawankerjaChat()" x-cloak>
    <!-- Floating Launcher Pill -->
    <button type="button"
            class="kawankerja-launcher"
            @click="open = !open; if(open) scrollToBottom()"
            aria-label="Buka KawanKerja AI Assistant"
            :aria-expanded="open.toString()">
        <div class="kawankerja-launcher-avatar-wrap">
            <div class="kawankerja-launcher-avatar">
                <img src="{{ asset('images/kawankerja.png') }}" alt="KawanKerja Avatar">
            </div>
            <span class="kawankerja-online-dot"></span>
        </div>
        <div class="kawankerja-launcher-label">
            <strong>KawanKerja</strong>
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
                        <img src="{{ asset('images/kawankerja.png') }}" alt="KawanKerja Avatar">
                    </div>
                    <span class="kawankerja-status-indicator"></span>
                </div>
                <div class="kawankerja-header-text">
                    <h3>KawanKerja</h3>
                    <p>Asisten Karier TraKerja</p>
                </div>
            </div>
            <div class="kawankerja-header-actions">
                <button type="button"
                        class="kawankerja-header-btn"
                        @click="clearChat()"
                        title="Hapus percakapan">
                    <i class="ph ph-trash" aria-hidden="true"></i>
                </button>
                <button type="button"
                        class="kawankerja-header-btn"
                        @click="open = false"
                        title="Tutup">
                    <i class="ph ph-x" aria-hidden="true"></i>
                </button>
            </div>
        </header>

        <!-- Message Body -->
        <div class="kawankerja-messages" x-ref="msgContainer">
            <div class="kawankerja-welcome" x-show="messages.length === 0">
                <div class="kawankerja-welcome-intro">
                    <h4 class="kawankerja-welcome-heading">Halo, {{ auth()->user()->name }}</h4>
                    <p class="kawankerja-welcome-sub">Pilih topik persiapan karier atau ketik pertanyaan langsung di bawah.</p>
                </div>

                <div class="kawankerja-chips">
                    <button type="button" class="kawankerja-chip" @click="input = 'Bantu simulasi interview untuk lamaran terbaru saya'; send()">
                        <span>Simulasi interview lamaran terbaru</span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Bagaimana status pipeline lamaran saya saat ini dan apa langkah terbaik berikutnya?'; send()">
                        <span>Cek status pipeline lamaran</span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Fitur apa saja di TraKerja yang bisa bantu persiapan kerja saya lebih cepat?'; send()">
                        <span>Panduan alur dan fitur TraKerja</span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Tolong buatkan draf pesan follow-up sopan ke HR setelah interview'; send()">
                        <span>Draf pesan follow-up ke HR</span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- Chat History -->
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'kawankerja-row kawankerja-row--user' : 'kawankerja-row kawankerja-row--bot'">
                    <template x-if="msg.role !== 'user'">
                        <div class="kawankerja-row-avatar">
                            <img src="{{ asset('images/kawankerja.png') }}" alt="KawanKerja">
                        </div>
                    </template>
                    <div :class="msg.role === 'user' ? 'kawankerja-bubble kawankerja-bubble--user' : 'kawankerja-bubble kawankerja-bubble--bot'">
                        <div class="kawankerja-bubble-content" x-html="formatText(msg.content)"></div>
                    </div>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div class="kawankerja-row kawankerja-row--bot" x-show="loading">
                <div class="kawankerja-row-avatar">
                    <img src="{{ asset('images/kawankerja.png') }}" alt="KawanKerja">
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
                          placeholder="Ketik pesan untuk KawanKerja..."
                          x-model="input"
                          @keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); send(); }"
                          :disabled="loading"></textarea>
                <button type="button"
                        class="kawankerja-send-btn"
                        @click="send()"
                        :disabled="!input.trim() || loading"
                        aria-label="Kirim pesan">
                    <i class="ph-fill ph-paper-plane-tilt" aria-hidden="true"></i>
                </button>
            </div>
            <div class="kawankerja-input-hint">Enter untuk kirim · Shift+Enter baris baru</div>
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
            chatUrl: '{{ route('kawankerja.chat') }}',
            historyUrl: '{{ route('kawankerja.history') }}',
            clearUrl: '{{ route('kawankerja.clear') }}',
            csrfToken: '{{ csrf_token() }}',

            async init() {
                window.addEventListener('open-kawankerja', (e) => {
                    this.open = true;
                    if (e.detail?.prompt) {
                        this.input = e.detail.prompt;
                        this.$nextTick(() => this.send());
                    }
                });

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
                    if (data.success && data.reply) {
                        this.messages.push({ role: 'model', content: data.reply });
                    } else {
                        this.messages.push({ role: 'model', content: data.reply || 'Maaf, terjadi kesalahan saat menghubungi server.' });
                    }
                } catch (e) {
                    this.messages.push({ role: 'model', content: 'Gagal terhubung ke KawanKerja. Periksa koneksi internet Anda.' });
                } finally {
                    this.loading = false;
                    this.scrollToBottom();
                }
            },

            async clearChat() {
                if (!confirm('Hapus riwayat obrolan dengan KawanKerja?')) return;
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

                // Paragraph spacing
                esc = esc.replace(/\n{2,}/g, '<div class="kawankerja-gap"></div>');
                esc = esc.replace(/\n/g, '<br>');

                // Clean up excess <br> around block containers
                esc = esc.replace(/<br>\s*(<(?:blockquote|div class="kawankerja-(?:gap|bullet)|h[34]|pre))/g, '$1');
                esc = esc.replace(/(<\/(?:blockquote|h[34]|pre)>|<div class="kawankerja-gap"><\/div>)\s*<br>/g, '$1');

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

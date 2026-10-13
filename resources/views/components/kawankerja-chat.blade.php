@if(auth()->check() && !auth()->user()->isAdmin())
<div class="kawankerja-widget" x-data="kawankerjaChat()" x-cloak>
    <!-- Floating Launcher Pill -->
    <button type="button"
            class="kawankerja-launcher"
            @click="open = !open; if(open) scrollToBottom()"
            aria-label="Buka KawanKerja AI Assistant"
            :aria-expanded="open.toString()">
        <div class="kawankerja-launcher-avatar">
            <img src="{{ asset('images/kawankerja.png') }}" alt="KawanKerja Avatar">
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
                <div class="kawankerja-header-avatar">
                    <img src="{{ asset('images/kawankerja.png') }}" alt="KawanKerja Avatar">
                    <span class="kawankerja-status-indicator"></span>
                </div>
                <div class="kawankerja-header-text">
                    <div class="kawankerja-header-title">
                        <h3>KawanKerja</h3>
                        <span class="kawankerja-ai-pill">AI Coach</span>
                    </div>
                    <p>Siap simulasi interview & karier</p>
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
                <span class="kawankerja-welcome-badge">
                    <i class="ph-fill ph-sparkle" aria-hidden="true"></i>
                    <span>Executive AI Career Companion</span>
                </span>
                <h4 class="kawankerja-welcome-heading">Halo, {{ auth()->user()->name }}! 👋</h4>
                <p>Butuh latihan simulasi interview langsung dari data lamaranmu, review jawaban STAR, atau draft pesan HR?</p>

                <div class="kawankerja-chips">
                    <button type="button" class="kawankerja-chip" @click="input = 'Bantu simulasi interview untuk lamaran terbaru saya dong'; send()">
                        <span class="kawankerja-chip-icon">🎯</span>
                        <span>Simulasi interview lamaran terbaru</span>
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Bagaimana cara menjawab pertanyaan interview tentang kelemahan diri?'; send()">
                        <span class="kawankerja-chip-icon">⭐</span>
                        <span>Cara jawab pertanyaan kelemahan diri</span>
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Tolong buatkan draf pesan WhatsApp follow-up sopan ke HR setelah interview'; send()">
                        <span class="kawankerja-chip-icon">✉️</span>
                        <span>Draf follow-up HR pasca interview</span>
                    </button>
                    <button type="button" class="kawankerja-chip" @click="input = 'Tips negosiasi gaji saat tahap offering agar tetap profesional'; send()">
                        <span class="kawankerja-chip-icon">💡</span>
                        <span>Tips negosiasi gaji saat offering</span>
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

                // Strip dangling markdown asterisks across line breaks (e.g. **\n> or > ... \n*)
                text = text.replace(/^\s*\*+\s*$/gm, '');

                let esc = text
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');

                // Bold: **content**
                esc = esc.replace(/\*\*(.+?)\*\*/gs, '<strong>$1</strong>');

                // Simulation / Target Header: 🎯 **...** or 🎯 ...
                esc = esc.replace(/(?:^|\n)🎯\s*(?:<strong>)?(.*?)(?:<\/strong>)?(?=\n|$)/g, function(match, title) {
                    return '<div class="kawankerja-target-banner">' +
                        '<span class="kawankerja-target-icon">🎯</span>' +
                        '<span class="kawankerja-target-title">' + title.trim() + '</span>' +
                    '</div>';
                });

                // Signature Section Badges: 💡, ✅, ⚠️, 💎
                esc = esc.replace(/(?:^|\n)(💡|✅|⚠️|💎)\s*(?:<strong>)?(.*?)(?:<\/strong>)?(?=\n|$)/g, function(match, icon, title) {
                    let typeClass = 'default';
                    if (icon === '💡') typeClass = 'amber';
                    if (icon === '✅') typeClass = 'emerald';
                    if (icon === '⚠️') typeClass = 'orange';
                    if (icon === '💎') typeClass = 'indigo';

                    return '<div class="kawankerja-section-pill kawankerja-section-pill--' + typeClass + '">' +
                        '<span class="kawankerja-section-pill__icon">' + icon + '</span>' +
                        '<span class="kawankerja-section-pill__title">' + title.trim() + '</span>' +
                    '</div>';
                });

                // Interview Question / Simulation Blockquote: lines starting with &gt;
                esc = esc.replace(/(?:^|\n)&gt;\s*([\s\S]*?)(?=(?:\n\s*\n|\n[^\s&]|$))/g, function(match, quote) {
                    let clean = quote.trim();
                    // Strip leading/trailing asterisks or underscores that could italicize the whole box
                    clean = clean.replace(/^[\*_]+\s*/, '').replace(/\s*[\*_]+$/, '');
                    // Strip enclosing quotes to prevent awkward duplicate quotation marks
                    clean = clean.replace(/^["“”]\s*/, '').replace(/\s*["“”]$/, '');

                    return '<div class="kawankerja-question-card">' +
                        '<div class="kawankerja-question-card__header">' +
                            '<span class="kawankerja-question-card__badge">' +
                                '<i class="ph-fill ph-chat-circle-dots"></i>' +
                                '<span>Pertanyaan Wawancara</span>' +
                            '</span>' +
                        '</div>' +
                        '<div class="kawankerja-question-card__body">“' + clean + '”</div>' +
                    '</div>';
                });

                // Bullet points (• or -)
                esc = esc.replace(/(?:^|\n)[-•]\s+(.*?)(?=\n|$)/g, function(match, bulletText) {
                    return '<div class="kawankerja-bullet">' +
                        '<span class="kawankerja-bullet-dot"></span>' +
                        '<span class="kawankerja-bullet-text">' + bulletText.trim() + '</span>' +
                    '</div>';
                });

                // Italic: *content*
                esc = esc.replace(/(?<!\*)\*([^\*\n]+?)\*(?!\*)/g, '<em>$1</em>');

                // Cleanup accidental extra breaks next to custom card blocks
                esc = esc.replace(/<\/div>\s*<br\s*\/?>/gi, '</div>');
                esc = esc.replace(/<br\s*\/?>\s*<div/gi, '<div');

                // Paragraph spacing
                esc = esc.replace(/\n{2,}/g, '<div class="kawankerja-spacer"></div>');
                esc = esc.replace(/\n/g, '<br>');

                return esc;
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

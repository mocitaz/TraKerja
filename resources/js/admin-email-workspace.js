import { t } from './i18n.js';
window.emailBlastWorkspace = function (routes) {
    const templateLabels = {
        brand_transformation: 'Suasana Baru TraKerja (Teman Perjuangan Karier)',
        product_update: 'Product update',
        new_vibe: 'New workspace',
        ai_analyzer: 'AI analyzer',
        ai_photo: 'Photo studio',
        follow_up_feature: 'Follow-up feature',
        chrome_extension: 'Chrome extension',
        maintenance_completed: 'Maintenance completed',
        welcome: 'Welcome',
        verification: 'Verify email',
        verification_reminder: 'Verification reminder',
        job_reminder: 'Application reminder',
        monthly_motivation: 'Monthly motivation',
        hiring_season: 'Hiring season',
        re_engagement: 'Re-engagement',
        idul_adha: 'Idul Adha',
        waisak: 'Waisak',
        pancasila: 'Pancasila Day',
        tahun_baru_islam: 'Islamic New Year',
        kemerdekaan_ri: 'Independence Day',
        maulid_nabi: 'Maulid Nabi',
        sumpah_pemuda: 'Youth Pledge Day',
        pahlawan: 'Heroes Day',
        guru_nasional: 'Teachers Day',
        hari_ibu: 'Mother’s Day',
        natal: 'Christmas',
        custom: 'Custom email',
    };

    return {
        audience: 'all', emailType: 'brand_transformation', banner: '', custom: { subject: '', content: '', buttonText: '', buttonUrl: '' },
        modal: null, device: 'desktop', loading: false, previewReady: false, error: '', users: [], payload: null,
        sending: false, paused: false, cancelled: false, processed: 0, successCount: 0, failures: [],
        currentUser: null, recentLogs: [], elapsedSeconds: 0, timerInterval: null,
        deliveryStatus: '', logError: '',
        audienceLabel() { return { all: 'All members', verified: 'Verified members', unverified: 'Unverified members', premium: 'Active premium members', free: 'Free members', new: 'New members' }[this.audience]; },
        templateLabel() { return templateLabels[this.emailType] || this.$refs.composer?.querySelector('[name=email_type]')?.selectedOptions[0]?.textContent || this.emailType; },
        templateDescription() {
            const descriptions = {
                brand_transformation: t("Sapaan hangat dan pengenalan suasana baru TraKerja yang lebih ramah, adem, dan nemenin cari kerja."),
                product_update: t("Rangkuman pembaruan fitur platform terbaru TraKerja."),
                new_vibe: t("Pemberitahuan pembaruan workspace dan papan pelacakan lamaran."),
                ai_analyzer: 'Pengumuman fasilitas uji coba gratis AI ATS Resume Scanner.',
                ai_photo: 'Pemberitahuan studio foto profil AI profesional.',
                follow_up_feature: 'Pengingat fitur tindak lanjut dan pengingat jadwal wawancara.',
                chrome_extension: 'Pengenalan Chrome Extension untuk pengisian otomatis lamaran kerja.',
                maintenance_completed: 'Pemberitahuan pemeliharaan sistem telah selesai dilaksanakan.',
                welcome: 'Email sambutan pengguna baru lengkap dengan panduan langkah awal.',
                verification: 'Email verifikasi akun untuk pengguna yang belum memverifikasi email.',
                verification_reminder: 'Pengingat verifikasi email agar akun dapat digunakan secara penuh.',
                job_reminder: 'Pengingat berkala untuk mencatat lamaran kerja di Job Tracker.',
                monthly_motivation: 'Pesan penyemangat berkala dalam proses pencarian kerja.',
                hiring_season: 'Informasi periode rekrutmen perusahaan dan panduan persiapan.',
                re_engagement: 'Ajakan kembali aktif untuk pengguna yang lama tidak membuka workspace.',
                idul_adha: 'Ucapan Hari Raya Idul Adha.',
                waisak: 'Ucapan Hari Raya Waisak.',
                pancasila: 'Peringatan Hari Lahir Pancasila.',
                tahun_baru_islam: 'Ucapan Tahun Baru Islam.',
                kemerdekaan_ri: 'Peringatan Hari Kemerdekaan Republik Indonesia.',
                maulid_nabi: 'Peringatan Maulid Nabi Muhammad SAW.',
                sumpah_pemuda: 'Peringatan Hari Sumpah Pemuda.',
                pahlawan: 'Peringatan Hari Pahlawan Nasional.',
                guru_nasional: 'Peringatan Hari Guru Nasional.',
                hari_ibu: 'Peringatan Hari Ibu.',
                natal: 'Ucapan Hari Raya Natal.',
                custom: 'Tulis pesan khusus secara leluasa dengan opsi tombol tindakan.',
            };
            return descriptions[this.emailType] || 'Pesan terstruktur siap kirim untuk anggota komunitas TraKerja.';
        },
        progressPercent() {
            if (!this.users.length) return 0;
            return Math.min(100, Math.round((this.processed / this.users.length) * 100));
        },
        etaText() {
            if (!this.sending) return 'Selesai';
            if (this.paused) return 'Dijeda';
            if (this.processed < 2) return 'Menghitung waktu…';
            const remaining = this.users.length - this.processed;
            const rate = this.processed / Math.max(1, this.elapsedSeconds);
            const sec = Math.round(remaining / Math.max(0.1, rate));
            if (sec < 60) return `~${sec} dtk lagi`;
            const min = Math.floor(sec / 60);
            const remSec = sec % 60;
            return `~${min}m ${remSec}s lagi`;
        },
        togglePause() {
            this.paused = !this.paused;
        },
        cancelSend() {
            this.cancelled = true;
        },
        async request(url, payload, html = false, timeout = 30000) {
            const controller = new AbortController();
            const timer = setTimeout(() => controller.abort(), timeout);
            try {
                const response = await fetch(url, { method: 'POST', signal: controller.signal, headers: { 'Content-Type': 'application/json', 'Accept': html ? 'text/html' : 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify(payload) });
                if (!response.ok) {
                    let message = t("The request could not be completed. Please check the form and try again.");
                    try { const data = await response.json(); message = Object.values(data.errors || {}).flat()[0] || data.message || data.error || message; } catch {}
                    throw new Error(message);
                }
                return html ? await response.text() : await response.json();
            } finally { clearTimeout(timer); }
        },
        async preview() {
            if (this.loading || this.sending || !this.$refs.composer.reportValidity()) return;
            this.error = '';
            if (this.emailType === 'custom' && Boolean(this.custom.buttonText.trim()) !== Boolean(this.custom.buttonUrl.trim())) {
                this.error = t("Add both button text and a destination URL, or leave both empty."); return;
            }
            this.loading = true; this.previewReady = false; this.modal = 'preview'; this.users = [];
            this.payload = { email_type: this.emailType, target_user: this.audience, banner: this.banner || null, custom_subject: this.custom.subject, custom_content: this.custom.content, custom_button_text: this.custom.buttonText || null, custom_button_url: this.custom.buttonUrl || null };
            try {
                const [html, audience] = await Promise.all([this.request(routes.previewUrl, this.payload, true), this.request(routes.initUrl, this.payload)]);
                this.$refs.previewFrame.srcdoc = html;
                this.users = audience.users;
                this.previewReady = true;
            } catch (error) { this.error = error.name === t("AbortError") ? 'Preview timed out. Close this dialog and try again.' : error.message; }
            finally { this.loading = false; }
        },
        async send() {
            if (this.sending || this.modal !== 'confirm' || !this.previewReady || !this.users.length) return;
            this.sending = true; this.modal = 'progress'; this.error = ''; this.logError = '';
            this.processed = 0; this.successCount = 0; this.failures = [];
            this.recentLogs = []; this.paused = false; this.cancelled = false;
            this.elapsedSeconds = 0;
            if (typeof setInterval === 'function') {
                this.timerInterval = setInterval(() => {
                    if (this.sending && !this.paused) this.elapsedSeconds++;
                }, 1000);
            }
            for (const user of this.users) {
                if (this.cancelled) break;
                while (this.paused && !this.cancelled) {
                    await new Promise(r => setTimeout(r, 200));
                }
                if (this.cancelled) break;
                this.currentUser = user;
                this.deliveryStatus = `Sending to ${user.name} · ${user.email}`;
                let isSuccess = false;
                let errorMsg = '';
                try {
                    const result = await this.request(routes.sendUrl, { ...this.payload, user_id: user.id }, false, 60000);
                    if (!result.success) throw new Error(result.error || t('Delivery was not confirmed.'));
                    this.successCount++;
                    isSuccess = true;
                } catch (error) {
                    errorMsg = error.name === 'AbortError' || error instanceof TypeError ? 'Delivery could not be confirmed. Review before sending again.' : error.message;
                    this.failures.push({ email: user.email, name: user.name, error: errorMsg });
                }
                this.processed++;
                const nowTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                this.recentLogs.unshift({
                    name: user.name,
                    email: user.email,
                    success: isSuccess,
                    error: errorMsg,
                    time: nowTime
                });
                if (this.recentLogs.length > 8) this.recentLogs.pop();
                if (this.processed < this.users.length && !this.cancelled) {
                    await new Promise(resolve => setTimeout(resolve, 800));
                }
            }
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.currentUser = null;
            this.deliveryStatus = this.cancelled
                ? `Delivery stopped: ${this.successCount} sent · ${this.failures.length} failed · ${this.users.length - this.processed} skipped.`
                : `${this.successCount} sent · ${this.failures.length} failed or unconfirmed.`;
            try {
                await this.request(routes.logUrl, {
                    email_type: this.payload.email_type,
                    target_audience: this.payload.target_user,
                    total_target: this.users.length,
                    success_count: this.successCount,
                    failed_count: this.failures.length,
                    failed_details: this.failures
                });
            } catch {
                this.logError = t("Delivery finished, but the history could not be saved. Keep these counts before closing.");
            } finally {
                this.sending = false;
            }
        },
    };
};

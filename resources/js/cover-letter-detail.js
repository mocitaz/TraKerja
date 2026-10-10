export function coverLetterDetail(filename) {
    return {
        copying: false, copied: false, copyFailed: false, notice: '',
        async copy() {
            if (this.copying) return;
            this.copying = true;
            this.copied = false;
            this.copyFailed = false;
            this.notice = '';
            try {
                await navigator.clipboard.writeText(this.$refs.content.textContent);
                this.copied = true;
                this.notice = 'Letter copied. Ready to paste into your application.';
            } catch {
                this.copyFailed = true;
                this.notice = 'Copy is unavailable. Select the text and copy it manually.';
            } finally { this.copying = false; }
        },
        selectText() {
            const range = document.createRange();
            range.selectNodeContents(this.$refs.content);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            this.$refs.content.focus();
        },
        download() {
            const url = URL.createObjectURL(new Blob([this.$refs.content.textContent], { type: 'text/plain;charset=utf-8' }));
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            link.click();
            URL.revokeObjectURL(url);
            this.notice = 'TXT download requested.';
        },
    };
}
function register() { window.Alpine.data('coverLetterDetail', coverLetterDetail); }
if (window.Alpine) register();
else document.addEventListener('alpine:init', register, { once: true });

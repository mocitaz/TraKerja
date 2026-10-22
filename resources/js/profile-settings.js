export function profileSettings(config) {
    return {
        tabs: config.tabs, tab: config.tab, avatarChoice: config.avatar,
        dialog: config.dialog || null, record: config.record || {}, busy: false,
        photoUrl: '', photoName: '', resumeName: '', horizontal: window.innerWidth < 900,
        init() { this.$nextTick(() => this.scrollSelectedTab()); },
        scrollSelectedTab() {
            if (!this.horizontal) return;
            const button = document.getElementById('profile-tab-' + this.tab);
            if (button) button.parentElement.scrollLeft = Math.max(0, button.offsetLeft - button.parentElement.offsetLeft - 10);
        },
        selectTab(tab) {
            if (!this.tabs.includes(tab)) return;
            this.tab = tab;
            const url = new URL(window.location.href);
            if (tab === 'about') url.searchParams.delete('tab'); else url.searchParams.set('tab', tab);
            window.history.replaceState(null, '', url);
            this.$nextTick?.(() => this.scrollSelectedTab());
        },
        moveTab(direction) {
            this.selectTab(this.tabs[(this.tabs.indexOf(this.tab) + direction + this.tabs.length) % this.tabs.length]);
            this.$nextTick(() => document.getElementById('profile-tab-' + this.tab)?.focus());
        },
        openDialog(dialog, record = {}) {
            if (!['appearance','resume-upload','resume-rename','resume-delete','token-revoke','tokens-revoke','delete-account'].includes(dialog)) return;
            this.busy = false; this.record = { ...record }; this.dialog = dialog;
        },
        closeDialog() {
            if (this.busy) return;
            this.dialog = null; this.clearPhoto();
            if (this.$refs?.photoInput) this.$refs.photoInput.value = '';
        },
        clearPhoto() {
            if (this.photoUrl) URL.revokeObjectURL(this.photoUrl);
            this.photoUrl = ''; this.photoName = '';
        },
        previewPhoto(file) {
            this.clearPhoto();
            if (file) { this.photoUrl = URL.createObjectURL(file); this.photoName = file.name; }
        },
        destroy() { this.clearPhoto(); },
    };
}
if (typeof window !== 'undefined') window.profileSettings = profileSettings;

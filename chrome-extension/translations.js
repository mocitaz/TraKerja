const ExtensionLocale = (() => {
    const messages = {
  "en": {
    "Buka halaman lowongan kerja di platform yang didukung untuk menyimpan data otomatis.": "Open a job listing on a supported platform to capture its details.",
    "Contoh: Software Engineer": "e.g. Software Engineer",
    "Contract": "Contract",
    "Dealls": "Dealls",
    "Detail Lamaran": "Application details",
    "Email Address": "Email address",
    "Extension": "Extension",
    "Freelance": "Freelance",
    "Full Time": "Full Time",
    "Glints": "Glints",
    "Hello!": "Hello!",
    "Intern": "Intern",
    "JobStreet": "JobStreet",
    "Kalibrr": "Kalibrr",
    "Kota": "City",
    "Language": "Language",
    "LinkedIn": "LinkedIn",
    "Logout": "Sign out",
    "MT": "MT",
    "Masuk ke Akun": "Sign in",
    "Masuk untuk menyinkronkan lamaran": "Sign in to sync your applications.",
    "Menunggu Loker...": "Ready to capture a job",
    "Mohon maaf jika terjadi ketidaksesuaian pembacaan data lamaran. Silakan sesuaikan secara manual jika diperlukan.": "Review the captured details before saving. Some listings may need manual adjustments.",
    "Nama Perusahaan": "Company name",
    "Password": "Password",
    "Perusahaan": "Company",
    "Pilih Kota": "Choose a city",
    "Pilih Provinsi": "Choose a province",
    "Posisi Pekerjaan": "Job title",
    "Provinsi": "Province",
    "Simpan ke Kanban": "Save application",
    "Talentics": "Talentics",
    "Tipe Pekerjaan": "Employment type",
    "TraKerja": "TraKerja",
    "Versi Beta (Tahap Pengembangan)": "Beta · Review before saving",
    "Welcome back": "Welcome back",
    "email@trakerja.com": "email@trakerja.com",
    "Memproses...": "Signing in…",
    "Login gagal": "Sign-in failed.",
    "Gagal koneksi ke server.": "Could not connect. Please try again.",
    "Login": "Sign in",
    "Menyimpan...": "Saving…",
    "Mohon pilih Provinsi dan Kota terlebih dahulu.": "Choose a province and city first.",
    "Berhasil disimpan!": "Application saved.",
    "Gagal menyimpan.": "Could not save. Please try again.",
    "Koneksi error.": "Connection interrupted. Please try again.",
    "Tidak dapat mencabut token. Periksa koneksi lalu coba logout lagi.": "Could not revoke access. Check your connection and try signing out again.",
    "Hello, :name!": "Hello, :name!",
    "Detected: :location": "Detected: :location"
  },
  "id": {
    "Buka halaman lowongan kerja di platform yang didukung untuk menyimpan data otomatis.": "Buka lowongan di platform yang didukung untuk mengambil detailnya.",
    "Contoh: Software Engineer": "Contoh: Software Engineer",
    "Contract": "Kontrak",
    "Dealls": "Dealls",
    "Detail Lamaran": "Detail lamaran",
    "Email Address": "Alamat email",
    "Extension": "Ekstensi",
    "Freelance": "Lepas",
    "Full Time": "Penuh Waktu",
    "Glints": "Glints",
    "Hello!": "Halo!",
    "Intern": "Magang",
    "JobStreet": "JobStreet",
    "Kalibrr": "Kalibrr",
    "Kota": "Kota",
    "Language": "Bahasa",
    "LinkedIn": "LinkedIn",
    "Logout": "Keluar",
    "MT": "MT",
    "Masuk ke Akun": "Masuk",
    "Masuk untuk menyinkronkan lamaran": "Masuk untuk menyinkronkan lamaran kamu.",
    "Menunggu Loker...": "Siap mengambil lowongan",
    "Mohon maaf jika terjadi ketidaksesuaian pembacaan data lamaran. Silakan sesuaikan secara manual jika diperlukan.": "Periksa detail sebelum menyimpan. Beberapa lowongan mungkin perlu disesuaikan secara manual.",
    "Nama Perusahaan": "Nama perusahaan",
    "Password": "Kata Sandi",
    "Perusahaan": "Perusahaan",
    "Pilih Kota": "Pilih kota",
    "Pilih Provinsi": "Pilih provinsi",
    "Posisi Pekerjaan": "Posisi pekerjaan",
    "Provinsi": "Provinsi",
    "Simpan ke Kanban": "Simpan lamaran",
    "Talentics": "Talentics",
    "Tipe Pekerjaan": "Jenis pekerjaan",
    "TraKerja": "TraKerja",
    "Versi Beta (Tahap Pengembangan)": "Beta · Periksa sebelum menyimpan",
    "Welcome back": "Selamat datang kembali",
    "email@trakerja.com": "email@trakerja.com",
    "Memproses...": "Sedang masuk…",
    "Login gagal": "Belum berhasil masuk.",
    "Gagal koneksi ke server.": "Belum dapat terhubung. Coba lagi, ya.",
    "Login": "Masuk",
    "Menyimpan...": "Menyimpan…",
    "Mohon pilih Provinsi dan Kota terlebih dahulu.": "Pilih provinsi dan kota terlebih dahulu.",
    "Berhasil disimpan!": "Lamaran tersimpan. Siap dipantau.",
    "Gagal menyimpan.": "Belum berhasil disimpan. Coba lagi, ya.",
    "Koneksi error.": "Koneksi terputus. Coba lagi, ya.",
    "Tidak dapat mencabut token. Periksa koneksi lalu coba logout lagi.": "Akses belum dapat dicabut. Periksa koneksi lalu coba keluar lagi.",
    "Hello, :name!": "Halo, :name!",
    "Detected: :location": "Terdeteksi: :location"
  }
};
    let locale = 'en';
    const t = (key, values = {}) => (Object.hasOwn(messages[locale], key) ? messages[locale][key] : key).replace(/:([A-Za-z0-9_]+)/g, (match, name) => Object.hasOwn(values, name) ? String(values[name]) : match);
    function render() {
        document.documentElement.lang = locale;
        document.querySelectorAll('[data-i18n]').forEach(node => { node.textContent = t(node.dataset.i18n); });
        for (const attribute of ['title', 'placeholder']) document.querySelectorAll(`[data-i18n-${attribute}]`).forEach(node => node.setAttribute(attribute, t(node.getAttribute(`data-i18n-${attribute}`))));
        const select = document.getElementById('extension-language'); if (select) select.value = locale;
        const greeting = document.getElementById('user-greeting');
        if (greeting?.dataset.name) greeting.textContent = t('Hello, :name!', { name: greeting.dataset.name });
    }
    const ready = chrome.storage.local.get('language').then(data => { if (['en','id'].includes(data.language)) locale = data.language; });
    document.addEventListener('DOMContentLoaded', async () => {
        await ready; render();
        document.getElementById('extension-language').addEventListener('change', async event => {
            if (!['en','id'].includes(event.target.value)) return;
            locale = event.target.value; await chrome.storage.local.set({ language: locale }); render();
        });
    });
    return { t, render, ready, get locale() { return locale; } };
})();

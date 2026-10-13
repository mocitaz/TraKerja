# Spec: TraKerja Gemini-Inspired Dark Mode Architecture

## 1. Overview & Vision
Menerapkan sistem Dark Mode di seluruh aplikasi TraKerja dengan estetika premium terinspirasi dari antarmuka Google Gemini. Menghindari hitam pekat datar (`#000000`) dan menggantikannya dengan kanvas *Deep Obsidian* (`#131314`), permukaan kartu matte solid bertingkat (`#1e1f20` dan `#282a2c`), aksen border kaca ultra-halus, dan pendaran latar belakang *Subtle Aurora Glow* bernuansa biru malam dan ungu TraKerja.

## 2. Token Warna & Palet Desain
| Token Semantik | Nilai Hex / RGBA | Peruntukan |
| :--- | :--- | :--- |
| Canvas Background | `#131314` | Latar belakang halaman utama (Obsidian murni) |
| Surface Card | `#1e1f20` | Kartu Kanban, sidebar, kontainer konten, modal dialog |
| Surface Elevated / Hover | `#282a2c` | Input text, dropdown menu, badge aktif, hover card |
| Hairline Border | `rgba(255, 255, 255, 0.07)` | Garis batas komponen (lembut dan tidak kaku) |
| Text Primary | `#f0f4f9` | Teks judul, nilai angka metrik, dan teks aktif |
| Text Secondary | `#94a3b8` | Teks keterangan, subtitle, placeholder, dan ikon sekunder |
| Brand Accent | `#a570f0` | Tombol utama, badge status, dan pendaran ungu TraKerja |

## 3. Komponen & Arsitektur Teknis

### A. Tailwind CSS Configuration
* Menambahkan opsi `darkMode: 'class'` pada `tailwind.config.js`.
* Memperluas token warna `gemini` atau semantik agar dapat digunakan langsung via utility Tailwind (`dark:bg-[#131314]`, `dark:border-white/[0.07]`, dll).

### B. Anti-FOUC & Fondasi Layout (`layouts/app.blade.php`)
* **Anti-FOUC Script**: Script inline di dalam tag `<head>` sebelum rendering CSS untuk membaca preferensi `localStorage.theme` atau `prefers-color-scheme` dan langsung menyematkan class `dark` ke tag `<html>`.
* **Ambient Aurora Glow**: Elemen fixed di bawah konten utama (`.gemini-ambient-glow`) yang memberikan pendaran radial lembut ungu-kebiruan tanpa mengganggu performa rendering atau event klik.

### C. Theme Toggle Component (`layouts/navigation.blade.php`)
* Tombol switch 1-klik (Matahari ☀️ / Bulan 🌙) di baris navigasi atas samping profil pengguna.
* Ditenagai oleh **Alpine.js**:
  * Menyimpan preferensi ke `localStorage.setItem('theme', ...)`
  * Menambah/menghapus class `dark` pada elemen `document.documentElement`
  * Mengirim custom DOM event `theme-changed` ke window untuk pembaruan komponen reaktif.

### D. Penyesuaian Komponen & Stylesheet
* Mengintegrasikan class dark mode ke layout workspace (`workspace.css`, `workspace-pages.css`, `tracker.css`, dll).
* Memastikan kontras kartu pelacak lowongan (Kanban), modal dialog, dan input teks tetap memenuhi standar aksesibilitas WCAG AA.
* Memastikan chart (Summary & Analytics) bereaksi terhadap event `theme-changed` untuk mengubah warna axis & grid lines.

## 4. Edge Cases & Resilience
1. **Private/Incognito Browsing**: Penanganan fallback aman jika akses `localStorage` diblokir oleh browser.
2. **Livewire Route Transitions**: Menjaga class `dark` tetap bertahan selama navigasi SPA menggunakan `wire:navigate`.
3. **Print & Export CV**: Memastikan fitur ekspor PDF (CV Builder) tetap mempertahankan format warna dokumen cetak yang bersih tanpa terpengaruh dark mode browser.

## 5. Rencana Verifikasi
* Uji coba toggle manual Light -> Dark -> Light di desktop dan mobile view.
* Uji coba persistensi saat hard refresh (Cmd + Shift + R) untuk memastikan tidak ada kedipan putih (*No FOUC*).
* Cek tampilan visual pada halaman utama: Home Dashboard, Job Tracker (Kanban), Summary, dan Account Settings.

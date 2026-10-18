# Audit dan penyempurnaan mobile halaman pengguna

Tanggal: 18 Oktober 2026. Status: perbaikan lokal; catatan verifikasi sebelum publikasi.

## Cakupan dan metode

Audit berbasis kode, render Laravel dengan data sintetis, pemeriksaan Chromium, dan regresi terpilih. Database pengujian memakai SQLite sementara; tidak mengubah data pengguna lokal/production. Tidak menjalankan checkout, mengirim email, memanggil provider AI, atau menjalankan scraper nyata.

Sebanyak 42 halaman/variasi dirender dalam English dan Bahasa Indonesia (84 respons HTTP 200):

- Dashboard; tracker; summary; detail lamaran; Explore; kalender interview.
- Profile dan membership, dilanjutkan pemeriksaan keenam tab profil: akun, CV, membership, keamanan, notifikasi, aktivitas.
- CV Builder, pemilih template, dan review keempat template; preview portfolio.
- Empat tugas AI Studio, hasil analisis CV, hasil cover letter/foto, dan status permintaan gagal.
- Support, Automation & CSV, community, survei, status ekspor data.
- Premium, top-up, status pembayaran menunggu, riwayat pembayaran, coming-soon.
- Landing, login, register, lupa/reset kata sandi, konfirmasi kata sandi, verifikasi email, syarat layanan, privasi.

URL lama yang hanya melakukan redirect dipetakan ke halaman tujuan. Endpoint download PDF/CSV dan webhook bukan halaman interaktif dalam cakupan layout ini. Isi serta stylesheet dokumen PDF tidak diubah.

## Temuan dan perbaikan

| # | Severity | Area dan bukti | Pemicu / dampak | Perbaikan | Confidence |
|---|---|---|---|---|---|
| 1 | Medium | CV Builder: lebar sidebar terukur sekitar 825 px di viewport 390 px; kolom editor tersedia sekitar 352 px. | Min-content navigasi memperlebar grid. Sebagian konten tampak hilang karena overflow shell dipotong. | Grid `minmax(0,1fr)` dan `min-width:0`; pemilih bagian CV native di mobile. Navigasi laptop tetap dipakai di layar besar. | 10/10 |
| 2 | Medium | Kalender: weekdays/days tetap 580 px di viewport 390 px. | Pengguna hanya melihat sebagian hari dan harus menggeser horizontal. | Tujuh kolom muat di layar mobile; jumlah jadwal muncul pada tanggal. Pilih tanggal tetap membuka daftar jadwal lengkap. | 10/10 |
| 3 | Medium | Skill CV memakai `opacity-0 group-hover:opacity-100` pada tombol edit/hapus. | Aksi tidak terlihat tanpa hover pada perangkat sentuh. | Aksi selalu terlihat di mobile dan area tombol diperbesar. | 10/10 |
| 4 | Medium | Filter Explore terukur 11–12 px dengan tinggi 40–42 px. Field auth memakai ukuran kecil serupa. | Sulit disentuh dan berpotensi memicu zoom fokus pada Safari iOS. | Field mobile 16 px, tinggi minimal 44 px; placeholder tetap ringkas. Tidak menonaktifkan zoom browser. | 9/10 |
| 5 | Medium | Riwayat pembayaran memakai tabel lebih lebar dari layar dan scroll horizontal. | Informasi nominal, status, dan tautan detail terpisah ke sisi layar. | Satu kartu per pesanan, dengan label kolom dan aksi detail. Tabel desktop dipertahankan. | 10/10 |
| 6 | Low | Modal CV dan detail memakai ukuran field serta tombol yang tidak seragam pada mobile. | Ruang sentuh kecil dan form panjang sulit dipakai pada layar pendek. | Sembilan modal diperiksa pada 390×500; field minimal 16 px, area aksi diperbesar, batas tinggi dan scroll disesuaikan. | 9/10 |
| 7 | Low | Dekorasi dasar laptop landing memakai lebar 114% dan margin negatif. | Overflow halaman 3 px terukur pada viewport 320 px. | Dekorasi dibatasi 100% hanya di mobile; pemeriksaan ulang overflow menjadi 0. | 10/10 |
| 8 | Low | Disclaimer auth memakai teks 10 px dengan warna sangat redup. | Keterbacaan rendah pada layar kecil. | Ukuran 11 px, line-height dan warna teks diperjelas khusus mobile. | 10/10 |
| 9 | Low | Judul, deskripsi, dan langkah status ekspor tidak melalui penerjemah walaupun katalog tersedia. | Halaman Bahasa Indonesia menampilkan status dalam English. | Terjemahkan label presentasi yang berasal dari daftar status tetap. Data ekspor pengguna tidak diterjemahkan. | 10/10 |

Perbaikan utama terkumpul dalam `resources/css/user-mobile.css`. Kontrol CV ada di `resources/views/livewire/cv-builder/main-builder.blade.php`; indikator jadwal di `resources/views/livewire/interview-calendar.blade.php`; label kartu pembayaran di `resources/views/payment/history.blade.php`. Kelas lingkup auth ditambahkan pada layout guest. Pembungkus dan isi PDF tetap terpisah.

## Penyempurnaan lain

- Spasi/header Explore, interview, detail lamaran, serta status ekspor dirapikan pada mobile.
- Long text pada judul, catatan, hasil AI, dan link diberi wrapping yang sesuai.
- Preview CV dan opsi template tetap berada dalam container mobile tanpa mengubah geometri A4 dokumen.
- Nara lebih ringkas di layar kecil, dengan tab fokus dan area sentuh yang lebih nyaman.
- Survei memakai jarak rating yang muat pada layar sempit dan target sentuh vertikal 44 px.
- Tombol dan pilihan berukuran kecil pada form, modal, pembayaran, serta hasil AI ditingkatkan seperlunya.
- Dashboard, tracker, summary, keenam tab profil, support, automation, dan community diperiksa; komponen yang sudah muat tetap dipertahankan.

## Verifikasi

Hasil pengukuran tersedia di `mobile-layout-checks.json`:

- 31 variasi halaman akun diperiksa pada 320 px (English): tidak ada overflow halaman setelah perbaikan.
- Tujuh halaman publik/auth diperiksa pada 320 px (Indonesia); overflow landing ditemukan lalu diperbaiki dan diukur ulang menjadi 0.
- Konfirmasi kata sandi dan verifikasi email diperiksa pada emulasi touch 320 px: overflow 0 dan field teks minimal 16 px. Support juga diperiksa dengan tiket dan balasan sintetis.
- Survei dan status ekspor diperiksa pada 320 px (Indonesia): overflow 0.
- 31 variasi halaman akun diperiksa pada 390 px (Indonesia, dark): overflow 0.
- 14 halaman utama diperiksa pada 430 px (Indonesia): overflow 0.
- Delapan halaman diperiksa kembali dengan emulasi mobile/touch dan pointer coarse: overflow 0.
- Keenam tab profil diperiksa melalui navigasi tab: overflow 0 dan field teks terlihat tidak lebih kecil dari 16 px.
- Sembilan modal: experience, education, skills, projects, organizations, achievements, publish portfolio, detail interview, detail Explore. Pada 390×500, lebar muat dan tinggi panel tidak melebihi 480 px; form panjang tetap bisa digulir.
- Baseline 18 halaman pada 1366 px dibandingkan sebelum/sesudah: tidak ada perbedaan pada ukuran container, ukuran font judul, dan susunan grid yang diukur. Hampir seluruh aturan baru dibatasi `max-width:640px`; pengecualian `max-width:850px` hanya memperbaiki penyusutan grid CV.

Pemeriksaan kode dan regresi:

- 128 test regresi halaman/alur terpilih: lolos, 803 assertions.
- 50 test tambahan Interview/Explore/Payment/CV template: lolos, 432 assertions.
- Test pemilihan bagian CV: lolos, 4 assertions.
- 10 test preferensi bahasa dan katalog: lolos, 29.165 assertions.
- 63 test JavaScript: lolos.
- 241 Blade view berhasil dikompilasi.
- Build frontend dan pemeriksaan whitespace berhasil.

Ini bukan klaim bahwa seluruh suite backend dijalankan. Test penghapusan akun lama `ProfileTest` tidak termasuk dalam regresi responsif terpilih.

## Batasan

- Pemeriksaan visual memakai HTML sintetis dari server, CSS build aktual, dan Chromium. Livewire di fixture visual disimulasikan; alur server diverifikasi terpisah lewat test. Ini tidak menggantikan pengujian end-to-end pada sesi akun nyata.
- Tidak ada perangkat fisik iOS/Android atau keyboard virtual Safari dalam sesi ini. Ukuran field dan viewport pendek diperiksa, tetapi perilaku keyboard/zoom native tetap perlu smoke test perangkat.
- Pembayaran Pakasir, login OAuth, provider AI, parsing PDF, dan unduhan browser native tidak dijalankan langsung dalam audit layout ini.
- Konten ekstrem, tema portfolio lain, dan seluruh kombinasi state/error tidak mungkin tercakup hanya oleh sampel ini. Tidak ditemukan overflow halaman pada sampel terukur; clipping elemen individual tetap dinilai secara visual pada halaman utama.
- Laporan ini mencatat verifikasi lokal; deployment production memerlukan pembaruan kode dan build aset di server.

# Deployment A16–A22

Perbaikan ini tersedia di kode lokal. Migration dan worker berikut belum dijalankan di produksi.

## Frontend dan aksesibilitas

- Build memakai Tailwind 3 melalui PostCSS. Dependency plugin Vite Tailwind 4 yang tidak digunakan dihapus; utility khusus yang dipakai Blade didefinisikan secara eksplisit. Jalankan `npm ci` dan `npm run build` pada lingkungan deployment.
- Sidebar memakai teks navigasi 14px, label 12px, dan token teks yang diuji pada CSS hasil build. Kontras normal 7,41:1; pasangan aktif, hover dan disabled juga melampaui 4,5:1. Ini bukan pengukuran computed style browser.
- Navigasi, form lamaran dan Explore memakai bahasa Indonesia. Nilai enum tersimpan tetap mengikuti kontrak status. Informasi Explore menampilkan sumber dan waktu pemeriksaan; respons HTTP bukan jaminan lowongan masih menerima pelamar.
- Dialog bernama memakai penguncian fokus, Escape, pengembalian fokus, penguncian scroll bertumpuk dan ukuran dari prop. Pengujian runtime/directive tersedia; keyboard, pembaca layar dan tampilan responsif masih perlu diperiksa di browser staging.
- Polling pembayaran hanya menjalankan satu request, memakai timeout 12 detik dan backoff hingga 60 detik, serta menampilkan error dan tombol coba lagi. Gangguan verifikasi provider mengembalikan 503, bukan status pembayaran gagal. Navigasi Livewire dan pemulihan halaman browser Back ditangani.

## Pagination dan ekspor

Page size admin dibatasi ke 10/20/50/100; tracker ke 10/30/50/100. Nilai tidak sah memakai default. Kanban memuat 30 item per kolom dengan pagination dan jumlah total terpisah. Belum ada benchmark beban atau klaim waktu respons produksi.

Ekspor sampai 500 baris memakai streaming dan pembacaan bertahap. Dataset lebih besar masuk antrean; request identik yang masih menunggu memakai record yang sama. File disimpan pada disk `local` privat, bukan direktori publik. Pemilik wajib cocok; ekspor pengguna juga memerlukan role admin saat worker berjalan dan saat diunduh. File kedaluwarsa setelah 24 jam.

1. Terapkan migration `2026_10_09_000002_create_data_exports_table.php` bersama migration audit sebelumnya melalui prosedur deployment biasa.
2. Jalankan worker asinkron khusus: `php artisan queue:work exports --queue=exports --timeout=240 --tries=1`. Koneksi database `exports` memiliki `retry_after=300`; jangan menggantinya dengan koneksi sinkron.
3. Aktifkan Laravel scheduler. `data-exports:cleanup` dijadwalkan setiap sepuluh menit untuk expiry, pemulihan antrean dan pembersihan pekerjaan processing yang tertinggal. File orphan berumur lebih dari 24 jam juga dibersihkan.
4. Worker dan aplikasi harus memakai storage privat yang sama serta cache bersama untuk unique job lock pada deployment multi-instance. Pastikan queue database dan failed jobs sudah tersedia.
5. Periksa alur queued → processing → ready, kegagalan, akses lintas akun, pencabutan admin dan unduhan kedaluwarsa di staging. Halaman status menyediakan pemeriksaan ulang manual; kegagalan dapat dibuat ulang dari halaman asal.

Filter dan ID maksimum sumber direkam saat permintaan ekspor. Nilai field mengikuti data saat worker membaca, bukan snapshot database; baris baru di atas ID maksimum tidak disertakan. Job duplikat dan callback gagal terlambat tidak menghapus hasil yang sudah siap.

## Regresi

Tes ekspor memakai route CSV aktif dan kontrak CSV data round-trip. Endpoint laporan/statistik legacy tetap tidak aktif. Tes Explore memeriksa persistensi data sebelum pesan sukses agar perubahan copy tidak melewati pemeriksaan database. Coverage pembayaran, otorisasi, kepemilikan, entitlement dan CSV A01–A15 tetap dijalankan bersama suite penuh.

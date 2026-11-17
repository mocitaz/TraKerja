# Audit sistem TraKerja: kualitas, value, dan bug

Tanggal: 18 November 2026 · Branch: `main` (`ae3b2a7`) · Lokal SQLite; produksi MySQL (dicek lewat output server sesi ini).

## 1. Ringkasan eksekutif

Kondisinya sehat. Seluruh test suite lolos (**984 tes**, `php artisan test`). Otorisasi data, pembayaran, webhook, dan pengambilan URL dari luar
sudah dijaga dengan baik. Masalah yang tersisa kebanyakan ada di konfigurasi produksi dan data, bukan di logika aplikasi. Lima temuan terpenting:
1. **Worker untuk AI Photo, AI Studio, dan ekspor data tidak ada di produksi** (K-1). Sudah dipasang 18 Nov setelah audit.
2. **Resolver perusahaan tidak membatasi panjang nama dan URL logo** (S-1). Di MySQL strict, data yang terlalu panjang membuat ingest item tersebut gagal.
3. **Golden Set Review masih berbahasa Indonesia** di UI admin yang sudah berbahasa Inggris (S-2).
4. **Explore default "maintenance" di instalasi baru** (S-3). Ini pernah membuat produksi menampilkan halaman lama.
5. **Seluruh monitoring bergantung pada cron yang sama** (S-4). Kalau cron mati, tidak ada alert sama sekali.

Sebelas bug dan celah lain sudah diperbaiki di sesi ini (lihat bagian 5) dan tidak diulang di tabel.

## 2. Temuan bug & keamanan yang masih terbuka

| ID | Severity | Area | Temuan | Bukti | Cara reproduksi / cek | Dampak | Perbaikan | Usaha | Status |
|---|---|---|---|---|---|---|---|---|---|
| K-1 | Kritis | Antrean | Job AI Photo, AI Studio, dan ekspor memakai koneksi antrean sendiri (`ai_photos`/`ai-photo`, `ai_studio`/`ai-studio`, `exports`/`exports`) yang tidak didengarkan `trakerja-worker` (`discovery,ingest,ai,default`) | `app/Jobs/ProcessAiPhoto.php:21`, `ProcessAiStudio.php:17`, `BuildDataExport.php:29`; dok worker khusus di `docs/security/a11-a15-deployment.md:16`, `a16-a22-deployment.md:20`, `docs/ai-studio/gemini-migration.md:11`; di Supervisor produksi hanya ada `trakerja-worker` | Di server: `sudo supervisorctl status` (cari worker `ai-photo`/`ai-studio`/`exports`) dan `php artisan tinker --execute='echo json_encode(App\Models\AiPhoto::selectRaw("status, count(*) n")->groupBy("status")->pluck("n","status"));'` | Permintaan premium tertahan "queued", kedaluwarsa setelah 30 menit, lalu kredit di-refund: fitur terasa rusak | Tambah 3 program Supervisor sesuai dokumen di atas (`queue:work ai_photos --queue=ai-photo --timeout=330 --tries=1`, dst.) | S | **Selesai 18 Nov**: terverifikasi di server (hanya `trakerja-worker` yang jalan), tiga worker dipasang; lihat `docs/operations/workers.md` |
| S-1 | Sedang | Ingest | `CompanyResolver` menyimpan `name` (kolom 200) dan `logo_url` (kolom 255) tanpa batas panjang | `app/JobEngine/Normalize/CompanyResolver.php:39-44`; `create_job_engine_tables.php:86,90` | Ingest perusahaan dengan URL logo CDN bertanda tangan >255 karakter di MySQL → `Data too long` | Loker dari perusahaan itu gagal masuk; SQLite lokal menerimanya, jadi tidak ketahuan | `Str::limit` nama ke 200; buang `logo_url` bila >255 atau ubah kolom ke `text` | S | Potensi; data saat ini maksimal 113 karakter |
| S-2 | Sedang | i18n admin | Golden Set Review sepenuhnya berbahasa Indonesia, termasuk flash message | `resources/views/livewire/admin/golden-set-review.blade.php` (20 teks), `app/Livewire/Admin/GoldenSetReview.php` | Buka `/admin/golden-set` dengan UI English | Tidak konsisten dengan admin lain | Terjemahkan seperti 3 halaman 2.0 lainnya | S | Terverifikasi |
| S-3 | Sedang | Konfigurasi | `explore_maintenance` default **aktif** di semua environment selain testing | `app/Support/WorkspaceHome.php:16`, `app/Livewire/ExploreJobs.php:432` | Database baru tanpa baris setting → Explore menampilkan halaman maintenance | Pernah membuat produksi menampilkan halaman lama | Default nonaktif, atau seed setting saat deploy | S | Terverifikasi (pernah terjadi) |
| S-4 | Sedang | Monitoring | Watchdog, laporan harian, dan semua jadwal bergantung pada satu cron `schedule:run` | `routes/console.php` | Matikan cron → tidak ada satu pun alert | Gangguan bisa berjalan berjam-jam tanpa diketahui | Heartbeat eksternal (ping ke layanan cron-monitor tiap 10 menit dari watchdog) | S | Terverifikasi (desain) |
| S-5 | Sedang | Keamanan | Rahasia `.env` produksi sempat tertempel di chat; rotasi belum dikonfirmasi selain OpenAI | Riwayat sesi | — | Kredensial DB, mail, Pakasir, OAuth, Telegram, Gemini, dan Turnstile bisa disalahgunakan | Rotasi semua key tersebut | M | Terbuka |
| R-1 | Rendah | Repo | Skrip debug yang mencetak data pengguna ikut di Git | `storage/test.php`, `storage/check_xp.php` | `git ls-files storage` | Tidak bisa diakses lewat web (di luar `public/`), tapi tidak semestinya ada di repo | Hapus dari repo | S | Terverifikasi |
| R-2 | Rendah | Repo | Seeder demo (admin `reviewer.admin@trakerja.test` / `password`) masih ada di riwayat Git (`b3ef286`) | `git show b3ef286 --stat` | — | Hanya berisiko kalau seseorang menjalankannya di produksi | Sudah dikeluarkan dari repo dan di-ignore (`ae3b2a7`); pastikan akun tidak ada di produksi | S | Diperbaiki sebagian |
| R-3 | Rendah | OAuth | `LINKEDIN_REDIRECT_URI` produksi dulu masih memakai domain lama `trakerja.web.id` | `.env` produksi (sesi sebelumnya) | Login LinkedIn di produksi | Login LinkedIn gagal | Ubah ke `https://trakerja.com/auth/linkedin/callback` di `.env` dan dashboard LinkedIn | S | Dugaan (perlu cek) |

## 3. Kualitas & UX per area

- **Explore:** diurutkan murni berdasarkan tanggal, jadi satu perusahaan dengan banyak cabang (Amartha 51%) memenuhi hasil. Kelompokkan per posisi dengan daftar lokasi.
- **Saved search & alert:** fiturnya lengkap tapi hampir tidak dipakai (1 saved search, 0 alert aktif). Tombol "Simpan pencarian" perlu lebih menonjol setelah pengguna memfilter, dan tawaran alert perlu muncul setelah lamaran pertama.
- **Halaman publik `/loker`:** sudah rapi dan berbahasa Indonesia. Deskripsi JSON-LD bisa diperkaya dengan keterampilan dan kualifikasi.
- **Admin Engine Monitor:** belum ada metrik cakupan verifikasi ("% loker buka yang dicek dalam 48 jam") maupun hasil golden-eval.
- **Admin Manage Jobs:** belum ada aksi "gabungkan perusahaan", padahal dibutuhkan untuk alias seperti Amartha dan Bumi Amartha.
- **Performa:** jumlah query per halaman wajar (Explore 52, dashboard 38, tracker 21, kalender 9, `/loker` 11) dan tidak ada N+1.

## 4. Peluang value (dampak ÷ usaha)

| Ide | Untuk | Alasan | Data | Usaha |
|---|---|---|---|---|
| Worker AI Photo/Studio/ekspor dipasang + dipantau watchdog | Pengguna premium | Fitur berbayar harus selesai, bukan di-refund | K-1 | S |
| Ajakan alert kontekstual setelah lamaran pertama atau setelah filter | Pengguna | Retensi: loker datang sendiri | 0 alert aktif | S |
| Pratinjau skor cocok CV untuk pengguna gratis | Konversi premium | Menunjukkan nilai fitur premium | Embeddings 100% | S |
| Kartu "1 posisi, banyak lokasi" | Pengguna | Explore lebih beragam | Amartha 51% | M |
| Logo dari favicon + estimasi gaji | Pengguna | Kepercayaan dan relevansi | Logo 32%, gaji 27% | S–M |
| Heartbeat cron eksternal | Admin | Gangguan cron tidak lagi senyap | S-4 | S |
| Gabung perusahaan di Manage Jobs | Admin | Data perusahaan bersih | Alias Amartha | S |

## 5. Yang sudah baik (jangan dikerjakan ulang)

- **Otorisasi data (IDOR):** semua komponen Livewire dan route pengguna membatasi data ke pemiliknya, dan ID sensitif memakai `#[Locked]` atau dicek ulang.
- **Pembayaran:** webhook Pakasir (secret `hash_equals`, cek jumlah/txn/sandbox) dan penyelesaian pembayaran (transaksi + `lockForUpdate`, perpanjangan dari tanggal akhir) sudah benar.
- **SSRF:** pengambilan URL lowongan memakai DNS publik, IP yang dikunci, dan validasi ulang di setiap redirect.
- **XSS:** template CV dan portofolio meng-escape teks dan memvalidasi URL.
- **SQL lintas database:** ekspresi tanggal dibungkus `DatabaseDateExpressions`, jadi sama-sama jalan di SQLite dan MySQL.
- **Route admin:** semuanya memakai middleware admin.
- **Diperbaiki di sesi ini:** `posted_at` MySQL; `retry_after` 960 detik; Telegram hanya dari produksi; `/test/error` 404 di produksi; penutupan loker berdasarkan `valid_through`; judul ALL CAPS; rate limit endpoint berat; label lokasi dan gaji sesuai bahasa; URL profil hanya http/https; halaman 410; scraper lama dihapus; 19 tes gagal sekarang hijau.

## 6. Rencana perbaikan

- **Minggu 1:** ~~K-1~~ (selesai), S-5 (rotasi rahasia), R-3 (redirect LinkedIn), S-1, S-3, R-1.
- **Minggu 2:** S-2 (terjemahan Golden Set Review), S-4 (heartbeat cron), ajakan alert, pratinjau skor cocok CV, gabung perusahaan.
- **Backlog:** kartu "banyak lokasi", estimasi gaji, logo dari favicon, metrik cakupan verifikasi, deskripsi JSON-LD yang lebih kaya.

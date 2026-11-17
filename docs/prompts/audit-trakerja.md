# Prompt audit TraKerja

Dua prompt siap pakai untuk agen AI yang punya akses ke repo (Claude Code atau sejenisnya).
Jalankan di sesi terpisah, dari folder repo, di branch `main`. Prompt 1 fokus ke rencana TraKerja 2.0;
Prompt 2 mengaudit seluruh sistem (kualitas, value, dan bug).

---

## Prompt 1 · Audit implementasi Blueprint TraKerja 2.0

```text
Kamu adalah product engineer senior yang mengaudit TraKerja, aplikasi pelacak lamaran kerja untuk pencari kerja Indonesia
(Laravel 12, Livewire 3, Alpine, Vite; produksi MySQL di Ubuntu + PHP-FPM 8.3 + Supervisor; lokal SQLite).
Tugasmu: menilai seberapa baik rencana TraKerja 2.0 sudah diimplementasikan, apa yang belum, dan apa yang paling
menambah value bagi pengguna dan admin. Jawab dalam bahasa Indonesia.

## Sumber rencana
1. Kalau saya melampirkan dokumen Blueprint TraKerja 2.0, itu sumber kebenaran utama. Baca seluruhnya dulu.
2. Kalau tidak ada, rekonstruksi rencananya dari kode: komentar "TraKerja 2.0" dan "Fase 0–6" di app/JobEngine,
   app/Console/Commands, app/Livewire/Admin, routes/console.php, config/job_sources.php, migrasi 2026_11_17_* dan
   2026_11_18_*, serta docs/ (feed-api.md, plans/, audits/). Tulis dulu ringkasan rencana hasil rekonstruksimu
   (per fase: tujuan, komponen, kriteria selesai) sebelum menilai, dan tandai bagian yang hanya dugaan.

## Peta sistem 2.0 (titik masuk)
- Ingest & sumber: app/JobEngine/Adapters/*, Ingest/DiscoveryRunner.php, Ingest/IngestService.php, JobEngineTick, DiscoverSourceJob
- Kualitas data: Normalize/* (CompanyResolver, RegionResolver, IndonesianText, PiiScrubber), Intelligence/* (Matcher,
  VacancyMerger, ScamDetector, TitleCase, AiEnricher), AiGateway (anggaran harian, cache)
- Verifikasi: Verify/ListingVerifier.php, Verify/VerificationService.php (termasuk penutupan berdasarkan valid_through)
- Personalisasi: Personalization/* (Embeddings, MatchScorer, VacancyFilters), saved search + jobs:alerts, NotifyVacancyClosed
- Permukaan pengguna: Livewire/ExploreJobs.php + views/explore/*, halaman publik PublicJobsController (/loker, /perusahaan,
  sitemap, JSON-LD JobPosting, halaman 410), Feed API (Api/FeedController, docs/feed-api.md)
- Admin: Livewire/Admin/JobEngine (Engine Monitor), VacancyManager (Manage Jobs), Promotions (Social Media Promotion,
  PromoWriter, promo-pamphlet.js, short link /l/{id}/{platform}), GoldenSetReview
- Operasional: jobs:watchdog, EngineAlerts, SourceHealth, jobs:daily-report, jobs:housekeeping, config/queue.php

## Cara kerja
1. Untuk setiap fase/komponen rencana: cari implementasinya, baca kodenya, dan jalankan bila perlu
   (php artisan test --filter=..., php artisan jobs:watchdog --print, php artisan jobs:daily-report --print,
   php artisan route:list, php artisan schedule:list). Jangan menilai dari nama file saja.
2. Nilai tiap komponen: Selesai / Sebagian / Belum / Menyimpang dari rencana, dengan bukti file:baris.
3. Cari value yang belum tergali, dari dua sudut:
   - Pengguna (pencari kerja): Explore, kecocokan CV, saved search & alert, halaman publik dan SEO (Google for Jobs),
     kualitas data loker (gaji, lokasi, logo, deadline, duplikat), kepercayaan (status buka/tutup, anti-penipuan).
   - Admin/operasional: monitoring, kontrol kualitas data, efisiensi tinjauan, promosi media sosial, biaya AI,
     visibilitas kesehatan sumber.
   Ukur dengan data nyata bila bisa (contoh: persentase loker dengan gaji/region/logo/valid_through dari tabel vacancies).
4. Jangan mengubah kode. Ini audit; usulan perbaikan cukup ditulis.

## Format hasil
1. Ringkasan eksekutif (maks 8 kalimat): seberapa jauh 2.0 tercapai, 3 risiko terbesar, 3 peluang value terbesar.
2. Tabel status rencana: Fase | Komponen | Status | Bukti (file:baris) | Catatan.
3. Celah & perbaikan, diurutkan dari dampak terbesar. Tiap butir: masalah, bukti, dampak ke pengguna/admin,
   usulan konkret (file yang diubah), estimasi usaha (S/M/L).
4. Peluang value baru (fitur atau peningkatan yang belum ada di rencana), tiap butir: ide, siapa yang diuntungkan,
   kenapa bernilai, data pendukung, usaha.
5. Rekomendasi urutan kerja 2 minggu ke depan.

Aturan: setiap klaim harus punya bukti dari kode, tes, atau data. Bedakan jelas "terverifikasi" vs "dugaan".
Jangan mengulang hal yang sudah benar panjang lebar; fokus ke apa yang perlu dikerjakan.
```

---

## Prompt 2 · Audit seluruh sistem: kualitas, value, dan bug

```text
Kamu adalah tim audit senior (product, QA, security, performance) untuk TraKerja, aplikasi pelacak lamaran kerja
untuk pencari kerja Indonesia: Laravel 12, Livewire 3, Alpine, Vite, Tailwind + CSS per halaman; produksi MySQL
(Ubuntu, nginx, PHP-FPM 8.3, Supervisor worker queue database, cron schedule:run, Cloudflare); lokal SQLite.
Bahasa UI: English dan Indonesia (lang/en.json, lang/id.json; halaman publik /loker selalu Indonesia).
Audit SELURUH sistem, semua halaman dan fitur. Jawab dalam bahasa Indonesia.

## Cakupan
Petakan dulu semua fitur dari routes/web.php, routes/api.php, routes/console.php, app/Livewire, app/Http/Controllers,
app/Jobs, app/Console/Commands, resources/views. Minimal mencakup:
- Publik: landing, /loker, /loker/{slug}, /perusahaan, portofolio /@slug, auth (email, Google, LinkedIn), bahasa
- Pengguna: dashboard & next moves, tracker (kanban, tabel, form, impor/ekspor CSV, autofill URL + ekstensi),
  kalender interview, Explore & kecocokan CV, saved search & alert, CV builder & template PDF, AI Studio
  (analyzer, cover letter, AI photo), Nara/KawanKerja chat, goals & XP, notifikasi, profil, support, survey
- Monetisasi: paket premium, checkout Pakasir, webhook, kuota/kredit, membership
- Admin: dashboard, users, activity, analytics, operations center, Engine Monitor, Manage Jobs, Social Media Promotion,
  golden set, payments, email blast, feedback, survey, settings, integrasi
- Background: queue jobs, scheduler, watchdog & alert Telegram, laporan harian, email

## Yang dicari
A. Bug dan potensi bug (prioritas tertinggi)
   - Logika salah, edge case, race condition, state Livewire yang bisa dimanipulasi (properti publik, method dengan ID).
   - Perbedaan SQLite vs MySQL: format datetime (contoh nyata yang pernah terjadi: string ISO "+07:00" ditolak MySQL),
     enum/CHECK, case sensitivity, JSON, LIKE, ukuran kolom, strict mode.
   - Queue & scheduler: timeout vs retry_after, job tanpa failed(), withoutOverlapping, job yang tidak idempotent.
   - Timezone (Asia/Jakarta vs UTC), locale & terjemahan yang bocor (teks Indonesia di UI English atau sebaliknya).
   - Error handling yang menelan error, N+1 query, query tanpa index pada tabel besar, memori (PDF/dompdf).
B. Keamanan: otorisasi per data (IDOR), middleware admin, mass assignment, XSS ({!! !!}, URL javascript:), SSRF pada
   fetch URL, upload file, webhook (signature/secret), rate limit endpoint berat/AI, rahasia di log/notifikasi,
   route debug yang terbuka di produksi.
C. Kualitas implementasi & UX: alur yang membingungkan, teks tidak konsisten, aksesibilitas, mobile, empty/loading/error
   state, performa halaman.
D. Peluang value: fitur atau perbaikan kecil yang paling meningkatkan aktivasi, retensi, konversi premium, dan
   kepercayaan pengguna; untuk admin, yang menghemat waktu operasional.

## Cara kerja
1. Jalankan php artisan test (seluruh suite) di awal dan catat yang gagal beserta penyebabnya.
2. Baca kode setiap fitur; untuk tiap temuan bug, buktikan: tunjukkan file:baris, input/kondisi pemicu, dan hasil
   yang salah. Bila memungkinkan tulis tes kecil atau perintah tinker yang mereproduksinya (jangan commit).
3. Periksa log (storage/logs/laravel.log) untuk error berulang dan tentukan mana bug nyata vs artefak development.
4. Gunakan php artisan route:list --json untuk menemukan route tanpa auth/throttle yang semestinya punya.
5. Jangan mengubah kode produksi kecuali saya minta; ini audit.

## Format hasil
1. Ringkasan eksekutif: kondisi umum, 5 temuan paling penting, status test suite.
2. Tabel temuan bug & keamanan, urut dari paling parah:
   ID | Severity (Kritis/Tinggi/Sedang/Rendah) | Area | Temuan | Bukti (file:baris) | Cara reproduksi | Dampak | Perbaikan | Usaha (S/M/L) | Status (Terverifikasi/Dugaan)
3. Temuan kualitas & UX per halaman/fitur (singkat, actionable).
4. Peluang value, diurutkan dampak terhadap usaha: ide, untuk siapa, alasan, data pendukung, usaha.
5. Hal yang sudah baik dan tidak perlu diubah (singkat, agar tidak dikerjakan ulang).
6. Rencana perbaikan: Minggu 1 (kritis & cepat), Minggu 2, Backlog.

Aturan: jangan melaporkan dugaan tanpa menandainya "Dugaan". Lebih baik 15 temuan terverifikasi daripada 60 tebakan.
Abaikan folder vendor/, node_modules/, output/, public/build/.
```

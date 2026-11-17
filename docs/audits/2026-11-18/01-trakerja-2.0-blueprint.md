# Audit implementasi TraKerja 2.0

Tanggal: 18 November 2026 · Branch: `main` (`ae3b2a7`) · Data: database lokal (982 loker buka); angka produksi dari laporan server sesi ini.

Dokumen Blueprint TraKerja 2.0 tidak ada di repo, jadi rencananya direkonstruksi dari komentar kode, migrasi, dan perintah artisan.
Bagian rekonstruksi yang hanya dugaan ditandai *(dugaan)*.

## 1. Ringkasan eksekutif

TraKerja 2.0 sudah berjalan end to end: 9 sumber aktif → ingest → normalisasi → dedup → enrichment AI → verifikasi → Explore, halaman publik
SEO, alert, dan admin. Sekitar 85% komponen rencana sudah selesai. Tiga risiko terbesar:
1. **Kualitas data belum pernah diukur.** Golden set punya 246 sampel tapi baru 2 yang direview, jadi akurasi engine tidak diketahui.
2. **Katalog didominasi satu perusahaan.** Amartha mengisi 51% loker buka (504/982), lewat ratusan cabang dengan judul hampir sama.
3. **Fitur AI Photo, AI Studio, dan ekspor data kemungkinan tidak diproses di produksi.** Antrean khususnya tidak didengarkan oleh `trakerja-worker` (detail di laporan 02).

Tiga peluang value terbesar:
1. Mengelompokkan loker cabang ("Team Leader – Karanganyar", "– Krembangan", …) menjadi satu kartu dengan daftar lokasi.
2. Membuat saved search & alert jadi kebiasaan. Saat ini pemakaiannya hampir nol (1 saved search, alert mati).
3. Memperkaya data yang paling dicari: gaji (27%), logo (32%), dan batas lamaran (20%).

## 2. Rencana hasil rekonstruksi

| Fase | Tujuan | Komponen utama | Kriteria selesai *(dugaan)* |
|---|---|---|---|
| 0 | Audit & fondasi legal | `jobs:audit`, registry sumber + cek robots/ToS (`config/job_sources.php`), golden set (`jobs:golden-*`, Golden Set Review), bekukan scraper lama | Data lama terpetakan, sumber legal disepakati, golden set siap diukur |
| 1 | Skema terpadu | Tabel `job_sources`, `companies`, `regions`, `vacancies`, `vacancy_listings`, `crawl_runs`, `ai_calls`; `AiGateway`; `jobs:migrate-legacy` | Satu skema untuk semua sumber, data lama bisa dipindahkan |
| 2 | Konektor sumber | Adapter API/ATS/RSS/JSON-LD/Next data, `PoliteHttpClient`, `RobotsPolicy`, `jobs:discover` | Sumber berjalan sopan dan legal, akurasi diukur di golden set |
| 3 | Kecerdasan data | Normalisasi (perusahaan, region, teks ID, PII), `Matcher` + `VacancyMerger` + review duplikat, `AiEnricher`, `ScamDetector` | Duplikat lintas platform tergabung, field kosong terisi, penipuan tertahan |
| 4 | Verifikasi & operasi | `ListingVerifier`, `VerificationService`, `SourceHealth` (jadwal adaptif, auto-pause), Engine Monitor | Loker tutup cepat terdeteksi, kesehatan sumber terlihat |
| 5 | Explore & publik | Explore membaca `vacancies`, `/loker`, `/perusahaan`, JSON-LD, sitemap | Pengguna dan Google melihat katalog 2.0 |
| 6 | Personalisasi | Explore sebagai beranda, skor cocok CV (embeddings, premium), saved search + alert, notifikasi loker tutup, Feed API, inbox | Pengguna kembali karena loker yang relevan datang sendiri |
| Ops* | Pasca-rencana (sesi ini) | Watchdog, alert Telegram, Manage Jobs, Social Media Promotion, halaman 410 | Admin tahu masalah sebelum pengguna |

## 3. Status per komponen

| Fase | Komponen | Status | Bukti | Catatan |
|---|---|---|---|---|
| 0 | Audit data lama | Selesai | `app/Console/Commands/AuditJobData.php` | — |
| 0 | Registry sumber + legalitas | Selesai | `config/job_sources.php` (28 sumber, alasan nonaktif tertulis) | LinkedIn, Glints, JobStreet, dll. sengaja nonaktif |
| 0 | Golden set | **Sebagian** | `jobs:golden-check`: 246 sampel, **2 direview**; `jobs:golden-eval`: "Belum ada label yang direview" | Akurasi engine belum pernah terukur |
| 1 | Skema terpadu | Selesai | `database/migrations/2026_11_17_120000_create_job_engine_tables.php` | — |
| 1 | Migrasi data lama | **Belum dijalankan** | `jobs:migrate-legacy` ada; produksi belum menjalankannya | Bookmark lama tidak terhubung ke `vacancies` |
| 2 | Sumber aktif | Sebagian | 9 aktif: workable, dealls, kalibrr, pintarnya, weworkremotely, jobicy, lever, greenhouse, himalayas | remotive & smartrecruiters `health=failing`; jooble/careerjet butuh API key (ditunda) |
| 2 | Crawling sopan | Selesai | `app/JobEngine/Http/PoliteHttpClient.php`, `RobotsPolicy.php` | — |
| 3 | Normalisasi | Sebagian | Region 86%, sektor 100%, tipe kerja 100% | Alias perusahaan belum menyatukan "Amartha" dan "Bumi Amartha Teknologi Mandiri" |
| 3 | Dedup lintas platform | Sebagian | 18 loker punya >1 listing; review tertunda 34 (lokal) / 76 (produksi) | Antrean review terus bertambah |
| 3 | Enrichment AI | Selesai | `enriched_at` 100%; `AiGateway` dengan anggaran harian | Key OpenAI lokal ditolak (401), produksi aktif |
| 3 | Deteksi penipuan | Selesai | `ScamDetector` + `scam_risk` AI; halaman 410 tanpa isi | — |
| 4 | Verifikasi | Selesai | `VerificationService` (+ penutupan `valid_through`) | Cakupan cek belum terlihat di Engine Monitor (lokal: 862/982 belum pernah dicek) |
| 4 | Kesehatan sumber | Selesai | `SourceHealth`, watchdog, alert Telegram | — |
| 5 | Explore | Selesai | `app/Livewire/ExploreJobs.php` | Diurutkan murni berdasarkan tanggal; tidak ada keberagaman per perusahaan |
| 5 | Halaman publik + SEO | Selesai | `PublicJobsController`, JSON-LD, sitemap di `robots.txt` | `description` hanya ringkasan rata-rata 303 karakter (`PublicJobsController.php:152`) |
| 6 | Explore sebagai beranda | Selesai | `app/Support/WorkspaceHome.php:16` | Default "maintenance" di produksi baru (jebakan yang pernah terjadi) |
| 6 | Skor cocok CV | Selesai | `MatchScorer`, embeddings 982/982 | Hanya premium; tidak ada pratinjau untuk pengguna gratis |
| 6 | Saved search & alert | Selesai, **tidak terpakai** | 1 saved search, 0 alert aktif | Masalah penemuan fitur, bukan teknis |
| 6 | Notifikasi loker tutup | Selesai | `Vacancy::booted`, `NotifyVacancyClosed` | — |
| 6 | Feed API | Selesai | `Api/FeedController`, `docs/feed-api.md` | — |

## 4. Celah & perbaikan (urut dampak)

1. **Golden set belum direview → akurasi tidak diketahui.** Bukti: `jobs:golden-check`. Dampak: keputusan soal kualitas data dibuat buta, termasuk apakah gaji, region, dan tipe kerja yang tampil di Explore benar. Usul: review minimal 60 sampel (20 Dealls, 20 Kalibrr, 20 Workable) di Admin → Golden Set Review, lalu jalankan `jobs:golden-eval` bulanan dan tampilkan hasilnya di Engine Monitor. Usaha: M (sebagian besar waktu admin).
2. **Satu perusahaan menguasai katalog.** Amartha 504/982 loker (51%) dengan judul berpola "Team Leader – [lokasi]". Dampak: Explore terasa sempit, pencarian "team leader" atau "sales" tenggelam. Usul: kelompokkan loker dengan judul dasar dan perusahaan yang sama menjadi satu kartu ("Team Leader · 120 lokasi"), atau batasi maksimal 2 per perusahaan per halaman Explore (`ExploreJobs.php:470`). Usaha: M.
3. **Alias perusahaan belum menyatu.** "Amartha" dan "Bumi Amartha Teknologi Mandiri" tercatat sebagai dua perusahaan. Dampak: halaman `/perusahaan` terpecah dan statistik salah. Usul: tambahkan alias manual di Manage Jobs (gabung perusahaan) dan aturan "nama legal PT berisi nama merek". Usaha: S–M.
4. **Antrean review duplikat terus bertambah** (76 di produksi). Usul: auto-merge bila judul identik, perusahaan sama, dan lokasi sama; tampilkan jumlah di laporan harian (sudah ada) beserta targetnya. Usaha: S.
5. **Data lama belum dipindahkan.** Jalankan `php artisan jobs:migrate-legacy` di produksi supaya bookmark lama pengguna kembali terhubung. Usaha: S.
6. **Cakupan verifikasi tidak terlihat.** Tambahkan metrik "% loker buka yang dicek dalam 48 jam" di Engine Monitor dan watchdog. Usaha: S.
7. **Deskripsi JSON-LD tipis** (rata-rata 303 karakter). Google for Jobs memberi bobot ke deskripsi. Usul: susun deskripsi dari ringkasan + keterampilan + kualifikasi (data yang sudah ada), tanpa menyalin teks sumber. Usaha: S.
8. **Sumber gagal dibiarkan.** remotive dan smartrecruiters `health=failing` dalam keadaan nonaktif. Putuskan: perbaiki atau hapus dari registry supaya Engine Monitor bersih. Usaha: S.

## 5. Peluang value baru

| Ide | Untuk | Kenapa bernilai | Data | Usaha |
|---|---|---|---|---|
| Kartu "1 posisi, banyak lokasi" | Pengguna | Explore lebih beragam; pengguna memilih kota terdekat | Amartha 51% katalog | M |
| Alert "loker baru di kotamu" otomatis setelah lamaran pertama | Pengguna | Saved search hampir tidak dipakai; ajakan kontekstual lebih efektif daripada fitur tersembunyi | 1 saved search | S |
| Pratinjau skor cocok CV untuk pengguna gratis (3 loker teratas) | Pengguna, konversi premium | Fitur premium paling "terasa" tapi tak terlihat oleh pengguna gratis | Embeddings 100% siap | S |
| Estimasi rentang gaji per posisi × kota | Pengguna | Gaji adalah filter nomor satu; baru 27% loker berisi gaji | 266 loker bergaji sebagai dasar | M |
| Logo dari favicon domain perusahaan | Pengguna, promosi | Kartu dan pamflet terlihat lebih tepercaya | Logo 32% | S |
| Lencana "Ditutup dalam 3 hari" | Pengguna | Mendorong melamar sekarang | `valid_through` 20% | S |
| Skor kualitas per sumber di Engine Monitor | Admin | Menentukan sumber mana yang layak dipertahankan | golden-eval + kelengkapan field | M |
| Rekomendasi otomatis loker untuk dipromosikan | Admin | Promosi harian lebih cepat | Saran di halaman Promosi sudah ada; bisa diperluas ke "jadwal mingguan" | S |

## 6. Urutan kerja 2 minggu

- **Minggu 1:** pastikan worker AI Photo/Studio/ekspor (laporan 02), jalankan `jobs:migrate-legacy`, review 60 sampel golden set, gabung alias Amartha, metrik cakupan verifikasi.
- **Minggu 2:** kartu "banyak lokasi" di Explore, ajakan alert setelah lamaran pertama, pratinjau skor cocok untuk pengguna gratis, deskripsi JSON-LD yang lebih kaya, logo dari favicon.

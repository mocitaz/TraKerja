# Audit TraKerja, 8 Oktober 2026

Antislop aktif: during (pilihan sesi pengguna).

Prioritas produk saat ini adalah menjaga integritas pembayaran dan data, lalu menyatukan alur lamaran. Revamp visual perlu mengikuti perbaikan tersebut agar antarmuka baru tidak membawa perilaku yang masih tidak konsisten.

## Status perbaikan A01–A22 — 9 Oktober 2026

**A01–A22 sudah diimplementasikan di kode lokal.** Temuan dan bukti di bagian audit berikutnya merupakan kondisi awal pada 8 Oktober 2026; penandaan ini tidak menyatakan perubahan sudah diterapkan ke produksi.

- [x] **A01 — Webhook pembayaran:** verifikasi server-to-server memakai order tersimpan dan pencocokan merchant, order, nominal serta status sebelum pemberian manfaat.
- [x] **A02 — Idempotensi pembayaran:** SUCCESS terminal, ledger grant unik per payment/manfaat, transaksi dan lock; replay tidak menggandakan kredit.
- [x] **A03 — Admin support:** middleware admin pada boundary dan policy untuk baca, balas, status serta hapus tiket; pengujian lintas role.
- [x] **A04 — Kepemilikan CV:** mutasi seluruh form CV memakai relasi pemilik; owner tidak menjadi field edit; pemetaan field skill dan form terkait diperbaiki.
- [x] **A05 — Admin scraper:** guard route, mount dan setiap aksi, middleware Livewire persisten; seed tidak dijalankan saat kunjungan halaman.
- [x] **A06 — Scrape URL:** allowlist domain, penolakan alamat nonpublik, validasi DNS dan setiap redirect, serta koneksi ke IP yang telah divalidasi.
- [x] **A07 — Premium:** definisi entitlement aktif memakai status pembayaran dan masa berlaku; penyimpanan expiry eksplisit serta renewal tanpa menghapus sisa durasi.
- [x] **A08 — Explore → tracker:** kontrak status dan tahap bersama untuk Explore, tabel, Kanban, CSV dan ringkasan; migration normalisasi nilai lama tersedia.
- [x] **A09 — Pelaporan lowongan:** satu laporan per user/lowongan dengan unique constraint dan transaksi; laporan memicu verifikasi, bukan langsung menutup lowongan.
- [x] **A10 — CSV ekspor → impor:** CSV data dengan skema round-trip, preview data nyata, validasi bersama dan ringkasan baris gagal.

- [x] **A11 — Login extension:** email wajib terverifikasi; throttle akun/IP, ability minimum dan token maksimal 30 hari termasuk token lama tanpa expiry; expiry/revoke diuji melalui bearer token nyata.
- [x] **A12 — AI photo/kuota:** reservasi kredit atomik bersama request UUID unik; worker asinkron dan settlement/refund sekali; status/retry, recovery job unik, expiry antrean serta cleanup sumber privat/orphan tersedia.
- [x] **A13 — Survey global:** undangan opsional dengan penundaan tujuh hari per akun dalam sesi; auth/OAuth, pembayaran dan tugas inti tidak dialihkan ke survey.
- [x] **A14 — Cache PWA:** hanya aset publik yang diizinkan dan aman; HTML/API privat tidak disimpan; cache lama dibersihkan saat aktivasi, logout dan pergantian akun.
- [x] **A15 — Label dan kontrol:** label/input terhubung dengan ID per komponen; kontrol ikon bernama, state expanded, tooltip saat fokus dan outline keyboard tersedia.

- [x] **A16 — Keterbacaan:** navigasi sidebar 14px dan label 12px; token teks dengan kontras normal 7,41:1 serta pemeriksaan state aktif, hover dan disabled pada CSS hasil build.
- [x] **A17 — CSS/design system:** Tailwind 3 melalui PostCSS; dependency plugin Vite v4 yang tidak dipakai dihapus, utility khusus didefinisikan dan deklarasi hasil build diuji.
- [x] **A18 — Konten/navigasi:** bahasa Indonesia pada sidebar, form tracker dan Explore; klaim tanpa bukti diganti sumber, waktu pemeriksaan dan keterangan verifikasi HTTP yang spesifik.
- [x] **A19 — Modal:** dialog bernama, aria-modal, prop ukuran, focus trap/restore termasuk dialog kosong, stack scroll lock dan cleanup navigasi; runtime serta directive Alpine diuji.
- [x] **A20 — Status pembayaran UI:** error pemeriksaan terlihat dan bisa dicoba ulang; satu request aktif, timeout/backoff, lifecycle Livewire dan pemulihan browser Back; gangguan provider memakai respons 503.
- [x] **A21 — Data besar:** page size divalidasi dengan maksimum 100, pagination Kanban 30 item per kolom, ekspor kecil streaming dan ekspor besar melalui antrean privat dengan expiry, otorisasi ulang serta cleanup.
- [x] **A22 — Regression suite:** route/skema CSV aktif diuji; persistensi Explore diperiksa sebelum copy; kegagalan ekspektasi legacy terselesaikan dan coverage A01–A15 tetap lulus.

**Verifikasi terakhir:** suite penuh **351/351 tes PHP lulus, 1.648 assertion**, tanpa failure/error; terdapat sembilan deprecation PHPUnit. **44/44 tes Node lulus** untuk dialog, polling pembayaran/status AI, service worker dan transisi akun/logout. Kontrak utility CSS hasil build dan kontras lulus. Build Vite, kompilasi Blade, lint 91 file PHP baru/berubah dan pemeriksaan whitespace lulus. Review independen menemukan polling berhenti pada pemulihan halaman browser Back; implementasi dan regresi pemulihan tersebut sudah diperbaiki.

**Batas verifikasi:** UI browser A10 belum tuntas; computed style, operasi keyboard/pembaca layar dan tampilan responsif A15–A19, transisi offline/worker terpasang A14, provider AI/pembayaran nyata, konkurensi MySQL dan benchmark dataset besar belum diuji. Penandaan selesai berarti implementasi dan regresi lokal, bukan persetujuan rilis produksi.

**Tindak lanjut deployment:** migration belum diterapkan ke produksi; queue `discovery` serta koneksi `ai_photos` dan `exports` membutuhkan worker asinkron, scheduler recovery/cleanup harus aktif, dan batas egress scraper perlu diterapkan di infrastruktur. Lihat [catatan SSRF/egress](../../security/job-scraping-egress.md), [premium dan status tracker](../../security/premium-and-job-status-deployment.md), [pelaporan dan CSV](../../security/reports-and-csv-deployment.md), [A11–A15](../../security/a11-a15-deployment.md), serta [A16–A22](../../security/a16-a22-deployment.md).

## Cakupan dan metode

Audit checkout lokal meliputi autentikasi/extension, tracker tabel/Kanban, Explore, interview/survey, CV, AI, pembayaran, admin, impor/ekspor, navigasi, komponen modal, dan service worker. Stack proyek: Laravel, Livewire, Sanctum, Vite, Tailwind dan Alpine.

Pemeriksaan terdiri dari pembacaan kode, suite PHPUnit, build frontend ke direktori sementara, HTTP landing/login lokal, dan pembuktian controller/komponen dengan fixture SQLite `:memory:`. HTTP provider dan email pada proof harness dipalsukan. Laporan September dipakai sebagai petunjuk dan pembuktiannya dijalankan ulang; hasil lama tidak diperlakukan sebagai hasil pengujian baru.

Kode produk dan database aplikasi tidak diubah. Berkas yang dibuat adalah laporan dan proof audit. Browser tidak tersedia (`iab` tidak tersedia, inventaris browser kosong). Karena itu audit ini **belum merupakan validasi visual atau pengujian end-to-end browser**. Tidak ada klaim tentang reproduksi di produksi, kecepatan riil, Core Web Vitals, keberhasilan payment gateway, rendering PDF, atau kualitas hasil AI. Temuan berbasis markup/CSS dibedakan dari masalah yang terbukti pada fixture.

Severity: **Critical** untuk integritas transaksi, data lintas pengguna, dan kendali admin tanpa otorisasi; **Major** untuk alur inti yang rusak, privasi, aksesibilitas, dan risiko biaya/ketersediaan; **Minor** untuk inkonsistensi yang masih menyediakan jalur penyelesaian. Severity tidak sama dengan estimasi usaha.

## Tabel temuan masalah

| ID | Komponen/Fitur | Isu/Bug/Pain Point dan bukti | Severity | Rekomendasi cepat | Status perbaikan |
|---|---|---|---|---|---|
| A01 | Webhook pembayaran | `PaymentController.php:316` menerima status dari payload publik tanpa verifikasi provider. Fixture webhook tanpa signature menghasilkan SUCCESS dan 10 kredit; tidak ada request provider. | Critical | Jadikan webhook pemicu verifikasi server-to-server memakai order tersimpan; cocokkan merchant, order, nominal dan status sebelum memberi manfaat. | ✅ Sudah diimplementasikan |
| A02 | Idempotensi pembayaran | `Payment.php:129` dapat menurunkan SUCCESS menjadi FAILED. Urutan completed → failed → completed menghasilkan 10 → 10 → 20 kredit. Guard SUCCESS di transaksi tidak mencegah replay lintas status. | Critical | SUCCESS harus terminal; pakai ledger grant unik per payment/manfaat, transaksi dan lock. Refund menjadi alur terpisah. | ✅ Sudah diimplementasikan |
| A03 | Admin support | Grup admin `routes/web.php:387` hanya auth/verified; route feedback `:570` dan `AdminSupportController` tidak memeriksa admin. Fixture user A berhasil mengubah status tiket B. Bukti controller plus middleware, belum request HTTP penuh. | Critical | Tambahkan middleware admin pada boundary dan policy pada baca/balas/status/hapus; uji guest, user, premium dan admin. | ✅ Sudah diimplementasikan |
| A04 | CV skill | `SkillsForm.php:87` mengambil skill global pada save dan mengisi `user_id` dari aktor. Fixture A memindahkan skill B ke A. Reorder `:178` juga memakai lookup global. | Critical | Scope semua lookup mutasi ke relasi pemilik; owner tidak boleh menjadi field edit. Periksa pola serupa di seluruh form CV. | ✅ Sudah diimplementasikan |
| A05 | Admin scraper | `/admin/scraper` tidak memiliki middleware admin; `ScraperDashboard.php:57` mount dan aksi pengaturan/crawl tidak memeriksa role. Mount bahkan seed source jika kosong. | Critical | Guard route, mount dan setiap aksi; pastikan custom middleware tetap berlaku pada update Livewire. Jangan seed saat kunjungan halaman. | ✅ Sudah diimplementasikan |
| A06 | Scrape URL | `JobScraperController.php:13` hanya memvalidasi format URL. Fixture membuktikan request diteruskan ke target loopback melalui fake HTTP. Akses jaringan internal sebenarnya belum diuji. | Major | Batasi tujuan/domain; tolak alamat nonpublik, validasi DNS dan setiap redirect, lengkapi dengan batas egress. | ✅ Sudah diimplementasikan |
| A07 | Premium | `User.php:347` tidak mengecek `premium_until`; field tersebut juga tidak fillable sementara handler sukses memperbaruinya melalui `update`. Fixture premium kedaluwarsa masih lolos `isPremium()`. | Major | Satukan definisi entitlement aktif dan durasinya; simpan masa berlaku secara eksplisit; uji expired, renewal dan status akun. | ✅ Sudah diimplementasikan |
| A08 | Explore → tracker | `ExploreJobs.php:190` menyimpan `application_status=Applied`; `JobKanbanBoard.php:14` hanya memiliki On Process/Accepted/Rejected. Fixture menghasilkan `kanban_has_status=false`. Tabel memiliki Applied, sehingga representasi berbeda antar tampilan. | Major | Tetapkan satu kontrak status dan tahap; migrasikan nilai lama, lalu gunakan di Explore, tabel, Kanban, CSV dan ringkasan. | ✅ Sudah diimplementasikan |
| A09 | Pelaporan lowongan | `ExploreJobs.php:144` menambah hitungan tanpa deduplikasi pelapor. Fixture satu user melapor tiga kali menghasilkan `report_dead_count=3`, status closed. | Major | Satu laporan per user/lowongan dengan unique constraint; laporan memicu pemeriksaan, bukan otomatis membuktikan lowongan tutup. | ✅ Sudah diimplementasikan |
| A10 | CSV ekspor → impor | `JobApplicationImportExportController.php:127` menambahkan metadata sebelum header dan ringkasan setelah data; importer `:276` menganggap baris pertama header. Validator menolak header ekspor pada fixture. | Major | Sediakan CSV data dengan skema round-trip, pisahkan laporan human-readable; tambahkan preview dan ringkasan baris gagal. | ✅ Sudah diimplementasikan |
| A11 | Login extension | `routes/api.php:7` tidak memakai throttle. Controller menerbitkan token tanpa pemeriksaan verifikasi email atau pembatasan ability/expiry eksplisit. | Major | Rate limit akun dan IP; putuskan aturan akun belum terverifikasi; terbitkan ability minimum dan masa berlaku, lalu uji revoke/expiry. | ✅ Sudah diimplementasikan |
| A12 | AI photo/kuota | `AiPhotoController.php:68` menunggu provider sampai 300 detik secara sinkron; kredit dikurangi sebelum record hasil dibuat. `User.php:918` memeriksa saldo dari model lalu decrement tanpa reservasi atomik. Risiko konkurensi dan kehilangan kredit setelah gagal simpan masih berbasis kode. | Major | Reservasi saldo atomik, job berstatus queued/processing/completed/failed, settlement/refund dan idempotensi. UI memberi status dan jalur mencoba lagi. | ✅ Sudah diimplementasikan |
| A13 | Survey global | `CheckSurveyRedirect.php` mengalihkan GET user berumur ≥3 hari yang belum survey ketika survey aktif. Checkout waiting/success/callback dan halaman kerja tidak termasuk pengecualian. | Major | Jadikan survey undangan yang bisa ditunda; lindungi login/OAuth, pembayaran dan tugas inti dari interupsi. Uji saat flag aktif. | ✅ Sudah diimplementasikan |
| A14 | Cache PWA | `public/sw.js:40` menyimpan semua GET same-origin sukses, termasuk halaman autentikasi/privat, ke cache bersama; tidak ada pembersihan cache saat logout. Potensi data akun lama terlihat pada fallback offline, belum diuji di browser. | Major | Cache hanya aset publik yang diizinkan; jangan cache HTML/API privat atau pembayaran, bersihkan cache yang relevan saat logout/pergantian akun. | ✅ Sudah diimplementasikan |
| A15 | Label dan kontrol | `job-application-form.blade.php:24` label Company tidak punya for dan input tidak punya id/aria-label; pola berulang pada field lain. Sidebar tombol close/logout/collapse berupa ikon tanpa nama aksesibel; tooltip hanya hover. | Major | Hubungkan label/input; beri nama tombol, tooltip saat fokus, aria-expanded dan focus-visible. Uji keyboard serta pembaca layar. R-32. | ✅ Sudah diimplementasikan |
| A16 | Keterbacaan | Sidebar memakai label 8–9px dan `text-zinc-400` di latar #fafafa. Warna Tailwind #a1a1aa di #fafafa dihitung ≈2,455:1, di bawah minimum 4,5:1 untuk teks normal. Belum mengukur computed style browser. | Major | Gunakan token teks yang memenuhi kontras; besarkan teks kerja dan label, audit state normal/hover/disabled. R-25. | ✅ Sudah diimplementasikan |
| A17 | CSS/design system | `tailwind.config.js` tidak mendefinisikan zinc-850/slate-555, ukuran 5.5/6.5 atau shadow-3xs yang dipakai Blade. Selector tersebut tidak ditemukan di CSS publik saat audit; beberapa di antaranya dapat berasal dari konvensi versi lain. | Minor | Tetapkan satu versi dan token yang didukung; ganti utility tak tersedia atau definisikan secara eksplisit. Periksa hasil CSS build dan tampilan. | ✅ Sudah diimplementasikan |
| A18 | Konten/navigasi | Sidebar: Dashboard/Track Progress/Summary/Explore Jobs; form tracker Inggris, pesan sistem Indonesia. Explore menyebut verified/anti-ghosting/real-time dan ribuan lowongan tanpa bukti untuk jaminan tersebut pada UI. Ini klaim belum tervalidasi, bukan kesimpulan bahwa seluruh lowongan palsu. | Minor | Gunakan bahasa Indonesia konsisten; tampilkan sumber, tanggal terakhir diperiksa dan status verifikasi spesifik. R-15/R-36/R-38. | ✅ Sudah diimplementasikan |
| A19 | Modal | `components/modal.blade.php` punya Escape dan logika tab, tetapi tidak mendefinisikan dialog/aria-modal/label; variabel maxWidth dihitung tetapi kontainer tetap max-w-md. Pengembalian fokus ke pemicu belum terlihat. | Major | Standarkan komponen dialog dengan nama, focus trap dan restore focus; gunakan prop ukuran. Uji seluruh modal, termasuk tanpa elemen fokus. R-32. | ✅ Sudah diimplementasikan |
| A20 | Status pembayaran UI | `payment/waiting.blade.php:182` hanya menulis error fetch ke console. Pengguna tetap melihat menunggu tanpa penjelasan gangguan; polling interval 10 detik tidak mencegah request overlap dan bergantung DOMContentLoaded. | Major | Tampilkan status gagal memeriksa beserta retry; satu request aktif, backoff, timeout dan lifecycle navigasi Livewire. R-27. | ✅ Sudah diimplementasikan |
| A21 | Data besar | `UserManagement.php:608` memperlakukan all sebagai 1.000.000 baris; `JobTableList.php:430` memakai perPage publik tanpa cap server; Kanban mengambil semua item per kolom. Ini risiko skalabilitas kode, belum hasil benchmark. | Major | Validasi page size dan cap; gunakan lazy pagination per kolom serta export terjadwal untuk dataset besar. | ✅ Sudah diimplementasikan |
| A22 | Regression suite | 63 test: 55 lulus, 8 gagal, 253 assertion, 9 deprecation. Empat Explore gagal pada teks Indonesia yang berubah ke Inggris; empat export memanggil route legacy yang dikomentari. Beberapa assertion database setelah assertion teks tidak sempat dijalankan. | Major | Selaraskan kontrak bahasa/rute; uji perilaku data secara independen dari copy. Tambah coverage A01–A10 sebelum perubahan visual. | ✅ Sudah diimplementasikan |

Referensi file pada tabel bersifat relatif terhadap root proyek. Bukti yang dapat dijalankan ulang ada di [proof-harness.php](proof-harness.php), [proof-output.txt](proof-output.txt), [workflow-proof.php](workflow-proof.php), [workflow-output.txt](workflow-output.txt) dan [test-summary.txt](test-summary.txt).

## Alur utama dan perbaikan pengalaman pengguna

Alur sasaran: cari lowongan → baca sumber/detail → simpan ke tracker → catat bahwa lamaran sudah dikirim → pantau tahap → jadwalkan interview → tutup sebagai diterima/ditolak. Menyimpan lowongan dan mengirim lamaran perlu dibedakan, karena aksi simpan sekarang langsung memakai tanggal hari ini dan tahap Applied. Status kanonik harus disepakati sebelum migrasi, termasuk pemetaan Declined/Rejected dan Applied/On Process.

| Alur | Kondisi saat ini | Rekomendasi dan kriteria selesai |
|---|---|---|
| Pengguna baru | Banyak modul langsung tersedia; survey dapat menjadi interupsi setelah hari ketiga. | Empty state tracker mengajak “Tambah lamaran pertama” atau “Cari lowongan”; survey tidak menghalangi tugas. Ukur keberhasilan sampai item pertama tersimpan. |
| Explore | Simpan dan buka portal berupa ikon kecil; laporan berulang memengaruhi status global. | Tombol “Simpan lowongan” dan “Buka situs perusahaan”; tampilkan sudah tersimpan dan sumber/tanggal pemeriksaan. Membuka portal tidak mengklaim lamaran terkirim. |
| Tracker | Status berbeda antar sumber dan tampilan. | Tabel/Kanban menampilkan dataset yang sama, filter tersinkron dan pilihan tampilan tersimpan. Setiap perubahan tahap memberi umpan balik dan bisa dikoreksi. |
| CSV | Ekspor laporan dan importer data tidak kompatibel; rincian error per baris terkumpul tetapi tidak dikirim ke UI pada sukses parsial. | Preview, total valid/gagal/duplikat, persetujuan impor dan file kesalahan. Reimpor ekspor tidak merusak field dan tidak menggandakan data tanpa pilihan pengguna. |
| AI | Request panjang, saldo dan hasil tidak menjadi satu lifecycle yang tahan gagal. | Sebelum mulai, tampilkan jenis proses dan biaya kredit aktual; sesudah mulai, status persisten. Kegagalan punya retry aman dan saldo dapat direkonsiliasi. |
| Pembayaran | Status gateway, entitlement dan UI dapat tidak sejalan. | Tampilkan order, nominal, masa berlaku/manfaat dan status verifikasi. Kegagalan memeriksa berbeda dari pembayaran gagal. Reload atau webhook duplikat tidak menggandakan manfaat. |

## Rencana modernisasi dan revamp UI/UX

Usulan ini merupakan arah untuk ditinjau, belum desain final atau persetujuan implementasi. Asumsi pengguna utama adalah pencari kerja Indonesia yang menggunakan ponsel untuk pencarian dan desktop untuk pengelolaan CV/lamaran. Tujuan revamp: mempercepat tugas inti dan membuat status serta biaya dapat dipahami. Identitas warna ungu yang sudah ada dipertahankan sebagai kandidat, tanpa menganggap referensi “ala Notion” sebagai identitas akhir.

Tiga pendekatan dipertimbangkan: memperbaiki komponen dan alur secara bertahap, merombak total frontend, atau memodernisasi hanya landing page. **Rekomendasi: perbaikan bertahap berbasis alur.** Pendekatan ini memungkinkan pengujian kontrak data dan komponen sebelum migrasi halaman; rewrite total menambah risiko regresi, sedangkan perubahan landing saja tidak menyelesaikan masalah tugas inti.

Design Read usulan: workspace karier untuk pencari kerja Indonesia, bahasa visual terang dan praktis, ENERGY 1 / RHYTHM 2 / MOTION 1. Fokus per halaman harus jelas, misalnya lamaran yang perlu ditindaklanjuti pada dashboard dan daftar hasil pada Explore. Spasi membedakan tugas utama, filter dan informasi pendukung. Aksen ungu dipakai untuk aksi utama/fokus; warna status tetap memiliki label teks. Motif identitas berasal dari perjalanan lamaran dan tahapnya, bukan dekorasi generik.

| Bagian design system | Arahan | Alasan dan acceptance criteria |
|---|---|---|
| Warna | Token surface, border, text-primary, text-secondary, action, focus dan status. | Token teks memenuhi 4,5:1; perubahan warna diuji pada setiap pasangan latar. Ungu tetap harus memenuhi kontras ketika dipakai untuk teks. |
| Tipografi | Gunakan satu keluarga utama yang sudah ada, kandidat Inter; body kerja sekitar 14–16px dan label sekitar 12–14px. | Keterbacaan tabel/form dan pengurangan permintaan font; ukuran adalah usulan, diuji dengan densitas data dan zoom. |
| Spasi dan ukuran | Skala konsisten untuk form, filter, tabel dan dialog; target sentuh utama dirancang sekitar 44px. | Membantu penggunaan ponsel dan mengurangi salah tekan; bukan memaksakan semua halaman pada grid identik. |
| Komponen | Button/link/icon-button, input/select, status-chip, alert, toast, empty state, progress, dialog dan pagination. | Semantik serta state idle/loading/success/error/disabled terdokumentasi; nama aksesibel dan keyboard diuji. |
| Navigasi | Lamaran, Cari lowongan, Jadwal interview, CV & portofolio, Studio AI; analisis sebagai bagian konteks lamaran. | Menyusun menu berdasarkan pekerjaan pengguna. Penempatan Ringkasan/Automation dipastikan melalui uji pencarian fitur, bukan langsung dihapus. |
| Responsif | Desktop mendukung tabel; mobile menampilkan item ringkas dan aksi yang jelas. Filter dapat dibuka dalam panel. | Tidak ada overflow halaman pada lebar 360/390/768/1280px; scroll tabel jika diperlukan harus terlokalisasi dan jelas. Belum diuji saat audit. |
| Motion | Transisi pendek untuk membuka panel/dialog atau perubahan state; hormati reduced-motion. | Gerakan menjelaskan perubahan, tidak mengalihkan perhatian dari daftar kerja. |

Contoh microcopy usulan: “Track Progress” → “Lamaran saya”; “App Status” → “Status lamaran”; “Current Stage” → “Tahap rekrutmen”; “Verified, anti-ghosting...” → “Lowongan dari beberapa portal. Periksa detail dan tanggal terakhir diperiksa sebelum melamar.” Error payment: “Status pembayaran belum bisa diperiksa. Coba lagi sebentar lagi.” Klaim ribuan lowongan hanya ditampilkan jika berasal dari hitungan dataset aktual.

Kebutuhan aksesibilitas label mengikuti [panduan label W3C](https://www.w3.org/WAI/tutorials/forms/labels/). Ambang kontras mengikuti [WCAG 2.2, Contrast Minimum](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html). Ukuran tipografi, target sentuh dan arah visual di atas adalah usulan desain audit, bukan hasil usability study atau sertifikasi kepatuhan.

## Actionable roadmap

Urutan berikut merupakan tahap kerja, bukan janji tanggal penyelesaian. Estimasi kalender baru layak dibuat setelah kapasitas tim, kondisi staging dan keputusan kontrak status diketahui.

| Tahap | Prioritas/backlog | Pemilik usulan | Dependensi | Kriteria selesai |
|---|---|---|---|---|
| Quick Wins, P0 | A01–A05: verifikasi payment, terminal state/ledger, admin boundary dan owner scoping CV. | Backend + QA | Kontrak provider dan fixture lintas user/role | Payload palsu tidak memberi manfaat; replay/konkurensi hanya satu grant; user biasa ditolak semua aksi admin; objek B tidak berubah oleh A. |
| Quick Wins, P1 | A07–A10: entitlement aktif, kontrak status, dedup pelaporan dan CSV data. | Backend + product + QA | Sepakati definisi status, arti “simpan” dan “melamar” | Item Explore muncul konsisten pada semua tampilan; satu pelapor tidak menutup lowongan; round-trip CSV lulus; premium expired mengikuti kebijakan. |
| Quick Wins, P1 | A13/A15/A16/A18/A20: survey tidak memblokir, label/nama tombol, kontras, bahasa dan error payment terlihat. | Frontend + designer + QA | Token teks dan kamus produk | Tugas inti selesai tanpa survey; kontrol dapat dinamai/difokuskan; pasangan warna lolos hitungan; error payment terlihat dengan retry. |
| Quick Wins, P1 | A22: pulihkan regression suite dan tambah test kritis. | QA + backend | Kontrak bahasa/rute disepakati | Route ekspor aktif diuji; assertion data tetap berjalan saat copy berubah; P0 teruji di CI. |
| Jangka Menengah, P1 | A06/A11/A14: egress scraper, auth extension, cache publik saja. | Backend + frontend + infra | Daftar tujuan scraping dan kebijakan token/offline | Target nonpublik/redirect ditolak; rate limit/revoke/expiry teruji; logout/offline tidak membuka halaman privat akun lama. |
| Jangka Menengah, P1 | A12/A21: lifecycle AI/ledger kredit, pagination terbatas dan export job. | Backend + infra + QA | Worker, retry, observabilitas dan budget provider | Dua request dengan satu kredit tidak menghasilkan dua proses berbayar; kegagalan dapat direkonsiliasi; workload besar memenuhi target yang diukur. |
| Jangka Menengah, P2 | A17/A19 dan design system: token CSS, dialog standar, navigasi/form/empty state. | Designer + frontend | Baseline browser/staging dan prioritas alur | CSS utility tersedia; Escape/Tab/restore focus lulus; uji desktop/mobile pada tracker, Explore, CV dan checkout. |
| Transformasi Skala Besar, P2 | Workspace tugas: dashboard tindakan, definisi pipeline lamaran, CV/portfolio dan AI terhubung ke konteks lowongan. | Product + design + engineering | Stabilitas P0/P1, riset pengguna dan migrasi data | Pilot membuktikan peningkatan penyelesaian tugas; rollout bertahap dengan rollback dan pemantauan regresi. |
| Transformasi Skala Besar, P2 | Permission admin terperinci, audit trail transaksi, monitoring dan quality gate end-to-end. | Architect + infra + QA | Model akses organisasi, staging dan data sintetis | Matriks role/action, reconciliation payment/credits, restore drill dan smoke browser menjadi gate rilis. |

Metrik baseline yang perlu dikumpulkan: keberhasilan tambah/simpan lamaran, konsistensi hitungan tabel/Kanban, waktu menyelesaikan tugas, tingkat kegagalan/parsial impor, mismatch pembayaran/manfaat, saldo yang perlu koreksi, durasi AI p50/p95, serta jumlah request/SQL per halaman. Target angka ditetapkan setelah pengukuran, tidak direkayasa dari audit kode.

## Bukti dan verifikasi

| Pemeriksaan | Hasil | Batas |
|---|---|---|
| Proof harness September dijalankan ulang | Exit 0; unsigned/replayed payment, tiket lintas user, pemindahan skill, loopback URL dan expired premium terkonfirmasi | Controller/komponen, bukan session/browser produksi |
| Workflow proof baru | Exit 0; Applied tidak ada pada status Kanban, satu user menutup lowongan dengan tiga laporan, header ekspor tidak cocok importer | Fixture database lengkap hasil migrasi di memory |
| PHPUnit seluruh suite | 63 test, 55 lulus, 8 gagal, 253 assertions; exit 1 | Empat kegagalan copy dan empat route legacy; bukan delapan bug runtime baru |
| Build frontend | Exit 0; 53 modules; CSS 179,74 kB (gzip 25,94), JS 36,08 kB (gzip 14,55) | Output /private/tmp; ukuran bundle bukan ukuran seluruh halaman atau benchmark |
| GET / dan /login lokal | HTTP 200; HTML memakai aset build publik yang sudah ada | Tanpa screenshot, eksekusi JavaScript atau submission form |
| Kontras statis | #a1a1aa/#fafafa ≈2,455:1 | Berdasarkan token CSS dan markup, bukan computed style |
| Browser | Tidak tersedia | Semua validasi visual, mobile, keyboard runtime, payment/provider, PDF dan AI nyata tetap terbuka |

Reproduksi proof: `php docs/audits/2026-10-08/proof-harness.php` dan `php docs/audits/2026-10-08/workflow-proof.php`. Keduanya memaksa koneksi memory, memeriksa isolasi dan memakai fake HTTP/mail. PHPUnit dijalankan dengan APP_ENV=testing, DB_CONNECTION=sqlite, DB_DATABASE=:memory:, MAIL_MAILER=array, CACHE_STORE=array, QUEUE_CONNECTION=sync, SESSION_DRIVER=array, LOG_CHANNEL=null dan APP_CONFIG_CACHE yang menunjuk file sementara tidak ada. Test summary mempertahankan nama delapan test gagal.

## Delivery gate laporan

Gate ini memeriksa kejujuran dan ketertelusuran **laporan audit**. Produk yang diaudit masih memiliki temuan terbuka; gate produk tidak dinyatakan lulus dan rekomendasi revamp bukan UI siap rilis.

- PASS, Hard Gate laporan: tidak ada klaim performa/usability palsu, metrik berasal dari hasil lokal, batas browser eksplisit, dan aplikasi tidak diubah. Bukti: tabel verifikasi dan berkas proof/output.
- PASS, Purpose Gate rekomendasi: setiap keputusan visual pada tabel design system memiliki alasan dan kriteria; tidak ada aset/testimoni/statistik yang dibuat.
- PASS, Liveliness arah usulan: audiens, bahasa visual dan dial dinyatakan; fokus, spasi, aksen dan motif perjalanan lamaran dijelaskan. Konsistensi tampilan belum diklaim.
- PASS, Craftsmanship laporan: temuan bernomor dengan sumber/severity/rekomendasi, roadmap dengan pemilik/dependensi/kriteria selesai, hasil test gagal dilaporkan apa adanya. Validasi runtime yang belum dilakukan tercantum.

Sebelum revamp dirilis, gate produk harus dijalankan dengan browser: klik seluruh kontrol, task completion, keyboard/screen reader, fokus/dialog, breakpoint/zoom, seluruh state, dan pengecekan klaim konten. Tidak ada hasil PASS produk untuk hal-hal tersebut dalam audit ini.

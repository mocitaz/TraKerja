# Rencana perbaikan TraKerja

Tanggal: 9 September 2026. Status: rencana untuk ditinjau; belum diimplementasikan.

Sumber: [audit TraKerja](audit-trakerja.md). ID F01–F22 merujuk ke audit tersebut. Dokumen RPK Legal Workspace yang muncul pada konteks IDE merupakan proyek berbeda dan tidak menjadi dasar perubahan ini.

## Sasaran dan pendekatan

Tutup jalur pembayaran palsu dan akses lintas pengguna terlebih dahulu, lalu pastikan saldo kredit, masa berlaku premium, serta operasi admin dapat dipercaya. Setelah itu selesaikan bug operasional dan validasi deployment.

Pendekatan yang direkomendasikan: perubahan bertahap per kelompok risiko dengan regression test dan rilis kecil. Hotfix saja cepat tetapi menyisakan konsistensi domain; refactor menyeluruh sekaligus membuat review dan rollback sulit. Rencana ini menggabungkan penutupan segera dan penguatan domain sesudahnya.

Estimasi adalah hari kerja efektif seorang engineer yang memahami Laravel, termasuk test dan review dasar; pekerjaan operator/provider dan antrean review dapat menambah waktu kalender. Tahap boleh tumpang tindih sesuai kapasitas, tetapi dependency dan gerbang kelulusan tetap berlaku. Tidak semua temuan berstatus perlu verifikasi akan membutuhkan perubahan kode setelah diuji.

## Urutan pengerjaan

| Tahap | Hasil | Temuan | Estimasi |
|---|---|---|---|
| 0 | Lingkungan pengujian terisolasi dan bukti regresi | Semua, terutama F01–F04 | 0,5–1 hari |
| 1 | Pembayaran palsu dan akses lintas user/admin tertutup | F01–F08, F15 | 4–7 hari |
| 2 | Entitlement dan kuota AI konsisten | F01, F11, F12 | 3–5 hari |
| 3 | Form, ekspor, survey, OAuth dapat diandalkan | F13–F20 | 3–5 hari |
| 4 | Admin sensitif, backup, dependency, deployment diperkuat | F09, F10, F21, F22 | 4–8 hari |
| 5 | Bukti kesiapan staging dan rilis bertahap | Seluruh temuan | 2–3 hari |

Perkiraan keseluruhan: sekitar 17–29 hari kerja efektif. Target 1–3 hari pertama adalah menutup sebagian jalur paling mendesak, bukan menyelesaikan seluruh audit. Rotasi credential dan perbaikan backup dimulai lebih awal bersama operator meskipun validasi menyeluruhnya berada pada tahap 4.

## Tahap 0 — fondasi verifikasi

- [ ] Catat commit awal, status working tree, dan baseline test; pertahankan artefak audit.
- [ ] Paksa database pengujian ke SQLite memory untuk tes cepat. Gunakan MySQL disposable dengan konfigurasi setara deployment untuk tes locking/concurrency; konfirmasi engine deployment terlebih dahulu.
- [ ] Fake HTTP, email, notifikasi, storage dan queue pada tes yang memerlukannya; cegah request eksternal tidak sengaja. Jangan menjalankan scraper atau backup data riil dari test.
- [ ] Ubah bukti controller-level menjadi regression test HTTP dan Livewire yang mengassert perilaku aman. Fixture memuat guest, user A/B, premium dan admin.
- [ ] Gunakan migrasi/factory aplikasi pada regression test; harness schema minimal audit tetap menjadi bukti tambahan, bukan pengganti integrasi.

**Lulus bila:** database operasional tidak tersentuh, baseline tercatat, dan test baru gagal karena celah yang ditargetkan, bukan karena fixture/runtime rusak.

## Tahap 1 — penutupan celah mendesak

### Paket 1A: pembayaran — F01

Area: `app/Http/Controllers/PaymentController.php`, `app/Services/PakasirService.php`, `app/Console/Commands/SyncPendingPayments.php`, `app/Models/Payment.php`, dan seluruh jalur pemberian premium/kredit dari pembayaran.

- [ ] Buat `tests/Feature/PaymentWebhookSecurityTest.php`: payload completed dengan provider pending tidak memberi kredit; amount/order/merchant tidak cocok ditolak.
- [ ] Perlakukan webhook sebagai pemicu pemeriksaan provider. Ambil nominal/order dari database, bukan sebagai kebenaran dari payload. Verifikasi kontrak API resmi saat implementasi; signature hanya dipakai bila provider mendukungnya.
- [ ] Provider timeout/error tidak memberikan manfaat; catat status verifikasi yang dapat diulang. Jangan mengandalkan pengirim webhook pasti retry—sertakan rekonsiliasi pending.
- [ ] Larang transisi SUCCESS ke FAILED/CANCELED dari notifikasi biasa. Refund menjadi operasi terpisah.
- [ ] Satukan callback, webhook, polling, dan command rekonsiliasi pada satu jalur grant idempoten; hindari patch hanya pada webhook.
- [ ] Pindahkan email sukses ke queue setelah commit agar kegagalan email tidak mengubah hasil pembayaran.

**Lulus bila:** urutan completed → failed → completed serta dua notifikasi bersamaan hanya memberi satu manfaat; callback dan command tidak bisa menggandakannya. Provider palsu/pending/timeout tidak mengaktifkan premium.

### Paket 1B: admin dan pencabutan akses — F02/F04/F08/F15

Area: `routes/web.php`, `bootstrap/app.php`, `app/Providers/AppServiceProvider.php`, `app/Http/Middleware/EnsureUserIsAdmin.php`, `app/Http/Controllers/Admin/AdminSupportController.php`, `app/Livewire/Admin/*`, `app/Http/Controllers/ContactMessageController.php`.

- [ ] Buat `tests/Feature/AdminAuthorizationTest.php` dan `tests/Feature/AdminLivewireAuthorizationTest.php`: uji GET, baca objek, dan aksi mutasi sebagai guest/user/premium/admin.
- [ ] Pasang middleware admin pada seluruh kelompok route admin, termasuk landing photos/contact/feedback/scraper. Pertahankan CSRF web.
- [ ] Otorisasi ulang setiap aksi Livewire sensitif; mount/render saja tidak cukup. Daftarkan custom persistent middleware sesuai versi Livewire.
- [ ] Uji snapshot yang sudah terbuka setelah admin diturunkan role. Hasil harus 403 dan tidak ada mutasi; tentukan pencabutan session/token yang sesuai mekanisme penyimpanan aktual.
- [ ] Hilangkan seed/ingestion dari navigasi GET scraper; jalankan lewat operasi eksplisit berizin.
- [ ] Konsolidasikan route contact lama ke feedback yang dilindungi atau pensiunkan dengan respons jelas. Jangan sekadar menambah import model.

**Lulus bila:** user biasa tidak dapat membaca/mengubah data admin melalui URL, ID, request langsung atau komponen; admin sah tetap dapat menyelesaikan tugasnya.

### Paket 1C: kepemilikan CV — F03

Area: `app/Livewire/CvBuilder/*Form.php`, model CV terkait.

- [ ] Buat `tests/Feature/CvOwnershipTest.php` untuk save/delete/reorder lintas user pada skill, experience, project, education, organization, achievement.
- [ ] Resolve ID melalui relasi milik user pada setiap operasi; tambahkan policy jika dipakai lintas entry point.
- [ ] Hilangkan `user_id` dari payload update; owner ditentukan server hanya ketika create.
- [ ] Periksa `editMode`, ID publik, dan kedua objek yang ditukar saat reorder. Locked property melengkapi, bukan menggantikan, pemeriksaan owner.

**Lulus bila:** ID milik B yang dikirim A menghasilkan 403/404 tanpa mengubah satu pun atribut B; CRUD milik A tetap berjalan.

### Paket 1D: autentikasi extension, SSRF, credential — F05/F06/F07

Area: `routes/api.php`, `app/Http/Controllers/Api/ExtensionController.php`, `app/Providers/AppServiceProvider.php`, `app/Http/Controllers/JobScraperController.php`, `upload.exp`, konfigurasi deployment.

- [ ] Test limiter extension per akun dan IP, expiry token, revoke, serta abilities. Usulan awal: token 30 hari; kebijakan akun belum verified perlu konsisten dengan aplikasi.
- [ ] Terapkan throttle tanpa mencoba brute force layanan nyata. Pastikan sumber IP dapat dipercaya di belakang proxy.
- [ ] Bungkus fetch scraper dalam service dengan validasi skema, host, seluruh A/AAAA, redirect, batas respons dan timeout. Tolak loopback/private/link-local/reserved; cegah DNS berubah antara validasi dan koneksi. Lengkapi dengan egress firewall.
- [ ] Test fake URL loopback/IPv6/redirect private dan portal sah. Jangan memakai endpoint metadata cloud nyata.
- [ ] Operator merotasi credential deployment literal, memindahkan akses ke key/secret store terbatas, lalu memastikan credential lama tidak berlaku. Jangan mencetak nilainya.
- [ ] Bersihkan file sumber dan cek arsip yang relevan. Penulisan ulang history Git memerlukan rencana koordinasi tersendiri; penghapusan file bukan pengganti rotasi.

**Lulus bila:** limit/expiry/revoke bekerja, target internal ditolak sebelum fetch, dan rotasi credential dibuktikan operator. Pembatasan egress belum dapat dianggap selesai hanya dari unit test.

## Tahap 2 — integritas manfaat berbayar dan AI

### Paket 2A: ledger grant dan expiry — F01/F11

Area baru yang diusulkan: `app/Services/PaymentFulfillmentService.php`, `app/Models/PaymentGrant.php`, migrasi tabel `payment_grants`. Area existing: model User, controller payment, middleware premium, command sync.

- [ ] Tambah ledger grant unik per payment dan jenis manfaat; update status, kredit/premium, dan ledger berada dalam satu transaksi database.
- [ ] Definisikan `PaymentFulfillmentService::fulfill(Payment $payment): bool` hanya untuk transaksi yang sudah diverifikasi server. Semua entry point pembayaran memakai service yang sama; return false untuk grant yang sudah ada.
- [ ] Lock payment dan user yang relevan dengan urutan konsisten untuk mencegah dua order berbeda menimpa saldo user.
- [ ] Tambahkan cast tanggal dan penyimpanan expiry eksplisit; satukan semua pengecekan premium pada aturan entitlement yang sama.
- [ ] Buat `tests/Feature/PaymentFulfillmentTest.php` dan `tests/Feature/PremiumEntitlementTest.php`: duplicate grant, dua order user sama, expiry sebelum/sesudah batas, renewal, premium manual, legacy.
- [ ] Sebelum migrasi data, buat laporan read-only order SUCCESS, manfaat existing, dan expiry null. Backfill ledger mencatat pemenuhan historis yang dapat dibuktikan tanpa memberikan kredit ulang. Data ambigu masuk daftar review.

**Keputusan sebelum mengubah data lama:** arti expiry null (legacy lifetime atau data hilang), durasi produk, serta renewal diperpanjang dari tanggal sekarang atau akhir masa aktif. Default aman dalam rencana: jangan mencabut manfaat lama secara massal dan jangan menerbitkan kredit ulang.

**Lulus bila:** satu pembayaran memberikan satu paket manfaat, expiry konsisten pada semua fitur, dan migrasi historis tidak mengubah saldo diam-diam.

### Paket 2B: reservasi kredit dan pekerjaan AI — F12

Area: controller AI Photo/Analyzer/CoverLetter, helper saldo User; service reservasi kredit dan job AI sesuai jenis layanan.

- [ ] Test dua permintaan dengan saldo satu, retry request ID sama, provider timeout, job gagal setelah debit, serta penyimpanan hasil gagal.
- [ ] Reservasi kredit secara atomik sebelum provider dipanggil; simpan operasi dengan idempotency key unik yang dibatasi user dan jenis pekerjaan.
- [ ] Proses berat lewat queue; hasil dan status tersimpan agar refresh tidak membuat request/debit baru.
- [ ] Refund hanya sekali saat kegagalan terminal yang diketahui. Timeout ambigu masuk rekonsiliasi/status unknown agar tidak otomatis mengulang operasi provider berbiaya.
- [ ] UI menampilkan queued/processing/succeeded/failed beserta aksi retry yang sesuai; endpoint status hanya dapat diakses pemilik.

**Lulus bila:** saldo tidak negatif, retry tidak menggandakan biaya/debit, dua request tidak menggunakan satu kredit yang sama, dan hasil tetap dapat diambil setelah koneksi putus.

## Tahap 3 — bug fungsional, ekspor, dan alur pengguna

| Paket | Pekerjaan konkret | Area | Test/kriteria selesai |
|---|---|---|---|
| 3A — F13 | Petakan proficiency UI ke proficiency_level saat load/save/edit | SkillsForm, UserSkill, view skill | Create Expert → reload → edit → reload mempertahankan nilai |
| 3B — F14 | Samakan field konfirmasi password; validasi email edit unik; lindungi admin terakhir | UserManagement dan view | Password cocok lolos, mismatch/duplikasi memberi field error, admin terakhir tidak terhapus/diturunkan tanpa pengganti |
| 3C — F16 | Gunakan sanitizer CSV bersama pada user/payment/tracker | Export controller dan UserManagement | =1+1, +, -, @, kontrol ditulis sebagai teks; periksa hasil di spreadsheet |
| 3D — F17 | Satu query filter untuk daftar/ekspor/agregat; label statistik global eksplisit jika tetap global | Admin PaymentController | Search+tanggal+status menghasilkan ID daftar=ekspor dan angka konsisten |
| 3E — F18 | Batasi pagination maksimal 100; ekspor iteratif/queue tanpa get semua row | UserManagement dan export payment | Memori ekspor terukur pada dataset sintetis besar; row count benar; file ekspor privat jika job menyimpan artefak |
| 3F — F19 | Survey dapat ditunda dan tidak menghalangi support/payment/security; maintenance menerima webhook terverifikasi/retry terkontrol | CheckSurveyRedirect, SoftMaintenanceMode, view survey | User >3 hari tetap bisa support/bayar; provider fake diproses atau direkonsiliasi saat maintenance |
| 3G — F20 | Pulihkan OAuth state LinkedIn dan verifikasi cookie/callback staging | LinkedInAuthController | State salah/kosong ditolak; alur sah tetap login tanpa membocorkan code/token di log |

Setiap paket memuat regression test terfokus sebelum perbaikan. Jalankan test terkait, test auth yang sudah ada, dan build bila view/JS berubah. Jangan menutup item visual dengan test source saja.

## Tahap 4 — tata kelola admin dan operasional

### Paket 4A: permission dan audit — F09

- [ ] Pilih matriks awal yang sederhana: pengelola admin, operator, finance, support. Nama role tidak boleh otomatis memberi hak lebih luas daripada permission yang disepakati.
- [ ] Buat permission untuk users/admins.manage, entitlements.adjust, payments.view/export, tickets.manage, scraper.manage, integrations.manage, backups.download, audit.view.
- [ ] Migrasikan admin existing secara eksplisit setelah pemilik menentukan siapa pengelola utama; jangan memberikan semua permission ke semua role baru.
- [ ] MFA/re-auth untuk promote admin, pengubahan manfaat, integrasi, export sensitif, dan backup. UI konfirmasi menampilkan target/jumlah/akibat sebelum submit.
- [ ] Record audit actor/target/request ID/waktu/before-after teredaksi dalam operasi domain; simpan ke sink yang tidak dapat dihapus admin operasional. Retensi memakai service identity terpisah; hilangkan kemampuan cleanAll tanpa kontrol retensi.
- [ ] Test setiap pasangan role/aksi, termasuk direct API, snapshot lama, dan log kegagalan otorisasi yang relevan.

### Paket 4B: backup nyata — F10

- [ ] Ganti shell command interpolasi dengan argument-array process dan credential file terbatas; jangan simpan password di command/output.
- [ ] Exit nonzero, file kosong, atau output invalid harus gagal tanpa download sukses semu. Hapus fallback dump komentar.
- [ ] Simpan checksum, enkripsi, akses privat, retensi dan metadata backup; GET download mengambil artefak yang sudah dibuat, pembuatan memakai aksi terotorisasi.
- [ ] Uji restore database dan file ke lingkungan disposable, cocokkan schema/row fixture serta checksum. Operator menentukan target RPO/RTO dan jadwal.

### Paket 4C: dependency dan konfigurasi — F21/F22

- [ ] Audit ulang root npm, scraper-service, dan Composer dengan advisori pada waktu implementasi. Hasil 17 paket adalah snapshot audit, bukan angka tetap.
- [ ] Pisahkan runtime/browser/dev tooling, prioritaskan exposure nyata, update dalam perubahan kecil dengan lockfile dan tes; jangan menjalankan force upgrade otomatis.
- [ ] Verifikasi TLS/header/cookie aktual, proxy/origin firewall, CORS, dan CSP di staging. Header .htaccess yang ada tidak boleh diabaikan atau diduplikasi sembarangan.
- [ ] Uji URL hasil foto sebagai guest/owner/user lain, expiry dan delete; pastikan log/error tidak membawa credential atau data CV berlebih.
- [ ] Konfirmasi retensi foto/CV dan transfer data provider bersama pemilik produk. URL sulit ditebak tidak menggantikan kontrol akses.

**Lulus bila:** matriks role teruji, penghapusan jejak audit oleh operator ditolak, restore terbukti, dependency mempunyai hasil audit eksplisit, dan checklist deployment ditopang respons/konfigurasi aktual.

## Tahap 5 — gerbang rilis

- [ ] Semua celah Critical/High yang terverifikasi ditutup atau fitur terkait dinonaktifkan secara eksplisit sampai aman; temuan belum terverifikasi mempunyai hasil probe/keputusan yang tercatat.
- [ ] Test HTTP dan Livewire membuktikan isolasi user/role; jangan hanya mengulang harness controller.
- [ ] Test concurrency pada database setara produksi: event duplicate, dua order user sama, dua debit saldo satu, dan rollback parsial.
- [ ] Jalankan suite yang relevan lalu suite penuh; catat kegagalan lingkungan/legacy terpisah. Build lulus tidak menggantikan test.
- [ ] Browser smoke: login/logout/reset, CV, tracker, checkout sandbox, AI fake/sandbox, admin tiket/user/ekspor. Uji mobile, keyboard, focus dialog, sesi habis, refresh dan double submit.
- [ ] Latih migrasi dan restore di staging. Rilis schema tambahan sebelum kode yang memakainya; uji kompatibilitas versi lama/baru saat rollout.
- [ ] Rollback aplikasi tidak boleh menghapus ledger/data grant atau menghidupkan kembali jalur webhook rentan. Untuk masalah payment, gunakan penghentian grant yang terkontrol dan rekonsiliasi.
- [ ] Pantau grant ganda, kegagalan provider, saldo negatif, 403 admin, backlog AI, failed jobs dan usia backup. Tetapkan operator serta respons alert.
- [ ] Perbarui audit: status fixed/verified/deferred, commit, perintah test, hasil, dan batas yang masih ada. Bukti tidak memuat secret/data produksi.

## Keputusan yang diperlukan pada tahap terkait

| Keputusan | Dibutuhkan sebelum | Usulan awal |
|---|---|---|
| Durasi premium, expiry null, renewal, akun legacy | Backfill expiry/grant tahap 2A | Tinjau data read-only; pertahankan manfaat lama sampai ada aturan eksplisit |
| Siapa super admin, finance, support/operator | Migrasi permission tahap 4A | Mulai dengan permission minimum dan satu penanggung jawab utama |
| Portal mana yang harus didukung scraper | Penguncian domain tahap 1D | HTTPS portal yang digunakan; tetap validasi DNS/IP dan egress |
| Provider sandbox dan staging | Validasi integrasi/rilis | Data sintetis, gateway sandbox, mailer test |
| Retensi PII, backup RPO/RTO | Kebijakan operasi tahap 4 | Tetapkan bersama operator; jangan mengklaim recovery selesai sebelum drill |

Keputusan tersebut tidak menghalangi memulai test, perbaikan owner CV, dan otorisasi admin dasar. Tidak ada implementasi/deployment/rotasi credential yang dilakukan oleh dokumen rencana ini.

## Pekerjaan yang ditunda

Redesign menyeluruh, fitur AI baru, impersonation support, export center kompleks, serta bulk action dengan rollback ditunda sampai celah dan integritas inti selesai. Permission backend, logging sensitif, dan ekspor aman tetap prioritas; UI permission manager lengkap dapat menyusul setelah matriks akses stabil.

## Cakupan temuan

F01: 1A/2A; F02: 1B; F03: 1C; F04: 1B; F05: 1D; F06: 1D; F07: 1D; F08: 1B; F09: 4A; F10: 4B; F11: 2A; F12: 2B; F13: 3A; F14: 3B; F15: 1B; F16: 3C; F17: 3D; F18: 3E; F19: 3F; F20: 3G; F21: 4C; F22: 4C.

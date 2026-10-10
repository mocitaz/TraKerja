# Audit TraKerja — 9 September 2026

## 1. Ringkasan eksekutif

**Kesimpulan: perbaiki pembayaran dan otorisasi sebelum memperluas penggunaan aplikasi.** Audit kode lokal dan pengujian dengan data sintetis membuktikan bahwa webhook tanpa autentikasi dapat menambahkan kredit, tiket pengguna lain dapat diubah oleh user biasa melalui controller admin, dan penyimpanan skill CV dapat memindahkan kepemilikan entri pengguna lain. Ini merupakan risiko integritas transaksi dan privasi, bukan sekadar persoalan kualitas kode.

Konteks yang ditemukan: TraKerja adalah aplikasi pelacakan lamaran, pencarian lowongan, CV/portfolio, kalender wawancara, layanan AI, serta pembayaran premium/add-on. Stack: Laravel **12.62.0**, Livewire **3.8.2**, Sanctum **4.3.2**, Vite **7.1.10**, Tailwind/Alpine. Role yang terlihat pada implementasi adalah `user` dan `admin`; premium merupakan entitlement, bukan role operasional terpisah. Tidak ditemukan model role perusahaan/rekruter pada area yang diperiksa.

**Batas audit:** URL, akun uji, prioritas khusus, dan definisi hak akses pada lampiran masih placeholder. Audit dilakukan terhadap checkout lokal, bukan terhadap produksi/staging. Tidak ada pemindaian layanan, brute force, penghapusan data riil, pengiriman email, atau perbaikan kode aplikasi. Pengujian mutasi hanya terhadap SQLite `:memory:` dengan fixture sintetis; HTTP dan email pada harness dipalsukan. Status “terverifikasi” berarti terbukti pada kode atau pengujian lokal yang disebutkan, bukan bahwa eksploitasi produksi telah dilakukan. Pengujian controller tidak mencakup seluruh browser/session/middleware HTTP. Skor berikut bersifat indikatif dan bukan sertifikasi.

| Aspek | Skor /10 | Dasar dan batas keyakinan |
|---|---:|---|
| Keamanan | 2 | Webhook palsu, kontrol akses admin tidak konsisten, dan IDOR CV; keyakinan tinggi pada kode lokal. |
| Stabilitas | 5 | 22 test terpilih lulus, tetapi ada bug field, masa berlaku, dan backup; suite keseluruhan belum dijalankan. |
| Performa | 5 | Bundle inti relatif kecil; pagination tak terbatas dan request AI sinkron panjang. Belum ada pengukuran CWV/SQL produksi. |
| UX | 5 | Konfirmasi aksi tersedia, tetapi survey memblokir funnel, hasil ekspor tidak konsisten, dan beberapa form gagal. Penilaian kode, belum visual/usability. |
| Maintainability | 4 | Pemeriksaan role tersebar pada closure, perilaku lintas controller/model tidak seragam, cakupan tes keamanan kritis belum tersedia. |

**Lima risiko terpenting:** (1) pembayaran/kredit dapat dipalsukan dan digandakan, F01; (2) tiket dan kendali scraper melewati pembatasan admin, F02/F04; (3) integritas dan kepemilikan CV lintas pengguna, F03; (4) permintaan server menuju jaringan internal, F06; (5) password deployment literal tersimpan di file terlacak Git, F07—validitasnya belum diuji.

**Lima perbaikan berdampak terbesar:** (1) konfirmasi transaksi ke provider dan ledger entitlement idempoten; (2) middleware admin terpusat serta policy di setiap aksi HTTP/Livewire; (3) scope semua mutasi CV ke pemilik; (4) rotasi credential deployment dan batasi egress scraper; (5) tambah regression gate pembayaran, lintas role, lintas user, dan restore backup di CI.

## 2. Daftar temuan

Kompleksitas menunjukkan ukuran implementasi relatif, bukan janji waktu. `High` dependency scanner tidak otomatis berarti aplikasi produksi memiliki jalur eksploitasi yang sama.

| ID | Kategori | Judul temuan | Prioritas | Dampak bisnis/teknis | Bukti / reproduksi | Area terkait | Rekomendasi spesifik | Kompleksitas | Status |
|---|---|---|---|---|---|---|---|---|---|
| F01 | Pembayaran | Webhook dipercaya tanpa autentikasi; status mundur memungkinkan kredit ulang | Critical | Premium/add-on tanpa pembayaran; pembukuan dan kredit tidak dapat dipercaya | Harness: unsigned completed → SUCCESS/10 kredit; failed → FAILED; completed baru → SUCCESS/20; 0 request provider | [app/Http/Controllers/PaymentController.php:316](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/PaymentController.php:316>), [bootstrap/app.php:20](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/bootstrap/app.php:20>) | Verifikasi server-to-server; cocokkan order, amount, merchant; ledger unik per payment; terminal state tidak boleh mundur | sedang | terverifikasi—harness dan kode |
| F02 | Otorisasi admin | CRUD feedback tidak memeriksa role atau permission | High | User terverifikasi dapat mencapai controller pembaca/penulis tiket pengguna lain | Middleware efektif hanya auth/verified; sebagai user A, controller updateStatus mengubah tiket B menjadi completed | [app/Http/Controllers/Admin/AdminSupportController.php:15](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/Admin/AdminSupportController.php:15>), [routes/web.php:570](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/routes/web.php:570>) | Lindungi seluruh prefix admin; policy tiket; uji baca/balas/status/hapus sebagai guest/user/admin | kecil | terverifikasi—controller dan route; E2E belum |
| F03 | IDOR CV | Save skill/CV mengambil ID global lalu menulis user_id pemanggil | High | Perubahan/pengambilalihan data CV; berpengaruh ke portfolio publik | Harness: skill B berubah pemilik ke A setelah skillId dimanipulasi; edit awal memeriksa owner tetapi save tidak | [app/Livewire/CvBuilder/SkillsForm.php:87](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/CvBuilder/SkillsForm.php:87>), [app/Livewire/CvBuilder/ExperienceForm.php:136](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/CvBuilder/ExperienceForm.php:136>), [app/Livewire/CvBuilder/ProjectForm.php:125](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/CvBuilder/ProjectForm.php:125>) | Query melalui relasi user pada save/reorder; policy; jangan ubah owner dalam payload edit | sedang | terverifikasi—skill; pola experience/project dari kode |
| F04 | Admin scraper | Dashboard scraper tidak memiliki pemeriksaan admin | High | User terverifikasi dapat mencapai fungsi konfigurasi/ingestion; risiko biaya dan gangguan operasional | Route hanya auth/verified; mount/load/save/aksi scraper tanpa guard role; mount bahkan dapat seed data | [routes/web.php:426](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/routes/web.php:426>), [app/Livewire/Admin/ScraperDashboard.php:62](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/Admin/ScraperDashboard.php:62>) | Middleware admin + permission scraper.manage pada setiap aksi; hapus seed dari navigasi GET | kecil | terverifikasi—kode; tidak menjalankan scraper |
| F05 | Autentikasi API | Login extension tanpa throttling aplikasi dan token tanpa expiry | High | Penebakan password tidak mendapat pembatasan setara login web; token dicuri berlaku panjang | Middleware efektif login hanya SubstituteBindings; login langsung Hash::check/createToken; expiration null | [routes/api.php:7](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/routes/api.php:7>), [app/Http/Controllers/Api/ExtensionController.php:16](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/Api/ExtensionController.php:16>), [config/sanctum.php:50](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/config/sanctum.php:50>) | Rate limiter email+IP, gate status/verifikasi akun, abilities terbatas, expiry dan revoke perangkat | kecil | terverifikasi—kode; proteksi edge perlu verifikasi |
| F06 | SSRF | URL scraper langsung diteruskan ke HTTP client | High | Server dapat diminta mengakses host internal; respons dapat bocor melalui hasil parsing/error | Harness membuktikan URL 127.0.0.1 diteruskan ke fake HTTP dan mendapat 200; tidak ada koneksi internal nyata | [app/Http/Controllers/JobScraperController.php:11](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/JobScraperController.php:11>) | Batasi skema/domain; validasi seluruh DNS/IP dan redirect; blok private/link-local/loopback; limit bytes/time; firewall egress | sedang | terverifikasi—jalur input; jangkauan jaringan perlu verifikasi |
| F07 | Secrets | Password deployment literal di file tracked | High | Credential dapat tersebar melalui clone/arsip/akses repo | git ls-files memasukkan upload.exp; baris 4 berisi assignment password literal; nilai sengaja tidak ditampilkan | [upload.exp:4](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/upload.exp:4>) | Rotasi credential yang bersangkutan, secret store/SSH key terbatas, bersihkan history setelah koordinasi; audit akses repo | kecil | terverifikasi—penyimpanan; validitas credential perlu verifikasi |
| F08 | Otorisasi Livewire | Aksi UserManagement tidak memeriksa ulang privilege | High | Admin yang diturunkan role berpotensi tetap menggunakan snapshot lama untuk mengubah role/kuota/hapus | Guard berada pada closure GET; updateUser/toggleAdmin/deleteUser/createAdmin tanpa guard; closure bukan persistent middleware | [app/Livewire/Admin/UserManagement.php:143](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/Admin/UserManagement.php:143>), [vendor/livewire/livewire/src/Mechanisms/PersistentMiddleware/PersistentMiddleware.php:14](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/vendor/livewire/livewire/src/Mechanisms/PersistentMiddleware/PersistentMiddleware.php:14>) | Gate pada setiap aksi, daftarkan middleware persisten, cabut sesi/token saat role berubah; uji snapshot lama | sedang | perlu verifikasi—skenario HTTP snapshot lama belum diuji |
| F09 | Admin governance | Satu role admin menguasai akun, finansial, backup, dan penghapusan audit log | High | Tidak ada least privilege atau pemisahan tugas; jejak investigasi dapat hilang | isAdmin hanya role===admin; cleanAll memanggil truncate; role/delete tidak merekam before/after | [app/Models/User.php:339](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Models/User.php:339>), [app/Livewire/Admin/UserActivities.php:89](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/Admin/UserActivities.php:89>), [app/Livewire/Admin/UserManagement.php:325](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/Admin/UserManagement.php:325>) | Permission terpisah, MFA/re-auth untuk aksi sensitif, audit event append-only di sink terpisah, retensi oleh service role | besar | terverifikasi—kode; kebijakan organisasi perlu disepakati |
| F10 | Backup/recovery | Backup dapat sukses semu atau kosong | High | Pemulihan bencana gagal ketika dibutuhkan | exec tidak memeriksa exit code; redirect shell dapat membuat file kosong; fallback hanya komentar tabel, bukan data | [app/Http/Controllers/Admin/DatabaseMaintenanceController.php:14](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/Admin/DatabaseMaintenanceController.php:14>) | Process API, exit/size/checksum validation, credential file, encrypted storage; restore test berkala; gagal harus fail closed | sedang | terverifikasi—kode; mysqldump tidak dijalankan |
| F11 | Entitlement | Masa berlaku premium tidak disimpan/diperiksa konsisten | High | Akses berbayar melewati durasi produk, data entitlement tidak konsisten | premium_until tidak fillable/cast; controller update field tersebut; isPremium true untuk tanggal lampau di harness | [app/Models/User.php:69](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Models/User.php:69>), [app/Models/User.php:347](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Models/User.php:347>), [app/Http/Controllers/PaymentController.php:424](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/PaymentController.php:424>), [app/Http/Middleware/EnsureUserIsPremium.php:26](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Middleware/EnsureUserIsPremium.php:26>) | Satu service entitlement: cast tanggal, simpan expiry eksplisit, cek setiap akses; tetapkan kebijakan lifetime/legacy | sedang | terverifikasi—harness dan kode |
| F12 | Kuota/performa AI | Credit check sebelum panggilan panjang, debit setelahnya | High | Request paralel bisa menghabiskan biaya provider melebihi saldo; PHP worker tertahan | Photo memeriksa saldo lalu Http timeout 300 detik; debit setelah respons; decrement memakai instance user lama | [app/Http/Controllers/AiPhotoController.php:46](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/AiPhotoController.php:46>), [app/Models/User.php:918](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Models/User.php:918>) | Reservasi kredit atomik sebelum queue job; idempotency key; refund satu kali saat gagal; status async | sedang | perlu verifikasi—race belum direproduksi paralel |
| F13 | Bug CV | Proficiency tidak tersimpan | Medium | CV menampilkan level skill yang hilang/tidak sesuai input | Form menulis proficiency; model/migrasi memakai proficiency_level; harness input Expert tetap Intermediate | [app/Livewire/CvBuilder/SkillsForm.php:114](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/CvBuilder/SkillsForm.php:114>), [app/Models/UserSkill.php:19](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Models/UserSkill.php:19>) | Samakan nama field form/model/view atau petakan eksplisit; round-trip test Expert | kecil | terverifikasi—harness dan kode |
| F14 | Bug admin | Konfirmasi password create admin memakai nama berbeda | Medium | Form gagal walau dua password sama | Properti newAdminPasswordConfirmation; aturan confirmed mengharapkan newAdminPassword_confirmation | [app/Livewire/Admin/UserManagement.php:43](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/Admin/UserManagement.php:43>), [app/Livewire/Admin/UserManagement.php:399](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/Admin/UserManagement.php:399>) | Gunakan confirmed:newAdminPasswordConfirmation atau nama standar; test cocok/tidak cocok | kecil | terverifikasi—kode; form browser belum |
| F15 | Bug/otorisasi admin | Endpoint contact lama rusak dan hanya dilindungi auth | Medium | Halaman gagal; bila hanya import diperbaiki dapat membuka data lintas role | index: Class App\Http\Controllers\ContactMessage not found; store menulis SupportTicket; route hanya auth | [app/Http/Controllers/ContactMessageController.php:74](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/ContactMessageController.php:74>), [routes/web.php:632](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/routes/web.php:632>) | Hapus route usang atau arahkan ke feedback yang diotorisasi; selaraskan model/view | kecil | terverifikasi—harness dan kode |
| F16 | Export security | Ekspor user/payment memasukkan formula CSV tanpa netralisasi | Medium | Nama user dapat menjadi formula ketika dibuka operator di spreadsheet | exportUsers dan export payments langsung fputcsv nama/email; helper ekspor tracker sudah punya sanitasi terpisah | [app/Livewire/Admin/UserManagement.php:638](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/Admin/UserManagement.php:638>), [app/Http/Controllers/Admin/PaymentController.php:193](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/Admin/PaymentController.php:193>) | Reuse sanitizer semua ekspor; nilai berawalan =,+,-,@/kontrol ditulis sebagai teks; uji =1+1 sintetis | kecil | terverifikasi—kode; perilaku spreadsheet perlu verifikasi |
| F17 | Data admin | Export payment mengabaikan search; statistik tidak mengikuti filter | Medium | File ekspor memuat lebih banyak data daripada daftar; analisis/review finansial keliru | index menerapkan search, export hanya status/tanggal; stats memakai Payment global | [app/Http/Controllers/Admin/PaymentController.php:24](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/Admin/PaymentController.php:24>), [app/Http/Controllers/Admin/PaymentController.php:149](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/Admin/PaymentController.php:149>) | Shared filteredQuery; label statistik global atau hitung sesuai filter; uji ID daftar=ekspor | kecil | terverifikasi—kode |
| F18 | Performa admin | Opsi semua memuat sampai 1.000.000 user; export eager get | Medium | Memori/HTML besar, halaman lambat dan worker sibuk saat data tumbuh | render memilih paginate(1000000); export memanggil get sebelum streaming | [app/Livewire/Admin/UserManagement.php:606](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/Admin/UserManagement.php:606>), [app/Livewire/Admin/UserManagement.php:640](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Livewire/Admin/UserManagement.php:640>) | Cap pagination 100, lazyById/chunk ekspor atau queue; aggregate cache; ukur p95 pada fixture besar | sedang | terverifikasi—kode; dampak latency perlu ukur |
| F19 | UX/reliability | Survey paksa dan maintenance menghalangi alur penting | Medium | User lama diarahkan dari halaman pembayaran/support; webhook dapat 503 selama maintenance | Survey mengecualikan sedikit route; SoftMaintenanceMode tidak mengecualikan webhook | [app/Http/Middleware/CheckSurveyRedirect.php:46](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Middleware/CheckSurveyRedirect.php:46>), [app/Http/Middleware/SoftMaintenanceMode.php:20](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Middleware/SoftMaintenanceMode.php:20>) | Survey opsional dengan dismiss; kecualikan support/payment/security; webhook tetap diterima aman atau antre + rekonsiliasi | sedang | terverifikasi—alur kode saat flag aktif |
| F20 | OAuth | LinkedIn stateless menghilangkan validasi state sesi | Medium | Potensi login CSRF/account confusion | redirect dan callback memakai stateless; Google memakai alur stateful | [app/Http/Controllers/Auth/LinkedInAuthController.php:25](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/Auth/LinkedInAuthController.php:25>) | Pulihkan stateful flow; perbaiki cookie/callback; uji state salah/kosong ditolak | kecil | terverifikasi—konfigurasi; exploit provider perlu verifikasi |
| F21 | Dependency | npm audit: 17 paket terdampak advisori | Medium | Risiko supply chain/dev tooling dan sebagian runtime; eksploitabilitas tergantung jalur penggunaan | 3 critical, 11 high, 2 moderate, 1 low dari npm audit; axios 1.12.2 dan vite 7.1.10 termasuk | [package-lock.json:1](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/package-lock.json:1>), bukti npm-audit.json | Update lockfile terkontrol, audit ulang, build dan regresi; jangan expose dev server; audit PHP terpisah | sedang | terverifikasi—scanner; exploit aplikasi perlu verifikasi |
| F22 | Hardening/privacy | Keamanan edge, cookie, hasil foto eksternal belum terukur | Medium | Bisa memperluas dampak celah dan paparan PII jika deployment salah | .htaccess berisi header, middleware header aplikasi nonaktif; trustProxies('*'); hasil foto berupa URL eksternal | [bootstrap/app.php:18](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/bootstrap/app.php:18>), [public/.htaccess:1](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/public/.htaccess:1>), [app/Http/Controllers/AiPhotoController.php:99](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/app/Http/Controllers/AiPhotoController.php:99>) | Verifikasi header/cookie aktual, trusted proxy/origin firewall, retensi/akses URL foto dan provider; batasi CORS sesuai kebutuhan | sedang | perlu verifikasi—deployment/provider |

## 3. Temuan Admin Panel

| ID | Modul admin | Role terdampak | Jenis masalah | Prioritas | Risiko | Reproduksi aman | Rekomendasi | Backend/frontend/database | Status verifikasi |
|---|---|---|---|---|---|---|---|---|---|
| F02 | Feedback/support | User biasa, admin | Broken access control | High | Tiket lintas pengguna dibaca/diubah/dihapus | Fixture A/B; jalankan controller status sebagai A | Middleware + policy dan tes lintas role | Backend | terverifikasi lokal |
| F04 | Scraper | User biasa, admin | Missing role guard | High | Konfigurasi/ingestion dapat diakses role salah | Inspeksi middleware; fake service sebelum mencoba mount | scraper.manage di setiap aksi; seed via migration/command | Backend | terverifikasi kode |
| F08 | User management | Admin yang dicabut rolenya | Re-authorize tidak tersedia | High | Snapshot lama berpotensi masih dapat mutasi | Di staging sintetis: buka panel, turunkan role dari sesi lain, submit snapshot lama | Action Gate + revoke session | Backend | perlu verifikasi |
| F09 | Akun/role/log | Semua admin | Least privilege/audit trail | High | Admin biasa dapat mengubah role/hapus log | Inspeksi role dan cleanAll; jangan jalankan pada log nyata | Pisahkan permission dan sink audit | Backend, database, frontend | terverifikasi kode |
| F10 | Backup | Admin | Recovery tidak valid | High | Berkas download tidak dapat direstore | Mock process exit nonzero/empty file | Validasi dump + drill restore | Backend, infrastruktur | terverifikasi kode |
| F14 | Create admin | Admin | Validasi form | Medium | Admin baru tidak bisa dibuat lewat form normal | Fixture password cocok pada validasi | Explicit confirmation field | Backend/frontend | terverifikasi kode |
| F15 | Contact lama | User login | Endpoint rusak/unprotected | Medium | Error sekarang; kebocoran potensial setelah fix parsial | Panggil index di harness | Consolidate ke feedback + policy | Backend/frontend | terverifikasi lokal |
| F16 | Export user/payment | Admin, subjek data | Formula CSV | Medium | Formula dari input user dijalankan operator | Ekspor nama sintetis =1+1, inspect sebagai teks | Sanitasi bersama | Backend | terverifikasi kode |
| F17 | Payment reporting | Admin finance | Filter/angka tidak konsisten | Medium | Ekspor dan keputusan finansial salah | Dua fixture berbeda; filter hanya satu | Shared query/filter | Backend/frontend | terverifikasi kode |
| F18 | User list/export | Admin | Unbounded result | Medium | UI/memori tidak stabil | Inspect cap; benchmark staging sintetis | Batas 100 + export job | Backend/frontend | terverifikasi kode |

**Kontrol yang sudah ada:** banyak halaman admin melakukan isAdmin pada closure, backup controller memeriksa admin, survey admin memeriksa admin, dan form web memakai CSRF bawaan. Modal konfirmasi delete/premium tersedia. Ini tidak menggantikan otorisasi aksi server, re-authentication, atau pembatasan objek. Tidak ada bukti bahwa semua user biasa otomatis dapat memanggil semua komponen admin; skenario snapshot F08 sengaja dibedakan dari akses langsung F02/F04.

## 4. Matriks akses role vs fitur admin

Kebijakan “seharusnya” di bawah adalah rekomendasi least privilege, bukan aturan organisasi yang telah disepakati. Role super admin/finance/support/operator belum diimplementasikan sebagai role terpisah pada area yang diperiksa. Akses saat ini berasal dari route dan kode, bukan login browser semua role.

| Fitur | Role seharusnya | Akses saat ini | Berlebihan/kurang | Permission usulan |
|---|---|---|---|---|
| Dashboard/analytics | Super admin, operator sesuai cakupan | Admin | Seluruh admin berbagi akses global | analytics.view dengan scope |
| Daftar/detail user | Super admin; support data minimum | Admin melalui GET | Tidak ada pembatasan kolom/role support | users.view, users.pii.view |
| Create/promote/demote admin | Super admin + re-auth/MFA | Semua admin melalui komponen; create punya F14 | Terlalu luas; last-super-admin guard belum tersedia | admins.manage |
| Premium/kuota manual | Finance berwenang; approval bila perlu | Semua admin | Tidak ada pemisahan pemberi manfaat dan reviewer | entitlements.adjust |
| Payment detail/export/cancel | Finance; super admin darurat | Semua admin pada closure | Pembukaan data finansial luas; cancel masih spesifik YUKK | payments.view/export/cancel |
| Tiket: baca/balas/status/hapus | Support; hapus terbatas supervisor | User terverifikasi dapat mencapai controller; admin | Akses user biasa berlebihan (F02) | tickets.view/reply/update/delete |
| Scraper/config/ingestion | Operator scraper | User terverifikasi dapat mencapai komponen | Akses user biasa berlebihan (F04) | scraper.view/manage/run |
| Survey/global settings | Super admin/operator produk terpilih | Admin | Perlu pemisahan settings dari operasional support | settings.manage, surveys.manage |
| Integration/API key | Super admin/integration operator | Admin GET; action guard belum konsisten | Secret/config menjangkau semua admin | integrations.manage |
| Backup database | Super admin/backup service | Semua admin, cek di controller | Dump seluruh data terlalu luas | backups.create/download |
| Audit log: baca | Security/auditor | Admin | Belum read-only terpisah | audit.view |
| Audit log: retensi/hapus | Service retensi, sesuai kebijakan | Admin dapat cleanAll/truncate | Penghapus juga subjek audit | retention.execute hanya service |
| Email blast | Marketing/operator ditunjuk | Semua admin | Blast terlalu luas; perlu preview/dry-run dan queue | campaigns.preview/send |
| Landing photo | Content operator | Admin melalui guard controller; auth/verified di route | Perlu permission konten terpisah | content.manage |
| Semua fitur admin bagi guest | Tidak ada | Auth menolak route admin | Tidak ada celah guest admin yang dibuktikan | deny by default |
| Semua fitur admin bagi user/premium | Tidak ada | Mayoritas GET ditolak; pengecualian F02/F04/F15 | Perlu perbaikan menyeluruh dan test matriks | deny by default |

## 5. Penyebab, reproduksi aman, contoh solusi, dan verifikasi

Semua langkah mutasi berikut hanya untuk database test disposable dan akun sintetis. Jangan menjalankannya pada order, tiket, log, atau CV pengguna nyata. Contoh kode adalah arah perbaikan, belum diterapkan atau diuji sebagai patch.

### F01 — pembayaran palsu dan replay lintas status

**Akar masalah:** handler webhook menggunakan status/amount/order dari request publik sebagai sumber kebenaran. Pengecekan amount bukan autentikasi. Lock pada SUCCESS melindungi duplikasi berturut-turut, tetapi `markAsFailed()` dapat mengembalikan status SUCCESS ke FAILED; transaksi berikutnya lalu memberi entitlement kembali. transaction_id dari pemanggil tidak cukup untuk idempotensi bisnis.

**Reproduksi lokal yang dilakukan:** fixture add-on PENDING sebesar 14999; panggil handler dengan completed tanpa signature, failed, lalu completed dengan event ID berbeda. Kredit AI naik 0→10→10→20. `Http::assertNothingSent()` membuktikan provider tidak ditanya. Tidak ada request ke gateway atau email nyata.

```php
// Treat webhook as a notification; verify using stored order data.
$verified = $provider->lookup($payment->order_id, $payment->amount);
abort_unless(matchesStoredMerchantOrderAmount($verified, $payment), 400);
if (!$verified->isPaid()) return acknowledgeWithoutGranting();
DB::transaction(function () use ($payment) {
    $locked = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
    // Database UNIQUE(payment_id, entitlement_type) protects business effect.
    if (EntitlementLedger::alreadyGranted($locked->id)) return;
    grantEntitlementAndRecordLedger($locked);
    $locked->update(['status' => 'SUCCESS']);
});
// Provider failure/cancel events must never undo a settled successful payment.
// Dispatch mail after commit via queue.
```

**Verifikasi fix:** unsigned/forged event tidak memberikan manfaat saat provider menyatakan pending; mismatch merchant/amount/order ditolak; sequential duplicate dan dua request bersamaan memberi satu grant; failed setelah SUCCESS tidak menurunkan status; timeout provider aman untuk retry. Uji refund sebagai alur terpisah, bukan mengubah SUCCESS menjadi FAILED.

### F02/F04/F08/F09 — batas admin

**Akar masalah:** guard tersebar di closure GET, bukan pada boundary admin dan aksi. Role tunggal memberi semua fungsi. Persistensi middleware Livewire tidak mengulang body closure GET. Dokumentasi Livewire juga mengharuskan parameter dan properti publik diperlakukan sebagai input tidak tepercaya serta aksi diotorisasi. [Livewire Security](https://livewire.laravel.com/docs/3.x/security).

**Reproduksi aman:** fixture user A dan tiket B; harness telah mengubah status lewat controller tanpa hak admin. Untuk scraper, gunakan fake HTTP/queue/process sebelum memuat halaman agar tidak seed/menjalankan ingestion nyata. Untuk snapshot lama, buka panel sebagai admin test, cabut role dari sesi kedua, kemudian submit aksi pada panel yang masih terbuka; ini belum dilakukan.

```php
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(...);
// Repeat at action boundary, including Livewire:
Gate::authorize('tickets.update', $ticket);
Gate::authorize('admins.manage');
// Register custom persistent middleware in the service provider when required:
Livewire::addPersistentMiddleware([EnsureUserIsAdmin::class]);
```

**Verifikasi fix:** guest ditolak autentikasi; user/premium mendapat 403 untuk GET dan semua mutasi admin; support hanya tiket sesuai scope; finance tidak dapat promote admin atau dump database; role dicabut menghentikan snapshot/sesi lama. Record audit harus memuat actor, target, aksi, before/after yang disamarkan, waktu, request ID, IP; admin operasional tidak memiliki hak menghapus sink audit. Konfirmasi UI harus tetap diikuti izin server.

### F03 — IDOR pada penyimpanan dan urutan CV

**Akar masalah:** `edit()` memeriksa owner, namun `save()` percaya public skillId/editMode lalu mengisi user_id dari Auth. Pola serupa ada pada experience/project. Reorder mengambil objek global sebelum memperbarui urutan. **Reproduksi:** buat skill B, gunakan komponen A dengan editMode=true dan skillId B; harness menunjukkan owner berubah ke A.

```php
$skill = auth()->user()->skills()->findOrFail($this->skillId);
Gate::authorize('update', $skill);
$skill->update(Arr::only($validated, [
    'skill_name', 'category', 'proficiency_level', 'years_of_experience'
])); // owner must not be edited
```

**Verifikasi fix:** A tidak bisa save/delete/reorder entri B; respons 403/404 dan row B tetap identik; A masih dapat CRUD miliknya. Uji seluruh education/experience/project/organization/achievement/skill, bukan hanya method edit.

### F05/F20 — jalur login alternatif

**Akar masalah:** extension melewati LoginRequest yang punya rate limit; Sanctum default expiry null; LinkedIn secara eksplisit stateless. **Reproduksi aman:** satu akun test, beberapa password salah melalui test HTTP dengan limiter array, tidak brute-force layanan; callback OAuth dibuat melalui provider mock dengan state salah.

```php
Route::post('/extension/login', ...)->middleware('throttle:extension-login');
// Limiter keys: normalized account AND trusted client IP; generic errors.
$token = $user->createToken('extension', ['jobs:create'], now()->addDays(30));
// LinkedIn: use the stateful driver, remove stateless() on both sides.
```

**Verifikasi fix:** limit test menghasilkan 429; akun belum terverifikasi mengikuti kebijakan eksplisit; token expired/revoked gagal; role/admin tidak otomatis memperoleh kemampuan extension; OAuth state salah/kosong ditolak tanpa login. Tidak menyatakan OAuth takeover sudah terbukti.

### F06/F07 — jaringan dan credential

**Akar masalah SSRF:** validasi `url` hanya format, bukan tujuan jaringan. Harness memakai fake HTTP untuk membuktikan request diteruskan ke loopback. **Solusi:** resolver/HTTP client yang memeriksa semua A/AAAA, menolak IP nonpublik, mengikat tujuan koneksi yang telah divalidasi, dan mengulang validasi setiap redirect; domain allowlist bila cakupan portal memungkinkan. Firewall egress melengkapi validasi aplikasi. [OWASP SSRF Prevention](https://cheatsheetseries.owasp.org/cheatsheets/Server_Side_Request_Forgery_Prevention_Cheat_Sheet.html).

```text
parse URL → require https → allowlisted host → resolve A/AAAA
→ reject loopback/private/link-local/reserved → connect to validated destination
→ enforce response byte/time limit → revalidate every redirect (or reject redirect)
```

**Verifikasi fix:** fake loopback, IPv6 local, link-local, redirect ke private, dan DNS berubah ditolak sebelum fetch; URL portal sah tetap berfungsi. Jangan memanggil metadata cloud nyata. Untuk F07, verifikasi hanya penyimpanan literal dalam file tracked; jangan mencoba credential. Setelah rotasi, pemilik sistem mengecek credential lama tidak berlaku, akses baru memakai secret manager/key, dan scanner repo/history tidak menemukan nilai aktif. Menghapus file saja tidak mencabut credential lama.

### F10 — backup palsu

**Akar masalah:** exit code diabaikan; keberadaan file disamakan dengan keberhasilan. Shell redirect dapat membuat berkas walau proses gagal, sedangkan fallback tidak berisi schema/data yang bisa direstore. Password juga dimasukkan ke command line dan escaping tidak aman untuk karakter khusus.

```text
run mysqldump via argument-array Process + restricted credentials file
if exit != 0 OR output empty: report failure, never offer download
encrypt + checksum + record backup metadata
restore into disposable isolated database; compare schema and fixture row counts
```

**Verifikasi fix:** fake exit 1 menghasilkan failure dan tidak ada download; backup nyata di lingkungan test dapat direstore, row count/checksum diverifikasi; tentukan RPO/RTO dan retensi bersama operator. Audit ini tidak menjalankan mysqldump atau membaca isi database produksi.

### F11/F12 — expiry dan reservasi kredit

**Akar masalah:** isPremium hanya flag/status; premium_until tidak fillable/cast. Middleware expiry masuk cabang nonpremium sehingga user premium aktif tidak diuji expiry; tidak terlihat dipasang pada route yang diperiksa. Foto menggunakan check-then-debit dengan instance user sebelum request 300 detik.

```php
// Exact expiry-null meaning (legacy lifetime?) must be defined first.
return $user->is_premium && $user->payment_status === 'paid'
    && ($user->premium_until === null || $user->premium_until->isFuture());
// Reserve one credit BEFORE enqueueing work:
$reserved = User::whereKey($id)->where('photo_credits', '>', 0)
    ->decrement('photo_credits');
abort_unless($reserved === 1, 403);
// Persist job + reservation atomically; refund once on terminal failure.
```

**Verifikasi fix:** expire satu detik sebelum/sesudah batas, lifetime legacy, perpanjangan, dan manual premium menghasilkan entitlement yang sama di seluruh halaman. Untuk kredit, dua request dengan saldo satu hanya membuat satu pekerjaan provider; retry tidak mendebit ulang; failure refund satu kali. Harness membuktikan expiry diabaikan, bukan membuktikan race produksi.

### F13/F14/F15 — form dan endpoint rusak

F13: petakan `proficiency` UI ke `proficiency_level` storage, termasuk load/edit. Harness menulis Expert tetapi nilai fixture tetap Intermediate. Uji create→reload→edit→reload untuk setiap pilihan.

F14: gunakan `'newAdminPassword' => 'required|min:8|confirmed:newAdminPasswordConfirmation'` atau rename properti konfirmasi. Uji password cocok lolos, tidak cocok gagal, email duplikat memberi field error. Tambahkan unique pada editEmail juga agar duplikasi tidak menjadi error database generik.

F15: `ContactMessage` tidak di-import, sedangkan form publik kini menyimpan SupportTicket. Panggilan index lokal membuktikan class-not-found. Konsolidasikan route/model/view di feedback yang diotorisasi; sekadar menambah import tidak menyelesaikan missing role guard. Uji guest/user/admin dan route lama menghasilkan redirect atau 404 yang sengaja ditetapkan.

### F16/F17/F18 — ekspor, angka, dan skala

**Akar masalah:** implementasi export terpisah dari query daftar, tidak semua memakai sanitizer tracker, dan streaming dimulai setelah semua row dimuat. **Reproduksi aman:** buat dua payment sintetis dengan nama berbeda; filter search satu nama dan bandingkan ID ekspor. Nama `=1+1` cukup untuk memeriksa formula CSV tanpa payload jaringan.

```text
filteredQuery(filters) used by list, export and filtered aggregates
paginate at most 100; large export uses queued lazyById/chunks
CSV cells with formula/control prefixes → explicit text representation
include applied filters, generation time and row count in export metadata
```

**Verifikasi fix:** daftar dan ekspor punya himpunan ID sama; statistik diberi label global/filter; spreadsheet menampilkan input sebagai teks; memori ekspor tidak bertumbuh linear dengan seluruh dataset; download diberikan kepada role berhak dan dicatat. Export center baru harus memakai penyimpanan privat serta link berumur pendek—audit ini tidak menemukan ekspor sekarang disimpan sebagai URL publik, sehingga itu bukan klaim celah yang ada.

### F19/F21/F22 — operasional dan batas verifikasi

Survey: gunakan akun sintetis berumur >3 hari dan flag survey aktif; navigasi support/pembayaran harus tetap tersedia setelah perbaikan. Maintenance: test webhook menggunakan fake provider; notifikasi harus diterima atau dapat diproses ulang dengan rekonsiliasi yang dibuktikan, bukan diasumsikan provider selalu retry.

Dependency: file bukti npm memuat advisori dan fixAvailable. Seluruh dependency langsung root berada di devDependencies, tetapi Axios di-import frontend sehingga label dev bukan jaminan tidak masuk runtime. Sebagian advisori Axios khusus Node adapter dan Vite khusus dev server/Windows; jangan menyebutnya SSRF produksi tanpa jalur yang sesuai. Rekomendasi upgrade ke versi yang lulus audit terbaru dan regression tests, tanpa `audit fix --force` otomatis. Composer audit gagal karena koneksi Packagist timeout bahkan setelah percobaan akses di luar sandbox; dependency PHP dan dependency scraper-service belum mendapat status bersih.

Hardening: .htaccess sudah menetapkan HSTS, X-Frame-Options, nosniff, referrer policy dan CSP, namun CSP masih unsafe-inline/unsafe-eval. Middleware SecurityHeaders nonaktif tidak membuktikan header produksi tidak ada. Periksa header aktual di deployment, Secure/HttpOnly/SameSite/expiry cookie, konfigurasi trusted proxy dan firewall origin; hasil foto eksternal perlu diuji anonimitas, expiry link, penghapusan, dan perjanjian retensi provider. CORS '*' dengan credentials=false sendiri bukan bukti kebocoran akun.

## 6. Rencana perbaikan

### Quick wins — target awal 1–3 hari, bukan seluruh backlog selesai

1. **Backend, hari 1:** tutup F02/F04 dengan middleware admin pada semua route terkait dan action guard. Tambah test denial guest/user/premium. Hentikan grant dari webhook yang belum diverifikasi sampai patch F01 siap.
2. **Backend, hari 1–2:** selesaikan verifikasi provider F01, jangan izinkan status SUCCESS mundur, tambah tes tiga event dan duplicate. Tambahkan ledger unik secepatnya sebelum menganggap replay selesai.
3. **Backend, hari 2:** perbaiki owner scope save/reorder CV F03, proficiency F13, confirmation F14, serta route usang F15.
4. **Security/ops, paralel sesuai kapasitas:** rotasi F07 tanpa menampilkan secret; batasi egress dan akses dev server. Tambah throttle extension F05. Penutupan item tergantung hasil tes masing-masing.
5. **QA:** pasang gate prioritas: tidak ada grant palsu, lintas-user/role ditolak, dan bukti terlampir dapat direproduksi hanya pada test DB.

### Sprint berikutnya

- Entitlement service F11 dan reservation/queue AI F12; idempotency finansial dengan ledger unik.
- Perkuat Livewire/revocation F08, MFA dan re-auth sensitif, least privilege awal F09.
- Backup yang tervalidasi dan satu restore drill F10; ukur RPO/RTO.
- Ekspor konsisten dan aman F16/F17; batas pagination F18; error state/retry AI yang jelas.
- Upgrade dependency F21; audit PHP dan scraper-service di CI; uji OAuth state F20.
- Jadikan survey nonblocking; uji webhook/rekonsiliasi ketika maintenance F19.

### Arsitektur jangka menengah

- **Authorization:** route middleware → policy objek → scoped query; role/permission disimpan sebagai satu sumber kebenaran. Hindari role + is_admin yang tidak sinkron. Uji matriks pada HTTP dan Livewire.
- **Transaksi:** service payment provider terpisah, state machine, entitlement ledger, queue/outbox setelah commit, reconciliation job. Admin cancel saat ini YUKK-specific meskipun checkout Pakasir: satukan adapter provider dan tampilkan kemampuan cancel yang sesuai.
- **Observability:** trace/request ID, audit actor/target terstruktur dan immutable, alert spike grant/login/export, queue lag, failure rate AI, backup age; redaksi PII/secret dari log.
- **Performance:** ukur query count/p95/EXPLAIN pada MySQL test yang menyerupai produksi; index berdasarkan query nyata; cache agregat, paginasi hasil, batasi payload/timeout. SQLite tidak mewakili locking dan collation MySQL.
- **Delivery/QA:** CI unit+feature+role/ownership regression+dependency scan; browser test viewport dan keyboard; environment test dipaksa terisolasi. Tidak ada persentase coverage yang diklaim karena belum diukur.

### Ide fitur baru

| Fitur | Target | Prioritas | Alasan/dampak yang diharapkan | Ukuran keberhasilan |
|---|---|---|---|---|
| Permission manager dengan preview akses | Super admin | P1 | Menjalankan least privilege dan mengurangi salah pemberian akses | Semua aksi admin terpetakan dan matrix test lulus |
| Audit trail read-only + alasan perubahan | Security/support | P1 | Investigasi perubahan role/kuota/status dapat ditelusuri | Setiap aksi sensitif memiliki actor/target/before-after |
| Approval dua pihak untuk grant besar/hapus massal | Finance/supervisor | P1 | Mengurangi kesalahan dan penyalahgunaan finansial | Tidak ada operasi di atas ambang tanpa approval |
| Health dashboard dan alert | Operator | P1 | Mengetahui queue macet, provider gagal, backup basi | Waktu deteksi/pemulihan menurun |
| Export center privat | Finance/support berizin | P2 | Filter jelas, batas ukuran, expiry dan riwayat download | Row count sesuai filter; semua download terotorisasi |
| Bulk action preview + undo untuk aksi reversible | Operator | P2 | Memperjelas jumlah/objek sebelum perubahan | Tidak ada mutasi di luar selection preview |
| Support triage, assignment, SLA, canned replies | Support | P2 | Memperluas modul tiket yang sudah ada, mempercepat respons | Median waktu respons dan backlog turun |
| Status pekerjaan AI + retry terkontrol | Pencari kerja | P1 | Refresh/koneksi putus tidak menghilangkan hasil atau mendebit ganda | Satu request ID → satu job/debit/hasil |
| Pengingat follow-up opt-in dan saved search | Pencari kerja | P2 | Mengurangi lamaran terlupakan dan mempercepat pencarian | Penggunaan mingguan dan follow-up selesai |
| Impersonation support dengan alasan/durasi/banner | Support supervisor | P3 | Diagnostik terkontrol setelah RBAC/audit matang | Semua sesi tercatat, larangan aksi finansial teruji |

## 7. Bukti pengujian dan cakupan yang belum selesai

| Pemeriksaan | Hasil |
|---|---|
| Harness SQLite memory, HTTP/Mail fake | Tiket lintas owner berubah; skill berpindah owner; webhook memberi kredit dua kali; URL private diteruskan ke fake client; contact index error; premium lampau tetap true. Lihat proof-output.txt. |
| PHPUnit Unit | 4 test, 13 assertion lulus; 3 PHPUnit deprecation. |
| PHPUnit tests/Feature/Auth | 18 test, 38 assertion lulus. Ini baseline autentikasi standar, bukan bukti extension/admin/financial aman. |
| Vite build ke /private/tmp | Berhasil, 53 modules; CSS 179.95 kB (gzip 25.95), JS 36.08 kB (gzip 14.55). Tidak termasuk seluruh CDN/Livewire/gambar dan HTML. |
| npm audit root | 17 paket terdampak: 3 critical, 11 high, 2 moderate, 1 low. Detail di npm-audit.json. |
| Composer audit | Tidak selesai: DNS sandbox gagal; retry luar sandbox timeout Packagist. Tidak ada kesimpulan bebas CVE untuk PHP. |
| Git sebelum laporan | Bersih; aplikasi tidak diedit. File laporan/bukti ditambahkan di docs/audits/2026-09-09. |

**Kontrol positif yang ditinjau:** login web memiliki rate limiting dan validasi Turnstile di luar local/testing; route verifikasi email signed/throttled; job detail/delete memeriksa owner; show foto membatasi user_id; payment waiting/success/history membatasi user; banyak query memakai Eloquent binding; pembayaran sukses memakai transaction dan row lock meskipun masih ada F01. Sanitasi CSV sudah ada pada exporter tracker, tetapi belum diterapkan pada seluruh exporter admin.

**Belum terverifikasi:** browser/mobile/responsive/keyboard/kontras/screen reader, loading/retry/session-expired/multi-tab secara E2E, CWV dan N+1 pada data produksi, keamanan upload PDF/CV end-to-end dan integrasi AI/provider, penautan akun OAuth dan klaim email provider, kontrol WAF/proxy/session/cookie nyata, public exposure arsip/update.zip, hasil foto dan retensi PII, delivery email nyata, restore backup, concurrent MySQL, dependency PHP/scraper-service serta pengujian semua endpoint. SQL/command/XSS injection produksi tidak dinyatakan ada hanya karena belum diuji.

Untuk melengkapi audit deployment dibutuhkan URL staging yang diizinkan, akun sintetis user/premium/admin (dan role operasional bila sudah tersedia), serta provider sandbox. Laporan ini dapat langsung digunakan untuk perbaikan kode, tetapi belum merupakan persetujuan kelayakan produksi atau audit dinamis menyeluruh.

### Menjalankan ulang bukti lokal

Harness memakai schema minimal sintetis, bukan migrasi lengkap; hasil ini membuktikan perilaku method dan route metadata. Jangan menafsirkannya sebagai pengujian HTTP/browser penuh.

```bash
php "/Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/docs/audits/2026-09-09/proof-harness.php"
```

- [Harness sintetis](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/docs/audits/2026-09-09/proof-harness.php>)
- [Hasil harness](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/docs/audits/2026-09-09/proof-output.txt>)
- [Advisori npm lengkap](</Users/Luthfi/Project/PT. Teknalogi Transformasi Digital/TraKerja/docs/audits/2026-09-09/npm-audit.json>)

# Floating top bar dan dashboard TraKerja

Pengguna meminta perombakan lebih jauh atas style, container dan ukuran dashboard, kemudian memberikan crop top bar Tasklyn sebagai acuan terbaru: navigasi melayang, menu aktif ungu dan profil dengan nama/email. Arahan ini menggantikan top bar datar sebelumnya. Konten dan hitungan dashboard yang ada tetap dipakai; bahasa navigasi/dashboard tetap English.

## Navigasi

Top bar hanya berisi Home, Applications, Explore Jobs dan Summary. Container melayang di atas latar biru keabu-abuan dengan border putih dan radius kapsul. Menu aktif memakai ungu gelap; tautan lainnya berupa teks. Logo berada di kiri; avatar 32px, nama dan email terlihat langsung di kanan. Dropdown tetap menyediakan status akun, settings, automation/CSV, support dan sign out; upgrade tersedia ketika monetisasi aktif. Otorisasi tidak berubah.

Di bawah 900px, keempat tujuan berada pada panel Menu di bawah top bar. Nama/email profil disembunyikan di bawah 640px untuk menjaga ruang dan tetap tersedia dalam dropdown. Toggle dan profil mempunyai nama aksesibel, aria-controls/expanded, Escape dengan restore focus serta click-outside. Sidebar user tidak dirender. Admin memakai layoutnya sendiri.

## Dashboard

Sapaan dan tanggal di kiri, Add application di kanan. Tiga statistik berada di kanan Recent applications: In progress, Offers & accepted dan Rejected. In selection dihapus dari dashboard. Diagram tiga lingkaran menunjukkan setiap metrik terhadap total lamaran milik pengguna termasuk arsip; Offers dapat beririsan dengan In progress dan bukan kategori eksklusif.

Baris pertama memakai rasio 1,85:1 untuk Recent applications/Application overview. Application activity berada pada baris kedua selebar konten. Baris terakhir memiliki tiga panel sama lebar: Upcoming interviews, Your tools dan Weekly goals. Panel radius 28px dan padding 28px; heading 21px dan teks kerja 13–14px. Progress ring mingguan diperbesar menjadi 96px. Di bawah 900px panel menjadi satu kolom. Seluruhnya menggunakan data akun. Your tools menyediakan tombol Interviews, CV Builder, AI Studio dan Cover Letter. Fitur tambahan tersebut tidak berada di top bar. Tidak ada duplikasi baris menu utama di dashboard.

## Visual

Plus Jakarta Sans dengan fallback Inter/font sistem, bobot 400–600. Latar #dfe7ee, panel utama putih, panel pendukung #edf3f7 dan aksen aksi #2d204e. Kartu statistik biru/lavender/peach mengikuti kategori dan referensi pengguna. Top bar tinggi 60px, menu desktop 40px dan kontrol mobile minimal 44px. Lebar maksimal 1440px; container dashboard maksimal 1504px termasuk gutter 62px agar isi dashboard sejajar dengan isi top bar. Gutter disesuaikan pada tablet/mobile. Bayangan hanya untuk dropdown. Tidak menambahkan search, notifikasi, statistik atau avatar fiktif. ENERGY 2 / RHYTHM 2 / MOTION 1.

## Validasi

Tes memeriksa tepat empat link desktop, tidak adanya Interviews/AI/CV pada header, keberadaan ketiganya di Your tools, nama/email pada pemicu profil, active page dan dashboard kosong/berisi. Suite penuh memakai SQLite memory. Build Vite dan HTTP Laravel/Vite diperiksa. Workflow, enum, saldo/premium dan policy tidak diubah oleh redesign.

Pemeriksaan visual/interaksi browser belum tuntas: browser Dia dapat diobservasi, tetapi input/pengikatan ulang gagal `cgWindowNotFound`. Karena itu hasil tes/render tidak diklaim sebagai validasi visual, mobile, keyboard atau click-through browser.

Recent applications menggunakan tiga kartu pastel (biru, lavender, peach) untuk tiga lamaran terbaru milik pengguna. Isi kartu: status, posisi, perusahaan, tanggal melamar dan tahap saat ini; seluruh kartu membuka detail. Tidak memakai persentase progres atau avatar fiktif. Mobile menampilkan kartu dalam satu kolom.

Application activity memakai panel dengan tinggi sesuai isi, selebar konten. Heatmap memakai grid mingguan yang mengisi lebar tersedia; layar sempit dapat menggulir dengan keyboard. Header menampilkan daily average, footer berisi akses tracker dan legend pink/fuchsia sesuai token accent TraKerja. Sel harian memiliki nama aksesibel serta tooltip tanggal/hitungan asli yang ditampilkan melalui Alpine dan teleport agar tidak terpotong area scroll. Tooltip dapat ditutup dengan Escape dan tetap terlihat ketika pointer berpindah ke tooltip.

Weekly goals memakai latar lavender, dua kartu target interaktif yang membuka pengaturan, progres SVG, status target tercapai dan streak ringkas. Weekly goals berada bersama Upcoming interviews dan Your tools di baris bawah. Tanpa goal aktif, angka aktivitas tetap tampil tetapi ring tidak mengklaim progres terhadap target default. Pencocokan periode memakai tanggal kalender agar pengaturan memperbarui goal yang sama dan periode aktif terbaca pada hari pertama.

Upcoming interviews menampilkan tiga jadwal mendatang terdekat milik pengguna, urut waktu lalu ID. Baris jadwal membuka detail, menggunakan tanggal pastel dan waktu Asia/Jakarta. Your tools memakai empat tautan dengan ikon pastel, penjelasan singkat dan respons hover/fokus. Panel bawah memakai biru keabu-abuan dan lavender dengan border putih serta radius 26px.

Dropdown profil berukuran 288px, dengan avatar, nama, badge Free/Premium/Admin dan email. Rewards memakai bar XP aktual; menu memiliki ikon dalam bidang lembut, respons hover/fokus, chevron dan transisi buka/tutup singkat. Navigasi, premium gating, admin guard dan konfirmasi sign out tetap memakai alur yang ada.

Overview memakai total dalam pill, proporsi legend/ring yang seimbang dan catatan metrik lebih ringkas. Tiga panel bawah menggunakan latar putih transparan, blur 18px, border putih dan bayangan tipis untuk efek kaca seperti referensi; item di dalamnya memakai transparansi yang konsisten.

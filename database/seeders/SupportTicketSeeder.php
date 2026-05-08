<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SupportTicket;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testUser = User::where('email', 'test@example.com')->first();
        $dummyUser = User::where('email', 'premium@trakerja.com')->first();

        if (!$testUser) {
            $testUser = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password123'),
            ]);
        }

        if (!$dummyUser) {
            $dummyUser = User::create([
                'name' => 'Budi Santoso',
                'email' => 'premium@trakerja.com',
                'password' => bcrypt('password123'),
                'is_premium' => true,
            ]);
        }

        $tickets = [
            [
                'user_id' => $testUser->id,
                'category' => 'technical_issue',
                'subject' => 'Ekspor PDF CV Terpotong pada Bagian Pengalaman',
                'message' => "Halo tim TraKerja, saya mengalami kendala saat mencoba mengunduh hasil CV builder saya sebagai PDF.\nTepat pada halaman pertama, bagian 'Pengalaman Kerja' terpotong di baris terakhir dan menyisakan banyak ruang kosong di halaman kedua.\nApakah ada solusi atau template layout khusus untuk menghindari isu pemotongan halaman ini?\n\nTerima kasih!",
                'status' => 'replied',
                'admin_reply' => "Halo kak! Terima kasih telah menghubungi kami.\n\nIsu halaman PDF terpotong biasanya terjadi karena ukuran paragraf deskripsi yang terlalu panjang atau margin antar-elemen yang melebihi batas cetak A4.\n\nKami menyarankan langkah-langkah berikut:\n1. Persingkat deskripsi poin menggunakan format bullet points agar lebih ringkas.\n2. Di menu CV Customization, coba sesuaikan slider 'Spacing/Padding' ke tingkat Medium atau Compact.\n3. Gunakan Template Modern yang memiliki optimasi pembagian halaman otomatis.\n\nKami juga baru saja merilis update engine PDF rendering kami pagi ini untuk meminimalkan isu pemotongan teks secara agresif. Silakan coba mengunduh kembali CV Anda dan beritahu kami jika masalah masih berlanjut ya!",
                'replied_at' => Carbon::now()->subHours(5),
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'user_id' => $testUser->id,
                'category' => 'payment_billing',
                'subject' => 'Pembayaran Paket Premium belum Ter-aktivasi (QRIS)',
                'message' => "Saya baru saja menyelesaikan pembayaran untuk upgrade akun Premium menggunakan metode QRIS Gopay sebesar Rp 19.999.\nTransaksi di aplikasi e-wallet saya sudah dinyatakan sukses dan saldo sudah terpotong, namun status akun saya di dashboard TraKerja masih bertuliskan 'Free Plan'.\nMohon bantuannya untuk memverifikasi order ini. Terima kasih.",
                'status' => 'replied',
                'admin_reply' => "Halo Kak! Mohon maaf atas ketidaknyamanan yang terjadi.\n\nSetelah kami lakukan pengecekan pada log transaksi payment gateway kami, terdapat delay callback dari penyedia layanan pembayaran QRIS dalam meneruskan status pembayaran sukses ke server kami.\n\nKami telah memverifikasi pembayaran Anda secara manual dan saat ini status akun TraKerja Anda sudah berhasil di-upgrade menjadi PREMIUM. Anda kini memiliki akses penuh tanpa batas ke semua template CV, fitur ekspor tak terbatas, serta AI resume analyzer.\n\nSilakan refresh atau re-login ke akun Anda untuk melihat perubahan statusnya ya. Selamat menggunakan fitur premium!",
                'replied_at' => Carbon::now()->subMinutes(30),
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $testUser->id,
                'category' => 'feature_request',
                'subject' => 'Request Fitur Integrasi Notifikasi via WhatsApp',
                'message' => "Halo! Aplikasi TraKerja ini sangat membantu saya dalam mengorganisir lamaran kerja.\nSaya ingin memberikan masukan fitur, apakah memungkinkan ke depannya ditambahkan opsi integrasi notifikasi jadwal interview langsung ke nomor WhatsApp pengguna?\nKadang notifikasi email sering terlewat karena tertumpuk email lain.\n\nTerima kasih banyak atas kerjasamanya!",
                'status' => 'pending',
                'admin_reply' => null,
                'replied_at' => null,
                'created_at' => Carbon::now()->subHours(1),
            ],
            [
                'user_id' => $dummyUser->id,
                'category' => 'general_feedback',
                'subject' => 'Apresiasi untuk Fitur AI Cover Letter Generator!',
                'message' => "Saya cuma ingin menyampaikan apresiasi luar biasa untuk seluruh tim pengembang TraKerja.\nFitur AI Cover Letter Generator baru saja membantu saya memenangkan panggilan wawancara di 3 perusahaan startup besar minggu ini karena kualitas surat lamaran yang dibuat sangat profesional dan disesuaikan dengan deskripsi pekerjaan secara akurat.\nUI dashboard-nya juga sangat memanjakan mata.\n\nSukses terus untuk TraKerja!",
                'status' => 'pending',
                'admin_reply' => null,
                'replied_at' => null,
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id' => $dummyUser->id,
                'category' => 'technical_issue',
                'subject' => 'Error 500 saat Mencoba Import File CSV',
                'message' => "Halo admin, saya mencoba melakukan migrasi data lamaran lama saya menggunakan fitur CSV Import.\nNamun setiap kali mengunggah file CSV template yang sudah saya isi, halaman langsung memunculkan error 500 (Internal Server Error).\nApakah ada batasan format tanggal atau tanda baca khusus yang menyebabkan parser gagal membaca berkas saya?\n\nTerima kasih.",
                'status' => 'replied',
                'admin_reply' => "Halo Kak Budi! Terima kasih atas feedback-nya.\n\nError 500 pada parser CSV umumnya disebabkan oleh format tanggal yang tidak sesuai standar database kami. Sistem kami mengharuskan format tanggal berbentuk 'YYYY-MM-DD' (contoh: 2026-05-08).\n\nSelain itu, pastikan tidak ada baris kosong di bagian akhir file CSV serta pemisah kolom tetap menggunakan tanda koma (,).\n\nSilakan periksa kembali format kolom tanggal pada file CSV Anda. Jika masih mengalami error, silakan kirimkan file CSV tersebut ke email support kami agar dapat kami bantu bersihkan dan import secara manual dari database backend ya.",
                'replied_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(3),
            ],
        ];

        foreach ($tickets as $ticketData) {
            SupportTicket::create($ticketData);
        }
    }
}

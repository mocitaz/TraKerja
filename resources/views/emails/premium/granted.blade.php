<x-mail::message>
# Yth. {{ $user->name }},

Melalui surat elektronik ini, kami dari manajemen TraKerja ingin menyampaikan apresiasi yang sebesar-besarnya atas partisipasi aktif Anda dalam menggunakan platform kami.

Sebagai bentuk komitmen kami dalam mendukung pengembangan talenta profesional di Indonesia, kami telah memilih akun Anda untuk ditingkatkan ke status **Premium** tanpa dikenakan biaya apa pun.

Peningkatan status ini berlaku secara permanen (Lifetime Access), sehingga Anda dapat secara leluasa memanfaatkan seluruh ekosistem TraKerja untuk mempercepat proses pencarian karier impian Anda.

<x-mail::panel>
**Status Akun:** Premium Access  
**Masa Berlaku:** Seumur Hidup (Lifetime)
</x-mail::panel>

Dengan status Premium, Anda kini memiliki hak akses penuh terhadap seluruh fasilitas tingkat lanjut (Advanced Features) berikut:

<x-mail::table>
| Fitur Premium | Deskripsi Fasilitas |
| :--- | :--- |
| **Unlimited Job Tracking** | Kemampuan melacak dan mengelola jumlah lamaran pekerjaan tanpa batas (unlimited quota). |
| **Unlimited CV Builder** | Akses pembuatan, modifikasi, dan pengunduhan dokumen CV (Format PDF) tanpa pembatasan harian. |
| **Premium CV Templates** | Penggunaan seluruh ragam desain CV profesional yang telah teroptimasi dengan sistem ATS (Applicant Tracking System). |
| **Advanced AI Copilot** | Analisis mendalam pada Resume dan Cover Letter menggunakan kecerdasan buatan untuk meningkatkan persentase kelulusan screening. |
| **Priority Support** | Layanan bantuan dengan prioritas respons tertinggi dari tim teknis TraKerja. |
</x-mail::table>

Kami berharap fasilitas penunjang ini dapat secara nyata meningkatkan peluang Anda dalam menembus proses seleksi di perusahaan-perusahaan terkemuka.

<x-mail::button :url="config('app.url') . '/dashboard'" color="primary">
Akses Dashboard Premium
</x-mail::button>

Hormat Kami,<br>
**Tim Manajemen {{ config('app.name') }}**
</x-mail::message>
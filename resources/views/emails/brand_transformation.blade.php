<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suasana Baru TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#faf8fb; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#2d2636; -webkit-font-smoothing: antialiased;">

    <table role="presentation" style="width:100%; border-collapse:collapse; background-color:#faf8fb;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" style="width:100%; max-width:580px; border-collapse:collapse; background-color:#ffffff; border-radius:18px; border:1px solid #efeaf2; overflow:hidden; box-shadow:0 8px 30px rgba(53,46,64,0.04);">

                    @include('emails.partials.header', [
                        'title'    => 'Ada yang baru menyapa di dashboard kamu',
                        'subtitle' => 'Pembaruan Antarmuka TraKerja: ruang kerja yang lebih ramah, tenang, dan menemani langkah kariermu'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:36px 32px 30px 32px;">

                            <!-- Sapaan Akrab & Hangat -->
                            <p style="margin:0 0 16px 0; font-size:16px; line-height:24px; color:#211d27;">
                                Hai, <strong>{{ $user->name }}</strong>.
                            </p>

                            <p style="margin:0 0 16px 0; font-size:14px; line-height:23px; color:#4a4254;">
                                Proses mencari kerja dan menunggu kabar dari recruiter sering kali terasa melelahkan. Kadang rasanya seperti berjuang sendirian di depan layar, bolak-balik memeriksa status lamaran tanpa kepastian.
                            </p>

                            <p style="margin:0 0 24px 0; font-size:14px; line-height:23px; color:#4a4254;">
                                Karena kami ingin proses ini terasa lebih manusiawi, kami merapikan TraKerja agar tidak lagi terasa kaku. Mulai hari ini, TraKerja hadir sebagai <strong>teman perjuangan kariermu</strong>—dengan suasana yang lebih tenang di mata, rapi, dan siap menemani setiap langkahmu.
                            </p>

                            <!-- Headline Poin Pembaruan -->
                            <p style="margin:0 0 14px 0; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#7b5ea7;">
                                Apa yang baru dan siap membantumu?
                            </p>

                            <!-- Daftar Poin Pembaruan Bersih & Rapi -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                                <tr>
                                    <td>
                                        <!-- Poin 1: Sapaan Personal -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">
                                                        Sapaan Hangat Setiap Kali Kamu Datang
                                                    </h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">
                                                        Tidak ada lagi dashboard yang kaku dan dingin. Begitu login, kamu disambut dengan sapaan personal yang mengingatkan bahwa kamu sedang berproses menuju peluang yang lebih baik.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Poin 2: Ruang Kerja Tenang -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">
                                                        Ruang Kerja Tenang dan Bebas Sesak
                                                    </h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">
                                                        Kami membersihkan elemen visual yang padat dan menggantinya dengan ruang kerja yang lapang dan nyaman di mata. Membuka tracker lamaran berjam-jam pun pikiran tetap fokus tanpa distraksi.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Poin 3: Job Tracker Lebih Teratur -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">
                                                        Menata Lamaran Jadi Lebih Rapi dan Cepat
                                                    </h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">
                                                        Dari status Applied, jadwal wawancara, hingga tahap penawaran kerja—semua tercatat rapi layaknya asisten pribadi. Memperbarui progres lamaran jadi jauh lebih cepat dan teratur.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Poin 4: Pengingat Follow-up -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">
                                                        Pengingat Follow-up Tepat Waktu
                                                    </h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">
                                                        Sedang menunggu kabar kepastian dari recruiter? TraKerja membantu mengingatkan momen yang tepat untuk mengirim pesan tindak lanjut, sehingga kamu tetap terarah tanpa rasa cemas berlebih.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                            <!-- Highlight / Catatan Semangat Hangat -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#fbf8f5; border:1px solid #f3ece4; border-left:3px solid #e08b5c; border-radius:10px; padding:15px 18px;">
                                        <p style="margin:0 0 4px 0; font-size:12px; font-weight:700; color:#8f4a21;">
                                            Catatan kecil untuk perjalanan kariermu
                                        </p>
                                        <p style="margin:0; font-size:12px; line-height:20px; color:#6c4026;">
                                            Perjalanan mencari kerja membutuhkan waktu dan ketahanan. Setiap langkah kecil—mulai dari memperbarui CV, mencatat satu lamaran baru, hingga persiapan wawancara—merupakan progres nyata. Ruang kerja TraKerja selalu siap kapan pun kamu ingin melangkah.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Tombol CTA Bersih -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:13px 36px; background-color:#35264f; color:#ffffff; text-decoration:none; border-radius:999px; font-size:13px; font-weight:600; letter-spacing:0.01em; box-shadow:0 4px 14px rgba(53,38,79,0.22);">
                                            Buka Ruang Kerja Baru
                                        </a>
                                        <p style="margin:9px 0 0 0; font-size:11px; color:#9e95a7;">
                                            Suasana baru sudah otomatis aktif di akun kamu tanpa perlu pengaturan tambahan
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Penutup Hangat & Rapi -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #f3eff6;">
                                <tr>
                                    <td style="padding-top:18px;">
                                        <p style="margin:0 0 6px 0; font-size:12px; line-height:20px; color:#6e6578;">
                                            Semoga minggu ini ada kabar baik yang mampir ke inbox kamu. Tetap jaga kesehatan dan semangat dalam berproses.
                                        </p>
                                        <p style="margin:0 0 2px 0; font-size:13px; font-weight:700; color:#35264f;">
                                            Salam hangat,
                                        </p>
                                        <p style="margin:0; font-size:12px; color:#7b5ea7; font-weight:600;">
                                            Tim TraKerja <span style="font-weight:400; color:#a49cb0;">— Kawan seperjuangan kariermu</span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    @include('emails.partials.footer')

                </table>
            </td>
        </tr>
    </table>

</body>
</html>

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
                        'title'    => 'Ada yang baru nyapa kamu di dashboard 👋',
                        'subtitle' => 'Pembaruan Antarmuka TraKerja: ruang kerja yang lebih ramah, adem, dan nemenin langkah kariermu'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:36px 32px 30px 32px;">

                            <!-- Sapaan Akrab, Hangat, & Manusiawi -->
                            <p style="margin:0 0 16px 0; font-size:16px; line-height:24px; color:#211d27;">
                                Hai, <strong>{{ $user->name }}</strong>!
                            </p>

                            <p style="margin:0 0 16px 0; font-size:14px; line-height:23px; color:#4a4254;">
                                Jujur aja ya, proses cari kerja dan nungguin kabar dari recruiter itu sering bikin lelah. Kadang rasanya seperti berjuang sendirian di depan layar, bolak-balik ngecek status lamaran tanpa kepastian.
                            </p>

                            <p style="margin:0 0 24px 0; font-size:14px; line-height:23px; color:#4a4254;">
                                Karena kami nggak mau kamu merasa sendirian, kami merombak TraKerja agar nggak terasa kaku lagi. Mulai hari ini, TraKerja hadir sebagai <strong>teman perjuangan kariermu</strong>—dengan suasana baru yang lebih hangat, tenang di mata, dan siap nemenin kamu di setiap langkah.
                            </p>

                            <!-- Headline Poin Pembaruan -->
                            <p style="margin:0 0 14px 0; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#7b5ea7;">
                                Apa aja yang baru & siap nemenin kamu?
                            </p>

                            <!-- Daftar Poin Pembaruan Ramah & Rapi -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                                <tr>
                                    <td>
                                        <!-- Poin 1: Sapaan Personal & Companion -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">
                                                        👋 Sapaan Hangat Setiap Kali Kamu Datang
                                                    </h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">
                                                        Nggak ada lagi dashboard yang kaku dan dingin. Begitu login, kamu bakal disambut dengan kawan TraKerja baru dan sapaan personal yang ngingetin kalau kamu sedang berproses ke arah yang lebih baik.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Poin 2: Meja Kerja Adem Tanpa Distraksi -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">
                                                        🌿 Ruang Kerja Adem & Bebas Sesak
                                                    </h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">
                                                        Kami membersihkan kotak-kotak kaku dan menggantinya dengan canvas hangat yang lega di mata. Buka tracker lamaran berjam-jam pun pikiran tetap fokus dan nyaman tanpa visual yang bikin pusing.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Poin 3: Job Tracker yang Lebih Terarah -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">
                                                        ⚡ Nata Lamaran Jadi Makin Sat-Set
                                                    </h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">
                                                        Dari status <em>Applied</em>, jadwal interview, sampai tahap penawaran kerja—semuanya tersusun rapi seperti catatan asisten pribadi. Tinggal geser dan update progres dengan mudah.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Poin 4: Teman Evaluasi & Anti-Ghosting -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">
                                                        💡 Pengingat Follow-up Biar Nggak Galau
                                                    </h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">
                                                        Lagi nunggu kabar kepastian dari HRD? TraKerja bantu ingetin momen paling tepat buat kirim pesan follow-up, jadi kamu nggak perlu overthinking sendirian.
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
                                            🌱 Catatan kecil buat nemenin harimu
                                        </p>
                                        <p style="margin:0; font-size:12px; line-height:20px; color:#6c4026;">
                                            Perjalanan mencari kerja memang butuh waktu dan ketahanan. Tiap langkah kecil—entah itu poles CV, catat satu lamaran baru, atau latihan interview—adalah progres nyata. Ambil napas, meja kerjamu selalu siap kapan pun kamu mau lanjut.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Tombol CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:13px 36px; background-color:#35264f; color:#ffffff; text-decoration:none; border-radius:999px; font-size:13px; font-weight:600; letter-spacing:0.01em; box-shadow:0 4px 14px rgba(53,38,79,0.22);">
                                            Yuk, Sapa Ruang Kerjamu Hari Ini →
                                        </a>
                                        <p style="margin:9px 0 0 0; font-size:11px; color:#9e95a7;">
                                            Suasana baru sudah otomatis aktif di akunmu tanpa perlu setting apa pun
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Penutup Hangat & Friendly -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #f3eff6;">
                                <tr>
                                    <td style="padding-top:18px;">
                                        <p style="margin:0 0 6px 0; font-size:12px; line-height:20px; color:#6e6578;">
                                            Semoga minggu ini ada kabar menyenangkan yang mampir ke inbox-mu ya. Tetap jaga kesehatan dan semangat terus!
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

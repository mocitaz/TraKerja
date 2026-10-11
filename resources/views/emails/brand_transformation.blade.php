<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TraKerja New Look</title>
</head>
<body style="margin:0; padding:0; background-color:#faf8fb; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#2d2636; -webkit-font-smoothing: antialiased;">

    <table role="presentation" style="width:100%; border-collapse:collapse; background-color:#faf8fb;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" style="width:100%; max-width:580px; border-collapse:collapse; background-color:#ffffff; border-radius:18px; border:1px solid #efeaf2; overflow:hidden; box-shadow:0 8px 30px rgba(53,46,64,0.04);">

                    @include('emails.partials.header', [
                        'title'    => 'TraKerja New Look',
                        'subtitle' => 'Pembaruan Antarmuka TraKerja yang lebih fresh, adem, dan sat-set buat nemenin perjalanan kariermu'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:36px 32px 30px 32px;">

                            <!-- Sapaan Akrab & Hangat -->
                            <p style="margin:0 0 16px 0; font-size:16px; line-height:24px; color:#211d27;">
                                Hai, <strong>{{ $user->name }}</strong>!
                            </p>

                            <p style="margin:0 0 16px 0; font-size:14px; line-height:23px; color:#4a4254;">
                                Ada kabar seru nih buat kamu yang lagi berjuang jemput karier impian. Kami paham banget kalau proses cari kerja dan mantau puluhan lamaran itu kadang bisa bikin overwhelm dan melelahkan.
                            </p>

                            <p style="margin:0 0 24px 0; font-size:14px; line-height:23px; color:#4a4254;">
                                Makanya, kami dengerin masukan kamu dan dandanin TraKerja dari ujung ke ujung. Mulai hari ini, TraKerja hadir dengan tampilan baru yang jauh lebih fresh, adem di mata, dan makin sat-set buat nemenin setiap langkah pencarian kerjamu.
                            </p>

                            <!-- Headline Poin Pembaruan -->
                            <p style="margin:0 0 14px 0; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; color:#7b5ea7;">
                                Apa aja yang seru di TraKerja New Look?
                            </p>

                            <!-- Daftar Poin Pembaruan Asik Ala Gojek -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                                <tr>
                                    <td>
                                        <!-- Poin 1 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">Workspace Lebih Adem & Bersih</h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">Desain minimalis bernuansa soft lilac dan tissue white yang bikin mata nggak gampang lelah. Buka tracker lamaran berjam-jam pun tetap fokus tanpa distraksi.</p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Poin 2 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">Job Tracker Makin Sat-Set</h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">Papan pantau lamaran kini jauh lebih ringan dan responsif. Geser status proses rekrutmen, catat jadwal interview, sampai cek info UMR regional jadi makin gampang.</p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Poin 3 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">AI Studio Siap Jadi Mentor Pribadi</h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">Mau poles CV biar ramah sistem ATS, bikin cover letter yang ngena buat HRD, atau latihan interview? AI TraKerja siap bantu kamu dalam hitungan detik.</p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Poin 4 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; background-color:#fcfbfe; border-radius:14px; border:1px solid #f4eff7;">
                                            <tr>
                                                <td style="padding:16px 18px;">
                                                    <h3 style="margin:0 0 4px 0; font-size:13px; font-weight:700; color:#261f30;">Follow-up Anti Ghosting</h3>
                                                    <p style="margin:0; font-size:12px; line-height:20px; color:#6e6578;">Pengingat otomatis yang ngasih tau kapan momen paling pas buat check-in ke recruiter. Jangan biarkan lamaran impianmu lewat begitu aja tanpa kabar!</p>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                            <!-- Highlight Fun Fact / Pro Tip -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#fdf7fa; border:1px solid #f7e6ef; border-left:3px solid #d96b8c; border-radius:10px; padding:15px 18px;">
                                        <p style="margin:0 0 4px 0; font-size:12px; font-weight:700; color:#9c385b;">
                                            Fun fact buat nemenin harimu
                                        </p>
                                        <p style="margin:0; font-size:12px; line-height:20px; color:#75354e;">
                                            Pencari kerja yang rutin nge-track dan follow-up lamarannya punya peluang dipanggil interview hingga 3x lebih tinggi lho! Yuk, manfaatin workspace baru ini semaksimal mungkin buat langkah kariermu.
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
                                            Yuk, Coba Tampilan Barunya!
                                        </a>
                                        <p style="margin:9px 0 0 0; font-size:11px; color:#9e95a7;">
                                            Tampilan baru sudah otomatis aktif di akun kamu, tanpa perlu ribet setting ulang
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Penutup Hangat & Friendly -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #f3eff6;">
                                <tr>
                                    <td style="padding-top:18px;">
                                        <p style="margin:0 0 4px 0; font-size:12px; line-height:20px; color:#6e6578;">
                                            Semangat terus buat setiap langkah kariermu! Kalau ada cerita sukses atau masukan, colek kami kapan aja ya.
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

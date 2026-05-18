<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Verifikasi Email — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Pengingat Verifikasi Email',
                        'subtitle' => 'Selesaikan verifikasi untuk mengaktifkan akun TraKerja Anda'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kami mencatat bahwa akun TraKerja Anda yang didaftarkan tiga hari lalu belum menyelesaikan proses verifikasi email. Akun Anda saat ini belum aktif sepenuhnya dan akses ke seluruh fitur platform masih terbatas.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Verifikasi email diperlukan untuk memastikan keamanan akun dan memberikan Anda akses penuh ke ekosistem TraKerja.
                            </p>

                            <!-- Status Notice -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Status Akun</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#52525b;">Akun Anda terdaftar namun belum aktif. Selesaikan verifikasi email untuk mendapatkan akses penuh ke seluruh fitur TraKerja.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Fitur yang Tersedia Setelah Verifikasi
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Pelacak Lamaran Kerja</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Kelola seluruh lamaran pekerjaan dalam satu dasbor terpusat yang terstruktur dan mudah dipantau.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Target dan Pencapaian Karier</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Tetapkan target pencarian kerja yang terukur dan pantau perkembangan Anda secara berkala.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Pengingat Jadwal Wawancara</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Sistem notifikasi otomatis memastikan Anda tidak melewatkan satu pun jadwal wawancara yang telah dijadwalkan.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">CV Builder Profesional</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Buat CV dengan templat berstandar industri yang telah dioptimalkan untuk kompatibilitas sistem ATS.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td>
                                        <a href="{{ url('/verify-email') }}"
                                           style="display:inline-block; padding:12px 24px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600;">
                                            Verifikasi Email Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Tip -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">
                                            Apabila Anda tidak menemukan email verifikasi di kotak masuk, periksa folder spam atau junk. Jika mengalami kendala, hubungi tim dukungan kami melalui fitur Support di aplikasi.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">Hormat kami,</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#6d28d9;">Tim TraKerja</p>
                                        <p style="margin:0; font-size:13px; color:#a1a1aa;">PT Teknalogi Transformasi Digital</p>
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

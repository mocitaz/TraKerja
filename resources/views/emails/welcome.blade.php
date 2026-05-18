<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Selamat Datang di TraKerja',
                        'subtitle' => 'Platform manajemen lamaran kerja yang terstruktur dan profesional'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Akun TraKerja Anda telah berhasil dibuat. Kami menyambut kehadiran Anda di platform yang dirancang untuk membantu para profesional mengelola proses pencarian kerja secara sistematis dan efisien.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Dengan TraKerja, Anda dapat mendokumentasikan seluruh lamaran, memantau statusnya secara real-time, dan mempersiapkan diri menghadapi setiap tahapan rekrutmen dengan lebih terstruktur.
                            </p>

                            <!-- Getting Started Steps -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Langkah Awal Penggunaan
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                        <tr>
                                                            <td width="36" style="vertical-align:top; padding-top:1px;">
                                                                <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">1</div>
                                                            </td>
                                                            <td style="padding-left:14px; vertical-align:top;">
                                                                <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Lengkapi Profil dan Buat CV</p>
                                                                <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Isi informasi profil Anda dan manfaatkan CV Builder untuk membuat dokumen yang siap digunakan melamar.</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                        <tr>
                                                            <td width="36" style="vertical-align:top; padding-top:1px;">
                                                                <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">2</div>
                                                            </td>
                                                            <td style="padding-left:14px; vertical-align:top;">
                                                                <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Catat Lamaran Pertama Anda</p>
                                                                <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Buka fitur Job Tracker dan tambahkan lamaran beserta informasi perusahaan, posisi, dan tanggal pengiriman.</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                        <tr>
                                                            <td width="36" style="vertical-align:top; padding-top:1px;">
                                                                <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">3</div>
                                                            </td>
                                                            <td style="padding-left:14px; vertical-align:top;">
                                                                <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Aktifkan Pengingat Tindak Lanjut</p>
                                                                <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Tetapkan pengingat untuk jadwal wawancara dan tindak lanjut agar tidak ada satu pun peluang yang terlewat.</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Platform Capabilities -->
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Kemampuan Platform
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#3f3f46;">Pantau seluruh lamaran dalam satu papan kerja yang terorganisir dan mudah dipindai</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#3f3f46;">Bangun CV profesional dengan templat yang telah dioptimalkan untuk sistem ATS</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0;">
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#3f3f46;">Terima notifikasi otomatis untuk jadwal wawancara dan batas waktu tindak lanjut</p>
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
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            Buka Dashboard
                                        </a>
                                        <a href="{{ config('app.url') }}/tracker"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            Mulai Catat Lamaran
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Support Note -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">
                                            Jika Anda memerlukan bantuan atau memiliki pertanyaan, balas email ini atau kunjungi fitur dukungan yang tersedia di dalam platform.
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

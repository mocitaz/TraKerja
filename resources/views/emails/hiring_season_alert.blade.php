<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Periode Rekrutmen Aktif — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">

                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    <!-- Header -->
                    @include('emails.partials.header', [
                        'title'    => 'Periode Rekrutmen Aktif',
                        'subtitle' => 'Informasi strategis untuk pencarian karier Anda'
                    ])

                    <!-- Main Card -->
                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kami ingin menyampaikan bahwa saat ini merupakan periode rekrutmen aktif — sebuah momen di mana banyak perusahaan membuka lowongan secara bersamaan. Kondisi ini menghadirkan peluang signifikan bagi para profesional dan pencari kerja yang siap dan terorganisir.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                TraKerja hadir untuk memastikan Anda memanfaatkan momentum ini secara optimal.
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Langkah Strategis yang Kami Rekomendasikan
                                        </p>

                                        <!-- Step 1 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:22px;">
                                            <tr>
                                                <td width="36" style="vertical-align:top; padding-top:1px;">
                                                    <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">1</div>
                                                </td>
                                                <td style="padding-left:14px; vertical-align:top;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Perbarui CV dan Dokumen Lamaran Anda</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Pastikan CV Anda mencerminkan pencapaian terkini. Rekruter aktif pada periode ini — kesan pertama yang kuat sangat menentukan peluang Anda untuk dipanggil ke tahap selanjutnya.</p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Step 2 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:22px;">
                                            <tr>
                                                <td width="36" style="vertical-align:top; padding-top:1px;">
                                                    <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">2</div>
                                                </td>
                                                <td style="padding-left:14px; vertical-align:top;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Lamar Posisi yang Relevan dan Spesifik</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Kualitas lamaran yang terarah jauh lebih efektif dibandingkan mengirim lamaran secara massal tanpa riset mendalam terhadap perusahaan dan posisi yang dituju.</p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Step 3 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:22px;">
                                            <tr>
                                                <td width="36" style="vertical-align:top; padding-top:1px;">
                                                    <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">3</div>
                                                </td>
                                                <td style="padding-left:14px; vertical-align:top;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Susun Cover Letter yang Personal</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Cover letter yang disesuaikan dengan setiap posisi terbukti meningkatkan peluang respons rekruter. Gunakan fitur AI Cover Letter kami untuk mempercepat proses penyusunan.</p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Step 4 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td width="36" style="vertical-align:top; padding-top:1px;">
                                                    <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">4</div>
                                                </td>
                                                <td style="padding-left:14px; vertical-align:top;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Pantau dan Tindak Lanjuti Setiap Lamaran</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Catat progres setiap lamaran di TraKerja dan jadwalkan follow-up agar tidak ada peluang yang terlewat akibat kurangnya tindak lanjut.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info Box -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Catatan dari Tim TraKerja</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#52525b;">
                                            Rekruter cenderung merespons lebih cepat di awal periode rekrutmen. Kami merekomendasikan untuk mengirimkan lamaran sedini mungkin dan melakukan follow-up dalam 5 hingga 7 hari kerja setelah pengiriman.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Buttons -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td>
                                        <a href="{{ config('app.url') }}/tracker"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            Mulai Lacak Lamaran
                                        </a>
                                        <a href="{{ config('app.url') }}/cv-builder"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            Perbarui CV Saya
                                        </a>
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

                    <!-- Footer -->
                    @include('emails.partials.footer')

                </table>
            </td>
        </tr>
    </table>

</body>
</html>

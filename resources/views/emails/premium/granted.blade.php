<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Premium Diberikan — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Akses Premium Diberikan',
                        'subtitle' => 'Akun Anda telah ditingkatkan ke status Premium secara penuh'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Sebagai wujud apresiasi atas partisipasi aktif Anda dalam menggunakan platform TraKerja, manajemen kami telah memilih akun Anda untuk ditingkatkan ke status <strong>Premium</strong> tanpa dikenakan biaya apa pun.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Peningkatan ini berlaku secara permanen (Lifetime Access), sehingga Anda dapat memanfaatkan seluruh ekosistem TraKerja tanpa batasan untuk mendukung perjalanan karier Anda.
                            </p>

                            <!-- Status Card -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:20px;">
                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding-bottom:10px; border-bottom:1px solid #ede9fe;">
                                                    <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Status Akun</p>
                                                    <span style="display:inline-block; padding:3px 10px; background-color:#faf5ff; color:#6d28d9; border:1px solid #ede9fe; border-radius:4px; font-size:12px; font-weight:700; text-transform:uppercase;">Premium Access</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top:10px;">
                                                    <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Masa Berlaku</p>
                                                    <p style="margin:0; font-size:14px; font-weight:600; color:#18181b;">Seumur Hidup (Lifetime)</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Premium Features -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Fasilitas Premium yang Kini Anda Miliki
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Pelacakan Lamaran Tanpa Batas</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Kemampuan mencatat dan mengelola lamaran pekerjaan dalam jumlah tidak terbatas tanpa batasan kuota harian maupun bulanan.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">CV Builder Tanpa Batas</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Akses pembuatan, modifikasi, dan pengunduhan dokumen CV dalam format PDF tanpa pembatasan jumlah dokumen.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Templat CV Premium</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Akses seluruh koleksi desain CV profesional yang telah dioptimalkan untuk kompatibilitas dengan sistem ATS (Applicant Tracking System).</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Advanced AI Copilot</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Analisis mendalam pada Resume dan Cover Letter menggunakan kecerdasan buatan untuk meningkatkan persentase kelulusan tahap seleksi berkas.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Layanan Dukungan Prioritas</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Akses ke jalur dukungan dengan prioritas respons tertinggi dari tim teknis TraKerja untuk setiap pertanyaan atau kendala yang Anda hadapi.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td>
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            Akses Dashboard Premium
                                        </a>
                                        <a href="{{ config('app.url') }}/ai-analyzer"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            Coba AI Copilot
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">Hormat kami,</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#6d28d9;">Manajemen TraKerja</p>
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
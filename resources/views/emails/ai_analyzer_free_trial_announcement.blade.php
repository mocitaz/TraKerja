<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Gratis AI Resume Analyzer — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'AI Resume Analyzer',
                        'subtitle' => 'Akses gratis untuk analisis CV berbasis kecerdasan buatan'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Sebagai bagian dari komitmen kami dalam mendukung perjalanan karier setiap pengguna TraKerja, kami memberikan akses <strong>gratis satu kali</strong> untuk fitur AI Resume Analyzer — alat analisis CV berbasis kecerdasan buatan yang dirancang untuk meningkatkan daya saing Anda di pasar kerja.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Fitur ini sebelumnya hanya tersedia untuk pengguna Premium. Manfaatkan kesempatan ini sebaik mungkin.
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Yang Akan Anda Dapatkan
                                        </p>

                                        <!-- Feature 1 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:0;">
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Analisis CV Komprehensif</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Setiap bagian CV Anda — mulai dari pengalaman kerja, pendidikan, keahlian, hingga struktur dokumen — dianalisis secara menyeluruh oleh sistem AI kami.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Rekomendasi Perbaikan yang Konkret</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Dapatkan saran perbaikan yang spesifik dan dapat langsung diterapkan untuk membuat CV Anda lebih menarik di mata rekruter.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Optimasi ATS (Applicant Tracking System)</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Sistem AI akan mengevaluasi apakah CV Anda kompatibel dengan perangkat lunak ATS yang digunakan oleh mayoritas perusahaan besar saat ini.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Skor dan Rating CV</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Lihat skor keseluruhan CV Anda beserta rincian per-kategori sehingga Anda tahu persis area mana yang perlu ditingkatkan terlebih dahulu.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Callout Box -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Catatan Penting</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#52525b;">
                                            Akses gratis ini berlaku untuk satu kali analisis dan tersedia khusus bagi pengguna pada paket Free. Upload CV Anda dalam format PDF atau DOCX untuk hasil analisis yang optimal.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td>
                                        <a href="{{ config('app.url') }}/ai-analyzer"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            Analisis CV Saya Sekarang
                                        </a>
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            Kembali ke Dashboard
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

                    @include('emails.partials.footer')
                </table>
            </td>
        </tr>
    </table>

</body>
</html>

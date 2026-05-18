<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kami Perhatikan Anda Belum Kembali — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">

                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    <!-- Header -->
                    @include('emails.partials.header', [
                        'title'    => 'Selamat Kembali',
                        'subtitle' => 'Perjalanan karier Anda masih terbuka lebar'
                    ])

                    <!-- Main Card -->
                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kami menyadari bahwa Anda belum sempat kembali ke TraKerja dalam beberapa waktu terakhir. Kami memahami bahwa proses pencarian kerja tidak selalu berjalan linear — ada kalanya membutuhkan jeda, evaluasi ulang arah karier, maupun penyesuaian strategi.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Namun satu hal yang tidak berubah: tujuan karier Anda masih ada dan layak untuk diperjuangkan. Seluruh data lamaran dan dokumen Anda tersimpan dengan aman — siap dilanjutkan kapan pun Anda siap.
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Fitur yang Siap Membantu Anda
                                        </p>

                                        <!-- Feature 1 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:0;">
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Pelacak Lamaran Kerja</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Catat dan pantau status setiap lamaran dalam satu dasbor yang terorganisir — dari tahap pengiriman, wawancara, hingga penawaran kerja diterima.</p>
                                                </td>
                                            </tr>
                                            <!-- Feature 2 -->
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">AI Resume Analyzer</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Dapatkan analisis mendalam terhadap CV Anda menggunakan kecerdasan buatan — mencakup skor relevansi, kelemahan konten, dan rekomendasi perbaikan yang konkret.</p>
                                                </td>
                                            </tr>
                                            <!-- Feature 3 -->
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Cover Letter Generator</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Buat cover letter yang dipersonalisasi untuk setiap posisi secara efisien, dengan bantuan AI yang memahami konteks industri dan jabatan yang dituju.</p>
                                                </td>
                                            </tr>
                                            <!-- Feature 4 -->
                                            <tr>
                                                <td style="padding:16px 0;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Manajemen Target dan Rutinitas Harian</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Tetapkan target mingguan dan bangun cadence pencarian kerja yang konsisten untuk menjaga momentum dan produktivitas Anda sepanjang proses.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Quote Box -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0; font-size:14px; line-height:23px; color:#52525b; font-style:italic;">
                                            "Konsistensi dalam pencarian kerja — meskipun terasa lambat — jauh lebih efektif daripada intensitas yang tidak berkelanjutan. Setiap langkah kecil yang terdokumentasi membawa Anda lebih dekat ke tujuan."
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Buttons -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td>
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            Kembali ke Dashboard
                                        </a>
                                        <a href="{{ config('app.url') }}/tracker"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            Lanjutkan Lacak Lamaran
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tinjauan Bulanan Karier — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Tinjauan Bulanan Karier',
                        'subtitle' => $monthName . ' ' . $year . ' — Evaluasi dan Rencanakan Langkah Berikutnya'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Memasuki bulan {{ $monthName }} {{ $year }}, ini adalah waktu yang tepat untuk mengevaluasi progres pencarian kerja Anda dan menyusun rencana yang lebih terstruktur untuk periode mendatang.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Konsistensi dalam mendokumentasikan dan menindaklanjuti setiap lamaran adalah faktor pembeda yang signifikan dalam proses rekrutmen yang kompetitif.
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Prioritas untuk Bulan {{ $monthName }}
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Perbarui dan Perluas Daftar Lamaran</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Targetkan minimal lima lamaran baru yang relevan dengan kompetensi dan arah karier Anda. Kualitas relevansi lebih penting dari kuantitas semata.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Tinjau dan Perbarui Dokumen Lamaran</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Pastikan CV dan cover letter Anda mencerminkan pengalaman dan pencapaian terbaru. Dokumen yang diperbarui secara berkala meningkatkan impresi pertama secara signifikan.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Tetapkan Target Terukur untuk Bulan Ini</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Gunakan fitur Goals di TraKerja untuk menetapkan target spesifik — baik jumlah lamaran, wawancara, maupun pengembangan keahlian — agar progres Anda dapat dipantau secara objektif.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Tindak Lanjuti Lamaran yang Belum Mendapat Respons</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Kirimkan follow-up profesional untuk lamaran yang telah melewati tujuh hingga sepuluh hari kerja tanpa respons. Inisiatif ini menunjukkan keseriusan dan dedikasi Anda.</p>
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
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Perspektif Bulan {{ $monthName }}</p>
                                        <p style="margin:0; font-size:14px; line-height:23px; color:#52525b; font-style:italic;">
                                            "Pencarian kerja yang efektif bukan tentang mengirimkan lamaran sebanyak mungkin, melainkan tentang mendekati setiap peluang dengan persiapan yang matang dan tindak lanjut yang konsisten."
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td>
                                        <a href="{{ config('app.url') }}/tracker"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            Mulai Catat Lamaran
                                        </a>
                                        <a href="{{ config('app.url') }}/goals"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            Tetapkan Target Bulanan
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nikmati Suasana Baru TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#ffffff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:20px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse; border: 1px solid #e4e4e7; border-radius: 8px; overflow: hidden;">

                    @include('emails.partials.header', [
                        'title'    => 'Nikmati Suasana Baru TraKerja',
                        'subtitle' => 'Kami melakukan perubahan besar untuk mempercepat karir impian Anda'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 24px 32px 24px;">

                            <p style="margin:0 0 16px 0; font-size:14px; line-height:22px; color:#18181b;">
                                Halo <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 24px 0; font-size:14px; line-height:22px; color:#3f3f46;">
                                Kami baru saja meluncurkan serangkaian pembaruan desain dan fitur besar untuk memberikan pengalaman yang jauh lebih produktif, bersih, dan cepat. Berikut adalah rangkuman perubahan utama yang kini dapat Anda nikmati langsung di workspace Anda:
                            </p>

                            <!-- Feature List (Notion Style Bullets) -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td>
                                        <p style="margin:0 0 16px 0; font-size:10px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase; font-family: monospace;">
                                            Daftar Perubahan Utama
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px;">
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f4f4f5; vertical-align:top;">
                                                    <p style="margin:0 0 4px 0; font-size:13px; font-weight:600; color:#18181b;">Desain Workspace Baru (Cupertino-Notion Style)</p>
                                                    <p style="margin:0; font-size:12px; line-height:18px; color:#71717a;">Tata letak papan pelacakan (Kanban) yang lebih datar, minimalis, dan hemat ruang visual untuk mengurangi rasa jenuh saat melacak puluhan lamaran kerja.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f4f4f5; vertical-align:top;">
                                                    <p style="margin:0 0 4px 0; font-size:13px; font-weight:600; color:#18181b;">AI ATS Resume Scanner & Matcher</p>
                                                    <p style="margin:0; font-size:12px; line-height:18px; color:#71717a;">Uji kelayakan CV Anda secara instan terhadap deskripsi posisi kerja. Periksa skor ATS dan temukan kata kunci penting yang terlewat sebelum mengirim berkas.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f4f4f5; vertical-align:top;">
                                                    <p style="margin:0 0 4px 0; font-size:13px; font-weight:600; color:#18181b;">Pembaruan Chrome Auto-Fill Extension</p>
                                                    <p style="margin:0; font-size:12px; line-height:18px; color:#71717a;">Sistem ekstraksi data yang kini mendukung integrasi portal kerja lebih luas (LinkedIn, Glints, JobStreet, Kalibrr, Dealls, Talentics) hanya dengan satu klik.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; vertical-align:top;">
                                                    <p style="margin:0 0 4px 0; font-size:13px; font-weight:600; color:#18181b;">Wawasan Estimasi Gaji Regional (UMK Tracker)</p>
                                                    <p style="margin:0; font-size:12px; line-height:18px; color:#71717a;">Lacak penawaran gaji pasar yang wajar untuk posisi kerja Anda dan dapatkan perbandingan otomatis terhadap batas minimal UMK wilayah regional tujuan.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Callout (Notion Style Block) -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#f9fafb; border:1px solid #e4e4e7; border-left:3px solid #18181b; border-radius:4px; padding:12px 16px;">
                                        <p style="margin:0; font-size:12px; line-height:18px; color:#52525b;">
                                            <strong>Semua fitur baru ini dapat langsung digunakan gratis.</strong> Kami percaya perombakan visual ini akan mempermudah konsistensi Anda dalam melamar pekerjaan secara lebih tertata.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button (Notion Solid Style) -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                                <tr>
                                    <td align="left">
                                        <a href="{{ config('app.url') }}/tracker"
                                           style="display:inline-block; padding:10px 20px; background-color:#18181b; color:#ffffff; text-decoration:none; border-radius:6px; font-size:12px; font-weight:600; text-transform: uppercase; letter-spacing: 0.05em;">
                                            Coba Workspace Baru
                                        </a>
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

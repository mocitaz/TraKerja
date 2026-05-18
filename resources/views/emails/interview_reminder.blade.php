<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Jadwal Wawancara — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Pengingat Wawancara',
                        'subtitle' => 'Jadwal wawancara Anda sudah semakin dekat'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $jobApplication->user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kami mengirimkan pengingat ini untuk memastikan Anda telah mempersiapkan diri dengan baik untuk wawancara yang akan datang. Berikut adalah detail jadwal wawancara Anda.
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Detail Wawancara
                                        </p>

                                        <!-- Interview Detail Card -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                            <tr>
                                                <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:20px;">
                                                    <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                        <tr>
                                                            <td style="padding-bottom:10px; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Posisi</p>
                                                                <p style="margin:0; font-size:15px; font-weight:600; color:#18181b;">{{ $jobApplication->position }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Perusahaan</p>
                                                                <p style="margin:0; font-size:14px; color:#18181b;">{{ $jobApplication->company_name }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; {{ ($jobApplication->interview_location || $jobApplication->interview_notes) ? 'border-bottom:1px solid #ede9fe;' : '' }}">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Tanggal dan Waktu</p>
                                                                <p style="margin:0; font-size:14px; color:#18181b;">{{ $jobApplication->getFormattedInterviewDateAttribute() }}</p>
                                                            </td>
                                                        </tr>
                                                        @if($jobApplication->interview_location)
                                                        <tr>
                                                            <td style="padding:10px 0; {{ $jobApplication->interview_notes ? 'border-bottom:1px solid #ede9fe;' : '' }}">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Lokasi</p>
                                                                <p style="margin:0; font-size:14px; color:#18181b;">{{ $jobApplication->interview_location }}</p>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        @if($jobApplication->interview_notes)
                                                        <tr>
                                                            <td style="padding-top:10px;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Catatan</p>
                                                                <p style="margin:0; font-size:13px; line-height:21px; color:#52525b;">{{ $jobApplication->interview_notes }}</p>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Preparation Checklist -->
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Panduan Persiapan
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Pelajari Profil Perusahaan</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Pahami visi, misi, produk, dan budaya kerja perusahaan sebelum wawancara berlangsung.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Siapkan Dokumen yang Diperlukan</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Pastikan CV terbaru, portofolio, dan dokumen pendukung lainnya sudah siap dalam format yang rapi.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Catat Hasil Wawancara di TraKerja</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Segera perbarui status lamaran Anda setelah wawancara selesai untuk memastikan semua progres tercatat dengan baik.</p>
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
                                        <a href="{{ config('app.url') }}/tracker"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            Perbarui Status Lamaran
                                        </a>
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            Buka Dashboard
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

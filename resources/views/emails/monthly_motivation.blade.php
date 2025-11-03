<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulan Baru Semangat Baru - TraKerja</title>
</head>
<body style="margin:0; padding:0; background:#f6f2ff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#111827;">
    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" style="width:100%; max-width:640px; border-collapse:collapse; background:#ffffff; border-radius:14px; box-shadow:0 8px 24px rgba(107,70,193,0.08), 0 2px 8px rgba(0,0,0,0.03); overflow:hidden;">
                    <!-- Compact Professional Header -->
                    @include('emails.partials.header', [
                        'title' => 'Bulan Baru Semangat Baru',
                        'subtitle' => $monthName . ' ' . $year . ' - Tingkatkan Karier Anda'
                    ])

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 28px 8px;">
                            <p style="margin:0 0 10px; font-size:14px; line-height:22px; color:#111827;">Halo <strong>{{ $user->name }}</strong>,</p>
                            <p style="margin:0 0 16px; font-size:14px; line-height:22px; color:#374151;">Selamat datang di bulan {{ $monthName }} {{ $year }}! Ini adalah kesempatan baru untuk meraih pekerjaan impian Anda. Mari kita mulai bulan ini dengan semangat baru!</p>

                            <!-- Highlight Box -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:linear-gradient(135deg, #7c5ce0 0%, #6b46c1 100%); border-radius:10px; margin:16px 0 20px; overflow:hidden;">
                                <tr>
                                    <td style="padding:20px; text-align:center;">
                                        <h2 style="margin:0 0 8px; font-size:22px; line-height:28px; font-weight:700; color:#ffffff;">Bulan Baru, Peluang Baru!</h2>
                                        <p style="margin:0; font-size:14px; line-height:20px; color:#ffffff; opacity:0.95;">Tingkatkan semangat dan aktifitas lamaran kerja Anda di bulan ini</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Action Items -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding-bottom:12px;">
                                        <p style="margin:0 0 12px; font-size:13px; line-height:20px; color:#4b5563; font-weight:700;">Ayo mulai bulan November dengan:</p>
                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f0eaff;">
                                                    <table role="presentation" style="border-collapse:collapse;">
                                                        <tr>
                                                            <td width="20" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:6px; height:6px; background:#7c5ce0; border-radius:50%;"></span></td>
                                                            <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;"><strong>Catat lamaran baru</strong> - Tambahkan minimal 5 lamaran baru di bulan ini</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f0eaff;">
                                                    <table role="presentation" style="border-collapse:collapse;">
                                                        <tr>
                                                            <td width="20" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:6px; height:6px; background:#7c5ce0; border-radius:50%;"></span></td>
                                                            <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;"><strong>Update CV Anda</strong> - Pastikan CV Anda selalu up-to-date dan menarik</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f0eaff;">
                                                    <table role="presentation" style="border-collapse:collapse;">
                                                        <tr>
                                                            <td width="20" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:6px; height:6px; background:#7c5ce0; border-radius:50%;"></span></td>
                                                            <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;"><strong>Set goal bulanan</strong> - Tetapkan target lamaran dan interview untuk {{ $monthName }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0;">
                                                    <table role="presentation" style="border-collapse:collapse;">
                                                        <tr>
                                                            <td width="20" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:6px; height:6px; background:#7c5ce0; border-radius:50%;"></span></td>
                                                            <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;"><strong>Follow-up lamaran</strong> - Jangan lupa follow-up lamaran yang sudah dikirim</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Motivation Box -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:#fbfaff; border:1px solid #f0eaff; border-radius:10px; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <p style="margin:0 0 12px; font-size:13px; line-height:20px; color:#4b5563; font-weight:700;">Motivasi {{ $monthName }}:</p>
                                        <p style="margin:0; font-size:13px; line-height:20px; color:#6b7280; font-style:italic;">"Setiap bulan baru adalah kesempatan baru. Jangan menyerah, teruslah berusaha. Kesuksesan tidak datang kepada mereka yang menunggu, tapi kepada mereka yang terus berusaha."</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Buttons -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:18px 0 12px;">
                                <tr>
                                    <td align="center" style="padding-bottom:12px;">
                                        <a href="{{ config('app.url') }}/tracker" style="display:inline-block; padding:12px 22px; background:linear-gradient(135deg, #7c5ce0 0%, #6b46c1 100%); color:#ffffff; text-decoration:none; border-radius:8px; font-weight:700; font-size:14px; letter-spacing:0.2px; box-shadow:0 6px 14px rgba(107,70,193,0.20);">Mulai Catat Lamaran</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('app.url') }}/goals" style="display:inline-block; padding:12px 22px; background:#ffffff; color:#7c5ce0; text-decoration:none; border-radius:8px; font-weight:600; font-size:14px; border:2px solid #7c5ce0; box-shadow:0 2px 8px rgba(107,70,193,0.10);">Set Goal Bulanan</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:8px;">
                                <tr>
                                    <td style="font-size:12px; line-height:18px; color:#6b7280; text-align:center;">
                                        <p style="margin:0 0 8px;"><strong>Ingat!</strong></p>
                                        <p style="margin:0;">Konsistensi adalah kunci. Setiap langkah kecil yang Anda ambil hari ini akan membawa Anda lebih dekat ke pekerjaan impian. Semangat!</p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:16px; padding-top:16px; border-top:1px solid #f0eaff;">
                                <tr>
                                    <td style="font-size:12px; line-height:18px; color:#6b7280;">Semoga bulan {{ $monthName }} penuh dengan keberuntungan dan kesempatan untuk Anda!</td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:12px;">
                                <tr>
                                    <td style="font-size:12px; line-height:18px; color:#6b7280;">
                                        Salam sukses,<br>
                                        <strong style="color:#7c5ce0;">Tim TraKerja</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Compact Professional Footer -->
                    @include('emails.partials.footer')
                </table>
            </td>
        </tr>
    </table>
</body>
</html>


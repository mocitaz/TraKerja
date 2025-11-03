<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apa anda belum melamar pekerjaan lagi? - TraKerja</title>
</head>
<body style="margin:0; padding:0; background:#f6f2ff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#111827;">
    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" style="width:100%; max-width:640px; border-collapse:collapse; background:#ffffff; border-radius:14px; box-shadow:0 8px 24px rgba(107,70,193,0.08), 0 2px 8px rgba(0,0,0,0.03); overflow:hidden;">
                    <!-- Compact Professional Header -->
                    @include('emails.partials.header', [
                        'title' => 'Apa anda belum melamar pekerjaan lagi?',
                        'subtitle' => 'Jangan lupa catat aplikasi lamaran Anda'
                    ])

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 28px 8px;">
                            <p style="margin:0 0 10px; font-size:14px; line-height:22px; color:#111827;">Halo <strong>{{ $user->name }}</strong>,</p>
                            <p style="margin:0 0 16px; font-size:14px; line-height:22px; color:#374151;">Sudah berapa lama Anda tidak mencatat aplikasi lamaran kerja baru di TraKerja? Jangan sampai lamaran Anda terlewat dan tidak tercatat!</p>

                            <!-- Highlight Box -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:linear-gradient(135deg, #7c5ce0 0%, #6b46c1 100%); border-radius:10px; margin:16px 0 20px; overflow:hidden;">
                                <tr>
                                    <td style="padding:20px; text-align:center;">
                                        <h2 style="margin:0 0 8px; font-size:22px; line-height:28px; font-weight:700; color:#ffffff;">Catat Semua Lamaran Anda</h2>
                                        <p style="margin:0; font-size:14px; line-height:20px; color:#ffffff; opacity:0.95;">Jangan biarkan aplikasi lamaran Anda terlewat - simpan dan track di TraKerja</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Features -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding-bottom:12px;">
                                        <p style="margin:0 0 12px; font-size:13px; line-height:20px; color:#4b5563; font-weight:700;">Mengapa penting mencatat di TraKerja:</p>
                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f0eaff;">
                                                    <table role="presentation" style="border-collapse:collapse;">
                                                        <tr>
                                                            <td width="20" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:6px; height:6px; background:#7c5ce0; border-radius:50%;"></span></td>
                                                            <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;"><strong>Tidak Terlewat</strong> - Semua aplikasi lamaran tersimpan rapi dan terorganisir</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f0eaff;">
                                                    <table role="presentation" style="border-collapse:collapse;">
                                                        <tr>
                                                            <td width="20" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:6px; height:6px; background:#7c5ce0; border-radius:50%;"></span></td>
                                                            <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;"><strong>Tracking Status</strong> - Pantau status lamaran dari aplikasi hingga interview</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0; border-bottom:1px solid #f0eaff;">
                                                    <table role="presentation" style="border-collapse:collapse;">
                                                        <tr>
                                                            <td width="20" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:6px; height:6px; background:#7c5ce0; border-radius:50%;"></span></td>
                                                            <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;"><strong>Reminder Otomatis</strong> - Dapatkan notifikasi untuk follow-up dan interview</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:12px 0;">
                                                    <table role="presentation" style="border-collapse:collapse;">
                                                        <tr>
                                                            <td width="20" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:6px; height:6px; background:#7c5ce0; border-radius:50%;"></span></td>
                                                            <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;"><strong>Statistik Lengkap</strong> - Lihat progress dan analisis pencarian kerja Anda</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Steps -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:#fbfaff; border:1px solid #f0eaff; border-radius:10px; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <p style="margin:0 0 12px; font-size:13px; line-height:20px; color:#4b5563; font-weight:700;">Cara mencatat lamaran (sangat mudah!):</p>
                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td width="24" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:20px; height:20px; border-radius:4px; background:#6b7280; text-align:center; color:#fff; font-size:12px; line-height:20px; font-weight:700;">1</span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Login ke akun TraKerja Anda</td>
                                            </tr>
                                            <tr>
                                                <td width="24" style="vertical-align:top; padding-top:6px;"><span style="display:inline-block; width:20px; height:20px; border-radius:4px; background:#6b7280; text-align:center; color:#fff; font-size:12px; line-height:20px; font-weight:700;">2</span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Klik menu "Job Tracker" atau "Tracker"</td>
                                            </tr>
                                            <tr>
                                                <td width="24" style="vertical-align:top; padding-top:6px;"><span style="display:inline-block; width:20px; height:20px; border-radius:4px; background:#6b7280; text-align:center; color:#fff; font-size:12px; line-height:20px; font-weight:700;">3</span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Klik "Tambah Aplikasi" atau "Add Application"</td>
                                            </tr>
                                            <tr>
                                                <td width="24" style="vertical-align:top; padding-top:6px;"><span style="display:inline-block; width:20px; height:20px; border-radius:4px; background:#6b7280; text-align:center; color:#fff; font-size:12px; line-height:20px; font-weight:700;">4</span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Isi informasi perusahaan, posisi, dan status</td>
                                            </tr>
                                            <tr>
                                                <td width="24" style="vertical-align:top; padding-top:6px;"><span style="display:inline-block; width:20px; height:20px; border-radius:4px; background:#6b7280; text-align:center; color:#fff; font-size:12px; line-height:20px; font-weight:700;">5</span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Simpan dan mulai tracking progress lamaran Anda!</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:18px 0 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('app.url') }}/tracker" style="display:inline-block; padding:12px 22px; background:linear-gradient(135deg, #7c5ce0 0%, #6b46c1 100%); color:#ffffff; text-decoration:none; border-radius:8px; font-weight:700; font-size:14px; letter-spacing:0.2px; box-shadow:0 6px 14px rgba(107,70,193,0.20);">Catat Lamaran Sekarang</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:8px;">
                                <tr>
                                    <td style="font-size:12px; line-height:18px; color:#6b7280; text-align:center;">
                                        <p style="margin:0 0 8px;"><strong>Ingat!</strong></p>
                                        <p style="margin:0;">Semakin banyak lamaran yang Anda catat, semakin mudah untuk tracking dan follow-up. Jangan biarkan kesempatan terlewat!</p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:16px; padding-top:16px; border-top:1px solid #f0eaff;">
                                <tr>
                                    <td style="font-size:12px; line-height:18px; color:#6b7280;">Punya pertanyaan atau butuh bantuan? Balas email ini atau hubungi tim support kami.</td>
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


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur Baru: Ekstensi TraKerja — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'TraKerja Chrome Extension',
                        'subtitle' => 'Otomatisasi Penyimpanan Data Lowongan Pekerjaan'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kami dengan bangga mengumumkan peluncuran <strong>TraKerja Chrome Extension</strong>, sebuah inovasi terbaru yang kami rancang secara khusus untuk meningkatkan efisiensi dan produktivitas Anda dalam manajemen proses pencarian kerja.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kami menyadari bahwa proses pencatatan dan pengelolaan data lowongan dari berbagai platform portal rekrutmen memakan waktu yang cukup lama. Melalui implementasi ekstensi ini, seluruh proses penyalinan data manual (copy-paste) tidak lagi diperlukan. Sistem ini akan secara otomatis mengekstraksi informasi lowongan kerja yang relevan dan menyinkronkannya langsung ke dalam Kanban Board pada akun TraKerja Anda.
                            </p>

                            <!-- Feature Highlight -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#eff6ff; border:1px solid #dbeafe; border-left:3px solid #3b82f6; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:0.08em;">Teknologi Ekstraksi Cerdas Terintegrasi</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#475569;">Ekstensi ini didukung oleh algoritma canggih yang secara cerdas mendeteksi elemen-elemen krusial seperti Judul Posisi, Nama Perusahaan, serta Lokasi Penempatan dengan presisi tinggi. Algoritma ini dirancang untuk memiliki daya tahan dan kemampuan adaptasi yang sangat baik, bahkan ketika antarmuka pengguna (UI) pada platform rekrutmen mengalami perubahan.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Supported Platforms Section -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#059669; letter-spacing:0.1em; text-transform:uppercase;">
                                            Kompatibilitas Platform Rekrutmen
                                        </p>

                                        <p style="margin:0 0 16px 0; font-size:14px; line-height:24px; color:#52525b;">
                                            Pada rilis perdana ini, ekstensi TraKerja telah mendukung sinkronisasi data secara optimal dengan berbagai portal rekrutmen terkemuka di Indonesia, antara lain:
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                            <tr>
                                                <td style="padding:10px 0; border-bottom:1px solid #f4f4f5; width:50%;">
                                                    <p style="margin:0; font-size:14px; font-weight:600; color:#18181b;">LinkedIn</p>
                                                </td>
                                                <td style="padding:10px 0; border-bottom:1px solid #f4f4f5; width:50%;">
                                                    <p style="margin:0; font-size:14px; font-weight:600; color:#18181b;">JobStreet</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0; font-size:14px; font-weight:600; color:#18181b;">Glints</p>
                                                </td>
                                                <td style="padding:10px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0; font-size:14px; font-weight:600; color:#18181b;">Kalibrr</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:10px 0;">
                                                    <p style="margin:0; font-size:14px; font-weight:600; color:#18181b;">Dealls</p>
                                                </td>
                                                <td style="padding:10px 0;">
                                                    <p style="margin:0; font-size:14px; font-weight:600; color:#18181b;">Talentics</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/extension') }}"
                                           style="display:inline-block; padding:14px 28px; background-color:#18181b; color:#ffffff; text-decoration:none; border-radius:8px; font-size:15px; font-weight:600; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                                            Pelajari dan Unduh Ekstensi
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Tip -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">
                                            <strong>Informasi Tambahan:</strong> Ekstensi TraKerja dikembangkan secara khusus untuk ekosistem peramban desktop. Silakan lakukan proses instalasi melalui peramban Google Chrome pada perangkat komputer (PC atau Laptop) Anda untuk dapat menikmati fungsionalitas ini.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">Semoga inovasi ini senantiasa mendukung kesuksesan perjalanan karier Anda.</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#2563eb;">Tim TraKerja</p>
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

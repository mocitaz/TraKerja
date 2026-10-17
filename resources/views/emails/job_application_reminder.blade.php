<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Pengingat Pencatatan Lamaran — TraKerja') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Pengingat Pencatatan Lamaran',
                        'subtitle' => 'Pastikan seluruh lamaran kerja Anda tercatat dan terpantau'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                {{ __('Yth.') }} <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                {{ __('Kami mengingatkan bahwa sudah beberapa waktu sejak Anda terakhir kali mencatat lamaran kerja baru di TraKerja. Pencatatan yang teratur adalah fondasi dari strategi pencarian kerja yang efektif dan terukur.') }}
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                {{ __('Setiap lamaran yang tidak tercatat berpotensi menjadi kesempatan yang terlewat tanpa tindak lanjut yang tepat waktu.') }}
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            {{ __('Manfaat Pencatatan yang Konsisten') }}
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Visibilitas Penuh atas Setiap Lamaran') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Seluruh lamaran tersimpan secara terstruktur sehingga Anda dapat memantau statusnya dari satu dasbor terpusat, tanpa risiko terlewat.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Pemantauan Status secara Real-Time') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Perbarui status lamaran dari tahap pengiriman hingga wawancara dan penawaran, sehingga Anda selalu mengetahui posisi terkini setiap aplikasi.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Notifikasi Tindak Lanjut Otomatis') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('TraKerja akan mengingatkan Anda untuk melakukan follow-up pada lamaran yang belum mendapat respons setelah jangka waktu tertentu.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Analisis Pola Pencarian Kerja') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Data lamaran yang lengkap memungkinkan Anda mengidentifikasi tren, mengoptimalkan strategi, dan meningkatkan tingkat keberhasilan secara keseluruhan.') }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Steps -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            {{ __('Cara Mencatat Lamaran') }}
                                        </p>

                                        <!-- Step 1 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:18px;">
                                            <tr>
                                                <td width="36" style="vertical-align:top; padding-top:1px;">
                                                    <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">1</div>
                                                </td>
                                                <td style="padding-left:14px; vertical-align:top;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Login ke Akun TraKerja Anda') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Akses platform melalui browser atau aplikasi mobile Anda.') }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Step 2 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:18px;">
                                            <tr>
                                                <td width="36" style="vertical-align:top; padding-top:1px;">
                                                    <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">2</div>
                                                </td>
                                                <td style="padding-left:14px; vertical-align:top;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Buka Menu Job Tracker') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Navigasikan ke fitur Job Tracker melalui menu navigasi utama.') }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Step 3 -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:18px;">
                                            <tr>
                                                <td width="36" style="vertical-align:top; padding-top:1px;">
                                                    <div style="width:26px; height:26px; border-radius:6px; background-color:#6d28d9; text-align:center; line-height:26px; font-size:11px; font-weight:700; color:#ffffff;">3</div>
                                                </td>
                                                <td style="padding-left:14px; vertical-align:top;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Tambahkan Lamaran Baru') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Klik tombol Tambah Lamaran dan isi informasi perusahaan, posisi yang dilamar, tanggal pengiriman, dan status awal.') }}</p>
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
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Simpan dan Pantau Secara Berkala') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Perbarui status lamaran secara rutin dan manfaatkan fitur pengingat untuk tindak lanjut yang tepat waktu.') }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Callout -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Catatan dari Tim TraKerja') }}</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#52525b;">
                                            {{ __('Pengguna yang mencatat lamaran secara konsisten cenderung menyelesaikan proses rekrutmen lebih cepat karena dapat mengidentifikasi peluang tindak lanjut dengan lebih tepat waktu.') }}
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
                                            {{ __('Catat Lamaran Sekarang') }}
                                        </a>
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            {{ __('Buka Dashboard') }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">{{ __('Hormat kami,') }}</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#6d28d9;">{{ __('Tim TraKerja') }}</p>
                                        <p style="margin:0; font-size:13px; color:#a1a1aa;">{{ __('PT Teknalogi Transformasi Digital') }}</p>
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

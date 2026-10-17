<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Pembaruan Platform TraKerja') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Pembaruan Platform',
                        'subtitle' => 'Rangkuman fitur dan peningkatan terbaru pada TraKerja'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                {{ __('Yth.') }} <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                {{ __('Sebagai bagian dari komitmen kami dalam terus meningkatkan kualitas platform, kami mengumumkan peluncuran pembaruan besar pada TraKerja. Serangkaian fitur baru telah dikembangkan berdasarkan masukan pengguna dan analisis kebutuhan pasar kerja terkini.') }}
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                {{ __('Seluruh pembaruan ini dirancang untuk membantu Anda mengelola proses pencarian kerja dengan lebih efisien, terstruktur, dan kompetitif.') }}
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            {{ __('Pembaruan Ekosistem TraKerja') }}
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('AI Resume Analyzer 2.0') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Peningkatan signifikan pada kemampuan analisis kecerdasan buatan untuk mengevaluasi resume secara komprehensif — mencakup relevansi konten, kompatibilitas ATS, dan keterbacaan oleh rekruter.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Smart Cover Letter') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Fitur penyusunan surat lamaran yang terstruktur dan dapat disesuaikan dengan konteks posisi dan perusahaan yang dilamar, sehingga setiap surat terasa personal dan relevan.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('TraKerja Sites Portfolio') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Fasilitas pembuatan halaman portofolio personal yang dapat dibagikan kepada rekruter untuk menampilkan karya, proyek, dan pencapaian profesional Anda secara terpusat.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Professional CV Builder') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Perangkat pembuat CV dengan koleksi templat berstandar industri yang telah dioptimalkan untuk meningkatkan peluang lolos seleksi berkas di berbagai jenis perusahaan.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:16px 0;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Peningkatan Antarmuka Platform') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Revitalisasi desain antarmuka yang lebih intuitif dan responsif, mencakup penyederhanaan navigasi dan optimasi performa untuk pengalaman pengguna yang lebih baik di semua perangkat.') }}</p>
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
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Catatan dari Tim Produk') }}</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#52525b;">
                                            {{ __('Seluruh pembaruan ini tersedia langsung melalui dasbor Anda tanpa perlu melakukan instalasi atau pembaruan manual. Apabila Anda menemukan kendala teknis, tim dukungan kami siap membantu melalui fitur obrolan di dalam aplikasi.') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td>
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            {{ __('Jelajahi Pembaruan') }}
                                        </a>
                                        <a href="{{ config('app.url') }}/ai-analyzer"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            {{ __('Coba AI Analyzer') }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">{{ __('Hormat kami,') }}</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#6d28d9;">{{ __('Manajemen TraKerja') }}</p>
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target Karier Berhasil Dicapai — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Target Berhasil Dicapai',
                        'subtitle' => 'Selamat atas pencapaian milestone karier Anda'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $goal->user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kami ingin menyampaikan apresiasi atas pencapaian Anda. Target karier yang Anda tetapkan telah berhasil diselesaikan — ini merupakan bukti nyata dari ketekunan dan komitmen Anda dalam mengelola perjalanan karier secara terstruktur.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Berikut adalah ringkasan target yang baru saja Anda selesaikan.
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Target yang Dicapai
                                        </p>

                                        <!-- Goal Card -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                                            <tr>
                                                <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:20px;">
                                                    <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:12px;">
                                                        <tr>
                                                            <td>
                                                                <span style="display:inline-block; padding:3px 10px; background-color:#dcfce7; color:#15803d; border-radius:4px; font-size:11px; font-weight:700; letter-spacing:0.05em; text-transform:uppercase;">Selesai</span>
                                                            </td>
                                                            @if($goal->target_date)
                                                            <td align="right">
                                                                <span style="font-size:12px; color:#a1a1aa;">Target: {{ \Carbon\Carbon::parse($goal->target_date)->format('d M Y') }}</span>
                                                            </td>
                                                            @endif
                                                        </tr>
                                                    </table>
                                                    <p style="margin:0 0 6px 0; font-size:16px; font-weight:700; color:#18181b;">{{ $goal->title }}</p>
                                                    @if($goal->description)
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ $goal->description }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- What's Next -->
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Langkah Selanjutnya
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:0;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Tetapkan Target Baru</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Momentum yang Anda bangun sangat berharga. Pertahankan dengan menetapkan target karier berikutnya — baik jangka pendek maupun jangka panjang.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Evaluasi Progres Lamaran</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Tinjau kembali semua lamaran aktif Anda, perbarui statusnya, dan jadwalkan tindak lanjut yang belum dilakukan.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Tingkatkan Kualitas Dokumen</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Gunakan pencapaian ini sebagai bahan pembaruan CV dan portofolio Anda agar semakin kompetitif di pasar kerja.</p>
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
                                        <a href="{{ config('app.url') }}/goals"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            Tetapkan Target Baru
                                        </a>
                                        <a href="{{ config('app.url') }}/dashboard"
                                           style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">
                                            Lihat Dashboard
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

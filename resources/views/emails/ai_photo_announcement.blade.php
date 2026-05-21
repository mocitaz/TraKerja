<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur Baru: TraKerja AI Photo Studio</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'TraKerja AI Photo Studio',
                        'subtitle' => 'Tingkatkan Kesan Profesional Anda dalam Hitungan Detik'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kesan pertama sangatlah menentukan, terutama dalam dunia profesional. Pas foto dan foto profil LinkedIn yang berkualitas dapat meningkatkan peluang Anda untuk dilirik oleh rekruter secara signifikan.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Berangkat dari kebutuhan tersebut, kami dengan bangga mengumumkan rilis fitur terbaru: <strong>TraKerja AI Photo Studio</strong>. Teknologi kecerdasan buatan kami dirancang khusus untuk mentransformasi foto biasa Anda menjadi potret profesional berstandar industri dengan mudah dan cepat.
                            </p>

                            <!-- Feature Highlight 1 -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:16px;">
                                <tr>
                                    <td style="background-color:#f5f3ff; border:1px solid #ede9fe; border-left:3px solid #8b5cf6; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#7c3aed; text-transform:uppercase; letter-spacing:0.08em;">AI Professional Enhance</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#4c1d95;">Secara cerdas mengubah pakaian kasual Anda menjadi setelan profesional (jas atau blazer), sekaligus memperbaiki pencahayaan dan resolusi wajah. Sangat ideal untuk kebutuhan profil LinkedIn dan CV.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Feature Highlight 2 -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#f8fafc; border:1px solid #f1f5f9; border-left:3px solid #64748b; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.08em;">Precision Background Removal</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#334155;">Menghapus latar belakang dengan presisi tinggi tanpa merusak detail rambut atau pakaian. Anda dapat menggantinya dengan warna solid (merah/biru) untuk kebutuhan administrasi formal dan dokumen resmi.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/ai-photo') }}"
                                           style="display:inline-block; padding:14px 28px; background-color:#8b5cf6; color:#ffffff; text-decoration:none; border-radius:8px; font-size:15px; font-weight:600; box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.4), 0 2px 4px -1px rgba(139, 92, 246, 0.2);">
                                            Coba AI Photo Studio Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Tip -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">
                                            <strong>Informasi Kredit:</strong> Setiap generasi foto menggunakan 5 kredit premium atau 2 kredit reguler. Pastikan foto asli Anda memiliki pencahayaan wajah yang cukup agar AI dapat bekerja secara maksimal.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">Mari ciptakan kesan profesional terbaik Anda dan raih karir impian bersama kami.</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#8b5cf6;">Tim TraKerja</p>
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur Baru: AI Follow-Up Email</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'AI Follow-Up Email (Tanya Kabar)',
                        'subtitle' => 'Dapatkan Kejelasan Status Lamaran Anda Tanpa Ragu'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Menunggu hasil wawancara atau kabar setelah mengirim lamaran seringkali menjadi bagian paling menegangkan dari proses mencari kerja. Di-*ghosting* oleh rekruter bukan hanya membuang waktu, tapi juga menyita pikiran.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Untuk membantu Anda mengambil kendali atas proses karir Anda, kami meluncurkan fitur terbaru: <strong>AI Follow-Up Email (Tanya Kabar)</strong>. Kini, Anda dapat dengan mudah mengirimkan email tindak lanjut yang profesional hanya dengan satu klik.
                            </p>

                            <!-- Feature Highlight 1 -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:16px;">
                                <tr>
                                    <td style="background-color:#fef2f2; border:1px solid #fee2e2; border-left:3px solid #f43f5e; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#e11d48; text-transform:uppercase; letter-spacing:0.08em;">Otomatisasi Profesional</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#881337;">Kecerdasan buatan kami merangkai kata-kata terbaik untuk menanyakan status lamaran Anda secara sopan, elegan, dan tetap profesional. Tidak perlu lagi bingung merangkai kata agar tidak terkesan mendesak.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Feature Highlight 2 -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="background-color:#f8fafc; border:1px solid #f1f5f9; border-left:3px solid #64748b; border-radius:4px; padding:16px 20px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.08em;">Deteksi "Ghosting" Cerdas</p>
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#334155;">Sistem kami akan secara otomatis memunculkan opsi "Tanya Kabar" apabila status lamaran Anda tidak mengalami perubahan dalam kurun waktu 14 hari, memastikan Anda *follow up* di waktu yang tepat.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/jobs') }}"
                                           style="display:inline-block; padding:14px 28px; background-color:#f43f5e; color:#ffffff; text-decoration:none; border-radius:8px; font-size:15px; font-weight:600; box-shadow: 0 4px 6px -1px rgba(244, 63, 94, 0.4), 0 2px 4px -1px rgba(244, 63, 94, 0.2);">
                                            Cek Status Lamaran Anda
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Tip -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">
                                            <strong>Cara Penggunaan:</strong> Kunjungi menu <em>Job Tracker</em> Anda. Jika ada lamaran yang sudah berumur lebih dari 14 hari tanpa *update*, tombol "Tanya Kabar" akan muncul secara otomatis.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">Jangan biarkan karir Anda menggantung tanpa kepastian. Ambil inisiatif hari ini!</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#f43f5e;">Tim TraKerja</p>
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Alamat Email Anda — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Verifikasi Email Anda',
                        'subtitle' => 'Satu langkah terakhir untuk mengaktifkan akun TraKerja Anda'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name ?? 'Pengguna TraKerja' }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Terima kasih telah mendaftar di TraKerja. Untuk mengaktifkan akun Anda dan mulai menggunakan seluruh fitur platform, kami memerlukan konfirmasi bahwa alamat email ini milik Anda.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Silakan klik tombol di bawah ini untuk menyelesaikan proses verifikasi.
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Manfaat Akun Terverifikasi
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:0;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Akses Penuh ke Semua Fitur</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Lacak lamaran kerja, buat CV profesional, dan gunakan AI Analyzer tanpa batasan setelah akun Anda diverifikasi.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Notifikasi Penting</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Terima pengingat follow-up lamaran, pembaruan status, dan informasi karier relevan langsung di kotak masuk Anda.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 5px 0; font-size:14px; font-weight:600; color:#18181b;">Keamanan Akun</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Alamat email yang terverifikasi memastikan Anda dapat memulihkan akses ke akun Anda kapan pun diperlukan.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                                <tr>
                                    <td>
                                        <a href="{{ $actionUrl }}"
                                           style="display:inline-block; padding:12px 24px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600;">
                                            Verifikasi Alamat Email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Fallback link -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Tombol Tidak Berfungsi?</p>
                                        <p style="margin:0 0 8px 0; font-size:13px; line-height:20px; color:#52525b;">Salin dan tempelkan tautan berikut ke peramban Anda:</p>
                                        <p style="margin:0; font-size:12px; line-height:18px; color:#6d28d9; word-break:break-all;">
                                            <a href="{{ $actionUrl }}" style="color:#6d28d9; text-decoration:underline;">{{ $actionUrl }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 32px 0; font-size:13px; line-height:21px; color:#71717a;">
                                Apabila Anda tidak merasa mendaftar di TraKerja, abaikan email ini. Akun tidak akan diaktifkan tanpa verifikasi dari Anda.
                            </p>

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

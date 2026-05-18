<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi OTP — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Kode Verifikasi',
                        'subtitle' => 'Masukkan kode berikut untuk menyelesaikan proses verifikasi'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kami menerima permintaan verifikasi untuk akun TraKerja Anda. Gunakan kode satu kali berikut untuk menyelesaikan proses tersebut.
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Kode OTP Anda
                                        </p>

                                        <!-- OTP Display -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                                            <tr>
                                                <td align="center" style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:28px 20px;">
                                                    <p style="margin:0 0 8px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Kode Verifikasi</p>
                                                    <p style="margin:0; font-size:36px; font-weight:700; letter-spacing:10px; color:#18181b; font-family:'Courier New', Courier, monospace;">{{ $otp }}</p>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Instructions -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Masa Berlaku Kode</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Kode OTP ini hanya berlaku selama <strong style="color:#18181b;">10 menit</strong> sejak email ini dikirimkan. Segera masukkan kode sebelum kedaluwarsa.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Hanya Untuk Sekali Pakai</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Kode ini bersifat unik dan hanya dapat digunakan satu kali. Apabila Anda membutuhkan kode baru, silakan ajukan permintaan ulang melalui aplikasi.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Jaga Kerahasiaan Kode</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Jangan bagikan kode ini kepada siapa pun, termasuk pihak yang mengaku sebagai tim TraKerja. Kami tidak pernah meminta kode verifikasi Anda.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Notice -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td style="background-color:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">
                                            Apabila Anda tidak merasa melakukan permintaan verifikasi ini, abaikan email ini. Akun Anda tetap aman dan tidak ada perubahan yang akan terjadi.
                                        </p>
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

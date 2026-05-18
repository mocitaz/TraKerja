<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Reset Kata Sandi — TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Permintaan Reset Kata Sandi',
                        'subtitle' => 'Ikuti instruksi berikut untuk memperbarui kata sandi akun Anda'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Kepada Pemilik Akun,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Kami menerima permintaan untuk mereset kata sandi akun TraKerja yang terkait dengan alamat email ini. Klik tombol di bawah untuk melanjutkan proses pembuatan kata sandi baru.
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Tautan reset kata sandi ini akan kedaluwarsa dalam <strong>{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} menit</strong> demi menjaga keamanan akun Anda.
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 24px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Informasi Keamanan
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Tautan Bersifat Rahasia</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Jangan bagikan tautan ini kepada siapa pun. Tautan hanya dapat digunakan satu kali dan akan kedaluwarsa secara otomatis.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Buat Kata Sandi yang Kuat</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Gunakan kombinasi huruf besar, huruf kecil, angka, dan karakter khusus dengan panjang minimal delapan karakter.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Setelah Reset Berhasil</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Semua sesi aktif pada perangkat lain akan diakhiri secara otomatis. Anda perlu login kembali menggunakan kata sandi baru.</p>
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
                                            Reset Kata Sandi
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Fallback Link -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Tombol Tidak Berfungsi?</p>
                                        <p style="margin:0 0 8px 0; font-size:13px; line-height:20px; color:#52525b;">Salin dan tempelkan tautan berikut ke peramban Anda:</p>
                                        <p style="margin:0; font-size:12px; line-height:18px; word-break:break-all;">
                                            <a href="{{ $actionUrl }}" style="color:#6d28d9; text-decoration:underline;">{{ $actionUrl }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Disclaimer -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">
                                            Apabila Anda tidak merasa mengajukan permintaan reset kata sandi, abaikan email ini. Kata sandi Anda tidak akan berubah dan akun Anda tetap aman.
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

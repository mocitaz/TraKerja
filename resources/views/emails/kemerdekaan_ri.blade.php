<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Hari Kemerdekaan Republik Indonesia</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Dirgahayu Republik Indonesia',
                        'subtitle' => 'Terus Melaju untuk Indonesia Maju'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Halo <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Segenap tim TraKerja mengucapkan Selamat Hari Kemerdekaan Republik Indonesia!
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Mari kita maknai kemerdekaan ini dengan terus berjuang menggapai cita-cita dan berkarya untuk negeri. Semangat pantang menyerah adalah kunci untuk terus maju menjadi profesional yang tangguh di dunia kerja.
                            </p>
                            
                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Merdeka! Merdeka! Merdeka!
                            </p>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">Salam hangat,</p>
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

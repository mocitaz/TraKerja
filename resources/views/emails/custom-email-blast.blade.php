<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#faf8fb; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#2d2636; -webkit-font-smoothing: antialiased;">

    <table role="presentation" style="width:100%; border-collapse:collapse; background-color:#faf8fb;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" style="width:100%; max-width:580px; border-collapse:collapse; background-color:#ffffff; border-radius:18px; border:1px solid #efeaf2; overflow:hidden; box-shadow:0 8px 30px rgba(53,46,64,0.04);">

                    @include('emails.partials.header', [
                        'title'    => $subject ?? config('app.name'),
                        'subtitle' => 'Pesan resmi dari Tim TraKerja'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:36px 32px 30px 32px;">

                            <p style="margin:0 0 18px 0; font-size:15px; line-height:24px; color:#211d27;">
                                Halo <strong>{{ $user->name }}</strong>,
                            </p>

                            <!-- Email Content -->
                            <div style="font-size:14px; line-height:24px; color:#4a4254; white-space:pre-wrap; margin-bottom:28px;">{{ $emailContent }}</div>

                            @if($buttonText && $buttonUrl)
                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:30px;">
                                <tr>
                                    <td>
                                        <a href="{{ $buttonUrl }}"
                                           style="display:inline-block; padding:12px 28px; background-color:#7b5ea7; color:#ffffff; text-decoration:none; border-radius:999px; font-size:13px; font-weight:600; letter-spacing:0.01em; box-shadow:0 3px 12px rgba(123,94,167,0.22);">
                                            {{ $buttonText }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #f3eff6;">
                                <tr>
                                    <td style="padding-top:18px;">
                                        <p style="margin:0 0 3px 0; font-size:12px; line-height:19px; color:#857c91;">Salam hangat,</p>
                                        <p style="margin:0 0 2px 0; font-size:13px; font-weight:700; color:#7b5ea7;">Tim TraKerja</p>
                                        <p style="margin:0; font-size:11px; color:#a49cb0;">PT Teknalogi Transformasi Digital</p>
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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TraKerja</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f4fb; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#111827;">
    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse; background:#ffffff; border-radius:10px; box-shadow:0 1px 2px rgba(0,0,0,0.04); overflow:hidden;">
                    <tr>
                        <td style="padding:28px 28px 0; text-align:center;">
                            <img src="{{ config('app.url') }}/images/icon.png" alt="TraKerja" width="56" height="56" style="display:block; margin:0 auto 12px;">
                            <h1 style="margin:0 0 4px; font-size:22px; line-height:28px; font-weight:800; color:#1f2937;">Welcome to TraKerja</h1>
                            <p style="margin:0 0 24px; font-size:14px; line-height:22px; color:#6b7280;">Smart tracking platform for job seekers</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 28px 24px;">
                            <p style="margin:0 0 12px; font-size:14px; line-height:22px;">Hi <strong>{{ $user->name }}</strong>,</p>
                            <p style="margin:0 0 16px; font-size:14px; line-height:22px;">Thank you for joining TraKerja. Your account is ready to use. Start organizing your job applications and stay on top of your progress.</p>
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:24px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('app.url') }}/dashboard" style="display:inline-block; padding:12px 22px; background-color:#6b46c1; color:#ffffff; text-decoration:none; border-radius:6px; font-weight:600; font-size:14px;">Go to Dashboard</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0; font-size:13px; line-height:20px; color:#6b7280;">If you have any questions, simply reply to this email and we will be happy to help.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px 24px; background:#fafafa; border-top:1px solid #f0f0f5; text-align:center;">
                            <p style="margin:6px 0 0; font-size:12px; line-height:18px; color:#9ca3af;">© {{ date('Y') }} TraKerja. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

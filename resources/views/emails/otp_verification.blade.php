<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TraKerja - OTP Verification</title>
</head>
<body style="margin:0; padding:0; background:#f6f2ff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#111827;">
    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" style="width:100%; max-width:520px; border-collapse:collapse; background:#ffffff; border-radius:14px; box-shadow:0 8px 24px rgba(107,70,193,0.08), 0 2px 8px rgba(0,0,0,0.03); overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="padding:24px; background:linear-gradient(180deg, #efe9ff 0%, #ffffff 70%); text-align:center; border-bottom:1px solid #efeaff;">
                            <div style="display:inline-block; padding:10px; border-radius:14px; background:#ffffff; box-shadow:0 4px 10px rgba(107,70,193,0.10); border:1px solid #ece7ff;">
                                <img src="{{ config('app.url') }}/images/icon.png" alt="TraKerja" width="44" height="44" style="display:block;">
                            </div>
                            <h1 style="margin:12px 0 2px; font-size:20px; line-height:26px; font-weight:800; color:#1f2937;">Verify your email</h1>
                            <p style="margin:0; font-size:13px; line-height:20px; color:#6b7280;">Enter the code below in TraKerja to continue</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:22px 24px 8px;">
                            <p style="margin:0 0 10px; font-size:14px; line-height:22px;">Hi <strong>{{ $user->name }}</strong>,</p>
                            <p style="margin:0 0 16px; font-size:14px; line-height:22px; color:#374151;">Use this One‑Time Password (OTP) to verify your email address. For security reasons, please do not share this code with anyone.</p>

                            <!-- OTP code -->
                            <div style="margin:16px 0 18px; text-align:center;">
                                <div style="display:inline-block; letter-spacing:10px; font-weight:800; font-size:26px; color:#6b46c1; background:#f5f1ff; border:1px solid #ece7ff; border-radius:10px; padding:14px 18px; box-shadow:inset 0 1px 0 #faf7ff;">
                                    {{ $otp }}
                                </div>
                                <div style="margin-top:10px; font-size:12px; color:#6b7280;">This code expires in 10 minutes</div>
                            </div>

                            <!-- Security note -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:#fbfaff; border:1px solid #f0eaff; border-radius:10px;">
                                <tr>
                                    <td style="padding:12px 14px; font-size:12px; line-height:18px; color:#6b7280;">If you didn't request this, you can safely ignore this email.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:14px 24px 20px; background:#fbfaff; border-top:1px solid #f0eaff; text-align:center;">
                            <p style="margin:6px 0 0; font-size:12px; line-height:18px; color:#9ca3af;">© {{ date('Y') }} TraKerja. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

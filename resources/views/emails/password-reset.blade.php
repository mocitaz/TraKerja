<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - TraKerja</title>
</head>
<body style="margin:0; padding:0; background:#f6f2ff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#111827;">
    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <!-- Card -->
                <table role="presentation" style="width:100%; max-width:640px; border-collapse:collapse; background:#ffffff; border-radius:14px; box-shadow:0 8px 24px rgba(107,70,193,0.08), 0 2px 8px rgba(0,0,0,0.03); overflow:hidden;">
                    <!-- Header with soft gradient -->
                    <tr>
                        <td style="padding:28px; background:linear-gradient(180deg, #efe9ff 0%, #ffffff 70%); text-align:center; border-bottom:1px solid #efeaff;">
                            <table role="presentation" style="width:100%; border-collapse:collapse;">
                                <tr>
                                    <td align="center">
                                        <div style="display:inline-block; padding:10px; border-radius:14px; background:#ffffff; box-shadow:0 4px 10px rgba(107,70,193,0.10); border:1px solid #ece7ff;">
                                            <img src="{{ asset('images/icon.png') }}" alt="TraKerja" width="48" height="48" style="display:block;">
                                        </div>
                                        <h1 style="margin:14px 0 4px; font-size:22px; line-height:28px; font-weight:800; color:#1f2937;">Password Reset Request</h1>
                                        <p style="margin:0; font-size:13px; line-height:20px; color:#6b7280;">Reset your password securely</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 28px 8px;">
                            <p style="margin:0 0 10px; font-size:14px; line-height:22px; color:#111827;">Hello!</p>
                            <p style="margin:0 0 16px; font-size:14px; line-height:22px; color:#374151;">You are receiving this email because we received a password reset request for your account. Click the button below to reset your password.</p>

                            <!-- Security Notice -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:#fef3c7; border:1px solid #fbbf24; border-radius:10px; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <p style="margin:0 0 8px; font-size:13px; line-height:20px; color:#92400e; font-weight:700;">Security Information</p>
                                        <p style="margin:0; font-size:12px; line-height:18px; color:#92400e;">This password reset link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes for your security.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:18px 0 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $actionUrl }}" style="display:inline-block; padding:12px 22px; background:linear-gradient(135deg, #7c5ce0 0%, #6b46c1 100%); color:#ffffff; text-decoration:none; border-radius:8px; font-weight:700; font-size:14px; letter-spacing:0.2px; box-shadow:0 6px 14px rgba(107,70,193,0.20);">Reset Password</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 16px; font-size:14px; line-height:22px; color:#374151;">If you did not request a password reset, no further action is required. Your account remains secure.</p>
                            
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:8px;">
                                <tr>
                                    <td style="font-size:12px; line-height:18px; color:#6b7280;">If the button doesn't work, copy and paste this link into your browser: <a href="{{ $actionUrl }}" style="color:#6b46c1; text-decoration:none; word-break:break-all;">{{ $actionUrl }}</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Subtle footer -->
                    <tr>
                        <td style="padding:14px 28px 20px; background:#fbfaff; border-top:1px solid #f0eaff; text-align:center;">
                            <p style="margin:6px 0 0; font-size:12px; line-height:18px; color:#9ca3af;">© {{ date('Y') }} TraKerja. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - TraKerja</title>
</head>
<body style="margin:0; padding:0; background:#f6f2ff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#111827;">
    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <!-- Card -->
                <table role="presentation" style="width:100%; max-width:640px; border-collapse:collapse; background:#ffffff; border-radius:14px; box-shadow:0 8px 24px rgba(107,70,193,0.08), 0 2px 8px rgba(0,0,0,0.03); overflow:hidden;">
                    <!-- Compact Professional Header -->
                    @include('emails.partials.header', [
                        'title' => 'Verify Your Email',
                        'subtitle' => 'Activate your TraKerja account'
                    ])

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 28px 8px;">
                            <p style="margin:0 0 10px; font-size:14px; line-height:22px; color:#111827;">Welcome to TraKerja!</p>
                            <p style="margin:0 0 16px; font-size:14px; line-height:22px; color:#374151;">Thanks for registering. Please verify your email to activate your account and start using all the features.</p>

                            <!-- Verification Benefits -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:#fbfaff; border:1px solid #f0eaff; border-radius:10px; overflow:hidden; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="margin:0 0 12px; font-size:13px; line-height:20px; color:#4b5563; font-weight:700;">Why verify your email?</p>
                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <div style="display:flex; align-items:center;">
                                                        <span style="display:inline-block; width:6px; height:6px; background:#6b7280; border-radius:50%; margin-right:10px;"></span>
                                                        <span style="font-size:13px; line-height:20px; color:#4b5563;">Access to all job tracking features</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <div style="display:flex; align-items:center;">
                                                        <span style="display:inline-block; width:6px; height:6px; background:#6b7280; border-radius:50%; margin-right:10px;"></span>
                                                        <span style="font-size:13px; line-height:20px; color:#4b5563;">Receive important notifications</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <div style="display:flex; align-items:center;">
                                                        <span style="display:inline-block; width:6px; height:6px; background:#6b7280; border-radius:50%; margin-right:10px;"></span>
                                                        <span style="font-size:13px; line-height:20px; color:#4b5563;">Secure account recovery options</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:18px 0 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $actionUrl }}" style="display:inline-block; padding:12px 22px; background:linear-gradient(135deg, #7c5ce0 0%, #6b46c1 100%); color:#ffffff; text-decoration:none; border-radius:8px; font-weight:700; font-size:14px; letter-spacing:0.2px; box-shadow:0 6px 14px rgba(107,70,193,0.20);">Verify Email Address</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 16px; font-size:14px; line-height:22px; color:#374151;">If you did not create an account, no further action is required.</p>
                            
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:8px;">
                                <tr>
                                    <td style="font-size:12px; line-height:18px; color:#6b7280;">If the button doesn't work, copy and paste this link into your browser: <a href="{{ $actionUrl }}" style="color:#6b46c1; text-decoration:none; word-break:break-all;">{{ $actionUrl }}</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Compact Professional Footer -->
                    @include('emails.partials.footer')
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

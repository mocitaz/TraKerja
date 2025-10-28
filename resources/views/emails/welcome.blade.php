<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TraKerja</title>
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
                                        <h1 style="margin:14px 0 4px; font-size:22px; line-height:28px; font-weight:800; color:#1f2937;">Welcome to TraKerja</h1>
                                        <p style="margin:0; font-size:13px; line-height:20px; color:#6b7280;">Your modern job application tracker</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 28px 8px;">
                            <p style="margin:0 0 10px; font-size:14px; line-height:22px; color:#111827;">Hi <strong>{{ $user->name }}</strong>,</p>
                            <p style="margin:0 0 14px; font-size:14px; line-height:22px; color:#374151;">Welcome to TraKerja — the modern way to manage your job search. Keep every application, follow‑up, and interview organized in one place so you can focus on what matters: getting hired.</p>

                            <!-- Quick start -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:#fbfaff; border:1px solid #f0eaff; border-radius:10px; overflow:hidden; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding:14px 16px;">
                                        <p style="margin:0 0 8px; font-size:13px; line-height:20px; color:#4b5563; font-weight:700;">Get started in 3 simple steps</p>
                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td width="20" style="vertical-align:top; padding-top:2px;"><span style="display:inline-block; width:16px; height:16px; border-radius:4px; background:#6b46c1;
                                                box-shadow:0 2px 6px rgba(107,70,193,0.35);
                                                text-align:center; color:#fff; font-size:12px; line-height:16px; font-weight:700;">1</span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Complete your <a href="{{ config('app.url') }}/profile" style="color:#6b46c1; text-decoration:none; font-weight:600;">profile</a> and create your CV.</td>
                                            </tr>
                                            <tr>
                                                <td width="20" style="vertical-align:top; padding-top:6px;"><span style="display:inline-block; width:16px; height:16px; border-radius:4px; background:#6b46c1; text-align:center; color:#fff; font-size:12px; line-height:16px; font-weight:700;">2</span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Add your first application on the <a href="{{ config('app.url') }}/tracker" style="color:#6b46c1; text-decoration:none; font-weight:600;">Job Tracker</a>.</td>
                                            </tr>
                                            <tr>
                                                <td width="20" style="vertical-align:top; padding-top:6px;"><span style="display:inline-block; width:16px; height:16px; border-radius:4px; background:#6b46c1; text-align:center; color:#fff; font-size:12px; line-height:16px; font-weight:700;">3</span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Set reminders for follow‑ups and interview dates in your <a href="{{ config('app.url') }}/dashboard" style="color:#6b46c1; text-decoration:none; font-weight:600;">dashboard</a>.</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Feature bullets (no emojis) -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:12px 0 6px;">
                                <tr>
                                    <td style="padding:8px 0;">
                                        <table role="presentation" style="border-collapse:collapse; width:100%;">
                                            <tr>
                                                <td width="12" style="vertical-align:top; padding-top:4px;"><span style="display:inline-block; width:6px; height:6px; background:#6b46c1; border-radius:50%;"></span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Track every application in a single, elegant board</td>
                                            </tr>
                                            <tr>
                                                <td width="12" style="vertical-align:top; padding-top:4px;"><span style="display:inline-block; width:6px; height:6px; background:#6b46c1; border-radius:50%;"></span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Build polished CVs with professional templates</td>
                                            </tr>
                                            <tr>
                                                <td width="12" style="vertical-align:top; padding-top:4px;"><span style="display:inline-block; width:6px; height:6px; background:#6b46c1; border-radius:50%;"></span></td>
                                                <td style="padding-left:10px; font-size:13px; line-height:20px; color:#4b5563;">Stay on schedule with interview reminders</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:18px 0 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('app.url') }}/dashboard" style="display:inline-block; padding:12px 22px; background:linear-gradient(135deg, #7c5ce0 0%, #6b46c1 100%); color:#ffffff; text-decoration:none; border-radius:8px; font-weight:700; font-size:14px; letter-spacing:0.2px; box-shadow:0 6px 14px rgba(107,70,193,0.20);">Go to Dashboard</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:8px;">
                                <tr>
                                    <td style="font-size:12px; line-height:18px; color:#6b7280;">Need help? Reply to this email or visit <a href="{{ config('app.url') }}" style="color:#6b46c1; text-decoration:none; font-weight:600;">TraKerja</a>. We’re here to help.</td>
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

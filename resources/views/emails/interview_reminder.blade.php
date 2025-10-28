<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Interview Reminder - TraKerja</title>
</head>
<body style="margin:0; padding:0; background:#f6f2ff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#111827;">
    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" style="width:100%; max-width:640px; border-collapse:collapse; background:#ffffff; border-radius:14px; box-shadow:0 8px 24px rgba(107,70,193,0.08), 0 2px 8px rgba(0,0,0,0.03); overflow:hidden;">
                    <!-- Compact Professional Header -->
                    @include('emails.partials.header', [
                        'title' => 'Interview Reminder',
                        'subtitle' => 'Your upcoming interview details'
                    ])

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 28px 8px;">
                            <p style="margin:0 0 10px; font-size:14px; line-height:22px; color:#111827;">Hi <strong>{{ $jobApplication->user->name }}</strong>,</p>
                            <p style="margin:0 0 16px; font-size:14px; line-height:22px; color:#374151;">This is a friendly reminder about your upcoming interview. Make sure you're well-prepared and ready to make a great impression.</p>

                            <!-- Interview Details Card -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:#fbfaff; border:1px solid #f0eaff; border-radius:10px; overflow:hidden; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <div style="display:flex; align-items:center; margin-bottom:12px;">
                                            <div style="width:8px; height:8px; background:#f59e0b; border-radius:50%; margin-right:8px;"></div>
                                            <span style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Interview Details</span>
                                        </div>
                                        
                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:8px 0; border-bottom:1px solid #f0eaff;">
                                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                                        <span style="font-size:13px; color:#6b7280; font-weight:600;">Position</span>
                                                        <span style="font-size:14px; color:#1f2937; font-weight:600;">{{ $jobApplication->position }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0; border-bottom:1px solid #f0eaff;">
                                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                                        <span style="font-size:13px; color:#6b7280; font-weight:600;">Company</span>
                                                        <span style="font-size:14px; color:#1f2937; font-weight:600;">{{ $jobApplication->company_name }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0; border-bottom:1px solid #f0eaff;">
                                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                                        <span style="font-size:13px; color:#6b7280; font-weight:600;">Date & Time</span>
                                                        <span style="font-size:14px; color:#1f2937; font-weight:600;">{{ $jobApplication->getFormattedInterviewDateAttribute() }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @if($jobApplication->interview_location)
                                            <tr>
                                                <td style="padding:8px 0; border-bottom:1px solid #f0eaff;">
                                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                                        <span style="font-size:13px; color:#6b7280; font-weight:600;">Location</span>
                                                        <span style="font-size:14px; color:#1f2937; font-weight:600;">{{ $jobApplication->interview_location }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($jobApplication->interview_notes)
                                            <tr>
                                                <td style="padding:8px 0;">
                                                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                                                        <span style="font-size:13px; color:#6b7280; font-weight:600;">Notes</span>
                                                        <span style="font-size:14px; color:#1f2937; font-weight:600; text-align:right; max-width:200px;">{{ $jobApplication->interview_notes }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Preparation Tips -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:#fef3c7; border:1px solid #fbbf24; border-radius:10px; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <p style="margin:0 0 8px; font-size:13px; line-height:20px; color:#92400e; font-weight:700;">Quick Preparation Tips</p>
                                        <ul style="margin:0; padding-left:16px; font-size:12px; line-height:18px; color:#92400e;">
                                            <li>Research the company and role thoroughly</li>
                                            <li>Prepare examples of your relevant experience</li>
                                            <li>Test your technology setup (for video interviews)</li>
                                            <li>Arrive 10-15 minutes early</li>
                                            <li>Prepare thoughtful questions to ask</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:18px 0 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('app.url') }}/tracker" style="display:inline-block; padding:12px 22px; background:linear-gradient(135deg, #7c5ce0 0%, #6b46c1 100%); color:#ffffff; text-decoration:none; border-radius:8px; font-weight:700; font-size:14px; letter-spacing:0.2px; box-shadow:0 6px 14px rgba(107,70,193,0.20);">View Application</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:8px;">
                                <tr>
                                    <td style="font-size:12px; line-height:18px; color:#6b7280;">Good luck with your interview! You've got this.</td>
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

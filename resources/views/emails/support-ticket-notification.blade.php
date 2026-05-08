<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Support Ticket - TraKerja</title>
</head>
<body style="margin:0; padding:0; background:#f6f2ff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#111827;">
    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" style="width:100%; max-width:640px; border-collapse:collapse; background:#ffffff; border-radius:14px; box-shadow:0 8px 24px rgba(107,70,193,0.08), 0 2px 8px rgba(0,0,0,0.03); overflow:hidden;">
                    
                    <!-- Compact Professional Header -->
                    @include('emails.partials.header', [
                        'title' => 'New Support Ticket',
                        'subtitle' => 'Tiket bantuan baru telah diajukan oleh pengguna'
                    ])

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px 28px 8px;">
                            <p style="margin:0 0 10px; font-size:14px; line-height:22px; color:#111827;">Halo Tim Admin,</p>
                            <p style="margin:0 0 16px; font-size:14px; line-height:22px; color:#374151;">Seorang pengguna baru saja mengirimkan tiket bantuan baru melalui Customer Support Desk. Berikut rinciannya:</p>

                            <!-- Ticket Details Card -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; background:#fbfaff; border:1px solid #f0eaff; border-radius:10px; overflow:hidden; margin:12px 0 18px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <div style="display:flex; align-items:center; margin-bottom:12px;">
                                            <div style="width:8px; height:8px; background:#f59e0b; border-radius:50%; margin-right:8px;"></div>
                                            <span style="font-size:11px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">{{ $ticket->category_label }}</span>
                                        </div>
                                        
                                        <h2 style="margin:0 0 12px; font-size:16px; line-height:22px; font-weight:700; color:#1f2937;">{{ $ticket->subject }}</h2>
                                        
                                        <div style="margin-bottom:16px; padding:12px; background:#ffffff; border:1px solid #f3f4f6; border-radius:8px; font-size:13px; line-height:20px; color:#4b5563; white-space:pre-line;">
                                            {{ $ticket->message }}
                                        </div>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; font-size:12px; color:#6b7280;">
                                            <tr>
                                                <td style="padding:4px 0; width:120px; font-weight:600; color:#4b5563;">Pengirim:</td>
                                                <td style="padding:4px 0; font-weight:700; color:#1f2937;">{{ $ticket->user->name ?? 'Guest' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0; font-weight:600; color:#4b5563;">Email:</td>
                                                <td style="padding:4px 0; font-weight:700; color:#1f2937;">{{ $ticket->user->email ?? 'no-email' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0; font-weight:600; color:#4b5563;">Tanggal Kirim:</td>
                                                <td style="padding:4px 0;">{{ $ticket->created_at->format('d M Y, H:i') }} WIB</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin:24px 0 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ config('app.url') }}/admin/feedbacks" style="display:inline-block; padding:12px 24px; background:linear-gradient(135deg, #7c5ce0 0%, #6b46c1 100%); color:#ffffff; text-decoration:none; border-radius:8px; font-weight:700; font-size:13px; letter-spacing:0.2px; box-shadow:0 6px 14px rgba(107,70,193,0.20);">Balas Tiket di Admin Panel</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-top:16px;">
                                <tr>
                                    <td style="font-size:11px; line-height:16px; color:#9ca3af; text-align:center;">This is an automated system notification from TraKerja support gateway.</td>
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

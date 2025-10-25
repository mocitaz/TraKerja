<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goal Tercapai!</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 20px; text-align: center; background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); border-radius: 8px 8px 0 0;">
                            <div style="font-size: 48px; margin-bottom: 12px;">🎯</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">
                                Selamat! Goal Tercapai!
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 1.6;">
                                Halo <strong>{{ $goal->user->name }}</strong>,
                            </p>
                            
                            <p style="margin: 0 0 24px; color: #374151; font-size: 16px; line-height: 1.6;">
                                Kami sangat senang memberitahukan bahwa Anda telah mencapai goal Anda! 🎉
                            </p>
                            
                            <!-- Goal Details -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); border-radius: 8px; padding: 24px; margin-bottom: 24px; border: 2px solid #9333ea;">
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 8px; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                            GOAL YANG DICAPAI
                                        </p>
                                        <h2 style="margin: 0 0 16px; color: #7e22ce; font-size: 24px; font-weight: 700;">
                                            {{ $goal->title }}
                                        </h2>
                                        @if($goal->description)
                                        <p style="margin: 0 0 16px; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                            {{ $goal->description }}
                                        </p>
                                        @endif
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span style="display: inline-block; padding: 6px 12px; background-color: #dcfce7; color: #15803d; border-radius: 4px; font-size: 13px; font-weight: 600;">
                                                ✓ Completed
                                            </span>
                                            @if($goal->target_date)
                                            <span style="color: #6b7280; font-size: 14px;">
                                                Target: {{ \Carbon\Carbon::parse($goal->target_date)->format('d M Y') }}
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Celebration Message -->
                            <div style="background-color: #fef3c7; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 24px;">
                                <p style="margin: 0 0 8px; font-size: 36px;">🏆</p>
                                <p style="margin: 0; color: #92400e; font-size: 16px; font-weight: 600; line-height: 1.6;">
                                    Kerja keras Anda membuahkan hasil!<br>
                                    Terus semangat meraih goal-goal berikutnya! 💪
                                </p>
                            </div>
                            
                            <!-- Stats or Next Steps -->
                            <p style="margin: 0 0 16px; color: #374151; font-size: 15px; line-height: 1.6;">
                                Pencapaian ini adalah bukti dedikasi dan kerja keras Anda. Jangan berhenti di sini - tetapkan goal baru dan raih kesuksesan lebih besar lagi!
                            </p>
                            
                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/dashboard') }}" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">
                                            Lihat Dashboard →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 24px 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Tetap semangat dan terus berprestasi!
                            </p>
                            
                            <p style="margin: 8px 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Salam hangat,<br>
                                <strong>Tim TraKerja</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f9fafb; border-radius: 0 0 8px 8px; text-align: center;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                © {{ date('Y') }} TraKerja. All rights reserved.
                            </p>
                            <p style="margin: 8px 0 0; color: #9ca3af; font-size: 12px;">
                                Terus raih impian Anda bersama TraKerja!
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

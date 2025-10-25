<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Interview</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 20px; text-align: center; background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">
                                📅 Pengingat Interview
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 1.6;">
                                Halo <strong>{{ $application->user->name }}</strong>,
                            </p>
                            
                            <p style="margin: 0 0 24px; color: #374151; font-size: 16px; line-height: 1.6;">
                                Ini adalah pengingat untuk interview Anda yang akan datang:
                            </p>
                            
                            <!-- Interview Details -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f9fafb; border-radius: 8px; padding: 24px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table role="presentation" style="width: 100%;">
                                            <tr>
                                                <td style="width: 100px; color: #6b7280; font-size: 14px; vertical-align: top;">
                                                    <strong>Perusahaan:</strong>
                                                </td>
                                                <td style="color: #111827; font-size: 16px; font-weight: 600;">
                                                    {{ $application->company }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table role="presentation" style="width: 100%;">
                                            <tr>
                                                <td style="width: 100px; color: #6b7280; font-size: 14px; vertical-align: top;">
                                                    <strong>Posisi:</strong>
                                                </td>
                                                <td style="color: #374151; font-size: 15px;">
                                                    {{ $application->position }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @if($application->interview_date)
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table role="presentation" style="width: 100%;">
                                            <tr>
                                                <td style="width: 100px; color: #6b7280; font-size: 14px; vertical-align: top;">
                                                    <strong>Tanggal:</strong>
                                                </td>
                                                <td style="color: #374151; font-size: 15px;">
                                                    {{ \Carbon\Carbon::parse($application->interview_date)->format('l, d F Y') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table role="presentation" style="width: 100%;">
                                            <tr>
                                                <td style="width: 100px; color: #6b7280; font-size: 14px; vertical-align: top;">
                                                    <strong>Waktu:</strong>
                                                </td>
                                                <td style="color: #374151; font-size: 15px;">
                                                    {{ \Carbon\Carbon::parse($application->interview_date)->format('H:i') }} WIB
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                                @if($application->interview_type)
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table role="presentation" style="width: 100%;">
                                            <tr>
                                                <td style="width: 100px; color: #6b7280; font-size: 14px; vertical-align: top;">
                                                    <strong>Tipe:</strong>
                                                </td>
                                                <td style="color: #374151; font-size: 15px;">
                                                    @php
                                                        $types = [
                                                            'phone' => 'Phone Interview',
                                                            'video' => 'Video Call',
                                                            'in-person' => 'In-Person',
                                                            'panel' => 'Panel Interview'
                                                        ];
                                                    @endphp
                                                    {{ $types[$application->interview_type] ?? $application->interview_type }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                                @if($application->interview_location)
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table role="presentation" style="width: 100%;">
                                            <tr>
                                                <td style="width: 100px; color: #6b7280; font-size: 14px; vertical-align: top;">
                                                    <strong>Lokasi:</strong>
                                                </td>
                                                <td style="color: #374151; font-size: 15px;">
                                                    {{ $application->interview_location }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                            </table>
                            
                            <!-- Tips -->
                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 4px; margin-bottom: 24px;">
                                <p style="margin: 0 0 8px; color: #92400e; font-weight: 600; font-size: 14px;">
                                    💡 Tips Persiapan Interview:
                                </p>
                                <ul style="margin: 0; padding-left: 20px; color: #92400e; font-size: 14px; line-height: 1.6;">
                                    <li>Riset perusahaan dan posisi yang dilamar</li>
                                    <li>Siapkan contoh pengalaman kerja yang relevan</li>
                                    <li>Pastikan koneksi internet stabil (untuk video call)</li>
                                    <li>Datang 15 menit lebih awal</li>
                                    <li>Siapkan pertanyaan untuk interviewer</li>
                                </ul>
                            </div>
                            
                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/tracker') }}" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">
                                            Lihat Detail →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 24px 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Semoga sukses untuk interview Anda! 🍀
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
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di TraKerja</title>
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
                                🎉 Selamat Datang di TraKerja!
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 1.6;">
                                Halo <strong>{{ $user->name }}</strong>,
                            </p>
                            
                            <p style="margin: 0 0 16px; color: #374151; font-size: 16px; line-height: 1.6;">
                                Terima kasih telah bergabung dengan <strong>TraKerja</strong>! Kami sangat senang Anda menjadi bagian dari komunitas kami.
                            </p>
                            
                            <p style="margin: 0 0 24px; color: #374151; font-size: 16px; line-height: 1.6;">
                                Dengan TraKerja, Anda dapat:
                            </p>
                            
                            <!-- Features -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 12px; background-color: #f9fafb; border-radius: 6px; margin-bottom: 8px;">
                                        <span style="color: #9333ea; font-size: 20px; margin-right: 8px;">📊</span>
                                        <span style="color: #374151; font-size: 15px;">Track semua lamaran kerja Anda dalam satu dashboard</span>
                                    </td>
                                </tr>
                                <tr><td style="height: 8px;"></td></tr>
                                <tr>
                                    <td style="padding: 12px; background-color: #f9fafb; border-radius: 6px;">
                                        <span style="color: #9333ea; font-size: 20px; margin-right: 8px;">📄</span>
                                        <span style="color: #374151; font-size: 15px;">Buat CV profesional dengan berbagai template</span>
                                    </td>
                                </tr>
                                <tr><td style="height: 8px;"></td></tr>
                                <tr>
                                    <td style="padding: 12px; background-color: #f9fafb; border-radius: 6px;">
                                        <span style="color: #9333ea; font-size: 20px; margin-right: 8px;">🎯</span>
                                        <span style="color: #374151; font-size: 15px;">Tetapkan dan capai career goals Anda</span>
                                    </td>
                                </tr>
                                <tr><td style="height: 8px;"></td></tr>
                                <tr>
                                    <td style="padding: 12px; background-color: #f9fafb; border-radius: 6px;">
                                        <span style="color: #9333ea; font-size: 20px; margin-right: 8px;">📅</span>
                                        <span style="color: #374151; font-size: 15px;">Kelola jadwal interview dengan mudah</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/dashboard') }}" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">
                                            Mulai Sekarang →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 24px 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.
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
                                Email ini dikirim ke {{ $user->email }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

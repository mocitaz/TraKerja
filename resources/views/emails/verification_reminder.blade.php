<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Anda</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .icon-container {
            background: rgba(255, 255, 255, 0.2);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #333;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .content p {
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.8;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
        .warning-box strong {
            color: #856404;
            display: block;
            margin-bottom: 5px;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            color: #014361;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 25px 0;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
        }
        .features {
            margin: 30px 0;
        }
        .feature-item {
            display: flex;
            align-items: start;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 15px;
            min-width: 30px;
        }
        .feature-text h3 {
            margin: 0 0 5px;
            font-size: 16px;
            color: #333;
        }
        .feature-text p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .countdown {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
        }
        .countdown h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }
        .countdown .days {
            font-size: 36px;
            font-weight: 700;
            margin: 10px 0;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon-container">
                ⚠️
            </div>
            <h1>TraKerja</h1>
            <p>Smart Tracking for Job Seekers</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Halo {{ $user->name }},</h2>
            
            <p>
                Kami perhatikan bahwa Anda telah mendaftar di TraKerja <strong>3 hari yang lalu</strong>, 
                tetapi belum melakukan verifikasi email.
            </p>

            <div class="countdown">
                <h3>⏰ Email Verifikasi Anda Akan Segera Kedaluwarsa!</h3>
                <p style="margin: 0; color: white;">Verifikasi sekarang sebelum terlambat</p>
            </div>

            <p>
                Tanpa verifikasi email, Anda tidak dapat mengakses fitur-fitur TraKerja yang akan 
                membantu Anda dalam perjalanan mencari kerja.
            </p>

            <div class="warning-box">
                <strong>🔒 Akun Anda Belum Aktif</strong>
                <p>Untuk keamanan dan akses penuh, mohon verifikasi email Anda segera.</p>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ url('/verify-email') }}" class="cta-button">
                    ✉️ Verifikasi Email Sekarang
                </a>
            </div>

            <div class="info-box">
                <p>
                    <strong>💡 Tip:</strong> Jika Anda tidak menemukan email verifikasi di inbox, 
                    coba cek folder spam atau junk mail Anda.
                </p>
            </div>

            <!-- Features Section -->
            <div class="features">
                <h3 style="margin-bottom: 20px; color: #333;">Yang Bisa Anda Lakukan Setelah Verifikasi:</h3>
                
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div class="feature-text">
                        <h3>Track Aplikasi Pekerjaan</h3>
                        <p>Kelola semua lamaran kerja Anda dalam satu dashboard yang rapi dan terorganisir</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">🎯</div>
                    <div class="feature-text">
                        <h3>Set Goals & Milestones</h3>
                        <p>Buat target pencarian kerja dan pantau progress Anda secara real-time</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">📅</div>
                    <div class="feature-text">
                        <h3>Jadwal Interview</h3>
                        <p>Tidak akan terlewat interview dengan sistem reminder otomatis</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">📄</div>
                    <div class="feature-text">
                        <h3>CV Builder</h3>
                        <p>Buat CV profesional dengan template yang menarik dan ATS-friendly</p>
                    </div>
                </div>
            </div>

            <p style="margin-top: 30px;">
                <strong>Butuh bantuan?</strong><br>
                Jika Anda mengalami kesulitan dalam verifikasi email, silakan balas email ini 
                atau hubungi tim support kami.
            </p>

            <p style="margin-top: 30px; color: #888; font-size: 14px;">
                Salam hangat,<br>
                <strong style="color: #667eea;">Tim TraKerja</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>TraKerja</strong> - Smart Tracking for Job Seekers</p>
            <p>
                Email ini dikirim karena Anda mendaftar di TraKerja.<br>
                Jika Anda tidak merasa mendaftar, abaikan email ini.
            </p>
            <p style="margin-top: 15px;">
                <a href="{{ url('/') }}">Kunjungi Website</a> • 
                <a href="mailto:support@trakerja.com">Hubungi Support</a>
            </p>
        </div>
    </div>
</body>
</html>

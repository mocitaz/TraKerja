<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Resume Analyzer - Gratis!</title>
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
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 18px;
            opacity: 0.95;
            position: relative;
            z-index: 1;
        }
        .badge-container {
            background: rgba(255, 255, 255, 0.2);
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            margin: 15px 0 0;
            position: relative;
            z-index: 1;
        }
        .badge {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .icon-container {
            background: rgba(255, 255, 255, 0.2);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            position: relative;
            z-index: 1;
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
        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: center;
        }
        .highlight-box h3 {
            margin: 0 0 10px;
            font-size: 28px;
            font-weight: 700;
        }
        .highlight-box p {
            margin: 0;
            font-size: 16px;
            color: white;
            opacity: 0.95;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            padding: 18px 45px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 18px;
            text-align: center;
            margin: 25px 0;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.5);
        }
        .features {
            margin: 30px 0;
        }
        .feature-item {
            display: flex;
            align-items: start;
            margin-bottom: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        .feature-icon {
            font-size: 28px;
            margin-right: 15px;
            min-width: 35px;
        }
        .feature-text h3 {
            margin: 0 0 8px;
            font-size: 18px;
            color: #333;
        }
        .feature-text p {
            margin: 0;
            font-size: 15px;
            color: #666;
        }
        .steps {
            background: #e7f3ff;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }
        .steps h3 {
            margin: 0 0 20px;
            color: #2196F3;
            font-size: 20px;
        }
        .step-item {
            display: flex;
            align-items: start;
            margin-bottom: 15px;
        }
        .step-number {
            background: #2196F3;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .step-text {
            color: #014361;
            font-size: 15px;
            padding-top: 4px;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 15px;
        }
        .warning-box strong {
            color: #856404;
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
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
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 26px;
            }
            .highlight-box h3 {
                font-size: 24px;
            }
            .cta-button {
                padding: 16px 35px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon-container">
                🤖
            </div>
            <h1>TraKerja</h1>
            <p>Smart Tracking for Job Seekers</p>
            <div class="badge-container">
                <span class="badge">✨ SPECIAL ANNOUNCEMENT ✨</span>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Halo {{ $user->name }}! 👋</h2>
            
            <p>
                Kami punya <strong>kabar gembira</strong> untuk Anda! 🎉
            </p>

            <!-- Highlight Box -->
            <div class="highlight-box">
                <h3>🎁 GRATIS 1x AI Resume Analyzer!</h3>
                <p>Tingkatkan CV Anda dengan teknologi AI canggih - Tanpa biaya!</p>
            </div>

            <p>
                <strong>AI Resume Analyzer</strong> adalah fitur premium kami yang menggunakan kecerdasan buatan 
                untuk menganalisis CV Anda secara mendalam dan memberikan rekomendasi profesional.
            </p>

            <!-- Features Section -->
            <div class="features">
                <h3 style="margin-bottom: 20px; color: #333; font-size: 20px;">✨ Apa yang Akan Anda Dapatkan:</h3>
                
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div class="feature-text">
                        <h3>Analisis Komprehensif</h3>
                        <p>AI akan menganalisis setiap bagian CV Anda: pengalaman, pendidikan, skills, dan struktur</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">💡</div>
                    <div class="feature-text">
                        <h3>Rekomendasi Perbaikan</h3>
                        <p>Dapatkan saran konkret untuk memperbaiki CV agar lebih menarik bagi recruiter</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">🎯</div>
                    <div class="feature-text">
                        <h3>ATS Optimization</h3>
                        <p>Pastikan CV Anda lolos sistem Applicant Tracking System (ATS) yang digunakan perusahaan</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">⭐</div>
                    <div class="feature-text">
                        <h3>Scoring & Rating</h3>
                        <p>Lihat score CV Anda dan area mana yang perlu ditingkatkan</p>
                    </div>
                </div>
            </div>

            <!-- Steps -->
            <div class="steps">
                <h3>📝 Cara Menggunakan (Sangat Mudah!):</h3>
                
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-text">Login ke akun TraKerja Anda</div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-text">Klik menu "AI Analyzer" di navigasi atas</div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-text">Upload CV Anda (PDF atau DOCX)</div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-text">Klik "Analyze" dan tunggu AI bekerja</div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">5</div>
                    <div class="step-text">Dapatkan hasil analisis lengkap + rekomendasi!</div>
                </div>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <strong>⚠️ Penawaran Terbatas!</strong>
                <p>
                    Ini adalah kesempatan <strong>GRATIS 1x</strong> untuk user free tier. 
                    Jangan sia-siakan kesempatan ini untuk meningkatkan kualitas CV Anda!
                </p>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center; margin: 35px 0;">
                <a href="{{ url('/ai-analyzer') }}" class="cta-button">
                    🚀 Coba AI Analyzer Sekarang!
                </a>
            </div>

            <p style="text-align: center; color: #888; font-size: 14px; margin-top: 30px;">
                <strong>Kenapa gratis?</strong><br>
                Kami ingin membantu Anda mendapatkan pekerjaan impian! Ini adalah cara kami mendukung 
                pencarian kerja Anda dengan teknologi terbaik.
            </p>

            <hr style="border: none; border-top: 1px solid #e9ecef; margin: 40px 0;">

            <p style="font-size: 15px; color: #666;">
                Punya pertanyaan atau butuh bantuan? Balas email ini atau hubungi tim support kami.
            </p>

            <p style="margin-top: 30px; color: #888; font-size: 14px;">
                Salam sukses,<br>
                <strong style="color: #667eea;">Tim TraKerja</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>TraKerja</strong> - Smart Tracking for Job Seekers</p>
            <p>
                <a href="{{ url('/') }}">Website</a> • 
                <a href="{{ url('/dashboard') }}">Dashboard</a> • 
                <a href="mailto:support@trakerja.com">Support</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                Email ini dikirim ke {{ $user->email }}<br>
                Anda menerima email ini karena terdaftar sebagai user TraKerja
            </p>
        </div>
    </div>
</body>
</html>

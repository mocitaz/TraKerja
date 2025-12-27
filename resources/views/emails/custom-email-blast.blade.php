<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 20px;
        }
        .content {
            color: #555555;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 30px;
            white-space: pre-wrap;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .email-button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            transition: transform 0.2s;
        }
        .email-button:hover {
            transform: translateY(-2px);
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            font-size: 13px;
            color: #666666;
            border-top: 1px solid #e9ecef;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #667eea;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            .greeting {
                font-size: 16px;
            }
            .content {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Halo {{ $user->name }},</p>
            
            <div class="content">{{ $emailContent }}</div>

            @if($buttonText && $buttonUrl)
            <div class="button-container">
                <a href="{{ $buttonUrl }}" class="email-button">{{ $buttonText }}</a>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Platform manajemen karir dan lamaran kerja</p>
            
            <div class="social-links">
                <a href="{{ config('app.url') }}">Website</a>
                <span>•</span>
                <a href="mailto:{{ config('mail.from.address') }}">Kontak</a>
            </div>
            
            <p style="margin-top: 15px; color: #999999; font-size: 12px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <p style="color: #999999; font-size: 12px;">
                Email ini dikirim ke {{ $user->email }}
            </p>
        </div>
    </div>
</body>
</html>

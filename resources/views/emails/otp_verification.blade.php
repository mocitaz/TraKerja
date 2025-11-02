<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TraKerja - Email Verification</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7fafc; color: #2d3748; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #e2e8f0; padding: 32px; }
        .otp { font-size: 2rem; font-weight: bold; letter-spacing: 8px; color: #7c3aed; background: #f3f0ff; padding: 12px 0; border-radius: 6px; text-align: center; margin: 24px 0; }
        .title { font-size: 1.25rem; font-weight: 600; margin-bottom: 12px; }
        .desc { font-size: 1rem; margin-bottom: 18px; }
        .footer { font-size: 0.9rem; color: #718096; margin-top: 32px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Verifikasi Email TraKerja</div>
        <div class="desc">Halo {{ $user->name }},<br>
            Berikut adalah kode OTP untuk verifikasi email kamu:
        </div>
        <div class="otp">{{ $otp }}</div>
        <div class="desc">Masukkan kode di aplikasi TraKerja untuk menyelesaikan proses registrasi.<br>
            Kode OTP berlaku selama 10 menit.</div>
        <div class="footer">Jika kamu tidak merasa melakukan registrasi, abaikan email ini.<br>TraKerja Team</div>
    </div>
</body>
</html>

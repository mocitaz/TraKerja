<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Premium Upgrade Confirmed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style type="text/css">
        body { margin: 0; padding: 0; min-width: 100%; background-color: #fcfcfd; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        .content { width: 100%; max-width: 480px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #f1f1f1; }
        .header { padding: 40px 0 20px 0; text-align: center; }
        .inner-content { padding: 0 40px 40px 40px; text-align: center; }
        .receipt-table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        .receipt-row td { padding: 12px 0; border-bottom: 1px solid #f8f8f8; }
        .label { color: #888888; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; text-align: left; }
        .value { color: #111111; font-size: 12px; font-weight: 700; text-align: right; }
        .button { display: inline-block; padding: 14px 30px; background-color: #10b981; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 13px; margin-top: 35px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
        .footer { padding: 30px; text-align: center; color: #aaaaaa; font-size: 11px; border-top: 1px solid #f8f8f8; }
        .badge { display: inline-block; padding: 6px 12px; background-color: #f0fdf4; color: #16a34a; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }
    </style>
</head>
<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td bgcolor="#fcfcfd" align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="480" class="content">
                    <tr>
                        <td class="header">
                            <img src="{{ asset('images/icon.png') }}" alt="TraKerja" width="48" style="display: block; margin: 0 auto;"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="inner-content">
                            <div class="badge">PRO UPGRADE</div>
                            <h2 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 900; color: #111111; letter-spacing: -0.02em;">Welcome to Premium</h2>
                            <p style="margin: 0 0 30px 0; font-size: 13px; line-height: 1.6; color: #666666;">Halo <strong>{{ $user->name }}</strong>, status akun Anda telah berhasil ditingkatkan ke tingkat tertinggi. Nikmati kebebasan mengejar karier impian tanpa batas.</p>
                            
                            <table border="0" cellpadding="0" cellspacing="0" class="receipt-table">
                                <tr class="receipt-row">
                                    <td class="label">Order ID</td>
                                    <td class="value">TRK-{{ strtoupper(Str::random(8)) }}</td>
                                </tr>
                                <tr class="receipt-row">
                                    <td class="label">Tanggal Transaksi</td>
                                    <td class="value">{{ now()->format('d M Y, H:i') }} WIB</td>
                                </tr>
                                <tr class="receipt-row">
                                    <td class="label">Status Akun</td>
                                    <td class="value" style="color: #6366f1;">Lifetime Premium</td>
                                </tr>
                                <tr class="receipt-row">
                                    <td class="label">Masa Berlaku</td>
                                    <td class="value">Seumur Hidup</td>
                                </tr>
                                <tr class="receipt-row">
                                    <td class="label">Metode Aktivasi</td>
                                    <td class="value">Sistem (Direct)</td>
                                </tr>
                                <tr class="receipt-row">
                                    <td class="label" style="padding-top: 20px; font-size: 13px;">Total Pembayaran</td>
                                    <td class="value" style="padding-top: 20px; font-size: 16px; font-weight: 900; color: #10b981;">LUNAS</td>
                                </tr>
                            </table>

                            <a href="{{ route('dashboard') }}" class="button">Masuk ke Dashboard</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <p style="margin: 0 0 5px 0;">&copy; {{ date('Y') }} TraKerja. All rights reserved.</p>
                            <p style="margin: 0;">Butuh bantuan? Hubungi <a href="mailto:support@trakerja.com" style="color: #888888; text-decoration: none;">support@trakerja.com</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
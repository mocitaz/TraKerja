<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran — TraKerja Premium</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Pembayaran Berhasil',
                        'subtitle' => 'Akun Anda telah resmi ditingkatkan ke paket Premium'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                Yth. <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                Terima kasih atas kepercayaan Anda. Transaksi telah berhasil diproses dan akun TraKerja Anda kini telah aktif dengan status Premium. Berikut adalah tanda terima resmi pembayaran Anda.
                            </p>

                            <!-- Status Badge -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td>
                                        <span style="display:inline-block; padding:4px 12px; background-color:#dcfce7; color:#15803d; border-radius:4px; font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase;">Pembayaran Lunas</span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Rincian Transaksi
                                        </p>

                                        <!-- Receipt Card -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                            <tr>
                                                <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:20px;">
                                                    <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                        <tr>
                                                            <td style="padding-bottom:10px; border-bottom:1px solid #ede9fe;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Nomor Tagihan</td>
                                                                        <td align="right" style="font-size:13px; font-weight:600; color:#18181b; font-family:'Courier New', Courier, monospace;">{{ $payment->order_id }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Waktu Transaksi</td>
                                                                        <td align="right" style="font-size:13px; font-weight:600; color:#18181b;">{{ $payment->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }} WIB</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">Metode Pembayaran</td>
                                                                        <td align="right" style="font-size:13px; font-weight:600; color:#18181b;">{{ strtoupper($payment->payment_method ?? 'QRIS') }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $basePrice  = 35000;
                                                            $paidAmount = $payment->amount ?? 19999;
                                                            $discount   = $basePrice - $paidAmount;
                                                        @endphp
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:13px; color:#3f3f46;">TraKerja Premium (Lifetime)</td>
                                                                        <td align="right" style="font-size:13px; color:#71717a; text-decoration:line-through;">Rp {{ number_format($basePrice, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:13px; color:#71717a;">Diskon Promo Launching</td>
                                                                        <td align="right" style="font-size:13px; font-weight:600; color:#15803d;">- Rp {{ number_format($discount, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:12px;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:14px; font-weight:700; color:#18181b;">Total Dibayar</td>
                                                                        <td align="right" style="font-size:18px; font-weight:700; color:#6d28d9;">Rp {{ number_format($paidAmount, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Premium Benefits -->
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            Yang Kini Anda Miliki
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Akses Premium Seumur Hidup</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Nikmati seluruh fitur TraKerja tanpa batasan dan tanpa biaya berlangganan bulanan, selamanya.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">AI Resume Analyzer Tanpa Batas</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">Analisis dan optimalkan CV Anda sebanyak yang diperlukan menggunakan teknologi kecerdasan buatan kami.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">Semua Fitur Eksklusif Premium</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">CV Builder profesional, Smart Cover Letter, portofolio personal, dan seluruh pembaruan fitur di masa mendatang.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:36px;">
                                <tr>
                                    <td>
                                        <a href="{{ route('dashboard') }}"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            Masuk ke Dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">Hormat kami,</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#6d28d9;">Tim TraKerja</p>
                                        <p style="margin:0; font-size:13px; color:#a1a1aa;">PT Teknalogi Transformasi Digital</p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    @include('emails.partials.footer')
                </table>
            </td>
        </tr>
    </table>

</body>
</html>

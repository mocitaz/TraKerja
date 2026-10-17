<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Payment Confirmation — TraKerja Premium') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Payment Successful',
                        'subtitle' => 'Your account has been officially upgraded to Premium'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                {{ __('Dear') }} <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                {{ __('Thank you for your trust. The transaction has been successfully processed and your TraKerja account is now active with Premium status. Below is your official payment receipt.') }}
                            </p>

                            <!-- Status Badge -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td>
                                        <span style="display:inline-block; padding:4px 12px; background-color:#dcfce7; color:#15803d; border-radius:4px; font-size:11px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase;">{{ __('Paid') }}</span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            {{ __('Transaction Details') }}
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
                                                                        <td style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Invoice Number') }}</td>
                                                                        <td align="right" style="font-size:13px; font-weight:600; color:#18181b; font-family:'Courier New', Courier, monospace;">{{ $payment->order_id }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Transaction Date') }}</td>
                                                                        <td align="right" style="font-size:13px; font-weight:600; color:#18181b;">{{ $payment->paid_at?->locale(app()->getLocale())->translatedFormat('d M Y, H:i') ?? now()->locale(app()->getLocale())->translatedFormat('d M Y, H:i') }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Payment Method') }}</td>
                                                                        <td align="right" style="font-size:13px; font-weight:600; color:#18181b;">{{ strtoupper($payment->payment_method ?? 'QRIS') }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $basePrice  = 35000;
                                                            $paidAmount = $payment->amount ?? 9999;
                                                            $discount   = $basePrice - $paidAmount;
                                                        @endphp
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:13px; color:#3f3f46;">{{ __('TraKerja Premium (Lifetime)') }}</td>
                                                                        <td align="right" style="font-size:13px; color:#71717a; text-decoration:line-through;">{{ __('Rp :value1', ['value1' => (number_format($basePrice, 0, ',', '.'))]) }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:13px; color:#71717a;">{{ __('Launching Promo Discount') }}</td>
                                                                        <td align="right" style="font-size:13px; font-weight:600; color:#15803d;">{{ __('- Rp :value1', ['value1' => (number_format($discount, 0, ',', '.'))]) }}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:12px;">
                                                                <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                                    <tr>
                                                                        <td style="font-size:14px; font-weight:700; color:#18181b;">{{ __('Total Paid') }}</td>
                                                                        <td align="right" style="font-size:18px; font-weight:700; color:#6d28d9;">{{ __('Rp :value1', ['value1' => (number_format($paidAmount, 0, ',', '.'))]) }}</td>
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
                                            {{ __('What You Now Have') }}
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Lifetime Premium Access') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Enjoy all TraKerja features with zero limits and no recurring monthly fees, forever.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Unlimited AI Resume Analyzer') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Analyze and optimize your CV as many times as you need using our advanced AI technology.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('All Premium Exclusive Features') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Professional CV Builder, Smart Cover Letter, personal portfolio site, and all future updates.') }}</p>
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
                                            {{ __('Go to Dashboard') }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">{{ __('Sincerely,') }}</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#6d28d9;">{{ __('TraKerja Team') }}</p>
                                        <p style="margin:0; font-size:13px; color:#a1a1aa;">{{ __('PT Teknalogi Transformasi Digital') }}</p>
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

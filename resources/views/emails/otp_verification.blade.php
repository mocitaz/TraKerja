@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.otp_verification.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.otp_verification.heading'),
    'subtitle' => __('emails.otp_verification.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $user->name ?? null])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.otp_verification.intro') }}</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin:24px 0;background-color:#f7f6f8;border:1px solid #e7e5e9;border-radius:8px;">
    <tr><td align="center" style="padding:22px 16px;">
        <p style="margin:0 0 14px;font-size:32px;line-height:42px;letter-spacing:6px;font-weight:700;font-family:'Courier New',monospace;color:#29252f;">{{ $otp }}</p>
        <p style="margin:0;font-size:13px;line-height:22px;color:#716c78;">{{ __('emails.common.otp_expiry') }}</p>
    </td></tr>
</table>
@include('emails.partials.notice', ['noticeText' => __('emails.common.security_note')])
<p style="margin:20px 0 0;font-size:13px;line-height:22px;color:#716c78;">{{ __('emails.common.unexpected_otp') }}</p>
@include('emails.partials.signoff')
@endsection

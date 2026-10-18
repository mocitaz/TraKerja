@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.email_verification.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.email_verification.heading'),
    'subtitle' => __('emails.email_verification.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $user->name ?? null])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.email_verification.intro') }}</p>
@include('emails.partials.action', ['actionLink' => $actionUrl, 'actionLabel' => __('emails.email_verification.cta')])
@include('emails.partials.notice', ['noticeText' => __('emails.common.unexpected_verify')])
@include('emails.partials.fallback')
<p style="margin:22px 0 0;font-size:13px;line-height:22px;color:#716c78;">{{ __('emails.email_verification.notice') }}</p>
@include('emails.partials.signoff')
@endsection

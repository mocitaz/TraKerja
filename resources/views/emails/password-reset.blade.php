@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.password_reset.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.password_reset.heading'),
    'subtitle' => __('emails.password_reset.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $user->name ?? null])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.password_reset.intro') }}</p>
<p style="margin:0 0 18px;font-size:14px;line-height:24px;color:#55515d;">{{ __('emails.common.reset_expiry', ['minutes' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]) }}</p>
@include('emails.partials.action', ['actionLink' => $actionUrl, 'actionLabel' => __('emails.password_reset.cta')])
@include('emails.partials.notice', ['noticeText' => __('emails.common.unexpected_reset')])
@include('emails.partials.fallback')
<p style="margin:22px 0 0;font-size:13px;line-height:22px;color:#716c78;">{{ __('emails.password_reset.notice') }}</p>
@include('emails.partials.signoff')
@endsection

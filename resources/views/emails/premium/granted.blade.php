@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.premium_granted.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.premium_granted.heading'),
    'subtitle' => __('emails.premium_granted.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $user->name])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.premium_granted.intro') }}</p>
@include('emails.partials.facts', ['facts' => [
    ['label' => __('emails.common.status'), 'value' => __('emails.common.premium')],
    ['label' => __('emails.common.active_until'), 'value' => $user->premium_until?->copy()->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y') ?? __('emails.common.no_end_date')],
]])
@include('emails.partials.action', ['actionLink' => config('app.url').'/profile?tab=membership', 'actionLabel' => __('emails.premium_granted.cta')])
@include('emails.partials.notice', ['noticeText' => __('emails.premium_granted.notice')])
@include('emails.partials.signoff')
@endsection

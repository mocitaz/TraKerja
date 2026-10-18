@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.payment_success.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.payment_success.heading'),
    'subtitle' => __('emails.payment_success.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $user->name ?? $payment->user?->name])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.payment_success.intro') }}</p>
@include('emails.partials.facts', ['facts' => [
    ['label' => __('emails.common.order_id'), 'value' => $payment->order_id],
    ['label' => __('emails.common.package'), 'value' => __(\App\Support\PaymentPresentation::package($payment))],
    ['label' => __('emails.common.payment_date'), 'value' => $payment->paid_at?->copy()->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i') ?? __('emails.common.not_recorded')],
    ['label' => __('emails.common.payment_method'), 'value' => \App\Support\PaymentPresentation::method($payment)],
    ['label' => __('emails.common.paid_total'), 'value' => 'Rp '.number_format($payment->amount, 0, ',', '.')],
]])
@include('emails.partials.action', ['actionLink' => config('app.url').'/profile?tab=membership', 'actionLabel' => __('emails.payment_success.cta')])
@include('emails.partials.notice', ['noticeText' => __('emails.payment_success.notice')])
@include('emails.partials.signoff')
@endsection

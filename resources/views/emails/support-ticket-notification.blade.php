@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.support_ticket_notification.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.support_ticket_notification.heading'),
    'subtitle' => __('emails.support_ticket_notification.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['adminGreeting' => true])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.support_ticket_notification.intro') }}</p>
@include('emails.partials.facts', ['facts' => [
    ['label' => __('emails.common.category'), 'value' => $ticket->category_label],
    ['label' => __('emails.common.subject'), 'value' => $ticket->subject],
    ['label' => __('emails.common.sender'), 'value' => $ticket->user?->name ?? __('emails.common.not_recorded')],
    ['label' => __('emails.common.email'), 'value' => $ticket->user?->email ?? __('emails.common.not_recorded')],
    ['label' => __('emails.common.submitted_at'), 'value' => $ticket->created_at ? $ticket->created_at->copy()->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i').' WIB' : __('emails.common.not_recorded')],
]])
<p style="margin:22px 0 6px;font-size:14px;font-weight:700;color:#29252f;">{{ __('emails.common.message') }}</p>
<p style="margin:0;font-size:14px;line-height:24px;color:#55515d;white-space:pre-line;">{{ $ticket->message }}</p>
@include('emails.partials.action', ['actionLink' => config('app.url').'/admin/feedbacks', 'actionLabel' => __('emails.support_ticket_notification.cta')])
<p style="margin:20px 0 0;font-size:12px;line-height:21px;color:#716c78;">{{ __('emails.common.system_notice') }}</p>
@endsection

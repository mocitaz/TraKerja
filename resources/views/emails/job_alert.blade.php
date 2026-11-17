@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.job_alert.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', ['title' => __('emails.job_alert.heading'), 'subtitle' => __('emails.job_alert.subtitle')])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $user->name])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.job_alert.intro') }}</p>
@foreach($searches as $search)
    <p style="margin:22px 0 4px;font-size:14px;font-weight:700;color:#29252f;">{{ trans_choice('emails.job_alert.search_line', $search['total'], ['name' => $search['name'], 'count' => $search['total']]) }}</p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin:6px 0 4px;">
        @foreach($search['jobs'] as $job)
            <tr><td style="padding:12px 0;{{ !$loop->last ? 'border-bottom:1px solid #e7e5e9;' : '' }}">
                <a href="{{ $job['url'] }}" style="color:#29252f;font-size:14px;font-weight:700;line-height:22px;text-decoration:none;">{{ $job['title'] }}</a>
                <p style="margin:2px 0 0;color:#716c78;font-size:13px;line-height:21px;">{{ collect([$job['company'], $job['location'], $job['salary'] ?? null])->filter()->implode(' · ') }}</p>
            </td></tr>
        @endforeach
    </table>
    @if($search['total'] > count($search['jobs']))<p style="margin:4px 0 0;font-size:13px;color:#716c78;">{{ __('emails.job_alert.more', ['count' => $search['total'] - count($search['jobs'])]) }}</p>@endif
@endforeach
@include('emails.partials.action', ['actionLink' => config('app.url').'/explore', 'actionLabel' => __('emails.job_alert.cta')])
@include('emails.partials.notice', ['noticeText' => __('emails.job_alert.notice')])
@include('emails.partials.signoff')
@endsection

@extends('emails.layouts.base')

@section('email_title')
{{ \App\Support\SelectionSchedule::heading($jobApplication->recruitment_stage) }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => \App\Support\SelectionSchedule::heading($jobApplication->recruitment_stage),
    'subtitle' => __('emails.interview_scheduled.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $jobApplication->user?->name])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.interview_scheduled.intro') }}</p>
@php
    $scheduleFacts = [
        ['label' => __('emails.common.role'), 'value' => $jobApplication->position],
        ['label' => __('emails.common.company'), 'value' => $jobApplication->company_name],
        ['label' => __('emails.common.date_time'), 'value' => $jobApplication->interview_date ? $jobApplication->interview_date->copy()->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i').' WIB' : __('emails.common.not_recorded')],
        ['label' => __('emails.common.method'), 'value' => \App\Support\SelectionSchedule::methodLabel($jobApplication->interview_type)],
    ];
    if (\App\Support\SelectionSchedule::online($jobApplication->interview_type) && $jobApplication->interview_platform) $scheduleFacts[] = ['label' => __('emails.common.platform'), 'value' => __($jobApplication->interview_platform)];
    if ($jobApplication->interview_location) $scheduleFacts[] = ['label' => \App\Support\SelectionSchedule::locationLabel($jobApplication->interview_type), 'value' => $jobApplication->interview_location];
@endphp
@include('emails.partials.facts', ['facts' => $scheduleFacts])
@if($jobApplication->interview_notes)
    <p style="margin:22px 0 6px;font-size:14px;font-weight:700;color:#29252f;">{{ __('emails.common.preparation') }}</p>
    <p style="margin:0;font-size:14px;line-height:24px;color:#55515d;white-space:pre-line;">{{ $jobApplication->interview_notes }}</p>
@endif
@include('emails.partials.action', ['actionLink' => config('app.url').'/interviews', 'actionLabel' => __('emails.interview_scheduled.cta')])
@include('emails.partials.notice', ['noticeText' => __('emails.interview_scheduled.notice')])
@include('emails.partials.signoff')
@endsection

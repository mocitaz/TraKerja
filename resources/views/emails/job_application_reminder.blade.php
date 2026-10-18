@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.job_application_reminder.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.job_application_reminder.heading'),
    'subtitle' => __('emails.job_application_reminder.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'job_application_reminder', 'campaignUrl' => config('app.url').'/tracker'])
@endsection

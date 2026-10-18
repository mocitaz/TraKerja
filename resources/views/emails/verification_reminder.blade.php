@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.verification_reminder.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.verification_reminder.heading'),
    'subtitle' => __('emails.verification_reminder.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'verification_reminder', 'campaignUrl' => config('app.url').'/verify-email'])
@endsection

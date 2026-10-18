@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.welcome.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.welcome.heading'),
    'subtitle' => __('emails.welcome.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'welcome', 'campaignUrl' => config('app.url').'/tracker'])
@endsection

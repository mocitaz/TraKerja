@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.re_engagement.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.re_engagement.heading'),
    'subtitle' => __('emails.re_engagement.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 're_engagement', 'campaignUrl' => config('app.url').'/tracker'])
@endsection

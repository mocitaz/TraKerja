@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.monthly_motivation.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.monthly_motivation.heading'),
    'subtitle' => __('emails.monthly_motivation.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'monthly_motivation', 'campaignUrl' => config('app.url').'/tracker'])
@endsection

@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.hiring_season_alert.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.hiring_season_alert.heading'),
    'subtitle' => __('emails.hiring_season_alert.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'hiring_season_alert', 'campaignUrl' => config('app.url').'/tracker'])
@endsection

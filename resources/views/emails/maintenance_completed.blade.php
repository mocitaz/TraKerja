@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.maintenance_completed.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.maintenance_completed.heading'),
    'subtitle' => __('emails.maintenance_completed.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'maintenance_completed', 'campaignUrl' => config('app.url').'/dashboard'])
@endsection

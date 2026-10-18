@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.brand_transformation.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.brand_transformation.heading'),
    'subtitle' => __('emails.brand_transformation.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'brand_transformation', 'campaignUrl' => config('app.url').'/dashboard'])
@endsection

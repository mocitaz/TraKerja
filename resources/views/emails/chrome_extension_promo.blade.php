@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.chrome_extension_promo.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.chrome_extension_promo.heading'),
    'subtitle' => __('emails.chrome_extension_promo.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'chrome_extension_promo', 'campaignUrl' => config('app.url').'/extension'])
@endsection

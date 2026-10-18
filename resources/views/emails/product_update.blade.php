@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.product_update.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.product_update.heading'),
    'subtitle' => __('emails.product_update.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'product_update', 'campaignUrl' => config('app.url').'/dashboard'])
@endsection

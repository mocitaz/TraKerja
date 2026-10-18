@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.pancasila.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.pancasila.heading'),
    'subtitle' => __('emails.pancasila.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'pancasila', 'campaignUrl' => null])
@endsection

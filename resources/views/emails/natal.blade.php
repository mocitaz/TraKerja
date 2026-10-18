@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.natal.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.natal.heading'),
    'subtitle' => __('emails.natal.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'natal', 'campaignUrl' => null])
@endsection

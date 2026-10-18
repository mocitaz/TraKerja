@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.idul_adha.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.idul_adha.heading'),
    'subtitle' => __('emails.idul_adha.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'idul_adha', 'campaignUrl' => null])
@endsection

@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.waisak.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.waisak.heading'),
    'subtitle' => __('emails.waisak.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'waisak', 'campaignUrl' => null])
@endsection

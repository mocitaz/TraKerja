@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.maulid_nabi.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.maulid_nabi.heading'),
    'subtitle' => __('emails.maulid_nabi.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'maulid_nabi', 'campaignUrl' => null])
@endsection

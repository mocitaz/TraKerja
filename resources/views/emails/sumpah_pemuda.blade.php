@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.sumpah_pemuda.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.sumpah_pemuda.heading'),
    'subtitle' => __('emails.sumpah_pemuda.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'sumpah_pemuda', 'campaignUrl' => null])
@endsection

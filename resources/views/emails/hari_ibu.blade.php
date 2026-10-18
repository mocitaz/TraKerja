@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.hari_ibu.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.hari_ibu.heading'),
    'subtitle' => __('emails.hari_ibu.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'hari_ibu', 'campaignUrl' => null])
@endsection

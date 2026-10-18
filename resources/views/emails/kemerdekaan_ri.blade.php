@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.kemerdekaan_ri.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.kemerdekaan_ri.heading'),
    'subtitle' => __('emails.kemerdekaan_ri.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'kemerdekaan_ri', 'campaignUrl' => null])
@endsection

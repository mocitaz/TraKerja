@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.guru_nasional.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.guru_nasional.heading'),
    'subtitle' => __('emails.guru_nasional.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'guru_nasional', 'campaignUrl' => null])
@endsection

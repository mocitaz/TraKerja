@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.tahun_baru_islam.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.tahun_baru_islam.heading'),
    'subtitle' => __('emails.tahun_baru_islam.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'tahun_baru_islam', 'campaignUrl' => null])
@endsection

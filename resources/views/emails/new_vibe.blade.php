@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.new_vibe.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.new_vibe.heading'),
    'subtitle' => __('emails.new_vibe.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'new_vibe', 'campaignUrl' => config('app.url').'/tracker'])
@endsection

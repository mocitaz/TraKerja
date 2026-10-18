@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.follow_up_feature_announcement.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.follow_up_feature_announcement.heading'),
    'subtitle' => __('emails.follow_up_feature_announcement.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'follow_up_feature_announcement', 'campaignUrl' => config('app.url').'/tracker'])
@endsection

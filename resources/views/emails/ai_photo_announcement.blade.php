@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.ai_photo_announcement.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.ai_photo_announcement.heading'),
    'subtitle' => __('emails.ai_photo_announcement.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.campaign-body', ['copyKey' => 'ai_photo_announcement', 'campaignUrl' => config('app.url').'/ai-photo'])
@endsection

@extends('emails.layouts.base')

@section('email_title')
{{ $subject ?? config('app.name') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => $subject ?? config('app.name'),
    'translateTitle' => false,
    'subtitle' => __('emails.custom_email_blast.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $user->name ?? null])
<div style="font-size:15px;line-height:25px;color:#55515d;white-space:pre-wrap;margin-bottom:22px;">{{ $emailContent }}</div>
@if($buttonText && $buttonUrl)
    @include('emails.partials.action', ['actionLink' => $buttonUrl, 'actionLabel' => $buttonText])
@endif
@include('emails.partials.signoff')
@endsection

@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.goal_achieved.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.goal_achieved.heading'),
    'subtitle' => __('emails.goal_achieved.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $goal->user?->name])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.goal_achieved.intro') }}</p>
@include('emails.partials.facts', ['facts' => array_filter([
    ['label' => __('emails.common.goal'), 'value' => $goal->title],
    $goal->target_date ? ['label' => __('emails.common.target_date'), 'value' => $goal->target_date->copy()->locale(app()->getLocale())->translatedFormat('d M Y')] : null,
])])
@if($goal->description)<p style="margin:0 0 18px;font-size:14px;line-height:24px;color:#55515d;white-space:pre-line;">{{ $goal->description }}</p>@endif
@include('emails.partials.action', ['actionLink' => config('app.url').'/dashboard', 'actionLabel' => __('emails.goal_achieved.cta')])
@include('emails.partials.signoff')
@endsection

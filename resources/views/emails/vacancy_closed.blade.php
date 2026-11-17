@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.vacancy_closed.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', ['title' => __('emails.vacancy_closed.heading'), 'subtitle' => __('emails.vacancy_closed.subtitle')])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['recipientName' => $user->name])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __($relation === 'applied' ? 'emails.vacancy_closed.intro_applied' : 'emails.vacancy_closed.intro_saved') }}</p>
@include('emails.partials.facts', ['facts' => array_values(array_filter([
    ['label' => __('emails.common.role'), 'value' => $title],
    $company ? ['label' => __('emails.common.company'), 'value' => $company] : null,
]))])
@include('emails.partials.action', [
    'actionLink' => $relation === 'applied' && $applicationId ? config('app.url').'/jobs/'.$applicationId : config('app.url').'/explore',
    'actionLabel' => __($relation === 'applied' ? 'emails.vacancy_closed.cta_applied' : 'emails.vacancy_closed.cta_saved'),
])
@include('emails.partials.notice', ['noticeText' => __('emails.vacancy_closed.notice')])
@include('emails.partials.signoff')
@endsection

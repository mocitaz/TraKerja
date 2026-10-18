@extends('emails.layouts.base')

@section('email_title')
{{ __('emails.new_user_registration.heading') }}
@endsection

@section('email_header')
@include('emails.partials.header', [
    'title' => __('emails.new_user_registration.heading'),
    'subtitle' => __('emails.new_user_registration.subtitle')
])
@endsection

@section('email_content')
@include('emails.partials.greeting', ['adminGreeting' => true])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.new_user_registration.intro') }}</p>
@include('emails.partials.facts', ['facts' => [
    ['label' => __('emails.common.name'), 'value' => $user->name],
    ['label' => __('emails.common.email'), 'value' => $user->email],
    ['label' => __('emails.common.registered_at'), 'value' => $user->created_at ? $user->created_at->copy()->timezone('Asia/Jakarta')->locale(app()->getLocale())->translatedFormat('d M Y, H:i').' WIB' : __('emails.common.not_recorded')],
    ['label' => __('emails.common.status'), 'value' => $user->email_verified_at ? __('emails.common.verified') : __('emails.common.unverified')],
]])
@include('emails.partials.action', ['actionLink' => config('app.url').'/admin/users/'.$user->id, 'actionLabel' => __('emails.new_user_registration.cta')])
<p style="margin:20px 0 0;font-size:12px;line-height:21px;color:#716c78;">{{ __('emails.common.system_notice') }}</p>
@endsection

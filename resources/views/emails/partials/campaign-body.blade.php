@include('emails.partials.greeting', ['recipientName' => $user->name ?? null])
<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#55515d;">{{ __('emails.'.$copyKey.'.intro') }}</p>
@if(\Illuminate\Support\Facades\Lang::has('emails.'.$copyKey.'.items'))
    @include('emails.partials.items', ['copyItems' => __('emails.'.$copyKey.'.items')])
@endif
@if(!empty($campaignUrl))
    @include('emails.partials.action', ['actionLink' => $campaignUrl, 'actionLabel' => __('emails.'.$copyKey.'.cta')])
@endif
@if(\Illuminate\Support\Facades\Lang::has('emails.'.$copyKey.'.notice'))
    @include('emails.partials.notice', ['noticeText' => __('emails.'.$copyKey.'.notice')])
@endif
@include('emails.partials.signoff')

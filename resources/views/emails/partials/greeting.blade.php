<p style="margin:0 0 18px;font-size:15px;line-height:25px;color:#29252f;">
    @if($adminGreeting ?? false)
        {{ __('emails.common.hello_admin') }},
    @else
        {{ __('emails.common.hello') }}@if(!empty($recipientName)) <strong>{{ $recipientName }}</strong>@endif,
    @endif
</p>

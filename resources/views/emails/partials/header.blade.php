<tr>
    <td class="email-header" style="padding:32px 36px 26px;background-color:#ffffff;border-bottom:1px solid #e7e5e9;border-radius:12px 12px 0 0;">
        <p style="margin:0{{ !empty($title) ? ' 0 28px' : '' }};font-family:Arial,Helvetica,sans-serif;">
            <a href="{{ $brandUrl ?? config('app.url') }}" style="display:inline-block;text-decoration:none;color:#29252f;font-size:17px;font-weight:700;line-height:28px;">
                <img src="{{ asset('images/icon.png') }}" alt="" width="28" height="28" style="display:inline-block;vertical-align:middle;width:28px;height:28px;margin-right:7px;border:0;">
                <span style="vertical-align:middle;">TraKerja</span>
            </a>
        </p>
        @if(!empty($title))
            <h1 class="email-title" style="margin:0;color:#29252f;font-size:28px;line-height:36px;font-weight:700;letter-spacing:-0.6px;font-family:Arial,Helvetica,sans-serif;">{{ ($translateTitle ?? true) ? __($title) : $title }}</h1>
        @endif
        @if(!empty($subtitle))
            <p style="margin:10px 0 0;color:#716c78;font-size:14px;line-height:23px;font-family:Arial,Helvetica,sans-serif;">{{ __($subtitle) }}</p>
        @endif
    </td>
</tr>

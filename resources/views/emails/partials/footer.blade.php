<tr>
    <td class="email-footer" style="padding:24px 36px;background-color:#ffffff;border-top:1px solid #e7e5e9;border-radius:0 0 12px 12px;font-family:Arial,Helvetica,sans-serif;">
        <p style="margin:0 0 12px;color:#716c78;font-size:12px;line-height:20px;">{{ __('emails.common.footer') }}</p>
        <p style="margin:0 0 14px;font-size:12px;line-height:24px;">
            <a href="{{ config('app.url') }}/dashboard" style="display:inline-block;padding:10px 0;color:#65507c;text-decoration:underline;">{{ __('emails.common.dashboard') }}</a>
            <span style="padding:0 8px;color:#b4aebb;">·</span>
            <a href="{{ config('app.url') }}/profile?tab=notifications" style="display:inline-block;padding:10px 0;color:#65507c;text-decoration:underline;">{{ __('emails.common.preferences') }}</a>
            <span style="padding:0 8px;color:#b4aebb;">·</span>
            <a href="mailto:infoteknalogi@gmail.com" style="display:inline-block;padding:10px 0;color:#65507c;text-decoration:underline;">{{ __('emails.common.help') }}</a>
        </p>
        <p style="margin:0;color:#716c78;font-size:11px;line-height:19px;">© {{ now('Asia/Jakarta')->year }} TraKerja<br>PT Teknalogi Transformasi Digital</p>
    </td>
</tr>

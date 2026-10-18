<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin:22px 0;background-color:#f7f6f8;border:1px solid #e7e5e9;border-radius:8px;">
    <tr><td style="padding:8px 18px;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;">
            @foreach($facts as $fact)
                <tr>
                    <td class="email-receipt-label" style="padding:12px 12px 12px 0;color:#716c78;font-size:12px;line-height:20px;vertical-align:top;{{ !$loop->last ? 'border-bottom:1px solid #e7e5e9;' : '' }}">{{ $fact['label'] }}</td>
                    <td class="email-receipt-value" style="padding:12px 0;color:#29252f;font-size:14px;font-weight:600;line-height:22px;vertical-align:top;word-break:break-word;{{ !$loop->last ? 'border-bottom:1px solid #e7e5e9;' : '' }}">{{ $fact['value'] }}</td>
                </tr>
            @endforeach
        </table>
    </td></tr>
</table>

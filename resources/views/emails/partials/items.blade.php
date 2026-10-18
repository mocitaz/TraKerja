<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin:22px 0;">
    @foreach($copyItems as $item)
        <tr><td style="padding:14px 0;{{ !$loop->last ? 'border-bottom:1px solid #e7e5e9;' : '' }}">
            <p style="margin:0 0 4px;color:#29252f;font-size:14px;font-weight:700;line-height:22px;">{{ $item['title'] }}</p>
            <p style="margin:0;color:#716c78;font-size:13px;line-height:22px;">{{ $item['text'] }}</p>
        </td></tr>
    @endforeach
</table>

<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>@yield('email_title', config('app.name'))</title>
    @include('emails.partials.styles')
</head>
<body style="margin:0;padding:0;width:100%;background-color:#ffffff;color:#29252f;font-family:Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;background-color:#ffffff;border-collapse:collapse;">
    <tr>
        <td align="center" class="email-canvas" style="padding:32px 12px;">
            <!--[if mso]><table role="presentation" width="600" align="center"><tr><td><![endif]-->
            <table role="presentation" class="email-frame" width="100%" cellpadding="0" cellspacing="0" style="width:100%;max-width:600px;margin:0 auto;background-color:#ffffff;border:1px solid #e7e5e9;border-radius:12px;border-spacing:0;">
                @yield('email_header')
                <tr>
                    <td class="email-content" style="padding:28px 36px 32px;word-break:break-word;overflow-wrap:anywhere;">
                        @yield('email_content')
                    </td>
                </tr>
                @include('emails.partials.footer')
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
        </td>
    </tr>
</table>
</body>
</html>

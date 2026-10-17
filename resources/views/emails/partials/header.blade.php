<!-- TraKerja Clean Editorial Email Header -->
<tr>
    <td style="padding: 32px 32px 24px 32px; background-color: #ffffff; text-align: left; border-bottom: 1px solid #f3eff6;">
        <table role="presentation" style="width: 100%; border-collapse: collapse;">
            <tr>
                <td align="left">
                    <!-- Brand -->
                    <table role="presentation" style="border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                            <td style="vertical-align: middle;">
                                <a href="{{ config('app.url') }}" style="display: inline-block; text-decoration: none;">
                                    <table role="presentation" style="border-collapse: collapse;">
                                        <tr>
                                            <td style="vertical-align: middle;">
                                                <img src="{{ asset('images/icon.png') }}" alt="{{ __('TraKerja') }}" width="26" height="26" style="display: block; border-radius: 6px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                <div style="width: 26px; height: 26px; background-color: #8b6cae; border-radius: 6px; display: none; text-align: center; line-height: 26px; color: #ffffff; font-size: 12px; font-weight: 700;">T</div>
                                            </td>
                                            <td style="padding-left: 9px; vertical-align: middle;">
                                                <span style="font-size: 15px; font-weight: 700; color: #211d27; letter-spacing: -0.02em; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">{{ __('TraKerja') }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </a>
                            </td>
                        </tr>
                    </table>

                    <!-- Heading -->
                    <h1 style="margin: 0 0 6px 0; font-size: 20px; font-weight: 700; color: #211d27; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        {{ $title ?? 'TraKerja' }}
                    </h1>

                    <!-- Subtitle -->
                    <p style="margin: 0; font-size: 13px; font-weight: 400; color: #7f778a; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        {{ $subtitle ?? 'Integrated Job Application Tracking' }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>

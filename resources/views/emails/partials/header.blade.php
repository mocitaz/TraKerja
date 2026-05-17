<!-- Minimalist Premium Header -->
<tr>
    <td style="padding: 32px 24px 24px 24px; background-color: #ffffff; text-align: center; border-bottom: 1px solid #f3f4f6; border-radius: 16px 16px 0 0;">
        <table role="presentation" style="width:100%; border-collapse:collapse;">
            <tr>
                <td align="center">
                    <!-- Logo -->
                    <a href="{{ config('app.url') }}" style="display: inline-block; margin-bottom: 20px;">
                        <img src="{{ asset('images/icon.png') }}" alt="TraKerja" width="48" height="48" style="display: block;">
                    </a>
                    
                    <!-- Title -->
                    <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800; color: #111827; letter-spacing: -0.02em;">
                        {{ $title ?? 'TraKerja' }}
                    </h1>
                    
                    <!-- Subtitle -->
                    <p style="margin: 0; font-size: 14px; font-weight: 500; color: #6b7280;">
                        {{ $subtitle ?? 'Professional Job Application Management' }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>

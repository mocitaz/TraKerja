<!-- Notion-Style Minimalist Header -->
<tr>
    <td style="padding: 40px 24px 20px 24px; background-color: #ffffff; text-align: left; border-bottom: 1px solid #e4e4e7;">
        <table role="presentation" style="width:100%; border-collapse:collapse;">
            <tr>
                <td align="left">
                    <!-- Logo -->
                    <a href="{{ config('app.url') }}" style="display: inline-block; margin-bottom: 16px; text-decoration: none;">
                        <table role="presentation" style="border-collapse:collapse;">
                            <tr>
                                <td>
                                    <img src="{{ asset('images/icon.png') }}" alt="TraKerja" width="28" height="28" style="display: block; border-radius: 4px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div style="width:28px; height:28px; background-color:#18181b; border-radius:4px; display:none; text-align:center; line-height:28px; color:#ffffff; font-size:12px; font-weight:900;">T</div>
                                </td>
                                <td style="padding-left: 8px; font-size: 13px; font-weight: 700; color: #18181b; letter-spacing: -0.01em;">
                                    TraKerja
                                </td>
                            </tr>
                        </table>
                    </a>
                    
                    <!-- Title -->
                    <h1 style="margin: 0 0 6px 0; font-size: 20px; font-weight: 700; color: #18181b; letter-spacing: -0.02em; line-height: 1.25;">
                        {{ $title ?? 'TraKerja' }}
                    </h1>
                    
                    <!-- Subtitle -->
                    <p style="margin: 0; font-size: 13px; font-weight: 450; color: #71717a; line-height: 1.5;">
                        {{ $subtitle ?? 'Workspace Pelacakan Lamaran Kerja Terintegrasi' }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>

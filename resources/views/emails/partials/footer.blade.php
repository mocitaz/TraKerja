<!-- TraKerja Clean Editorial Email Footer -->
<tr>
    <td style="padding: 28px 32px 36px 32px; background-color: #fdfbfe; text-align: left; border-top: 1px solid #f3eff6;">
        <table role="presentation" style="width: 100%; border-collapse: collapse;">
            <tr>
                <td align="left">
                    <p style="margin: 0 0 12px 0; font-size: 12px; font-weight: 600; color: #352e40; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        {{ __('TraKerja') }} <span style="font-weight: 400; color: #8e8699;">{{ __('· PT Teknalogi Transformasi Digital') }}</span>
                    </p>

                    <p style="margin: 0 0 16px 0; font-size: 12px; color: #7f778a; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        {{ __('Platform pelacakan lamaran kerja & karier terintegrasi. Membantu kamu memantau progres dan meraih karir impian secara lebih teratur.') }}
                    </p>

                    <!-- Links -->
                    <p style="margin: 0 0 18px 0; font-size: 11px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        <a href="{{ config('app.url') }}/dashboard" style="color: #7b5ea7; text-decoration: none; font-weight: 500;">{{ __('Dashboard') }}</a>
                        <span style="color: #dcd6e3; margin: 0 7px;">·</span>
                        <a href="{{ config('app.url') }}/tracker" style="color: #7b5ea7; text-decoration: none; font-weight: 500;">{{ __('Job Tracker') }}</a>
                        <span style="color: #dcd6e3; margin: 0 7px;">·</span>
                        <a href="{{ config('app.url') }}/ai-studio" style="color: #7b5ea7; text-decoration: none; font-weight: 500;">{{ __('AI Studio') }}</a>
                        <span style="color: #dcd6e3; margin: 0 7px;">·</span>
                        <a href="mailto:infoteknalogi@gmail.com" style="color: #8e8699; text-decoration: none;">{{ __('Bantuan') }}</a>
                    </p>

                    <p style="margin: 0; font-size: 11px; color: #a49cb0; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                        {{ __('© :value1 TraKerja. Seluruh hak cipta dilindungi. Email ini dikirimkan ke akun terdaftar kamu.', ['value1' => (date('Y'))]) }}
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>

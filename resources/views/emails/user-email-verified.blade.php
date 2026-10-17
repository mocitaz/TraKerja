<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Email Pengguna Terverifikasi — TraKerja Admin') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Email Pengguna Terverifikasi',
                        'subtitle' => 'Notifikasi sistem — pengguna telah menyelesaikan verifikasi alamat email'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">{{ __('Kepada Tim Administrator,') }}</p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                {{ __('Sistem mendeteksi penyelesaian proses verifikasi email oleh seorang pengguna pada platform TraKerja. Berikut adalah informasi lengkap mengenai akun yang bersangkutan.') }}
                            </p>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">{{ __('Informasi Akun') }}</p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                            <tr>
                                                <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:20px;">
                                                    <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                        <tr>
                                                            <td style="padding-bottom:10px; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Nama Lengkap') }}</p>
                                                                <p style="margin:0; font-size:15px; font-weight:600; color:#18181b;">{{ $user->name }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Alamat Email') }}</p>
                                                                <p style="margin:0; font-size:14px; color:#18181b;">{{ $user->email }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Waktu Verifikasi') }}</p>
                                                                <p style="margin:0; font-size:14px; color:#18181b;">{{ __(':value1 WIB', ['value1' => ($user->email_verified_at->locale(app()->getLocale())->translatedFormat('d M Y, H:i'))]) }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Tanggal Registrasi') }}</p>
                                                                <p style="margin:0; font-size:14px; color:#18181b;">{{ __(':value1 WIB', ['value1' => ($user->created_at->locale(app()->getLocale())->translatedFormat('d M Y, H:i'))]) }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:10px;">
                                                                <p style="margin:0 0 6px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Status Akun') }}</p>
                                                                <span style="display:inline-block; padding:3px 10px; background-color:#dcfce7; color:#15803d; border-radius:4px; font-size:11px; font-weight:700; text-transform:uppercase;">{{ __('Aktif — Email Terverifikasi') }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">{{ __('Tindakan yang Tersedia') }}</p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Pantau Aktivitas Onboarding') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Tinjau apakah pengguna telah memulai penggunaan fitur utama seperti Job Tracker dan CV Builder dalam 48 jam pertama sejak verifikasi.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Kelola Hak Akses jika Diperlukan') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Jika pengguna memerlukan konfigurasi khusus, lakukan perubahan melalui panel manajemen pengguna.') }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                                <tr>
                                    <td>
                                        <a href="{{ config('app.url') }}/admin/users" style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">{{ __('Buka Panel Admin') }}</a>
                                        <a href="{{ config('app.url') }}/admin/users/{{ $user->id }}" style="display:inline-block; padding:12px 22px; background-color:#ffffff; color:#18181b; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; border:1px solid #d4d4d8; margin-bottom:8px;">{{ __('Lihat Detail Pengguna') }}</a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0; font-size:12px; line-height:20px; color:#71717a;">{{ __('Notifikasi ini dikirimkan secara otomatis oleh sistem TraKerja setiap kali pengguna berhasil memverifikasi alamat email mereka.') }}</p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" style="width:100%; border-collapse:collapse; border-top:1px solid #e4e4e7;">
                                <tr>
                                    <td style="padding-top:24px;">
                                        <p style="margin:0 0 4px 0; font-size:14px; line-height:22px; color:#3f3f46;">{{ __('Hormat kami,') }}</p>
                                        <p style="margin:0 0 2px 0; font-size:14px; font-weight:700; color:#6d28d9;">{{ __('Sistem TraKerja') }}</p>
                                        <p style="margin:0; font-size:13px; color:#a1a1aa;">{{ __('PT Teknalogi Transformasi Digital') }}</p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    @include('emails.partials.footer')
                </table>
            </td>
        </tr>
    </table>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Tiket Bantuan Baru — TraKerja Admin') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color:#18181b;">

    <table role="presentation" style="width:100%; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table role="presentation" style="width:100%; max-width:600px; border-collapse:collapse;">

                    @include('emails.partials.header', [
                        'title'    => 'Tiket Bantuan Baru',
                        'subtitle' => 'Notifikasi sistem — seorang pengguna mengajukan permintaan dukungan'
                    ])

                    <tr>
                        <td style="background-color:#ffffff; padding:40px 40px 32px 40px; border-left:1px solid #e4e4e7; border-right:1px solid #e4e4e7;">

                            <p style="margin:0 0 20px 0; font-size:15px; line-height:24px; color:#18181b;">
                                {{ __('Kepada Tim Administrator,') }}
                            </p>

                            <p style="margin:0 0 32px 0; font-size:15px; line-height:26px; color:#3f3f46;">
                                {{ __('Sistem mendeteksi tiket bantuan baru yang diajukan melalui Customer Support Desk TraKerja. Segera tinjau dan berikan respons dalam waktu yang wajar untuk menjaga kepuasan pengguna.') }}
                            </p>

                            <!-- Section Label -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                <tr>
                                    <td style="border-top:1px solid #e4e4e7; padding-top:24px;">
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            {{ __('Detail Tiket') }}
                                        </p>

                                        <!-- Ticket Card -->
                                        <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:28px;">
                                            <tr>
                                                <td style="background-color:#faf5ff; border:1px solid #ede9fe; border-left:3px solid #6d28d9; border-radius:4px; padding:20px;">
                                                    <table role="presentation" style="width:100%; border-collapse:collapse;">
                                                        <tr>
                                                            <td style="padding-bottom:10px; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Kategori') }}</p>
                                                                <p style="margin:0; font-size:14px; font-weight:600; color:#18181b;">{{ $ticket->category_label }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Subjek') }}</p>
                                                                <p style="margin:0; font-size:15px; font-weight:600; color:#18181b;">{{ $ticket->subject }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Pengirim') }}</p>
                                                                <p style="margin:0; font-size:14px; color:#18181b;">{{ __(':value1 —', ['value1' => ($ticket->user->name ?? 'Guest')]) }} <span style="color:#71717a;">{{ $ticket->user->email ?? 'Tidak tersedia' }}</span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0; border-bottom:1px solid #ede9fe;">
                                                                <p style="margin:0 0 3px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Waktu Pengajuan') }}</p>
                                                                <p style="margin:0; font-size:14px; color:#18181b;">{{ __(':value1 WIB', ['value1' => ($ticket->created_at->locale(app()->getLocale())->translatedFormat('d M Y, H:i'))]) }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:10px;">
                                                                <p style="margin:0 0 8px 0; font-size:11px; font-weight:700; color:#6d28d9; text-transform:uppercase; letter-spacing:0.08em;">{{ __('Isi Pesan') }}</p>
                                                                <p style="margin:0; font-size:13px; line-height:21px; color:#52525b; white-space:pre-line;">{{ $ticket->message }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Admin Actions -->
                                        <p style="margin:0 0 20px 0; font-size:11px; font-weight:700; color:#7c3aed; letter-spacing:0.1em; text-transform:uppercase;">
                                            {{ __('Panduan Penanganan') }}
                                        </p>

                                        <table role="presentation" style="width:100%; border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Tinjau Konteks Pengguna') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Sebelum merespons, periksa riwayat akun dan aktivitas pengguna melalui panel admin untuk memahami konteks permasalahan secara menyeluruh.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0; border-bottom:1px solid #f4f4f5;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Berikan Respons dalam 24 Jam') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Standar layanan kami menargetkan respons awal dalam satu hari kerja. Gunakan fitur balasan cepat di panel admin untuk efisiensi penanganan.') }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;">
                                                    <p style="margin:0 0 4px 0; font-size:14px; font-weight:600; color:#18181b;">{{ __('Perbarui Status Tiket') }}</p>
                                                    <p style="margin:0; font-size:13px; line-height:21px; color:#71717a;">{{ __('Pastikan status tiket diperbarui setiap kali ada progres penanganan agar pengguna mendapatkan notifikasi perkembangan yang tepat waktu.') }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                                <tr>
                                    <td>
                                        <a href="{{ config('app.url') }}/admin/feedbacks"
                                           style="display:inline-block; padding:12px 22px; background-color:#6d28d9; color:#ffffff; text-decoration:none; border-radius:6px; font-size:14px; font-weight:600; margin-right:10px; margin-bottom:8px;">
                                            {{ __('Balas Tiket di Admin Panel') }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- System Note -->
                            <table role="presentation" style="width:100%; border-collapse:collapse; margin-bottom:32px;">
                                <tr>
                                    <td style="background-color:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:14px 18px;">
                                        <p style="margin:0; font-size:12px; line-height:20px; color:#71717a;">
                                            {{ __('Notifikasi ini dikirimkan secara otomatis oleh sistem TraKerja setiap kali pengguna mengajukan tiket bantuan baru. Apabila Anda menerima email ini secara tidak sengaja, harap hubungi tim teknis.') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Sign-off -->
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

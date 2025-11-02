@component('mail::message')
# 🎉 Selamat! Pembayaran Berhasil

Terima kasih **{{ $user->name }}**! Pembayaran Anda telah berhasil diproses dan akun Anda telah di-upgrade ke **TraKerja Premium**.

## Detail Pembayaran

@component('mail::table')
| Item | Detail |
|:-----|:-------|
| Order ID | {{ $payment->order_id }} |
| Jumlah Pembayaran | **{{ $payment->formatted_amount }}** |
| Metode Pembayaran | {{ $payment->payment_method }} |
| Tanggal Pembayaran | {{ $payment->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }} |
| Status | ✅ **Berhasil** |
@endcomponent

## ✨ Fitur Premium Anda

Sekarang Anda dapat menikmati:

- 🤖 **AI-Powered CV Analysis** - Analisa CV Anda dengan teknologi AI terdepan
- 🎨 **Professional CV Templates** - Akses ke semua template premium ATS-friendly
- ⬇️ **Unlimited Exports** - Download CV sebagai PDF tanpa batas
- 🎯 **Priority Support** - Dapatkan dukungan prioritas dari tim kami
- ⏰ **Lifetime Access** - Akses seumur hidup tanpa batas waktu!

## Langkah Selanjutnya

@component('mail::button', ['url' => route('cv.builder')])
Buat CV Premium Sekarang
@endcomponent

@component('mail::button', ['url' => route('tracker'), 'color' => 'success'])
Lihat Dashboard
@endcomponent

---

**Terima kasih telah mempercayai TraKerja!**

Jika ada pertanyaan, jangan ragu untuk menghubungi kami.

Best regards,<br>
**Tim TraKerja**

---
<small>Email ini dikirim otomatis. Mohon jangan membalas email ini.</small>
@endcomponent

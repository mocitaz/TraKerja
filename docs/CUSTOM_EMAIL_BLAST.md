# Custom Email Blast Feature

## Overview
Fitur Custom Email Blast memungkinkan admin untuk membuat dan mengirim email dengan konten yang sepenuhnya dapat dikustomisasi ke target user tertentu.

## Fitur Utama

### 1. Email Template Options
- **Welcome Email**: Email selamat datang untuk user baru
- **Verification Email**: Email verifikasi akun
- **AI Resume Analyzer**: Pengumuman free trial AI Analyzer
- **Job Application Reminder**: Reminder untuk mencatat lamaran kerja
- **Monthly Motivation**: Motivasi bulanan
- **✨ Custom Email**: Email kustom dengan konten yang dapat disesuaikan (NEW!)

### 2. Target User Options
- **Semua User**: Kirim ke semua user terdaftar
- **User Baru**: User yang registrasi dalam 7 hari terakhir
- **User Belum Terverifikasi**: User dengan email belum terverifikasi
- **User Terverifikasi**: User dengan email sudah terverifikasi
- **User Premium**: User dengan status premium/paid
- **User Free**: User dengan status free

## Custom Email Fields

### Required Fields:
1. **Subject Email** (max 255 karakter)
   - Judul email yang akan diterima user
   - Contoh: "Update Fitur Terbaru TraKerja", "Promo Spesial Akhir Tahun"

2. **Isi Email** (max 5000 karakter)
   - Konten utama email
   - Mendukung multi-line text
   - Gunakan bahasa yang jelas dan mudah dipahami
   - Personalisasi otomatis dengan nama user

### Optional Fields:
1. **Teks Button** (max 100 karakter)
   - Call-to-action button text
   - Contoh: "Coba Sekarang", "Lihat Selengkapnya", "Upgrade Premium"

2. **URL Button** (max 500 karakter)
   - Link tujuan untuk button
   - Harus berupa valid URL (https://...)
   - Contoh: "https://trakerja.com/premium", "https://trakerja.com/features"

**Note**: Jika ingin menambahkan button, kedua field (Teks Button dan URL Button) harus diisi.

## Cara Penggunaan

### Step 1: Pilih Tipe Email
- Navigasi ke halaman Admin > Email Blast
- Pilih "✨ Custom Email" dari opsi tipe email
- Form custom email akan muncul secara otomatis

### Step 2: Isi Konten Email
```
Subject Email: Update Fitur Terbaru TraKerja

Isi Email:
Kami dengan senang hati mengumumkan fitur-fitur baru yang akan membantu 
Anda dalam proses pencarian kerja:

✨ Fitur Unggulan:
• AI Resume Analyzer - Analisis CV otomatis dengan AI
• Job Application Tracker - Pantau status lamaran dengan mudah
• Interview Calendar - Jadwal interview terorganisir

Segera coba fitur-fitur baru ini dan tingkatkan peluang Anda mendapatkan 
pekerjaan impian!

Teks Button: Coba Sekarang
URL Button: https://trakerja.com/features
```

### Step 3: Pilih Target User
- Pilih target user yang sesuai dengan konten email
- Pastikan target user relevan dengan konten yang dikirim

### Step 4: Konfirmasi dan Kirim
- Klik tombol "Kirim Email Blast"
- Periksa kembali detail di modal konfirmasi
- Klik "Ya, Kirim Sekarang" untuk mengirim

## Email Template Design

Custom email menggunakan template profesional dengan:
- Header bergradient (purple/indigo)
- Greeting personal dengan nama user
- Konten email dengan formatting yang baik
- Optional CTA button dengan hover effect
- Footer informatif dengan branding

## Best Practices

### ✅ DO:
- Gunakan subject yang menarik dan relevan
- Tulis konten yang jelas, singkat, dan mudah dipahami
- Personalisasi konten sesuai target user
- Sertakan call-to-action yang jelas
- Test dengan target user kecil terlebih dahulu
- Gunakan bahasa yang profesional namun ramah

### ❌ DON'T:
- Jangan kirim email berlebihan dalam waktu singkat
- Hindari konten spam atau misleading
- Jangan gunakan subject yang clickbait
- Hindari mengirim di luar jam kerja (best time: 09:00-17:00)
- Jangan kirim ke semua user jika tidak relevan
- Hindari typo dan kesalahan grammar

## Tips untuk Email yang Efektif

1. **Subject Line**
   - Singkat dan jelas (max 50 karakter untuk mobile)
   - Hindari kata-kata spam (FREE, URGENT, BUY NOW)
   - Personalisasi jika memungkinkan

2. **Email Content**
   - Gunakan paragraf pendek (2-3 kalimat)
   - Gunakan bullet points untuk list
   - Highlight benefit untuk user
   - Tambahkan emoji untuk visual appeal (gunakan secukupnya)

3. **Call-to-Action**
   - Gunakan action verb (Coba, Lihat, Download, Upgrade)
   - Buat satu CTA yang jelas
   - Pastikan URL valid dan tested

4. **Timing**
   - Kirim di hari kerja (Selasa-Kamis lebih baik)
   - Waktu optimal: 09:00-11:00 atau 14:00-16:00
   - Hindari akhir pekan dan hari libur

## Security & Spam Prevention

### Anti-Spam Measures:
- Built-in rate limiting
- Validation untuk konten email
- Monitoring dan logging semua email blast
- Warning sebelum mengirim

### Data Privacy:
- Email hanya dikirim ke user terdaftar
- Tidak ada sharing data ke pihak ketiga
- User dapat unsubscribe dari email list
- Compliance dengan regulasi email marketing

## Technical Details

### File Locations:
- **Controller**: `app/Http/Controllers/Admin/EmailBlastController.php`
- **Mail Class**: `app/Mail/CustomEmailBlastMail.php`
- **Template**: `resources/views/emails/custom-email-blast.blade.php`
- **View**: `resources/views/admin/email-blast.blade.php`
- **Job**: `app/Jobs/SendEmailBlastJob.php`

### Validation Rules:
```php
'custom_subject' => 'required|string|max:255'
'custom_content' => 'required|string|max:5000'
'custom_button_text' => 'nullable|string|max:100'
'custom_button_url' => 'nullable|url|max:500'
```

### Email Sending:
- Synchronous sending untuk immediate feedback
- Try-catch error handling
- Detailed logging untuk debugging
- Success/failure count tracking

## Troubleshooting

### Email tidak terkirim:
1. Periksa konfigurasi SMTP di `.env`
2. Cek log di `storage/logs/laravel.log`
3. Pastikan queue worker running (jika menggunakan queue)
4. Verifikasi email address valid

### Email masuk spam:
1. Setup SPF, DKIM, dan DMARC records
2. Gunakan domain yang verified
3. Hindari konten spam
4. Jangan kirim terlalu banyak dalam waktu singkat

### Validation error:
1. Pastikan semua required fields terisi
2. Periksa character limit
3. Validasi URL format dengan benar
4. Jika pakai button, isi kedua field

## Support & Feedback

Jika ada pertanyaan atau menemukan bug, silakan:
- Buat issue di repository
- Hubungi tim development
- Cek log untuk error details

---

**Last Updated**: December 2025
**Version**: 1.0.0
**Author**: TraKerja Development Team

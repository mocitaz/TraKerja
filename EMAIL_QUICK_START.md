# 📧 TraKerja Email System - Quick Start

## ⚡ Quick Setup (5 menit)

### 1. Update `.env` file:

```bash
# Untuk Development/Testing - Mailtrap (RECOMMENDED)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@trakerja.com"
MAIL_FROM_NAME="TraKerja"
```

**Daftar Mailtrap gratis**: https://mailtrap.io (Best untuk testing!)

### 2. Test kirim email:

```bash
php artisan email:test welcome
php artisan email:test interview
php artisan email:test goal
```

### 3. Setup Scheduler (Production):

```bash
# Edit crontab
crontab -e

# Tambahkan baris ini:
* * * * * cd /path-to-trakerja && php artisan schedule:run >> /dev/null 2>&1
```

## 📨 Fitur Email yang Sudah Siap

### ✅ 1. Welcome Email

**Trigger:** Otomatis saat user registrasi  
**Template:** `resources/views/emails/welcome.blade.php`  
**Test:** `php artisan email:test welcome`

### ✅ 2. Password Reset (Laravel Built-in)

**Trigger:** Saat user klik "Lupa Password"  
**Sudah jalan:** Otomatis by Laravel

### ✅ 3. Interview Reminder

**Trigger:** Otomatis setiap hari jam 9 pagi (24 jam sebelum interview)  
**Template:** `resources/views/emails/interview-reminder.blade.php`  
**Test:** `php artisan email:test interview`  
**Manual:** `php artisan interviews:send-reminders`

### ✅ 4. Goal Achieved

**Trigger:** Otomatis saat `is_achieved` berubah jadi `true`  
**Template:** `resources/views/emails/goal-achieved.blade.php`  
**Test:** `php artisan email:test goal`

## 🎨 Email Templates

Semua template menggunakan:

- ✨ Purple theme TraKerja
- 📱 Mobile-responsive design
- 🎯 Inline CSS (compatible with all email clients)
- 🖼️ Professional layout

Edit di folder: `resources/views/emails/`

## 🚀 Commands Tersedia

```bash
# Test individual email
php artisan email:test welcome              # Test welcome email
php artisan email:test welcome user@email.com  # Test ke email spesifik
php artisan email:test interview            # Test interview reminder
php artisan email:test goal                 # Test goal achievement

# Manual send interview reminders
php artisan interviews:send-reminders       # Kirim ke semua interview besok

# Run scheduler (development)
php artisan schedule:work                   # Keep running
php artisan schedule:run                    # Run once
```

## 🔧 Troubleshooting

### Email tidak terkirim?

```bash
# 1. Clear cache
php artisan config:clear
php artisan cache:clear

# 2. Check logs
tail -f storage/logs/laravel.log

# 3. Test koneksi
php artisan email:test welcome
```

### Gmail setup?

1. Aktifkan 2-Factor Authentication
2. Generate App Password: https://myaccount.google.com/apppasswords
3. Update `.env`:

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password-here  # 16 karakter dari Google
```

## 📋 Checklist Production

- [ ] Update `MAIL_*` di `.env` dengan credentials production
- [ ] Setup cron job untuk scheduler
- [ ] Test semua email templates
- [ ] Update `MAIL_FROM_ADDRESS` dan `MAIL_FROM_NAME`
- [ ] Enable queue untuk performa (`QUEUE_CONNECTION=database`)
- [ ] Run queue worker: `php artisan queue:work`
- [ ] Monitor logs di `storage/logs/laravel.log`

## 📚 Full Documentation

Lihat `SMTP_EMAIL_SETUP.md` untuk dokumentasi lengkap.

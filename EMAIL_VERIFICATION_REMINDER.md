# Email Verification Reminder System

## 📧 Overview

Sistem otomatis untuk mengirim email reminder kepada user yang sudah registrasi **minimal H+3** tapi belum melakukan verifikasi email. Sistem ini mencegah spam dengan batasan jumlah reminder dan interval pengiriman.

## 🎯 Fitur Utama

- ✅ Kirim ke user yang registrasi **minimal 3 hari yang lalu** (bukan tepat 3 hari)
- ✅ **Anti-spam**: Maksimal 3 reminder per user
- ✅ **Interval**: Minimal 3 hari antara reminder
- ✅ **Tracking**: Catat kapan dan berapa kali reminder dikirim
- ✅ **Exclude admin**: Admin tidak perlu verifikasi

## 🔧 Database Schema

### Fields Baru di Tabel `users`:

```sql
last_verification_reminder_sent_at  TIMESTAMP NULL
verification_reminder_count         INTEGER DEFAULT 0
```

**Tracking**:
- `last_verification_reminder_sent_at`: Kapan terakhir reminder dikirim
- `verification_reminder_count`: Sudah dikirim berapa kali (max 3)

## 📁 File yang Dibuat

### 1. Email Mailable
**File**: `app/Mail/EmailVerificationReminderMail.php`
- Class untuk email reminder verifikasi
- Subject: "⚠️ Verifikasi Email Anda - TraKerja"

### 2. Email Template
**File**: `resources/views/emails/verification_reminder.blade.php`
- Template HTML yang menarik dengan gradient design
- Countdown visual menunjukkan urgensi
- Daftar fitur yang bisa diakses setelah verifikasi:
  - 📊 Track Aplikasi Pekerjaan
  - 🎯 Set Goals & Milestones
  - 📅 Jadwal Interview
  - 📄 CV Builder
- CTA button untuk verifikasi email
- Warning box dan info box untuk highlight penting
- Responsive design

### 3. Artisan Command
**File**: `app/Console/Commands/SendVerificationReminders.php`
- Command: `email:send-verification-reminders`
- Otomatis kirim email ke user yang registrasi H+X (default 3 hari)

### 4. Scheduler
**File**: `routes/console.php`
- Dijadwalkan otomatis jalan setiap hari jam 10:00 WIB
- Cari user yang registrasi H+3 dan belum verifikasi

## 🚀 Cara Menggunakan

### Manual Testing

#### 1. Dry Run (Tanpa Kirim Email)
```bash
php artisan email:send-verification-reminders --dry-run
```
Output: Akan menampilkan list user yang akan dikirimi email tanpa benar-benar mengirim

#### 2. Kirim Email Reminder (Default: min 3 hari)
```bash
php artisan email:send-verification-reminders
```

#### 3. Custom Settings
```bash
# Ubah minimum days
php artisan email:send-verification-reminders --min-days=5

# Ubah max reminders
php artisan email:send-verification-reminders --max-reminders=5

# Ubah interval antar reminder
php artisan email:send-verification-reminders --reminder-interval=7

# Kombinasi semua
php artisan email:send-verification-reminders --min-days=3 --max-reminders=3 --reminder-interval=3 --dry-run
```

### Output Command

```
🔍 Looking for users who registered at least 3 days ago without email verification...
📋 Settings: Max 3 reminders, interval 3 days

📧 Found 2 user(s) who need verification reminders:

  • John Doe (john@example.com)
    Registered: 5 days ago | Reminders sent: 1 | Last sent: 4 days ago
    ✅ Reminder sent successfully (#2)
    
  • Jane Smith (jane@example.com)
    Registered: 3 days ago | Reminders sent: 0 | Last sent: never
    ✅ Reminder sent successfully (#1)

📊 Summary:
  • Total users found: 2
  • Successfully sent: 2
✅ Email verification reminders sent successfully!
```

## ⏰ Automated Schedule

### Jadwal Otomatis
Command ini dijadwalkan untuk berjalan **otomatis setiap hari jam 10:00 WIB**.

**Settings**:
- Minimum days: 3 hari
- Maximum reminders: 3 kali per user
- Reminder interval: 3 hari antar reminder

```php
Schedule::command('email:send-verification-reminders --min-days=3 --max-reminders=3 --reminder-interval=3')
    ->dailyAt('10:00')
    ->timezone('Asia/Jakarta')
```

### Contoh Timeline Reminder:

**User registrasi tanggal 1 Nov:**
- **4 Nov (H+3)**: Reminder #1 dikirim ✉️
- **7 Nov (H+6)**: Reminder #2 dikirim ✉️ (interval 3 hari)
- **10 Nov (H+9)**: Reminder #3 dikirim ✉️ (interval 3 hari)
- **Setelah H+9**: Tidak ada lagi reminder (max 3 tercapai) ⛔

**User registrasi tanggal 1 Nov dan verifikasi tanggal 5 Nov:**
- **4 Nov (H+3)**: Reminder #1 dikirim ✉️
- **5 Nov**: User verifikasi email ✅
- **7 Nov & seterusnya**: Tidak ada reminder (sudah verified) ⛔

### Menjalankan Scheduler

#### Development (Manual)
```bash
php artisan schedule:work
```

#### Production (dengan Cron)
Tambahkan ke crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 🎨 Email Template Design

### Header
- Gradient purple background
- Icon warning emoji (⚠️)
- Logo dan tagline TraKerja

### Content Highlights
1. **Personal Greeting**: "Halo {nama}"
2. **Countdown Box**: Visual urgensi dengan gradient merah-pink
3. **Warning Box**: Kuning dengan border, highlight akun belum aktif
4. **CTA Button**: Gradient purple, centered, prominent
5. **Info Box**: Biru, tips cek spam folder
6. **Features Grid**: 4 fitur utama dengan icon dan deskripsi
7. **Footer**: Info kontak dan link

### Colors
- Primary: `#667eea` (Purple)
- Secondary: `#764ba2` (Dark Purple)
- Warning: `#ffc107` (Yellow)
- Danger: `#f5576c` (Red-Pink)

## 🎯 Target Users

### Kriteria User yang Menerima Email:
1. ✅ Registered **minimal 3 hari yang lalu** (created_at <= 3 hari lalu)
2. ✅ Email **belum diverifikasi** (`email_verified_at` = NULL)
3. ✅ Bukan admin (`role != 'admin'`)
4. ✅ Belum mencapai **max 3 reminder** (`verification_reminder_count < 3`)
5. ✅ Sudah lewat **interval 3 hari** sejak reminder terakhir (jika pernah dikirim)

### User yang TIDAK Menerima Email:
- ❌ Admin (admin tidak perlu verifikasi)
- ❌ User yang sudah verifikasi
- ❌ User yang baru registrasi (<3 hari)
- ❌ User yang sudah dapat 3x reminder (max reached)
- ❌ User yang baru dapat reminder <3 hari lalu (belum lewat interval)

### Anti-Spam Logic:

**Scenario 1: User baru (belum pernah dapat reminder)**
```
Registrasi: 1 Nov
H+3 (4 Nov): ✅ Kirim reminder #1
```

**Scenario 2: User sudah dapat reminder**
```
Registrasi: 1 Nov
H+3 (4 Nov): ✅ Kirim reminder #1
H+4 (5 Nov): ❌ Skip (belum 3 hari sejak reminder terakhir)
H+5 (6 Nov): ❌ Skip (belum 3 hari sejak reminder terakhir)
H+6 (7 Nov): ✅ Kirim reminder #2 (sudah 3 hari sejak reminder #1)
```

**Scenario 3: User sudah max reminder**
```
Registrasi: 1 Nov
H+3 (4 Nov): ✅ Kirim reminder #1
H+6 (7 Nov): ✅ Kirim reminder #2
H+9 (10 Nov): ✅ Kirim reminder #3
H+12+: ❌ Skip (max 3 reminder tercapai)
```

## 📊 Metrics & Monitoring

### Tracking Success
Command akan log semua aktivitas:
- ✅ Berapa user yang dikirim email
- ❌ Email yang gagal terkirim (dengan error message)
- 📈 Summary count di akhir eksekusi

### Error Handling
- Try-catch untuk setiap email
- Log error ke `storage/logs/laravel.log`
- Command tidak akan stop jika 1 email gagal

## 🔧 Kustomisasi

### Mengubah Jumlah Hari
Edit scheduler di `routes/console.php`:
```php
// Ubah dari 3 hari ke 5 hari
Schedule::command('email:send-verification-reminders --days=5')
    ->dailyAt('10:00')
```

### Mengubah Jadwal
```php
// Kirim 2x sehari (jam 10 dan jam 16)
Schedule::command('email:send-verification-reminders --days=3')
    ->twiceDaily(10, 16)
    ->timezone('Asia/Jakarta');

// Atau setiap jam
->hourly()

// Atau hari tertentu saja
->weekdays() // Senin-Jumat
->weekends() // Sabtu-Minggu
```

### Multiple Reminders
Bisa setup multiple reminder untuk hari yang berbeda:
```php
// Reminder pertama: H+3
Schedule::command('email:send-verification-reminders --days=3')
    ->dailyAt('10:00');

// Reminder kedua: H+7
Schedule::command('email:send-verification-reminders --days=7')
    ->dailyAt('10:00');

// Reminder final: H+14 (sebelum akun expired/deleted)
Schedule::command('email:send-verification-reminders --days=14')
    ->dailyAt('10:00');
```

## 🧪 Testing Checklist

### Pre-deployment Testing
- [ ] Test dry-run mode
- [ ] Test dengan 1 user dummy (H+3)
- [ ] Verify email diterima di inbox
- [ ] Check email tidak masuk spam
- [ ] Test link verifikasi di email bekerja
- [ ] Test responsive email di mobile
- [ ] Test error handling (SMTP down)
- [ ] Test logging error

### Production Monitoring
- [ ] Monitor scheduler berjalan setiap hari
- [ ] Check log file untuk error
- [ ] Monitor email delivery rate
- [ ] Track conversion rate (berapa user yang verifikasi setelah reminder)

## 🔐 Security & Best Practices

### Rate Limiting
- Email verification reminder tidak di-rate limit karena 1 user max 1 email per hari
- Laravel queue recommended untuk production (hindari timeout)

### Privacy
- Email hanya dikirim ke user yang memang registrasi
- Tidak ada data sensitif di email
- Link verifikasi menggunakan signed URL

### Performance
- Query optimized dengan whereBetween untuk index
- Batch processing jika user banyak (bisa pakai chunk)
- Consider using queue untuk large volume

## 💡 Tips

### Email Not Received?
1. Check SMTP configuration di `.env`
2. Check spam folder
3. Verify email address valid
4. Test SMTP connection: `php artisan tinker` → `Mail::raw('Test', ...)`

### Debugging
```bash
# See all scheduled tasks
php artisan schedule:list

# Run scheduler manually
php artisan schedule:run

# Test specific command with verbose
php artisan email:send-verification-reminders -v
```

### Queue (Optional for Production)
Convert ke queued email untuk better performance:
```php
Mail::to($user->email)->queue(new EmailVerificationReminderMail($user));
```

## 📈 Expected Impact

### Goals
- **Target**: 40-50% user verifikasi setelah reminder
- **Reduce**: 30% unverified accounts
- **Improve**: User activation rate

### A/B Testing Ideas
- Test different subject lines
- Test different send times (morning vs evening)
- Test reminder frequency (3 days vs 5 days vs 7 days)
- Test urgency level in copy

## 🚀 Future Enhancements

### Possible Improvements
1. **Resend verification link** langsung di email (bukan redirect)
2. **Personalized content** based on user's registration source
3. **Multiple language support** (EN/ID)
4. **SMS reminder** sebagai backup email
5. **Push notification** jika user install PWA
6. **Analytics tracking** untuk click rate dan conversion
7. **Auto-delete** unverified accounts setelah 30 hari

## 📝 Notes

- Command ini aman dijalankan berkali-kali (tidak akan kirim duplicate untuk user yang sama)
- Email hanya dikirim ke user yang registrasi TEPAT X hari lalu (bukan >= X hari)
- Recommended: Monitor first week deployment untuk adjust timing & copy

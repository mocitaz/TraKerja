# Setup SMTP Email Configuration

## Konfigurasi SMTP di .env

Update file `.env` dengan konfigurasi SMTP Anda. Contoh menggunakan Gmail:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Alternatif Provider SMTP:

#### 1. **Gmail** (Gratis - Development)

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

**Note:** Gunakan App Password, bukan password Gmail biasa

- Aktifkan 2FA di Google Account
- Generate App Password: https://myaccount.google.com/apppasswords

#### 2. **Mailtrap** (Gratis - Testing)

```env
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
```

Daftar di: https://mailtrap.io

#### 3. **SendGrid** (Gratis 100 email/day)

```env
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
```

#### 4. **Mailgun** (Gratis 5000 email/month)

```env
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@your-domain.mailgun.org
MAIL_PASSWORD=your-mailgun-password
```

## Fitur Email yang Tersedia

### 1. **Welcome Email** (Otomatis saat registrasi)

- ✅ Terkirim otomatis saat user baru sign up
- 📧 Template: `resources/views/emails/welcome.blade.php`
- 🎨 Design profesional dengan purple theme
- 📋 Berisi panduan fitur-fitur TraKerja

### 2. **Password Reset Email** (Laravel Default)

- ✅ Sudah built-in di Laravel
- 📧 Terkirim otomatis saat user klik "Lupa Password"
- 🔐 Menggunakan sistem token Laravel

### 3. **Interview Reminder** (Otomatis setiap hari)

- ✅ Mengirim reminder 24 jam sebelum interview
- 📧 Template: `resources/views/emails/interview-reminder.blade.php`
- 📅 Berisi detail interview: company, position, tanggal, waktu, lokasi
- 💡 Termasuk tips persiapan interview

### 4. **Goal Achieved Notification**

- ✅ Terkirim otomatis saat user menyelesaikan goal
- 📧 Template: `resources/views/emails/goal-achieved.blade.php`
- 🎯 Celebration message dengan detail goal yang dicapai

## Setup Scheduler untuk Interview Reminder

### 1. Tambahkan ke `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('interviews:send-reminders')
    ->dailyAt('09:00') // Kirim setiap hari jam 9 pagi
    ->timezone('Asia/Jakarta');
```

### 2. Setup Cron Job di Server:

Tambahkan ke crontab (`crontab -e`):

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Testing di Development:

```bash
# Test kirim interview reminder manual
php artisan interviews:send-reminders

# Test scheduler
php artisan schedule:work
```

## Testing Email

### 1. Test Welcome Email:

```bash
php artisan tinker
```

```php
$user = User::first();
Mail::to($user->email)->send(new App\Mail\WelcomeMail($user));
```

### 2. Test Interview Reminder:

```bash
php artisan tinker
```

```php
$app = App\Models\JobApplication::whereNotNull('interview_date')->first();
Mail::to($app->user->email)->send(new App\Mail\InterviewReminderMail($app));
```

### 3. Test Goal Achieved:

```bash
php artisan tinker
```

```php
$goal = App\Models\UserGoal::first();
Mail::to($goal->user->email)->send(new App\Mail\GoalAchievedMail($goal));
```

## Queue untuk Email (Optional - Production)

Untuk performa lebih baik di production, gunakan queue:

### 1. Update `.env`:

```env
QUEUE_CONNECTION=database
```

### 2. Jalankan queue worker:

```bash
php artisan queue:work
```

### 3. Update Mailable classes untuk use queue:

Sudah otomatis menggunakan `Queueable` trait di semua Mailable class.

## Troubleshooting

### Email tidak terkirim?

1. **Check configuration:**

```bash
php artisan config:clear
php artisan cache:clear
```

2. **Test koneksi SMTP:**

```bash
php artisan tinker
```

```php
Mail::raw('Test email', function($msg) {
    $msg->to('your-email@example.com')->subject('Test');
});
```

3. **Check logs:**

```bash
tail -f storage/logs/laravel.log
```

### Gmail error "Less secure app access"?

- Aktifkan 2-Factor Authentication
- Generate App Password di: https://myaccount.google.com/apppasswords
- Gunakan App Password sebagai `MAIL_PASSWORD`

### Port blocked?

Try alternative ports:

- Port 587 (TLS)
- Port 465 (SSL)
- Port 2525 (Alternative)

## Email Templates Customization

Semua template ada di `resources/views/emails/`:

- `welcome.blade.php` - Welcome email
- `interview-reminder.blade.php` - Interview reminder
- `goal-achieved.blade.php` - Goal achievement

Edit template sesuai kebutuhan, semua sudah menggunakan inline CSS untuk kompatibilitas email client.

## Security Notes

⚠️ **JANGAN commit `.env` file ke Git!**
⚠️ **Gunakan App Password, bukan password utama**
⚠️ **Gunakan environment variables untuk credentials**

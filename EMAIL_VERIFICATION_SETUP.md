# Email Verification Setup

## Status: ✅ ACTIVATED

Email verification telah diaktifkan dengan pengecualian untuk user dengan role admin.

## Perubahan yang Dilakukan

### 1. User Model (`app/Models/User.php`)
- ✅ Mengimplementasikan interface `MustVerifyEmail`
- ✅ User sekarang wajib verifikasi email sebelum bisa mengakses aplikasi

### 2. Custom Middleware (`app/Http/Middleware/EnsureEmailIsVerifiedExceptAdmin.php`)
- ✅ Dibuat middleware custom untuk mengecualikan admin dari verifikasi email
- ✅ Admin bisa langsung login tanpa perlu verifikasi email
- ✅ Non-admin wajib verifikasi email

### 3. Middleware Registration (`bootstrap/app.php`)
- ✅ Middleware `verified` sekarang menggunakan custom middleware `EnsureEmailIsVerifiedExceptAdmin`
- ✅ Menggantikan middleware default Laravel

### 4. Register Controller (`app/Http/Controllers/Auth/RegisteredUserController.php`)
- ✅ Sudah mengirim email verifikasi otomatis setelah registrasi
- ✅ User di-logout setelah register dan diarahkan ke login dengan notifikasi
- ✅ Session status: `please-verify-email`

### 5. Login Controller (`app/Http/Controllers/Auth/AuthenticatedSessionController.php`)
- ✅ Admin langsung bisa login (skip verifikasi)
- ✅ Non-admin yang belum verifikasi akan di-logout dan dikembalikan ke halaman login
- ✅ Menampilkan notifikasi: `email-not-verified`

## Flow Verifikasi Email

### Untuk User Biasa (Non-Admin):
1. **Register** → Email verifikasi dikirim otomatis + Admin notification dikirim
2. **Redirect** → Ke halaman login dengan notifikasi "Silakan verifikasi email Anda"
3. **Cek Email** → User menerima email dari TraKerja dengan link verifikasi
4. **Klik Link** → User klik link verifikasi di email
5. **Verified** → Email terverifikasi + **Welcome email dikirim** 🎉
6. **Login** → User bisa login dan mengakses dashboard + semua fitur

### Untuk Admin:
1. **Register/Login** → Langsung bisa akses tanpa verifikasi email
2. **Skip Verification** → Middleware otomatis skip pengecekan untuk admin
3. **Full Access** → Admin langsung bisa mengakses admin dashboard

## Routes yang Dilindungi

Semua routes dengan middleware `verified` sekarang memerlukan email terverifikasi (kecuali admin):

```php
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard, Tracker, Goals, Interviews, CV Builder, dll
});
```

## Halaman Verifikasi Email

- **Route**: `/verify-email`
- **View**: `resources/views/auth/verify-email.blade.php`
- **Fitur**:
  - ✅ Tampilan modern dengan glassmorphism design
  - ✅ Tombol resend verification email
  - ✅ Notifikasi sukses ketika email terkirim ulang
  - ✅ Tombol logout untuk ganti akun
  - ✅ Help section dengan tips

## Notifikasi di Login Page

### Status: `please-verify-email`
Ditampilkan setelah registrasi berhasil.

### Status: `email-not-verified`
Ditampilkan ketika user mencoba login tanpa verifikasi email.

## Email yang Dikirim

### 1. **Email Verification** (Laravel default)
   - Dikirim saat: Setelah registrasi
   - Berisi: Link verifikasi email
   - Dikirim otomatis oleh Laravel
   - Link berlaku 60 menit

### 2. **Welcome Email** (`WelcomeMail`)
   - Dikirim saat: **Setelah email berhasil diverifikasi** ✅
   - Berisi: Selamat datang dan panduan menggunakan TraKerja
   - User sudah bisa login setelah menerima email ini

### 3. **Admin Notification** (`NewUserRegistrationMail`)
   - Dikirim saat: Setelah registrasi (bersamaan dengan verification email)
   - Kepada: Admin
   - Berisi: Notifikasi tentang user baru yang mendaftar

## Testing

### Test User Biasa:
```bash
# 1. Register user baru
# 2. Cek bahwa user di-redirect ke login dengan notifikasi
# 3. Cek email inbox untuk link verifikasi
# 4. Klik link verifikasi
# 5. Login → seharusnya berhasil masuk ke dashboard
```

### Test Admin:
```bash
# 1. Login sebagai admin (dengan atau tanpa verifikasi email)
# 2. Seharusnya langsung bisa masuk ke admin dashboard
# 3. Tidak perlu verifikasi email
```

### Test Resend Email:
```bash
# 1. Akses /verify-email setelah register
# 2. Klik "Resend Verification Email"
# 3. Cek email inbox untuk link verifikasi baru
```

## Konfigurasi Email

Pastikan SMTP sudah dikonfigurasi di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-email
MAIL_PASSWORD=your-brevo-smtp-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@trakerja.com"
MAIL_FROM_NAME="TraKerja"
```

## Troubleshooting

### User tidak menerima email verifikasi
1. Cek konfigurasi SMTP di `.env`
2. Cek spam folder
3. Test SMTP connection: `php artisan tinker` → `Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });`

### Admin tidak bisa login
1. Pastikan user memiliki `role = 'admin'`
2. Method `isAdmin()` memeriksa `role === 'admin'`

### Link verifikasi expired
1. Link berlaku 60 menit
2. User bisa klik "Resend Verification Email" untuk mendapat link baru

## Security

- ✅ Link verifikasi menggunakan signed URL (tidak bisa dipalsukan)
- ✅ Link berlaku 60 menit (mencegah link lama digunakan)
- ✅ Rate limiting: max 6 request per menit untuk resend email
- ✅ Admin bypass verification untuk akses cepat ke sistem

## Next Steps

- [ ] Test complete flow dari register sampai login
- [ ] Test resend verification email
- [ ] Test admin bypass verification
- [ ] Monitor email delivery rate
- [ ] Update email template design jika diperlukan

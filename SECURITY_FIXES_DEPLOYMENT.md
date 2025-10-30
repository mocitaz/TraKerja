# 🔒 PANDUAN DEPLOYMENT SECURITY FIXES

**Tanggal:** 30 Oktober 2025  
**Status:** Ready for Deployment  
**Prioritas:** 🔴 KRITIS - Deploy Segera

---

## ✅ PERUBAHAN YANG SUDAH DILAKUKAN

### 1. ✅ Email Admin ke Environment Variable
**File:** `app/Http/Controllers/Auth/RegisteredUserController.php`
- Email hardcoded sudah dipindahkan ke environment variable
- Default fallback masih ada untuk backward compatibility

### 2. ✅ Session Encryption Enabled
**File:** `config/session.php`
- Default session encryption diubah dari `false` ke `true`
- Session data sekarang dienkripsi secara otomatis

### 3. ✅ Path Traversal Protection
**File:** `app/Http/Controllers/LogoController.php`
- Tambahkan validasi path di method `delete()`
- Mencegah path traversal attacks
- Logging untuk attempted attacks

### 4. ✅ Secure Cookie Flag
**File:** `config/session.php`
- Secure cookie otomatis aktif di production
- Cookies hanya dikirim via HTTPS

---

## 🚀 LANGKAH DEPLOYMENT KE PRODUCTION

### STEP 1: Update File .env di Server Production

Tambahkan/update baris berikut di file `.env` production:

```bash
# ====================================
# SECURITY CONFIGURATION (Added: 30 Oct 2025)
# ====================================

# Admin Email (untuk notifikasi registrasi user baru)
ADMIN_EMAIL=infoteknalogi@gmail.com

# Session Security
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true

# Pastikan APP_ENV sudah production
APP_ENV=production
APP_DEBUG=false
```

**Cara edit .env di server:**
```bash
# SSH ke server
ssh user@your-server.com

# Masuk ke directory project
cd /path/to/TraKerja

# Edit .env
nano .env

# Tambahkan konfigurasi di atas
# Save: Ctrl+O, Enter, Ctrl+X
```

---

### STEP 2: Backup Database & Files

**PENTING:** Selalu backup sebelum deployment!

```bash
# Backup database
php artisan backup:database

# Atau manual dengan mysqldump
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup files
tar -czf trakerja_backup_$(date +%Y%m%d_%H%M%S).tar.gz /path/to/TraKerja
```

---

### STEP 3: Deploy Code ke Production

**Cara 1: Via Git (Recommended)**
```bash
# Di server production
cd /path/to/TraKerja

# Pull latest changes
git pull origin main

# Atau jika branch lain:
git pull origin your-branch-name
```

**Cara 2: Manual Upload**
Upload file yang berubah via FTP/SFTP:
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/LogoController.php`
- `config/session.php`

---

### STEP 4: Clear Cache & Optimize

```bash
# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize composer autoload
composer dump-autoload -o
```

---

### STEP 5: Clear Active Sessions (PENTING!)

⚠️ **WARNING:** Step ini akan logout semua user yang sedang aktif!

**Opsi A: Via Database (Recommended)**
```sql
-- Connect ke MySQL
mysql -u username -p database_name

-- Hapus semua session lama
TRUNCATE TABLE sessions;

-- Exit
exit;
```

**Opsi B: Via Artisan (Laravel 11)**
```bash
php artisan session:flush
```

**Opsi C: Via File (jika session driver = file)**
```bash
rm -rf storage/framework/sessions/*
```

---

### STEP 6: Test Deployment

#### Test 1: Registrasi User Baru
1. Buka browser (incognito/private mode)
2. Akses: `https://yourdomain.com/register`
3. Registrasi user baru
4. ✅ Check: Email notifikasi ke admin masuk?

#### Test 2: Login & Session
1. Login dengan user yang baru dibuat
2. ✅ Check: Bisa login normal?
3. ✅ Check: Session tetap aktif saat navigasi?
4. ✅ Check: Logout berfungsi normal?

#### Test 3: Upload Profile Photo
1. Login ke aplikasi
2. Upload profile photo
3. ✅ Check: Upload berhasil?
4. Delete profile photo
5. ✅ Check: Delete berhasil?
6. ✅ Check: Tidak ada error di logs?

#### Test 4: Check Security Headers
```bash
# Test dengan curl
curl -I https://yourdomain.com

# Expected headers:
# X-Frame-Options: SAMEORIGIN
# X-Content-Type-Options: nosniff
# Strict-Transport-Security: max-age=31536000 (jika HTTPS)
```

---

### STEP 7: Monitor Logs

Monitor logs selama 1-2 jam setelah deployment:

```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Monitor web server logs (nginx)
tail -f /var/log/nginx/error.log

# Monitor web server logs (apache)
tail -f /var/log/apache2/error.log
```

**Yang harus diperhatikan:**
- ❌ Error terkait session encryption
- ❌ Error terkait file upload/delete
- ❌ Error terkait email sending
- ✅ Warning log untuk attempted path traversal (ini bagus, berarti proteksi bekerja)

---

## ⚠️ TROUBLESHOOTING

### Problem 1: User Tidak Bisa Login Setelah Deployment

**Penyebab:** Session encryption enabled, session lama tidak compatible

**Solusi:**
```bash
# Clear semua session
TRUNCATE TABLE sessions;

# Clear cache
php artisan cache:clear

# User harus login ulang (normal)
```

---

### Problem 2: Error "Unable to decrypt session"

**Penyebab:** APP_KEY berbeda atau session encryption issue

**Solusi:**
```bash
# Check APP_KEY di .env
cat .env | grep APP_KEY

# Jika kosong, generate baru:
php artisan key:generate

# Clear config cache
php artisan config:clear

# Clear sessions
TRUNCATE TABLE sessions;
```

---

### Problem 3: Profile Photo Tidak Bisa Dihapus

**Penyebab:** Path validation terlalu strict atau permission issue

**Solusi:**
```bash
# Check permission directory
ls -la public_html/storage/logos/

# Set permission yang benar
chmod 755 public_html/storage/logos/ -R
chown www-data:www-data public_html/storage/logos/ -R

# Check logs untuk detail error
tail -f storage/logs/laravel.log
```

---

### Problem 4: Email Admin Tidak Terkirim

**Penyebab:** ADMIN_EMAIL tidak terset di .env

**Solusi:**
```bash
# Check .env
cat .env | grep ADMIN_EMAIL

# Jika tidak ada, tambahkan:
echo "ADMIN_EMAIL=infoteknalogi@gmail.com" >> .env

# Clear config cache
php artisan config:clear

# Test kirim email
php artisan tinker
>>> Mail::to('test@example.com')->send(new \App\Mail\WelcomeMail(\App\Models\User::first()));
```

---

## 📊 CHECKLIST DEPLOYMENT

```
PERSIAPAN:
☐ Backup database dibuat
☐ Backup files dibuat
☐ Tim/stakeholder sudah diinformasikan

DEPLOYMENT:
☐ File .env sudah diupdate dengan konfigurasi baru
☐ Code sudah diupload/pull ke server
☐ Cache sudah di-clear
☐ Config sudah di-cache ulang
☐ Session lama sudah di-flush

TESTING:
☐ Registrasi user baru berhasil
☐ Email admin notification terkirim
☐ Login/logout berfungsi normal
☐ Upload profile photo berhasil
☐ Delete profile photo berhasil
☐ Security headers aktif

MONITORING:
☐ Logs dipantau 1-2 jam
☐ Tidak ada error kritis
☐ User bisa akses aplikasi normal
☐ Session tetap stable
```

---

## 🎯 ROLLBACK PLAN (Jika Ada Masalah)

Jika terjadi masalah serius setelah deployment:

### Quick Rollback:

```bash
# 1. Restore .env lama (backup dulu yang baru)
cp .env .env.new
cp .env.backup .env

# 2. Clear cache
php artisan config:clear
php artisan cache:clear

# 3. Restore database jika perlu
mysql -u username -p database_name < backup_YYYYMMDD_HHMMSS.sql

# 4. Restore code
git reset --hard HEAD~1
# atau
git checkout previous-commit-hash

# 5. Clear cache lagi
php artisan config:clear
php artisan cache:clear
```

---

## 📞 SUPPORT & CONTACTS

**Developer Contact:**
- Email: [Your Email]
- Phone: [Your Phone]

**Server Admin:**
- Email: [Server Admin Email]
- Phone: [Server Admin Phone]

**Emergency Rollback:**
- Contact developer immediately
- Follow rollback plan di atas

---

## 📝 NOTES PENTING

1. **Session Encryption:** 
   - Semua user akan logout otomatis setelah enable
   - Ini NORMAL dan expected behavior
   - Inform users sebelum deployment

2. **Secure Cookie:**
   - Hanya bekerja jika production menggunakan HTTPS
   - Jika masih HTTP, set `SESSION_SECURE_COOKIE=false`

3. **Path Validation:**
   - Tambahan logging akan mencatat attempted attacks
   - Monitor logs untuk suspicious activity

4. **Admin Email:**
   - Fallback ke email lama masih ada
   - Tapi best practice: set di .env

---

## ✅ POST-DEPLOYMENT

Setelah deployment berhasil:

1. ✅ Update dokumentasi internal
2. ✅ Inform stakeholder deployment selesai
3. ✅ Monitor logs selama 24 jam
4. ✅ Update security audit report status
5. ✅ Schedule next security review (3 bulan)

---

**Deployment By:** _________________  
**Date:** _________________  
**Time:** _________________  
**Status:** ☐ Success  ☐ Failed  ☐ Rollback

---

*Untuk pertanyaan atau bantuan, hubungi developer team*


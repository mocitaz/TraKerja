# 🔒 ENVIRONMENT VARIABLES - SECURITY CONFIGURATION

**File ini menjelaskan konfigurasi .env yang diperlukan untuk security fixes**  
**Tanggal:** 30 Oktober 2025

---

## 📋 KONFIGURASI WAJIB DI .ENV PRODUCTION

Tambahkan atau update baris berikut di file `.env` di server production Anda:

```bash
# ====================================
# SECURITY CONFIGURATION
# ====================================

# Admin Email (BARU - WAJIB!)
ADMIN_EMAIL=infoteknalogi@gmail.com

# Session Security (UPDATED)
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true

# Pastikan environment production
APP_ENV=production
APP_DEBUG=false
```

---

## 📖 PENJELASAN SETIAP KONFIGURASI

### 1. ADMIN_EMAIL

```bash
ADMIN_EMAIL=infoteknalogi@gmail.com
```

**Fungsi:**
- Email untuk menerima notifikasi saat ada user baru registrasi
- Menggantikan hardcoded email di code

**Wajib?** ✅ YA (untuk production)

**Default:** Jika tidak diset, akan fallback ke `infoteknalogi@gmail.com`

---

### 2. SESSION_ENCRYPT

```bash
SESSION_ENCRYPT=true
```

**Fungsi:**
- Mengenkripsi semua data session sebelum disimpan ke database
- Melindungi data sensitif seperti:
  - User role (admin/user)
  - Premium status
  - Payment information
  - Authentication tokens

**Wajib?** ✅ YA (untuk production)

**Impact:**
- ⚠️ Semua user yang sedang login akan logout otomatis
- User harus login ulang setelah setting ini diaktifkan
- **INFORM USER SEBELUM ENABLE!**

---

### 3. SESSION_SECURE_COOKIE

```bash
SESSION_SECURE_COOKIE=true
```

**Fungsi:**
- Cookie session hanya dikirim via HTTPS
- Mencegah session hijacking via man-in-the-middle attacks

**Wajib?** ✅ YA (jika menggunakan HTTPS)

**Prerequisite:**
- ⚠️ Server HARUS sudah pakai HTTPS/SSL
- Jika masih HTTP, SET `false` atau comment out

**Auto-Detection:**
- Config sudah diset auto-detect production environment
- Tapi lebih baik set explicit di .env

---

### 4. APP_ENV & APP_DEBUG

```bash
APP_ENV=production
APP_DEBUG=false
```

**Fungsi:**
- `APP_ENV=production`: Aktifkan mode production
- `APP_DEBUG=false`: Matikan debug mode (jangan expose error details)

**Wajib?** ✅ YA (untuk production)

**⚠️ BAHAYA jika `APP_DEBUG=true` di production:**
- Expose database credentials
- Expose file paths
- Expose sensitive data di error messages

---

## 🔄 CONTOH FILE .ENV LENGKAP

```bash
# ====================================
# APPLICATION CONFIGURATION
# ====================================
APP_NAME="TraKerja"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_UNIQUE_32_CHAR_KEY_HERE
APP_URL=https://trakerja.com

# ====================================
# SECURITY CONFIGURATION
# ====================================
ADMIN_EMAIL=infoteknalogi@gmail.com

SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_LIFETIME=300
SESSION_DRIVER=database

# ====================================
# DATABASE CONFIGURATION
# ====================================
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trakerja_production
DB_USERNAME=trakerja_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# ====================================
# MAIL CONFIGURATION
# ====================================
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@trakerja.com
MAIL_FROM_NAME="${APP_NAME}"

# ====================================
# LOGGING
# ====================================
LOG_CHANNEL=stack
LOG_LEVEL=warning
```

---

## ✅ CHECKLIST KONFIGURASI

Sebelum deployment, pastikan:

```
☐ ADMIN_EMAIL sudah diset
☐ SESSION_ENCRYPT=true
☐ SESSION_SECURE_COOKIE=true (jika HTTPS)
☐ APP_ENV=production
☐ APP_DEBUG=false
☐ APP_KEY sudah di-generate dan unique
☐ Database credentials benar
☐ Mail configuration benar dan tested
☐ LOG_LEVEL=warning (bukan debug)
```

---

## 🚫 KONFIGURASI DEVELOPMENT vs PRODUCTION

### Development (.env di local):
```bash
APP_ENV=local
APP_DEBUG=true
SESSION_ENCRYPT=false  # Optional, untuk faster debugging
SESSION_SECURE_COOKIE=false  # Karena local biasanya HTTP
LOG_LEVEL=debug
```

### Production (.env di server):
```bash
APP_ENV=production
APP_DEBUG=false
SESSION_ENCRYPT=true  # WAJIB!
SESSION_SECURE_COOKIE=true  # WAJIB jika HTTPS!
LOG_LEVEL=warning
```

---

## 🔧 CARA UPDATE .ENV DI SERVER

### Via SSH:

```bash
# 1. SSH ke server
ssh user@your-server.com

# 2. Masuk ke directory project
cd /path/to/TraKerja

# 3. Backup .env lama
cp .env .env.backup

# 4. Edit .env
nano .env

# 5. Tambahkan konfigurasi security (lihat di atas)

# 6. Save file
# Tekan: Ctrl+O (save), Enter, Ctrl+X (exit)

# 7. Verify changes
cat .env | grep -E "ADMIN_EMAIL|SESSION_ENCRYPT|SESSION_SECURE"

# 8. Clear config cache
php artisan config:clear
php artisan config:cache
```

### Via cPanel / File Manager:

1. Login ke cPanel
2. Buka "File Manager"
3. Navigate ke directory project
4. Right click pada `.env` → "Edit"
5. Tambahkan konfigurasi security
6. Save
7. SSH dan jalankan `php artisan config:clear`

---

## ⚠️ COMMON MISTAKES

### ❌ MISTAKE 1: Lupa Tambahkan ADMIN_EMAIL
```bash
# Error di logs:
# env('ADMIN_EMAIL') returns null
```
**Fix:** Tambahkan `ADMIN_EMAIL=infoteknalogi@gmail.com`

---

### ❌ MISTAKE 2: Set SESSION_SECURE_COOKIE=true tapi masih HTTP
```bash
# Cookie tidak terkirim, user tidak bisa login
```
**Fix:** 
- Opsi A: Install SSL/HTTPS
- Opsi B: Set `SESSION_SECURE_COOKIE=false` (tidak recommended)

---

### ❌ MISTAKE 3: Lupa Clear Config Cache
```bash
# Perubahan .env tidak apply
```
**Fix:**
```bash
php artisan config:clear
php artisan config:cache
```

---

### ❌ MISTAKE 4: APP_DEBUG=true di Production
```bash
# Security risk! Expose sensitive data
```
**Fix:** Set `APP_DEBUG=false`

---

## 📞 HELP & SUPPORT

Jika mengalami masalah setelah update konfigurasi:

1. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Verify .env:**
   ```bash
   cat .env | grep -E "APP_ENV|APP_DEBUG|SESSION|ADMIN"
   ```

3. **Clear all cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. **Test configuration:**
   ```bash
   php artisan tinker
   >>> env('ADMIN_EMAIL')  // Should return your email
   >>> config('session.encrypt')  // Should return true
   >>> config('session.secure')  // Should return true
   ```

---

**Last Updated:** 30 Oktober 2025  
**Author:** Security Audit Team


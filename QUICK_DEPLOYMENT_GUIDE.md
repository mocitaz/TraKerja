# ⚡ QUICK DEPLOYMENT GUIDE - Security Fixes

**5 Menit Deployment** 🚀  
**Last Updated:** 30 Oktober 2025

---

## 🎯 WHAT YOU NEED

✅ SSH access ke server  
✅ 5-10 menit waktu  
✅ Backup (recommended)  
✅ HTTPS/SSL sudah aktif (untuk secure cookie)

---

## ⚡ QUICK STEPS (Production)

### 1️⃣ SSH & Backup (1 menit)

```bash
ssh user@your-server.com
cd /path/to/TraKerja

# Quick backup
cp .env .env.backup
mysqldump -u username -p database_name > backup.sql
```

---

### 2️⃣ Update .env (1 menit)

```bash
nano .env
```

**Tambahkan 3 baris ini:**
```bash
ADMIN_EMAIL=infoteknalogi@gmail.com
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
```

Save: `Ctrl+O` → `Enter` → `Ctrl+X`

---

### 3️⃣ Deploy Code (1 menit)

```bash
# Option A: Git
git pull origin main

# Option B: Upload manual via FTP
# Upload 3 files:
# - app/Http/Controllers/Auth/RegisteredUserController.php
# - app/Http/Controllers/LogoController.php
# - config/session.php
```

---

### 4️⃣ Clear Cache (1 menit)

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

---

### 5️⃣ Clear Sessions (1 menit)

⚠️ **WARNING: Ini akan logout semua user!**

```bash
# Connect to MySQL
mysql -u username -p database_name

# Run this:
TRUNCATE TABLE sessions;
exit;
```

---

### 6️⃣ Test (2 menit)

```bash
# Test 1: Check config
php artisan tinker
>>> env('ADMIN_EMAIL')
# Should show: infoteknalogi@gmail.com
>>> exit

# Test 2: Browse website
# - Try login
# - Try registrasi
# - Try upload photo
```

---

## ✅ DONE! 

Total waktu: **~7 menit**

---

## 🆘 IF SOMETHING BREAKS

### Quick Rollback:

```bash
# 1. Restore .env
cp .env.backup .env

# 2. Clear cache
php artisan config:clear

# 3. Rollback code (if deployed via git)
git reset --hard HEAD~1

# 4. Clear cache again
php artisan config:clear
php artisan cache:clear
```

---

## 📱 INFORM USERS

**Before deployment, send this message:**

```
Dear Users,

We will perform a security update today at [TIME].
The update will take ~5 minutes.

⚠️ You will be automatically logged out.
✅ Please re-login after [TIME].

Sorry for any inconvenience!

- TraKerja Team
```

---

## 🔍 VERIFY DEPLOYMENT

```bash
# Check if changes applied
cat .env | grep ADMIN_EMAIL
cat .env | grep SESSION_ENCRYPT

# Check logs for errors
tail -f storage/logs/laravel.log

# Check web
curl -I https://yourdomain.com
```

---

## 📋 CHECKLIST

```
☐ Backup dibuat
☐ .env updated
☐ Code deployed
☐ Cache cleared
☐ Sessions flushed
☐ Tested login
☐ Tested registrasi
☐ No errors in logs
```

---

## 📞 NEED HELP?

**Check full guide:** `SECURITY_FIXES_DEPLOYMENT.md`

**Emergency?** Follow rollback steps above

---

**That's it! 🎉**

*For detailed explanation, see other documentation files.*


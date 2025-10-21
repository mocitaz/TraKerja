# 📋 CHECKLIST DEPLOYMENT VIA SSH

## 🔐 PRE-DEPLOYMENT (Lokal)

### ✅ File Preparation
- [ ] Build assets: `npm run build`
- [ ] Copy `env-production.txt` sebagai `.env`
- [ ] Verify `public/build/` folder exists
- [ ] Check all files ready for upload

### ✅ Upload Files
- [ ] Upload semua file ke `/home/u672201335/domains/trakerja.web.id/public_html/`
- [ ] Pastikan folder `public/` menjadi document root
- [ ] Upload file `.env` ke root project

## 🖥️ SSH CONNECTION

### ✅ Connect to Server
```bash
ssh u672201335@trakerja.web.id
# atau
ssh u672201335@[IP_ADDRESS]
```

### ✅ Navigate to Project
```bash
cd /home/u672201335/domains/trakerja.web.id/public_html
```

## 🔧 SERVER CONFIGURATION

### ✅ Set Permissions
```bash
chmod -R 755 public/
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### ✅ Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### ✅ Database Setup
```bash
php artisan migrate --force
```

### ✅ Laravel Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan key:generate --force
```

## ✅ VERIFICATION

### ✅ Test Website
- [ ] https://trakerja.web.id loads correctly
- [ ] CSS and JS assets load properly
- [ ] Login/Register functionality works
- [ ] Database connection successful
- [ ] No 500 errors in browser console

### ✅ Check Logs
```bash
tail -f storage/logs/laravel.log
```

## 🆘 TROUBLESHOOTING

### ❌ If CSS/JS not loading:
```bash
ls -la public/build/
# Should show: styles.*.css and entry.*.js
```

### ❌ If Database Error:
```bash
php artisan config:show database
# Check database configuration
```

### ❌ If 500 Error:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
# Then re-cache
php artisan config:cache
```

### ❌ If Permission Error:
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

# 🚀 PANDUAN DEPLOY JOBTRACKER KE TRAKERJA.WEB.ID

## 📋 INFORMASI HOSTING
- **Domain:** trakerja.web.id
- **Database:** u672201335_jobtracker
- **Username:** u672201335_trakerja
- **Password:** Kertasbasah45.

## 📁 FILE YANG PERLU DI-UPLOAD

### 1. File Utama (Upload ke ROOT hosting)
```
app/
bootstrap/
config/
database/
resources/
routes/
storage/
vendor/
artisan
composer.json
composer.lock
.env (rename dari env-production.txt)
```

### 2. File Public (Upload ke folder PUBLIC atau PUBLIC_HTML)
```
public/
├── build/           ← Asset build (PENTING!)
├── images/
├── favicon.ico
├── favicon.png
├── index.php
└── robots.txt
```

## 🔧 LANGKAH DEPLOYMENT

### Step 1: Upload File
1. Upload semua file ke hosting
2. Pastikan folder `public/` menjadi document root
3. Upload file `env-production.txt` dan rename menjadi `.env`

### Step 2: Set Permissions
```bash
chmod -R 755 public/
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Step 3: Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### Step 4: Setup Database
```bash
php artisan migrate --force
```

### Step 5: Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 6: Generate APP_KEY (jika belum)
```bash
php artisan key:generate --force
```

## ⚠️ PENTING!

1. **Pastikan folder `public/` adalah document root**
2. **File `.env` harus ada di root project**
3. **Folder `storage/` dan `bootstrap/cache/` harus writable**
4. **Asset build sudah ada di `public/build/`**

## 🔍 VERIFIKASI

Setelah upload, cek:
- ✅ Website bisa diakses: https://trakerja.web.id
- ✅ CSS dan JS load dengan benar
- ✅ Database connection berhasil
- ✅ Login/Register berfungsi

## 🆘 TROUBLESHOOTING

### Jika CSS/JS tidak load:
- Cek folder `public/build/` sudah ter-upload
- Cek permissions folder `public/`

### Jika database error:
- Cek konfigurasi database di `.env`
- Pastikan database sudah dibuat di hosting

### Jika 500 error:
- Cek file `.env` sudah ada
- Cek permissions folder `storage/` dan `bootstrap/cache/`
- Cek log error di hosting panel

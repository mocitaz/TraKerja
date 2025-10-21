# 🚀 QUICK SSH COMMANDS FOR DEPLOYMENT

## 📁 File Upload Commands (Lokal)
```bash
# Upload semua file ke server
scp -r * u672201335@trakerja.web.id:/home/u672201335/domains/trakerja.web.id/public_html/

# Upload file .env khusus
scp env-production.txt u672201335@trakerja.web.id:/home/u672201335/domains/trakerja.web.id/public_html/.env
```

## 🔧 Server Commands (SSH)
```bash
# Connect to server
ssh u672201335@trakerja.web.id

# Navigate to project
cd /home/u672201335/domains/trakerja.web.id/public_html

# Set permissions
chmod -R 755 public/
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Install dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate key
php artisan key:generate --force

# Check status
php artisan --version
php artisan config:show app
```

## 🔍 Debugging Commands
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check PHP version
php -v

# Check Composer
composer --version

# Check file permissions
ls -la storage/
ls -la bootstrap/cache/

# Check database connection
php artisan tinker
# Then run: DB::connection()->getPdo();
```

## ⚡ One-Line Deployment
```bash
# After SSH connection, run this single command:
cd /home/u672201335/domains/trakerja.web.id/public_html && chmod -R 755 public/ && chmod -R 775 storage/ && chmod -R 775 bootstrap/cache/ && composer install --optimize-autoloader --no-dev && php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan key:generate --force && echo "✅ Deployment completed!"
```

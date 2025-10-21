#!/bin/bash
# Deployment Script untuk JobTracker - trakerja.web.id
# Jalankan script ini setelah SSH ke server

echo "🚀 Starting JobTracker Deployment..."

# 1. Navigate to project directory
cd /home/u672201335/domains/trakerja.web.id/public_html

# 2. Set proper permissions
echo "📁 Setting permissions..."
chmod -R 755 public/
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# 3. Install/Update Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# 4. Run Laravel migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Cache Laravel configuration
echo "⚡ Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Generate application key (if needed)
echo "🔑 Generating application key..."
php artisan key:generate --force

# 7. Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 8. Re-cache for production
echo "⚡ Re-caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed successfully!"
echo "🌐 Your website should be available at: https://trakerja.web.id"

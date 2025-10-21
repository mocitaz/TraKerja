#!/bin/bash
# Laravel JobTracker Deployment Script
# Jalankan setelah SSH ke server

echo "🚀 Starting Laravel JobTracker Deployment..."

# Navigate to project directory
cd /home/u672201335/domains/trakerja.web.id/public_html

# Set permissions
echo "📁 Setting permissions..."
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod 755 public/

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# Check .env file
echo "🔧 Checking .env configuration..."
if [ ! -f .env ]; then
    echo "❌ .env file not found! Please upload env-production.txt and rename to .env"
    exit 1
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --force

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache for production
echo "⚡ Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify deployment
echo "✅ Verifying deployment..."
php artisan --version
php artisan config:show app.name

echo "🎉 Deployment completed successfully!"
echo "🌐 Your website should be available at: https://trakerja.web.id"
echo "📊 Check logs with: tail -f storage/logs/laravel.log"

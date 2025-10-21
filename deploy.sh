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

# 4. Install/Update Node dependencies and build assets
echo "📦 Installing Node dependencies..."
npm install

echo "🔨 Building frontend assets..."
npm run build

# 5. Run Laravel migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 6. Cache Laravel configuration
echo "⚡ Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Generate application key (if needed)
echo "🔑 Generating application key..."
php artisan key:generate --force

# 8. Setup storage link
echo "🔗 Setting up storage link..."
php artisan storage:link

# 9. Copy storage files to public_html (for web server access)
echo "📋 Copying storage files to public_html..."
mkdir -p public_html/storage/logos
cp -r public/storage/* public_html/storage/
chmod -R 755 public_html/storage/
chmod 644 public_html/storage/logos/*

# 10. Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 11. Re-cache for production
echo "⚡ Re-caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed successfully!"
echo "🌐 Your website should be available at: https://trakerja.web.id"

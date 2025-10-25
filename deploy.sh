#!/bin/bash

echo "🚀 Starting deployment process..."

# Clear all caches
echo "🧹 Clearing caches..."
php artisan optimize:clear

# Regenerate autoload
echo "📦 Regenerating autoload..."
composer dump-autoload --optimize

# Cache for production
echo "⚡ Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear and rebuild
echo "🔄 Final optimization..."
php artisan optimize

echo "✅ Deployment completed successfully!"
echo "📋 Next steps:"
echo "1. Upload all files to production server"
echo "2. Run 'composer install --no-dev --optimize-autoloader' on production"
echo "3. Run 'php artisan migrate --force' on production"
echo "4. Run 'php artisan config:cache' on production"

#!/bin/bash

echo "🔧 Production Fix Script - Clearing all caches..."

# Clear all possible caches
echo "1. Clearing application caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

echo "2. Regenerating autoload..."
composer dump-autoload --optimize

echo "3. Clearing compiled files..."
php artisan clear-compiled

echo "4. Rebuilding caches for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "5. Final optimization..."
php artisan optimize

echo "✅ Production fix completed!"
echo ""
echo "📋 If still having issues, run these commands on production server:"
echo "1. composer install --no-dev --optimize-autoloader"
echo "2. php artisan config:clear"
echo "3. php artisan route:clear" 
echo "4. php artisan view:clear"
echo "5. php artisan cache:clear"
echo "6. php artisan config:cache"
echo "7. php artisan route:cache"
echo "8. php artisan view:cache"

#!/bin/bash

echo "🚀 Final Production Deployment Script"
echo "====================================="

# Step 1: Clear all caches
echo "1️⃣ Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan clear-compiled

# Step 2: Regenerate autoload
echo "2️⃣ Regenerating autoload..."
composer dump-autoload --optimize

# Step 3: Rebuild caches
echo "3️⃣ Rebuilding production caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 4: Final optimization
echo "4️⃣ Final optimization..."
php artisan optimize

echo ""
echo "✅ Local preparation completed!"
echo ""
echo "📋 Now run these commands on your production server:"
echo "=================================================="
echo "1. Upload all files to production server"
echo "2. composer install --no-dev --optimize-autoloader"
echo "3. php artisan config:clear"
echo "4. php artisan route:clear"
echo "5. php artisan view:clear"
echo "6. php artisan cache:clear"
echo "7. php artisan config:cache"
echo "8. php artisan route:cache"
echo "9. php artisan view:cache"
echo "10. php artisan optimize"
echo ""
echo "🎯 This will fix the is_premium() error completely!"

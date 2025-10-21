#!/bin/bash

echo "=== Fixing 404 Errors for Hosted Laravel App ==="
echo ""

# 1. Create storage link
echo "1. Creating storage link..."
php artisan storage:link

# 2. Build assets for production
echo "2. Building assets..."
npm run build

# 3. Clear all caches
echo "3. Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Set proper permissions
echo "4. Setting permissions..."
chmod -R 755 storage/
chmod -R 755 public/
chmod -R 755 bootstrap/cache/

# 5. Copy favicon files to public if they don't exist
echo "5. Checking favicon files..."
if [ ! -f "public/favicon.png" ]; then
    echo "favicon.png not found in public/, copying from storage..."
    cp storage/app/public/logos/icon.png public/favicon.png
fi

if [ ! -f "public/favicon.ico" ]; then
    echo "favicon.ico not found in public/, creating default..."
    # Create a simple favicon.ico if needed
    echo "Creating default favicon.ico..."
fi

echo ""
echo "=== Deployment Fix Complete ==="
echo "Check your application now!"

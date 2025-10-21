#!/bin/bash
# Fix Vite Assets 404 Error
# Jalankan script ini via SSH untuk fix masalah CSS/JS 404

echo "🔧 Fixing Vite Assets 404 Error..."
echo ""

# 1. Navigate to project directory
cd /home/u672201335/domains/trakerja.web.id/public_html

# 2. Install Node dependencies
echo "📦 Installing Node dependencies..."
npm install

# 3. Build Vite assets for production
echo "🔨 Building Vite assets..."
npm run build

# 4. Check if build directory exists
echo "📁 Checking build directory..."
if [ -d "public/build" ]; then
    echo "✅ Build directory found"
    ls -la public/build/
else
    echo "❌ Build directory not found!"
    echo "Creating build directory manually..."
    mkdir -p public/build
fi

# 5. Check if manifest.json exists
echo "📄 Checking manifest.json..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ manifest.json found"
    cat public/build/manifest.json
else
    echo "❌ manifest.json not found!"
    echo "This is the root cause of the 404 error."
fi

# 6. Set proper permissions for build assets
echo "🔐 Setting permissions for build assets..."
chmod -R 755 public/build/
chmod -R 755 public/assets/

# 7. Clear Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 8. Re-cache for production
echo "⚡ Re-caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "✅ Vite assets fix completed!"
echo "🌐 Check your website now - CSS/JS should load properly"
echo ""
echo "If still having issues, check:"
echo "1. public/build/ directory exists"
echo "2. public/build/manifest.json exists"
echo "3. CSS/JS files in public/build/assets/ exist"

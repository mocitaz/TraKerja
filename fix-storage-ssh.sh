#!/bin/bash

echo "🔧 Fixing Storage Link for TraKerja..."
echo "======================================"

# 1. Navigate to project directory (sesuaikan dengan path server Anda)
echo "📁 Navigating to project directory..."
cd /home/u672201335/domains/trakerja.web.id/public_html
# Ganti path di atas sesuai dengan path server Anda

# 2. Check current status
echo "🔍 Checking current status..."
echo "Storage directory:"
ls -la storage/app/public/logos/ 2>/dev/null || echo "❌ Storage directory not found"

echo "Public storage directory:"
ls -la public/storage/logos/ 2>/dev/null || echo "❌ Public storage directory not found"

# 3. Remove old symbolic link if exists
echo "🗑️ Removing old symbolic link..."
rm -f public/storage

# 4. Create new symbolic link
echo "🔗 Creating storage symbolic link..."
php artisan storage:link

# 5. Check if symbolic link was created successfully
if [ -L "public/storage" ]; then
    echo "✅ Symbolic link created successfully"
    ls -la public/storage
else
    echo "❌ Symbolic link failed, trying manual copy..."
    
    # 6. Manual copy as fallback
    echo "📋 Copying files manually..."
    mkdir -p public/storage/logos
    cp -r storage/app/public/logos/* public/storage/logos/ 2>/dev/null
    
    # 7. Set proper permissions
    echo "🔐 Setting permissions..."
    chmod -R 755 public/storage/
    chown -R www-data:www-data public/storage/ 2>/dev/null || chown -R $(whoami):$(whoami) public/storage/
fi

# 8. Final verification
echo "✅ Final verification..."
if [ -f "public/storage/logos/icon.png" ]; then
    echo "✅ icon.png is accessible at public/storage/logos/"
    echo "🌐 Test URL: https://trakerja.web.id/storage/logos/icon.png"
else
    echo "❌ icon.png not found in public/storage/logos/"
    echo "📋 Manual upload required:"
    echo "   1. Upload icon.png to public/storage/logos/"
    echo "   2. Set permission: chmod 644 public/storage/logos/icon.png"
fi

echo ""
echo "🎉 Storage fix completed!"
echo "🌐 Test your website: https://trakerja.web.id"

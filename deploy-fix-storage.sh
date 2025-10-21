#!/bin/bash

echo "=== TraKerja Storage Fix Script ==="
echo "Jalankan script ini di server hosting Anda"
echo ""

# Masuk ke direktori project
cd domains/trakerja.web.id

echo "1. Membuat direktori storage di public..."
mkdir -p public/storage/logos

echo "2. Copy file dari storage/app/public ke public/storage..."
if [ -d "storage/app/public/logos" ]; then
    cp storage/app/public/logos/* public/storage/logos/
    echo "   ✅ File logo berhasil di-copy"
else
    echo "   ❌ Folder storage/app/public/logos tidak ditemukan"
fi

echo "3. Membuat symbolic link (jika bisa)..."
if ln -s ../storage/app/public public/storage 2>/dev/null; then
    echo "   ✅ Symbolic link berhasil dibuat"
else
    echo "   ⚠️  Symbolic link gagal dibuat, menggunakan copy file"
fi

echo "4. Set permission..."
chmod -R 755 public/storage/

echo "5. Verifikasi file..."
if [ -f "public/storage/logos/icon.png" ]; then
    echo "   ✅ File icon.png tersedia di public/storage/logos/"
else
    echo "   ❌ File icon.png tidak ditemukan"
fi

echo ""
echo "=== Selesai ==="
echo "Sekarang coba akses: https://trakerja.web.id/storage/logos/icon.png"
echo "Jika masih 404, upload file icon.png secara manual ke public/storage/logos/"

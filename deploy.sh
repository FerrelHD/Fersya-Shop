#!/bin/bash
# =============================================================
# Fersya Shop — Deploy Script
# Jalankan ini di server setiap kali ada update kode
# Usage: bash deploy.sh
# =============================================================

set -e  # hentikan script kalau ada error

echo "🚀 Mulai deploy Fersya Shop..."

# 1. Maintenance mode ON (tampilkan halaman "Sedang Maintenance")
echo "⏸  Maintenance mode ON..."
php artisan down --render="errors.503" --retry=60

# 2. Pull kode terbaru dari GitHub
echo "📦 Pull kode terbaru..."
git pull origin main

# 3. Install/update PHP dependencies
echo "🔧 Install dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 4. Jalankan migrasi database (aman, tidak hapus data)
echo "🗄️  Migrasi database..."
php artisan migrate --force

# 5. Build asset frontend (CSS/JS)
echo "🎨 Build frontend assets..."
npm ci
npm run build

# 6. Clear semua cache lama
echo "🧹 Clear cache lama..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 7. Buat cache baru (ini yang bikin website cepat)
echo "⚡ Build cache production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Pastikan storage link ada (untuk gambar produk)
echo "🔗 Storage link..."
php artisan storage:link 2>/dev/null || true

# 9. Set permission file yang benar
echo "🔐 Set permissions..."
chmod -R 775 storage bootstrap/cache

# 10. Maintenance mode OFF
echo "✅ Maintenance mode OFF..."
php artisan up

echo ""
echo "✅ Deploy selesai! Fersya Shop sudah live."

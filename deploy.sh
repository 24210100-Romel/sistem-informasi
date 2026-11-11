#!/usr/bin/env bash
# Jalankan di server setiap kali kode diperbarui:  bash deploy.sh
set -euo pipefail
cd "$(dirname "$0")"

php -r 'exit(PHP_VERSION_ID >= 80401 ? 0 : 1);' || { echo "PHP terlalu lama: dibutuhkan PHP 8.4.1+ (sekarang: $(php -r 'echo PHP_VERSION;'))."; exit 1; }

[ -f .env ] || { echo "File .env belum ada. Salin dari .env.production.example lalu isi."; exit 1; }

composer install --no-dev --optimize-autoloader --no-interaction
grep -q '^APP_KEY=base64' .env || php artisan key:generate --force
php artisan migrate --force
[ -L public/storage ] || php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Selesai. Pastikan storage/ dan bootstrap/cache/ dapat ditulis oleh server web."

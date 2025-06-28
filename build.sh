#!/usr/bin/env bash
# exit on error
set -o errexit

# Tambahkan dua baris ini
npm install
npm run build

# Perintah Laravel tetap sama
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
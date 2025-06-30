#!/bin/bash
param1="$1"
if [ -z "$param1" ]; then
  echo "Vui long nhap ten project folder"
  exit 1
fi
cp .env.example .env
composer update 
/var/www/ham-sh-mysql/create-databse.sh "db_$param1"
php artisan migrate:fresh
php artisan db:seed --class=UserSeeder
php artisan optimize:clear
npm i
npm run build

/var/www/ham-sh-mysql/create-domain-laravel-tk.sh "$param1.laravel.tk" "$param1"

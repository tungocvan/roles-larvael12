#!/bin/bash
cp .env.example .env
composer update 
/var/www/ham-sh-mysql/create-databse.sh db_laravel12
php artisan migrate
php artisan db:seed --class=UserSeeder
php artisan optimize:clear
npm i
npm run build

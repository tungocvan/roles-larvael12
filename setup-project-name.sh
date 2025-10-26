#!/bin/bash

# ===============================
# Script: setup_project.sh
# Mục đích: Tự động setup Laravel project mới từ repo mẫu
# Yêu cầu:
#   - Thực thi trong thư mục /var/www
#   - Có sẵn các script:
#       /var/www/ham-sh-mysql/create-databse.sh
#       /var/www/ham-sh-mysql/create-domain-laravel-tk.sh
# Cách dùng:
#   ./setup_project.sh <ten_project>
# Ví dụ:
#   ./setup_project.sh inafo
# ===============================

param1="$1"

if [ -z "$param1" ]; then
  echo "❌ Vui lòng nhập tên project folder. Ví dụ: ./setup_project.sh inafo"
  exit 1
fi

# --- Kiểm tra đang ở /var/www ---
CURRENT_DIR=$(pwd)
if [[ "$CURRENT_DIR" != *"/var/www"* ]]; then
  echo "❌ Script này phải được chạy trong thư mục /var/www"
  exit 1
fi

# --- Tạo thư mục project ---
echo "📁 Đang tạo thư mục: $param1 ..."
mkdir -p "$param1" || { echo "❌ Không thể tạo thư mục $param1"; exit 1; }

# --- Clone repo mẫu vào thư mục ---
echo "⬇️  Đang clone project mẫu..."
git clone git@github.com:tungocvan/roles-larvael12.git "$param1" || { echo "❌ Clone thất bại"; exit 1; }

cd "$param1" || exit 1

# --- Copy file .env ---
echo "⚙️  Sao chép file .env.example -> .env ..."
cp .env.example .env

# --- Cài đặt Composer packages ---
echo "📦 Cập nhật Composer..."
composer update --no-interaction --prefer-dist

# --- Tạo database ---
echo "🛢️  Tạo database: db_$param1 ..."
/var/www/ham-sh-mysql/create-databse.sh "db_$param1"

# --- Cập nhật DB_DATABASE trong .env ---
echo "📝 Cập nhật DB_DATABASE=db_$param1 ..."
/var/www/ham-sh-mysql/update_env_db.sh "$param1"

# --- Migrate và seed ---
echo "🚀 Chạy migrate & seed..."
php artisan migrate:fresh --force
php artisan db:seed  --force

# --- Tối ưu Laravel cache ---
echo "🧹 Dọn dẹp cache..."
php artisan optimize:clear

# --- Cài npm & build ---
echo "⚙️  Cài đặt npm packages..."
npm install
npm run build

echo "✅ Hoàn tất cài đặt project: $param1"
echo "➡️ Truy cập: https://$param1.laravel.tk"

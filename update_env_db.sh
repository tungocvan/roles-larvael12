#!/bin/bash

# ===============================
# Script: update_env_db.sh
# Mục đích: Cập nhật biến DB_DATABASE trong file .env
# Cách dùng: ./update_env_db.sh <ten_db>
# Ví dụ: ./update_env_db.sh inafo  => DB_DATABASE=db_inafo
# ===============================

# Kiểm tra xem có truyền tham số không
if [ -z "$1" ]; then
  echo "❌ Vui lòng nhập tên database. Ví dụ:"
  echo "   ./update_env_db.sh inafo"
  exit 1
fi

DB_NAME="db_$1"
ENV_FILE=".env"

# Kiểm tra file .env tồn tại
if [ ! -f "$ENV_FILE" ]; then
  echo "❌ Không tìm thấy file .env trong thư mục hiện tại!"
  exit 1
fi

# Cập nhật giá trị DB_DATABASE
if grep -q "^DB_DATABASE=" "$ENV_FILE"; then
  # Nếu đã tồn tại DB_DATABASE thì thay thế
  sed -i.bak "s/^DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" "$ENV_FILE"
else
  # Nếu chưa có thì thêm mới
  echo "DB_DATABASE=${DB_NAME}" >> "$ENV_FILE"
fi

echo "✅ Đã cập nhật DB_DATABASE=${DB_NAME} trong file .env"

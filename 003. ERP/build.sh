#!/bin/bash
# Script untuk memisahkan modul yang sudah selesai (ready) untuk Vercel

echo "Menyiapkan folder Vercel (dist)..."

# 1. Bersihkan folder dist jika sudah ada
rm -rf dist
mkdir -p dist

# 2. Copy file utama (Shell)
cp app.html dist/

# 3. Copy Assets
cp -r assets dist/

# 4. Copy Modul Dashboard
mkdir -p dist/"001. Dashboard"
cp -r "001. Dashboard"/* dist/"001. Dashboard"/

# 5. Copy Modul Master -> Produk
mkdir -p dist/"002. Master/001. Produk"
cp -r "002. Master/001. Produk"/* dist/"002. Master/001. Produk"/

echo "Selesai! Folder 'dist' sudah siap digunakan oleh Vercel."

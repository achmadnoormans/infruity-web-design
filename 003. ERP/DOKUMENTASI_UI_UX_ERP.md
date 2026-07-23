# Dokumentasi UI/UX Sistem ERP - Infruity

## 1. Ikhtisar Proyek
Proyek ini difokuskan pada pembuatan antarmuka (UI/UX) berbasis web untuk sistem ERP internal perusahaan bernama **Infruity**. Fokus yang telah diselesaikan saat ini adalah arsitektur navigasi utama (Sidebar) dan layout dasar (Shell) bergaya *Single Page Application* (SPA).

## 2. Tech Stack & Library
Sistem dibangun menggunakan stack yang ringan (tanpa *build-tools* rumit) namun kuat:
- **HTML5**: Kerangka struktur semantik.
- **Tailwind CSS (via CDN)**: Digunakan untuk styling utilitas (*utility-first*). *Custom token* warna diterapkan langsung melalui konfigurasi `tailwind.config` di dalam file HTML.
- **Alpine.js (via CDN)**: Digunakan untuk manajemen state reaktif di sisi klien (seperti logika buka-tutup sidebar, state menu aktif, dan *routing* simulasi).
- **Phosphor Icons (via CDN)**: Digunakan untuk ikon-ikon antarmuka yang bersih, konsisten, dan modern.

## 3. Sistem Desain & Estetika (Design System)
- **Tema Warna Utama**:
  - `bg-cream` (`#f8f9fa`): Latar belakang utama area konten (putih tulang/bersih).
  - `sidebar-green` (`#194B3F`): Warna latar belakang Sidebar (hijau gelap elegan yang memberikan kesan premium).
  - `active-highlight` (`#b8e833`): Warna hijau *lime* cerah untuk menyorot Modul Utama yang sedang aktif.
  - `bullet-orange` (`#f97316`): Warna oranye cerah (*vibrant*) untuk menyorot Sub-Modul yang sedang aktif beserta efek *glow* transparan.
- **Transisi Accordion (*Buttery Smooth*)**:
  Menggunakan pendekatan trik CSS Grid (`grid-template-rows: 0fr` bertransisi ke `1fr`) untuk menghasilkan animasi peluasan menu yang sangat halus, menghindari masalah loncatan render (*jumping*) yang biasa terjadi pada trik `max-height`.
- **Indikator Aktif (Bullet Point)**:
  Berbeda dari modul utama yang memakai warna latar, sub-modul menggunakan sistem indikator *bullet* di sebelah kiri. Berwarna abu-abu redup saat tidak aktif, dan menyala oranye saat dipilih.

## 4. Struktur Modul & Navigasi (Sidebar)
Sidebar menggunakan sistem **Exclusive Accordion** (Hanya satu menu utama yang bisa terbuka (mekar) pada satu waktu agar antarmuka tidak semrawut). Berikut adalah hierarki modulnya:

1. **Dashboard** (Modul Induk Tunggal)
2. **Master**
   - Produk
   - Kategori
   - Satuan Produk
   - Pemasok
   - Pelanggan
   - Cabang
   - Jabatan
   - Karyawan
   - Kurir
   - Metode Pembayaran
3. **Pembelian**
   - Pengadaan
4. **Inventaris**
   - Stok Barang
   - Sortir Barang
   - Pindah Stok (Memiliki struktur Sub-sub Modul / *Level 3*)
     - Mengirim
     - Menerima
   - Stok Opname
5. **Produksi**
   - Produksi Barang
6. **Penjualan**
   - Kasir / POS
   - Pesanan
   - Pengiriman
7. **Keuangan**
   - Pemasukan
   - Pengeluaran
8. **Laporan**
   - Laporan Harian
9. **Pengaturan**
   - Nota & Cetak
   - Akun Pengguna

## 5. Logika UX & Manajemen State (Alpine.js)
State global dikelola di dalam `<body x-data="erpApp()">`.
- **State Variabel Utama**:
  - `sidebarCollapsed` (Boolean): Mengontrol mode *mini* (menciut) dari sidebar.
  - `activePage` (String): Menyimpan referensi ID sub-modul yang sedang terbuka (untuk menampilkan konten di layar tengah).
  - `expandedMenus` (Array): Array yang diatur secara ketat agar hanya memuat maksimal 1 elemen (ID dari modul utama yang sedang mekar).
- **Behavior Mode Mini (Sidebar Collapsed)**:
  - Teks, ikon, dan seluruh *container* sub-modul disembunyikan total (`display: none !important`) agar ikon modul utama bisa menumpuk padat ke atas tanpa jeda/spasi kosong yang aneh.
  - **Smart Auto-Expand**: Jika pengguna mengeklik ikon modul utama saat sidebar sedang dalam keadaan menciut, sidebar akan langsung membesar otomatis (*auto-expand*) dan menavigasi ke halaman *default* modul tersebut. Ini menciptakan *experience* layaknya aplikasi skala *enterprise*.
- **Navigasi Bersarang (Level 3)**:
  - Khusus untuk grup menu **Pindah Stok**, accordion menggunakan state lokal AlpineJS (`x-data="{ openTransfer: ... }"`) agar ekspansinya terisolasi dan tidak mengganggu state `expandedMenus` utama yang eksklusif.

## 6. Status Pengembangan Saat Ini (Tahapan)
- **Selesai (Tahap 1 & 2)**: Arsitektur kerangka tata letak utama, komponen antarmuka Sidebar (termasuk *styling*, mikro-animasi, animasi accordion), logika UX *mini-mode*, integrasi state AlpineJS, dan penyusunan struktur hierarki seluruh modul yang diminta.
- **Belum Dimulai (Tahap 3)**: Desain tata letak, komponen UI, dan visualisasi untuk area konten utama (layar tengah). Target pengerjaan selanjutnya adalah mendesain *layout* Dashboard statistik, halaman Kasir / POS, atau tabel/formulir data Master Produk.

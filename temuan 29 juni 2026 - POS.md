# 🔍 Laporan Investigasi Bug — Temuan 29 Juni 2026

**Tanggal Investigasi**: 29 Juni 2026  
**Laporan User**: 
1. *"aku coba edit qty setelah simpan tampilannya di list transaksinya jadi 0"*
2. *"pilih produk terus input harga jual lalu simpan ada momen dimana total harga jual berubah tidak sesuai inputan"*
3. *"di UI tidak menemukan harga jual"* (Klarifikasi: di UI namanya **"Jumlah Harga"**)
4. *"Bug Diskon: jika di awal persen terus diedit nilainya jadi 0, jika nominal langsung simpan diskonnya menggelembung berlipat ganda"*

Berikut adalah **4 Bug Valid** yang merupakan akar dari masalah yang dilaporkan.

---

<br>

## 001. "Total Harga Berubah" Akibat Backend Membuang Field `total_input`

> **Akar Masalah Laporan:** *"ada momen dimana total harga jual berubah tidak sesuai inputan"*. Saat kasir input "Jumlah Harga", angkanya dikirim ke backend, tapi backend membuangnya dan menghitung ulang dari `qty` yang sudah dibulatkan.

**Modul :** Transaksi  
**Lokasi UI :** Modal Tambah Buah (Kolom "Jumlah Harga")  

### 🖥️ Simulasi Step-by-Step

1. Kasir memilih **Anggur** (Harga Rp 30.000 / kg).
2. Kasir mengetik angka **10.000** di kolom **"Jumlah Harga"**.
3. Frontend (JavaScript) menghitung Qty: `10.000 / 30.000 = 0.333333...`
4. Frontend membulatkan Qty menjadi **0.33 kg**.
5. Frontend mengirim data ke backend: `qty = 0.33` dan `total_input = 10000`.

### 💾 Dampak di Backend (Bug!)

Di Laravel (`PosController.php` baris 440), data divalidasi dengan `$request->validate([...])`. 
Namun, developer **lupa memasukkan `items.*.total_input`** ke dalam daftar validasi. Aturan bawaan Laravel adalah: *semua field yang tidak divalidasi akan otomatis dihapus (stripped) dari array hasil*.

Akibatnya, saat kode backend berjalan:
```php
// PosController.php baris 706
$itemTotal = isset($item['total_input']) ? $item['total_input'] : (($item['price'] * $item['qty']) - $discount);
```
Karena `total_input` sudah terhapus, backend **SELALU** menggunakan fallback rumus.
Backend menghitung: `30.000 × 0.33` = **Rp 9.900**.

🔴 Total yang tersimpan di `pos_details.subtotal` adalah **9.900**, bukan **10.000**. Ini yang membuat kasir bingung karena total yang tersimpan tidak sesuai dengan yang di-input awal.

**Saran Solusi:**  
Tambahkan `'items.*.total_input' => 'nullable|numeric|min:0'` ke dalam `$request->validate()` di `PosController.php`.

---

<br><br>

## 002. Transaksi Menjadi Rp 0 Karena Qty Kosong Lolos Validasi Backend

> **Akar Masalah Laporan:** *"aku coba edit qty setelah simpan tampilannya di list transaksinya jadi 0"*.

**Modul :** Transaksi  
**Lokasi UI :** Modal Edit Buah (Keranjang) — Kolom "Quantity"

### 🖥️ Simulasi Step-by-Step

1. Kasir membuka keranjang dan klik salah satu item buah untuk diedit.
2. Kasir **menghapus angka** di kolom "Quantity" (mungkin bermaksud mau menghapus item, tapi salah cara).
3. Kolom "Quantity" menjadi kosong (string kosong `""`).
4. Frontend langsung mengubah total harga menjadi **Rp 0** (karena `qty * harga = 0 * harga = 0`).
5. Kasir menekan "Simpan" di modal edit, lalu "Bayar/Simpan Transaksi".

### 💾 Dampak di Backend (Bug!)

Frontend **tidak memblokir** pengiriman `qty` kosong dari modal Edit (tidak seperti modal Tambah yang memiliki validasi `qty <= 0`).

Di Backend (`PosController.php` baris 448), validasi Qty adalah:
```php
'items.*.qty' => 'nullable|numeric|min:0.01',
```
Karena ada tulisan `nullable`, Laravel otomatis mengubah string kosong `""` menjadi `null`. Karena `null` diperbolehkan, validasi **LOLOS** dengan sukses!

Backend kemudian menghitung:
`$itemTotal = price × null` = **0**.
Keseluruhan transaksi disimpan dengan total **Rp 0**, sehingga saat dicek di "List Transaksi", angkanya tampil 0.

**Saran Solusi:**  
Di frontend `js-buah.blade.php`, tambahkan validasi `if (this.editQty <= 0)` pada fungsi `saveEditToCart()`. Dan di backend, ubah `nullable` menjadi `required` pada validasi `items.*.qty`.

---

<br><br>

## 003. Input "Jumlah Harga" (Edit Modal) Rusak Saat Pakai Desimal (Koma)

**Modul :** Transaksi  
**Lokasi UI :** Modal Edit Buah — Kolom "Jumlah Harga" (Klarifikasi: ini yang dimaksud user sebagai harga jual di UI)

### 🖥️ Simulasi Step-by-Step

Saat user mengedit item, fungsi `updateEditTotalFormatted()` dipanggil setiap kali user mengetik "Jumlah Harga". Fungsi ini menggunakan regex kasar: `replace(/[^\d]/g, '')` (Hapus semua yang bukan angka).

Jika kasir mengetik pecahan (misalnya `22.500,50`):
Regex akan menghapus koma desimal, mengubahnya menjadi **2250050** (Rp 2.250.050). 
Akibatnya Qty otomatis melonjak secara absurd (misal jadi 75 kg). 

**Saran Solusi:**  
Samakan metode regex-nya dengan yang ada di form Tambah (`updateQtyFromAddTotal`), yaitu `replace(/\./g, '').replace(/[^0-9]/g, '')` — sehingga titik ribuan dihapus dengan aman tanpa merusak input.

---

<br><br>

## 004. Bug Diskon Tercampur (Persen vs Nominal vs Total Akumulasi)

> **Akar Masalah Laporan:** *"jika di awal persen terus diedit nilainya jadi 0, jika nominal langsung simpan diskonnya jadi 500.000"* (Gambar diskon -50,000,000,000).

**Modul :** Transaksi  
**Lokasi UI :** Modal Edit Buah — Kolom "Diskon"

### 🖥️ Simulasi Step-by-Step (Sesuai Gambar)

Penyebab utamanya adalah: **Frontend menyimpan Total Diskon (akumulasi dari semua qty), tapi saat diedit, modal menganggap angka itu sebagai "Diskon per 1 Kg" atau "Diskon Persen".**

**Kasus A: Input Awal Persen, Lalu Diedit Sekali**
1. Kasir input diskon **10%** (Harga 80k, Qty 10).
2. Diskon = Rp 8.000/kg. Total Diskon tersimpan = **80.000**.
3. Saat diklik "Edit", form Diskon diisi angka **80.000**.
4. Sistem melihat **80.000 > 100**, jadi dianggap **Nominal**.
5. Sistem menghitung: Harga 80.000 dikurangi Diskon Nominal 80.000 = **0**.
6. Harga Jual menjadi **Rp 0**!

**Kasus B: Input Awal Nominal, Lalu Disimpan Berulang-ulang (Screenshot 50 Miliar)**
1. Kasir input diskon **5.000** (Nominal). Harga 80k, Qty 10.
2. Total Diskon tersimpan = **50.000** (5.000 × 10).
3. Saat diklik "Edit", form Diskon diisi **50.000**.
4. Kasir **hanya menekan Simpan** tanpa mengubah apapun.
5. Sistem mengambil angka **50.000** sebagai diskon *per-unit*.
6. Total Diskon baru = 50.000 × 10 = **500.000**.
7. Jika kasir klik edit dan simpan lagi, angkanya menjadi **5.000.000**. Diulang terus sampai menyentuh **50.000.000.000** seperti di gambar Anda.

**Kasus C: Mutasi dari Persen menjadi Nominal Menggelembung**
1. Berawal dari Kasus A, di mana Harga Jual menjadi **Rp 0**.
2. Jika pada titik ini kasir tetap menekan "Simpan", sistem akan menyimpan diskon sebesar **80.000** sebagai **diskon nominal**.
3. Jika item tersebut diklik "Edit" untuk kedua kalinya, perlakuannya akan langsung berubah menjadi seperti **Kasus B**.
4. Angka **80.000** akan dikalikan lagi dengan Qty (misal 10), sehingga menjadi diskon **800.000**, lalu **8.000.000**, dan seterusnya menggelembung tak terkendali.

**Saran Solusi:**  
Di `js-buah.blade.php`, simpan variabel asli yang diketik user (`discountNominal` atau `discountPercent`) ke dalam _cart_. Saat `openEditModal`, isikan `editDiscount` dengan nilai asli tersebut, bukan total diskon hasil perkalian.


# 🧪 Draf Investigasi Lanjutan Sub-Modul Penjualan (Staging)

Setelah melakukan penelusuran sangat mendalam (baris demi baris pada `PosController.php` dan `js-buah.blade.php`), saya menemukan **4 Bug Kalkulasi & Keamanan Kritis** di backend yang tidak kasat mata secara langsung di UI, tetapi **sangat merusak Laporan Laba Rugi dan Database**.

Berikut adalah panduan lengkap cara Anda bisa mereproduksi dan membuktikan bug ini secara langsung (Validasi).

---

## 005. Bug `price_after_discount` Menjadi Minus (Minus Puluhan Ribu) & Mengabaikan Diskon Item

**Lokasi Kode:** `PosController.php` (baris 731)  
**Masalah:** Backend mengurangi Harga Satuan (`price`) langsung dengan Total Diskon Global Item (`posDiscount`). Padahal Diskon Global itu berlaku untuk *keseluruhan Qty*, bukan per 1 kg! Parahnya lagi, sistem mengabaikan Diskon Item yang diset di keranjang.

### 🖥️ Cara Validasi / Buktikan Sendiri:
1. Masuk ke halaman Kasir (Penjualan).
2. Tambahkan **10 Kg Anggur** dengan harga **Rp 30.000 / Kg**. (Subtotal: 300.000)
3. Jangan beri diskon item di keranjang.
4. Di bagian bawah kasir (Rincian Total), masukkan **Diskon Global: Rp 50.000**.
5. Simpan & Bayar transaksi tersebut.
6. Buka database (atau PhpMyAdmin), lihat tabel `pos_details` untuk transaksi barusan.
7. **HASIL BUG:** Kolom `price_after_discount` akan tercatat **-20.000** (Negatif 20 ribu!). 
   *Kenapa? Karena backend menghitung: `30.000 (Harga Satuan) - 50.000 (Total Diskon Global) = -20.000`.*

---

## 006. Bug Laporan Laba Rugi Fiktif (Profit Dihitung dari Harga Sebelum Diskon)

**Lokasi Kode:** `PosController.php` (baris 732)  
**Masalah:** Perhitungan Margin / Profit (`exp`) dikalkulasi dengan rumus: Harga Asli dikurangi HPP (Modal). Sistem **sama sekali tidak memotong diskon** dalam menghitung profit!

### 🖥️ Cara Validasi / Buktikan Sendiri:
1. Buka Kasir. Pilih buah yang HPP-nya (Modalnya) misal **Rp 20.000 / Kg**. Harga jual normal **Rp 30.000**.
2. Masukkan Qty: **1 Kg**.
3. Di modal Edit/Tambah, berikan **Diskon Rp 15.000** (sehingga pelanggan hanya bayar Rp 15.000). 
   *Secara logika, toko RUGI Rp 5.000 (Modal 20rb, jual 15rb).*
4. Simpan & Bayar transaksi.
5. Cek tabel `pos_details` atau Laporan Laba Rugi untuk transaksi tersebut.
6. **HASIL BUG:** Sistem akan mencatat Profit (`exp`) sebesar **Rp +10.000**. 
   *Kenapa? Karena sistem menghitung: `30.000 (Harga Normal) - 20.000 (Modal) = 10.000`.* Diskon 15.000 yang diberikan kasir dianggap tidak pernah ada dalam perhitungan Laba.

---

## 007. Bug Diskon Item Hilang / Menguap Jika Ada Diskon Global (Sesuai Temuan Validasi)

**Lokasi Kode:** `PosController.php` (baris 731)  
**Masalah:** Seperti yang Anda temukan saat memvalidasi, **diskon per item tiba-tiba hilang** dan yang tercatat hanya diskon transaksi (global). Hal ini terjadi karena pada saat sistem menyimpan data barang ke tabel `pos_details`, kolom `price_after_discount` (harga setelah diskon) dihitung dengan rumus yang salah:

```php
'price_after_discount' => $item['price'] - $posDiscount,
```
*(di mana `$posDiscount` adalah porsi diskon global untuk item tersebut)*

**Sistem sama sekali tidak mengikutsertakan diskon item (`$item['discount']`) ke dalam perhitungan harga bersih!** 
Akibatnya, diskon item yang susah payah diinput oleh kasir akan "ditimpa" dan dianggap hilang di database (maupun di struk cetak), dan harga item akan kembali ke harga normal (hanya dipotong diskon global saja).

### 🖥️ Cara Validasi / Buktikan Sendiri:
1. Buka Kasir.
2. Tambahkan **Item A**: Rp 100.000, Qty 1. Berikan **Diskon Item: Rp 20.000**.
3. Di bagian Rincian Total bawah, masukkan **Diskon Global: Rp 10.000**.
4. Simpan transaksi dan cetak struk (atau lihat list detail transaksi).
5. **HASIL BUG:** Sistem menganggap Item A hanya mendapatkan diskon Rp 10.000 (dari global). Diskon Rp 20.000 yang Anda berikan di awal sepenuhnya **hilang menguap** dari harga item!

---

## 008. Celah Keamanan (Security Flaw): Total Transaksi Dipercaya 100% dari Frontend

**Lokasi Kode:** `PosController.php` (baris 680)  
**Masalah:** Backend menyimpan kolom `subtotal` dan `total` langsung dari nilai yang dikirimkan oleh Frontend (`$data['total']`). Backend **tidak pernah menghitung ulang** (Qty × Harga) + Ongkir - Diskon. 

### 🖥️ Cara Validasi / Buktikan Sendiri (Via Inspect Element / API Postman):
1. Buka Kasir. Belanja normal sebesar **Rp 5.000.000**.
2. Klik kanan di browser > Pilih **Inspect Element** > Tab **Network** (Jaringan).
3. Matikan koneksi internet sesaat (atau gunakan Postman).
4. Ubah payload JSON yang dikirimkan dari frontend (misalnya menggunakan *intercept*), ubah field `"total": 5000000` menjadi `"total": 1`.
5. Lepaskan ke backend.
6. **HASIL BUG:** Sistem backend menelan data tersebut bulat-bulat dan menyimpan transaksi senilai **Rp 1**, meskipun rincian item di bawahnya bernilai jutaan rupiah. Tidak ada validasi tolak-ukur sama sekali di sisi backend.


## 005. Bug `price_after_discount` Menjadi Minus (Minus Puluhan Ribu) & Mengabaikan Diskon Item

**Lokasi Kode:** `PosController.php` (baris 731)  
**Masalah:** Backend mengurangi Harga Satuan (`price`) langsung dengan Total Diskon Global Item (`posDiscount`). Padahal Diskon Global itu berlaku untuk *keseluruhan Qty*, bukan per 1 kg! Parahnya lagi, sistem mengabaikan Diskon Item yang diset di keranjang.

### 🖥️ Cara Validasi / Buktikan Sendiri:
1. Masuk ke halaman Kasir (Penjualan).
2. Tambahkan **10 Kg Anggur** dengan harga **Rp 30.000 / Kg**. (Subtotal: 300.000)
3. Jangan beri diskon item di keranjang.
4. Di bagian bawah kasir (Rincian Total), masukkan **Diskon Global: Rp 50.000**.
5. Simpan & Bayar transaksi tersebut.
6. Buka database (atau PhpMyAdmin), lihat tabel `pos_details` untuk transaksi barusan.
7. **HASIL BUG:** Kolom `price_after_discount` akan tercatat **-20.000** (Negatif 20 ribu!). 
   *Kenapa? Karena backend menghitung: `30.000 (Harga Satuan) - 50.000 (Total Diskon Global) = -20.000`.*

---

## 006. Bug Laporan Laba Rugi Fiktif (Profit Dihitung dari Harga Sebelum Diskon)

**Lokasi Kode:** `PosController.php` (baris 732)  
**Masalah:** Perhitungan Margin / Profit (`exp`) dikalkulasi dengan rumus: Harga Asli dikurangi HPP (Modal). Sistem **sama sekali tidak memotong diskon** dalam menghitung profit!

### 🖥️ Cara Validasi / Buktikan Sendiri:
1. Buka Kasir. Pilih buah yang HPP-nya (Modalnya) misal **Rp 20.000 / Kg**. Harga jual normal **Rp 30.000**.
2. Masukkan Qty: **1 Kg**.
3. Di modal Edit/Tambah, berikan **Diskon Rp 15.000** (sehingga pelanggan hanya bayar Rp 15.000). 
   *Secara logika, toko RUGI Rp 5.000 (Modal 20rb, jual 15rb).*
4. Simpan & Bayar transaksi.
5. Cek tabel `pos_details` atau Laporan Laba Rugi untuk transaksi tersebut.
6. **HASIL BUG:** Sistem akan mencatat Profit (`exp`) sebesar **Rp +10.000**. 
   *Kenapa? Karena sistem menghitung: `30.000 (Harga Normal) - 20.000 (Modal) = 10.000`.* Diskon 15.000 yang diberikan kasir dianggap tidak pernah ada dalam perhitungan Laba.

---

## 007. Bug Diskon Item Hilang / Menguap Jika Ada Diskon Global (Sesuai Temuan Validasi)

**Lokasi Kode:** `PosController.php` (baris 731)  
**Masalah:** Seperti yang Anda temukan saat memvalidasi, **diskon per item tiba-tiba hilang** dan yang tercatat hanya diskon transaksi (global). Hal ini terjadi karena pada saat sistem menyimpan data barang ke tabel `pos_details`, kolom `price_after_discount` (harga setelah diskon) dihitung dengan rumus yang salah:

```php
'price_after_discount' => $item['price'] - $posDiscount,
```
*(di mana `$posDiscount` adalah porsi diskon global untuk item tersebut)*

**Sistem sama sekali tidak mengikutsertakan diskon item (`$item['discount']`) ke dalam perhitungan harga bersih!** 
Akibatnya, diskon item yang susah payah diinput oleh kasir akan "ditimpa" dan dianggap hilang di database (maupun di struk cetak), dan harga item akan kembali ke harga normal (hanya dipotong diskon global saja).

### 🖥️ Cara Validasi / Buktikan Sendiri:
1. Buka Kasir.
2. Tambahkan **Item A**: Rp 100.000, Qty 1. Berikan **Diskon Item: Rp 20.000**.
3. Di bagian Rincian Total bawah, masukkan **Diskon Global: Rp 10.000**.
4. Simpan transaksi dan cetak struk (atau lihat list detail transaksi).
5. **HASIL BUG:** Sistem menganggap Item A hanya mendapatkan diskon Rp 10.000 (dari global). Diskon Rp 20.000 yang Anda berikan di awal sepenuhnya **hilang menguap** dari harga item!

---

## 008. Celah Keamanan (Security Flaw): Total Transaksi Dipercaya 100% dari Frontend

**Lokasi Kode:** `PosController.php` (baris 680)  
**Masalah:** Backend menyimpan kolom `subtotal` dan `total` langsung dari nilai yang dikirimkan oleh Frontend (`$data['total']`). Backend **tidak pernah menghitung ulang** (Qty × Harga) + Ongkir - Diskon. 

### 🖥️ Cara Validasi / Buktikan Sendiri (Via Inspect Element / API Postman):
1. Buka Kasir. Belanja normal sebesar **Rp 5.000.000**.
2. Klik kanan di browser > Pilih **Inspect Element** > Tab **Network** (Jaringan).
3. Matikan koneksi internet sesaat (atau gunakan Postman).
4. Ubah payload JSON yang dikirimkan dari frontend (misalnya menggunakan *intercept*), ubah field `"total": 5000000` menjadi `"total": 1`.
5. Lepaskan ke backend.
6. **HASIL BUG:** Sistem backend menelan data tersebut bulat-bulat dan menyimpan transaksi senilai **Rp 1**, meskipun rincian item di bawahnya bernilai jutaan rupiah. Tidak ada validasi tolak-ukur sama sekali di sisi backend.


## 009. Bug *Infinity* Qty (Sistem *Crash* Akibat Pembagian Nol)

**Lokasi Kode:** `js-buah.blade.php` (Fungsi `updateQtyFromAddTotal`)  
**Masalah:** Saat kasir mengubah "Jumlah Harga", sistem otomatis menghitung ulang Qty dengan rumus `Qty = Jumlah Harga / Harga Setelah Diskon`. Namun, sistem **tidak mengecek** jika *Harga Setelah Diskon* bernilai 0. Pembagian dengan angka 0 pada JavaScript akan menghasilkan nilai tak terhingga (`Infinity`).

### 🖥️ Cara Validasi / Buktikan Sendiri:
1. Buka Kasir, klik **Tambah Produk**.
2. Pilih buah apa saja (misal Anggur dengan harga **Rp 30.000**).
3. Di kolom Diskon, masukkan **Diskon Nominal Rp 30.000** (sama dengan harga barang, artinya digratiskan 100%).
4. Pada titik ini, *Harga Setelah Diskon* menjadi **Rp 0**.
5. Sekarang, ketik angka apa saja di kolom **"Jumlah Harga"** (misal ketik `50000`).
6. **HASIL BUG:** Kolom Qty akan langsung terisi dengan teks **`Infinity`**! Jika Anda klik "Simpan", item dengan Qty tak terhingga ini akan masuk ke keranjang dan bisa menyebabkan *error fatal* saat dikirim ke backend.

---

## 010. Bug Total Minus / Harga Defisit (Toko "Berutang" pada Pembeli)

**Lokasi Kode:** `js-buah.blade.php` (Fungsi `updateAddTotalFromQty` & `updateTotalFromEditQty`)  
**Masalah:** Sistem sama sekali tidak memberikan batasan maksimal untuk Diskon Nominal. Kasir bebas memberikan diskon *per unit* yang jauh lebih besar daripada harga asli barang tersebut, dan sistem tidak menggunakan pengaman `Math.max(0, ...)` pada total item.

### 🖥️ Cara Validasi / Buktikan Sendiri:
1. Buka Kasir, klik **Tambah Produk**.
2. Pilih buah dengan harga murah (misal Jeruk **Rp 15.000 / Kg**).
3. Masukkan Qty: **1 Kg**.
4. Di kolom Diskon, masukkan angka **Rp 50.000** (jauh melebihi harga buah).
5. **HASIL BUG:** Kolom "Jumlah Harga" (Subtotal) akan langsung menampilkan **Rp -35.000** (Minus!). 
6. Jika Anda klik "Simpan" ke keranjang, dan menambahkan belanjaan lain senilai Rp 50.000, Total Bayar di kasir hanya akan menjadi Rp 15.000 karena disubsidi oleh minus tadi. Artinya, alih-alih pembeli bayar penuh, toko malah seolah-olah berutang pada pembeli!


## 011. Bug Parcel Subtotal Mengabaikan Qty (Hanya Dihitung 1)

> **Akar Masalah Laporan:** Kasir yang menginput pembelian Parcel dengan Qty lebih dari 1 akan mendapatkan pencatatan Subtotal yang salah (hanya harga 1 Parcel) di database detail transaksi.

**Modul :** Transaksi (Backend)  
**Lokasi Kode:** `PosController.php` (Fungsi `saveTransaction`, Baris 788-796)  

### 🖥️ Simulasi Step-by-Step

1. Kasir masuk ke tab/modal Parcel.
2. Kasir membuat Parcel dengan harga total Rp 100.000.
3. Kasir menginput **Qty = 5** (Pelanggan beli 5 parcel yang sama).
4. Di UI total transaksi sudah benar Rp 500.000 dan kasir melakukan pembayaran.

### 💾 Dampak di Backend (Bug!)

Saat menyimpan data ke database tabel `pos_transaction_detail`, sistem memang mencatat *Quantity = 5*. **TETAPI**, sistem merekam nilai *Subtotal* secara statis dengan hanya mengambil nilai harga 1 parcel (`'subtotal' => $product->price`). 
Akibatnya: Laporan rincian penjualan per item akan korup, karena nilai `Qty x Harga` tidak sama dengan Subtotal yang dicatat!

---

<br>

## 012. Biaya Jasa Parcel (Fee) Hilang dan Tidak Tersimpan di Detail Transaksi

> **Akar Masalah Laporan:** Saat kasir membuat parcel, Biaya Jasa (Fee) yang dimasukkan menguap begitu saja dan tidak tersimpan di riwayat transaksi.

**Modul :** Transaksi (Backend)  
**Lokasi Kode:** `PosController.php` (Fungsi `saveTransaction`, Baris 774-804)  

### 🖥️ Simulasi Step-by-Step

1. Kasir merakit Parcel dan menetapkan **Biaya Jasa (Fee) = Rp 15.000**.
2. Kasir menekan simpan dan checkout pesanan.
3. Data terkirim ke backend termasuk data `fee`.

### 💾 Dampak di Backend (Bug!)

Saat backend menyimpan data detail parcel (`PosDetailModel::insert`), kolom untuk merekam `biaya_jasa / fee` sama sekali **tidak ada / diabaikan**. Sistem hanya menyimpan *harga kemasan* dan *harga total produk*. 
Lebih parahnya lagi, di pembuatan produk *master* parcel (`Product::firstOrCreate`), nilai *Fee* yang baru hanya akan disimpan jika Parcel tersebut belum pernah dibuat. Jika nama parcel sudah ada (karena harganya sama dengan transaksi lama), *fee* transaksi saat ini tidak akan disimpan sama sekali!
Dampak: Laporan laba/rugi tidak bisa memisahkan mana yang pendapatan dari barang dan mana yang pendapatan dari jasa merakit parcel.

---

<br>

## 013. Subsidi Silang Terselubung via Diskon Ongkir (Ongkir Minus)

> **Akar Masalah Laporan:** Kasir bisa memanipulasi total harga belanjaan buah dengan mengeksploitasi fitur Diskon Ongkir tanpa batas.

**Modul :** Transaksi  
**Lokasi Kode :** `js-buah.blade.php` (Fungsi `totalHargaKeseluruhan`) & `PosController.php` (Validasi)

### 🖥️ Simulasi Step-by-Step

1. Kasir memproses keranjang belanja (misalnya subtotal Rp 100.000).
2. Kasir menambahkan **Ongkir sebesar Rp 10.000**.
3. Di kolom **Diskon Ongkir**, kasir memasukkan nominal ekstrem, misal **Rp 50.000**.
4. Sistem menghitung Total Ongkir = `10.000 - 50.000 = -40.000` (Minus!).

### 💾 Dampak di Backend (Bug!)

Angka minus ini kemudian ditambahkan ke total belanjaan utama. Bukannya sistem membatasi diskon ongkir maksimal harus seharga ongkir (Rp 10.000), sistem malah membiarkan minus tersebut terjadi. 
Akibatnya, Total Belanja Buah yang tadinya Rp 100.000 dikurangi subsidi minus Rp 40.000, sehingga tagihan total menjadi **Rp 60.000**. Toko jualan buah merugi!

---

<br>

## 014. Server Mati / Error 500 (Division By Zero) Jika Qty Dikosongkan

> **Akar Masalah Laporan:** Jika kasir iseng menghapus kolom Qty sampai kosong (terutama pada transaksi yang diedit ulang), aplikasi akan error (Layar Blank / Loading Terus) saat tombol Simpan ditekan, karena sistem "meledak" saat menghitung diskon.

**Modul :** Transaksi (Backend)  
**Lokasi Kode:** `PosController.php` (Baris 729-732)  

### 🖥️ Simulasi Step-by-Step

1. Kasir login dan membuat transaksi produk buah seperti biasa, lalu menyelesaikannya dengan menekan **Simpan/Bayar** (transaksi berhasil tersimpan).
2. Dari menu daftar transaksi, kasir mencari transaksi tersebut lalu mengklik tombol **Edit**, sehingga kasir kembali masuk ke layar POS (Point of Sales).
3. Kasir mengklik produk buah tersebut di dalam keranjang belanja sebelah kiri untuk memunculkan **Modal Edit Produk**.
4. Di dalam form Edit tersebut, kasir **menghapus seluruh angka** pada kolom **Qty** menggunakan tombol *backspace* (sehingga kotaknya benar-benar kosong / *null*).
5. Kasir mengklik tombol **Simpan** di modal tersebut. Item di keranjang sekarang tertulis Qty kosong.
6. Kasir menekan tombol **Bayar / Simpan Transaksi Utama** untuk memperbarui (*update*) transaksi tersebut ke database.
7. **HASIL BUG:** Layar kasir akan terus *loading* tanpa henti atau mendadak muncul peringatan **Error 500**. Transaksi gagal ter-*update*.

### 💾 Dampak di Backend (Bug!)

Dalam matematika komputer, **angka tidak bisa dibagi dengan 0**. 
Karena sistem membiarkan Qty kosong dikirim dari layar kasir, Qty tersebut otomatis dianggap bernilai **0** oleh *database*. Kemudian, pada tahap akhir penyimpanan/update, sistem mencoba mencari harga asli setelah diskon dengan rumus: `Harga - (Diskon / Qty)`.
Karena sistem mencoba membagi nilai diskon dengan Qty yang nilainya 0, server langsung mengalami "konslet" (Fatal Error: *Division By Zero*). Ini menyebabkan server gagal merespon, sistem *crash*, dan keseluruhan proses dibatalkan paksa.


<br>

## 015. 🔴 [KRITIS] Vulnerabilitas Manipulasi Total Tagihan (Backend Mempercayai Payload Buta)

> **Masalah Keamanan:** Ini adalah celah keamanan yang sangat berbahaya. Backend sama sekali tidak menghitung ulang total belanjaan berdasarkan harga barang dan kuantitas, melainkan **percaya 100%** pada angka Total yang dikirimkan oleh browser (frontend).

**Modul :** Transaksi (Backend)  
**Lokasi Kode:** `PosController.php` (Fungsi `saveTransaction`, Baris 679-680)  

### 🖥️ Simulasi Step-by-Step (Skenario Peretas)
1. Seseorang (kasir iseng atau pembeli jika aplikasi ini terekspos ke publik) memasukkan **Anggur senilai Rp 5.000.000** ke keranjang kasir.
2. Saat menekan tombol Bayar, ia menahan koneksi internet atau menggunakan aplikasi sejenis *Inspect Element / Postman* untuk mencegat data (*payload*) yang dikirim ke server.
3. Ia mengubah data JSON pada variabel `total` dan `subtotal` secara paksa menjadi **Rp 100**.
4. Ia meneruskan *request* modifikasi tersebut ke server.

### 💾 Dampak di Backend (Bug!)
Pada `PosController.php`, kode hanya menjalankan perintah:
```php
'subtotal' => $data['subtotal'],
'total'    => $data['total'],
```
Tanpa ada pengecekan ulang! Backend akan menyimpan transaksi senilai Rp 100 dan menganggapnya valid. Jika penipu membayar Rp 100, transaksi akan langsung berstatus Lunas (`paid`), dan stok anggur senilai 5 Juta sukses keluar dari toko tanpa terdeteksi sebagai utang/kurang bayar! **Seharusnya backend wajib menghitung ulang total harga secara mandiri.**

<br>

## 016. 🔴 [KRITIS] Multi-Payment & Manipulasi Kembalian (Kasir Bisa Mencuri Kas)

> **Masalah Keamanan:** Sistem pembayaran membiarkan transaksi yang sudah lunas (Paid) untuk terus dibayar berulang-ulang, dan menghitung uang kembalian berdasarkan *akumulasi total semua pembayaran* dikurangi *tagihan*. Ini membuka celah pencurian uang tunai fisik di laci kasir.

**Modul :** Transaksi (Pembayaran)  
**Lokasi Kode:** `PosController.php` (Fungsi `savePayment`, Baris 361-375)  

### 🖥️ Simulasi Step-by-Step (Skenario Pencurian Kas)
1. Kasir melayani pembeli dengan tagihan jujur **Rp 40.000**. Pelanggan membayar **Rp 50.000**.
2. Kasir memproses pembayaran Rp 50.000 di sistem. Sistem mencatat transaksi LUNAS dengan Uang Kembalian (Return) **Rp 10.000**.
3. Pelanggan pergi. Kasir yang berniat curang membuka kembali transaksi yang sama dan **menambahkan pembayaran fiktif sebesar Rp 1.000**.
4. Sistem menerima pembayaran tersebut. Total pembayaran di database sekarang menjadi **Rp 51.000**.
5. Saat menghitung kembalian untuk pembayaran kedua, sistem memakai rumus: `(Total Pembayaran 51.000) - (Tagihan 40.000) = Rp 11.000`. Sistem merekam bahwa kasir memberikan kembalian Rp 11.000!
6. Kasir mengambil uang Rp 10.000 dari laci ke kantong pribadinya.

### 💾 Dampak di Backend (Bug!)
Saat *owner* mengecek laporan serah terima shift kasir:
- **Kas Masuk:** 50.000 + 1.000 = Rp 51.000
- **Kas Keluar (Kembalian):** 10.000 + 11.000 = Rp 21.000
- **Saldo di Laci:** Rp 30.000 (Tepat sama dengan sisa uang fisik di laci).

Laporan sistem 100% *balance/klop* dengan fisik uang, padahal stok barang senilai Rp 40.000 telah keluar dari toko! Toko secara diam-diam dirugikan sebesar Rp 10.000 per transaksi yang dimanipulasi, dan pencurian ini tidak terdeteksi oleh laporan kasir. **Seharusnya sistem menolak pembayaran baru untuk status yang sudah 'Paid', dan perhitungan kembalian tidak boleh diakumulasikan secara global.**

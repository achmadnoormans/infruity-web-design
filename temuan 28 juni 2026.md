# Catatan Temuan & Perbaikan (Bug Fixes)
**Tanggal**: 28 Juni 2026

Dokumen ini berisi daftar temuan *error/bug/lagging* pada aplikasi beserta solusi yang telah diterapkan maupun yang masih direncanakan. Dokumen ini dibuat se-sederhana mungkin agar mudah dipahami oleh siapa pun.

<br><br><br>

---

<br>

# 001. Form Pencarian Tambah Produk di POS Sangat Lambat (Nge-Lag/Hang)
**Modul :** Transaksi  
**Sub Modul :** Penjualan (POS)  
**Detail lokasi UI nya dimana :** Modal Tambah Produk (Dropdown Select2)  

**Masalahnya :**  
Penyebab utamanya adalah **"N+1 Query Problem" (Masalah pemanggilan database yang berlebihan)**:
1. **Tidak ada batas maksimal (Limit):** Ketika kasir mengetik sesuatu atau sekadar mengklik, aplikasi mencari semua produk di seluruh *database* tanpa batas. Jika ada ribuan produk, sistem akan mengambil semuanya sekaligus.
2. **Looping pencarian data di dalam data:** Untuk **SETIAP** produk yang ditemukan, aplikasi menjalankan perintah tambahan ke database secara terpisah untuk mengecek harga.
3. **Efek Domino:** Alih-alih hanya 1 pencarian, aplikasi secara brutal melakukan ribuan pencarian ke *database* secara bersamaan. Ini menyebabkan antrean panjang di *server database* dan *browser* menjadi macet.

**Alur kerja bug bisa dimunculkan :**  
Saat kasir mengklik tombol "Pilih Produk" di halaman *Tambah Transaksi* (POS), *browser* memakan waktu sangat lama (nge-hang) hingga *dropdown* terbuka.

**Dampak :**  
Tinggi (*HIGH*). Server kewalahan dan UI kasir macet total (*freeze*).

**Saran Solusi (✅ SUDAH DIEKSEKUSI):**  
1. **Menerapkan Batas Pencarian (Limit):** Menambahkan fungsi `->limit(50)`. Sekarang, aplikasi hanya akan mengambil maksimal 50 produk paling relevan.
2. **Menggabungkan Kueri (Optimasi Loop):** Menyederhanakan cara aplikasi mengecek harga terakhir dari yang membutuhkan 3 kali panggilan database per produk menjadi lebih efisien.
**Hasil Akhir:** Dropdown pemilihan produk di kasir (POS) sekarang muncul secara instan *(real-time)* tanpa ada lag.

<br><br><br>

---

<br>

# 002. Bug Reaktivitas UI (DOM vs State): Input Angka Bisa Diisi Huruf dan Simbol
**Modul :** Transaksi  
**Sub Modul :** Penjualan (POS)  
**Detail lokasi UI nya dimana :** Halaman Utama Transaksi (`Diskon Global`, `Biaya Pengiriman`, `Diskon Ongkir`) & Form Buah (`Diskon`)  

**Masalahnya :**  
Sistem mengalami *bug* reaktivitas (sinkronisasi) *out-of-sync* antara DOM dan State Alpine.js. Saat kasir mengetik huruf (misal "A") atau simbol (misal "-"), *script* di latar belakang memang membersihkannya menjadi angka 0, namun gagal memaksa layar untuk membuang huruf tersebut. Selain itu, fungsi pembersih angka bawaannya sangat agresif karena turut menghancurkan ketikan titik (`.`) atau koma (`,`) yang krusial untuk persentase desimal.

**Alur kerja bug bisa dimunculkan :**  
1. Kasir mengetik angka `100` pada kolom Diskon Global.
2. Kasir menekan tombol `A` atau `-` di akhir angka, menjadi `100A-`.
3. Di balik layar, JS membersihkan nilainya kembali menjadi `100`. Karena memori tetap `100` (tidak berubah), Alpine.js diam saja dan menolak me-render ulang layar.
4. **Dampak UI:** Elemen layar tetap menampilkan huruf/simbol secara visual, menipu mata kasir. Dan jika kasir mengetik desimal `2,5`, komanya terhapus dan angkanya hancur menjadi `25`.

**Dampak :**  
Tinggi (*HIGH*). Antarmuka terlihat rapuh dan membingungkan kasir. Kehilangan fleksibilitas untuk diskon desimal.

**Saran Solusi (⏳ RENCANA PERBAIKAN):**  
Mengubah seluruh fungsi `@input` terkait angka agar mengeksekusi `e.target.value = ...` secara eksplisit di dalam `$nextTick`, guna memaksa sinkronisasi pembersihan layar (*real-time sweep*). Serta memodifikasi regex menjadi `replace(/[^\d.]/g, '')` dan mengakomodasi koma desimal `replace(/,/g, '.')`.

<br><br><br>

---

<br>

# 003. Diskon Ongkir Menjadi Subsidi Silang Harga Barang
**Modul :** Transaksi  
**Sub Modul :** Penjualan (POS)  
**Detail lokasi UI nya dimana :** Halaman Utama Transaksi (Kolom `Diskon Ongkir`)  

**Masalahnya :**  
Pada kolom Diskon Ongkir, aturan *(Rp jika > 100, % jika ≤ 100)* berlaku. Kasir dapat memasukkan nominal diskon ongkir yang melebihi biaya ongkir itu sendiri karena tidak ada limit maksimal.

**Alur kerja bug bisa dimunculkan :**  
1. Kasir mengisi Biaya Pengiriman: **Rp 10.000**
2. Kasir mengisi Diskon Ongkir sebesar: **Rp 50.000**
3. Sistem menghitung: Total Ongkir = `10.000 - 50.000` = **Minus Rp 40.000**.
4. Bukannya menolak, sistem malah menggunakan sisa minus 40.000 ini untuk **memotong harga produk**. Ongkirnya seolah-olah menyubsidi harga barang belanjaan.

**Dampak :**  
Menengah (*MEDIUM*). Logika *billing* menjadi cacat, menyebabkan kerugian toko karena total transaksi berkurang.

**Saran Solusi (⏳ RENCANA PERBAIKAN):**  
Menambahkan validasi limit `Math.max(ongkir - diskonOngkir, 0)` di variabel `totalOngkir` di dalam `js-buah.blade.php` agar potongan ongkir tidak boleh membuat nominal total ongkir menjadi negatif.

<br><br><br>

---

<br>

# 004. Error Pembagian Nol (Infinity Qty) pada Kolom Jumlah Harga Produk
**Modul :** Transaksi  
**Sub Modul :** Penjualan (POS)  
**Detail lokasi UI nya dimana :** Modal Tambah Buah & Jus (Kolom Input "Jumlah Harga")  

**Masalahnya :**  
Fitur *auto-calculate* mengizinkan kasir menginput "Jumlah Harga", lalu sistem akan membaginya dengan harga satuan untuk mencari *Quantity* otomatis (`qty = inputTotal / priceAfterDiscount`).

**Alur kerja bug bisa dimunculkan :**  
1. Kasir memilih barang, lalu memberikan promo gratis dengan mengetik diskon **100** (diskon 100%).
2. Harga produk setelah diskon kini menjadi **Rp 0**.
3. Kasir lalu iseng mengetik angka apa saja (misal Rp 10.000) di kolom "Jumlah Harga".
4. *Script* membagi 10.000 dengan 0. Hasilnya *Infinity* / *NaN*, memecahkan (crash) memori state Alpine.js.

**Dampak :**  
Tinggi (*HIGH*). Antarmuka web bisa menjadi tidak responsif atau memunculkan *bug* visual berupa `NaN`.

**Saran Solusi (⏳ RENCANA PERBAIKAN):**  
Memberikan *attribute binding* dinamis `:readonly="priceAfterDiscount <= 0"` pada input "Jumlah Harga", sehingga otomatis redup/dinonaktifkan jika harganya gratis. Bug tertutup rapat tanpa perlu error pop-up.

<br><br><br>

---

<br>

# 005. Tidak Ada Validasi Batas Maksimal Kuantitas (Bypass Limit Stok) di Keranjang
**Modul :** Transaksi  
**Sub Modul :** Penjualan (POS)  
**Detail lokasi UI nya dimana :** Form Tambah Buah, Parcel, dan Jus (Semua input "Quantity")  

**Masalahnya :**  
Secara UI, tidak ada batasan atribut batas atas *stok maksimal* pada kolom *Quantity*. Sistem hanya menolak di *backend* saat proses penyimpanan tahap akhir.

**Alur kerja bug bisa dimunculkan :**  
1. Kasir mencari apel yang sisa stoknya hanya tersisa **2**.
2. Kasir langsung mengetik angka **50** di kolom *Quantity* produk.
3. UI mengizinkannya tanpa peringatan dan tetap menambahkan pesanan 50 Apel ke keranjang.
4. Kasir memproses transaksi panjang hingga akhir, dan baru ditolak oleh *Error 500* backend saat menekan "Simpan/Bayar".

**Dampak :**  
Menengah (*MEDIUM*). Sangat mengganggu UX antrean kasir karena membuang waktu.

**Saran Solusi (⏳ RENCANA PERBAIKAN):**  
Menyematkan batas maksimum `:max="addProduct.stock"` serta melakukan *auto-correction* via watch state: jika input melewati batas, sistem *secara real-time langsung mengubah angkanya kembali* ke stok maksimal disertai peringatan *Toast Notification* instan di pojok layar.

<br><br><br>

---

<br>

# 006. Input Tanggal Transaksi Bebas Tanpa Proteksi Rentang Usia (Date Boundary)
**Modul :** Transaksi  
**Sub Modul :** Penjualan (POS)  
**Detail lokasi UI nya dimana :** Form Kasir Utama (Kolom "Tanggal Transaksi" & "Jadwal Ongkir")  

**Masalahnya :**  
Input tanggal `<input type="date">` sama sekali tidak disematkan atribut pembatas rentang `min` dan `max`.

**Alur kerja bug bisa dimunculkan :**  
1. Kasir secara tidak sengaja (*typo*) menyentuh tahun di kalender (*Datepicker*).
2. Tahun berubah menjadi `0001` atau `2055`.
3. Kasir melanjutkan transaksi dan berhasil tersimpan.

**Dampak :**  
Rendah (*LOW*). Sangat berisiko merusak kurva validitas *Dashboard Analytics* (Laporan Penjualan) perusahaan.

**Saran Solusi (⏳ RENCANA PERBAIKAN):**  
Menambahkan atribut `min="{{ date('Y-m-d', strtotime('-7 days')) }}"` dan `max="{{ date('Y-m-d') }}"` pada HTML *form date*.

<br><br><br>

---

<br>

# 007. Select2 Produk Menampilkan Stok Habis Secara Default
**Modul :** Transaksi  
**Sub Modul :** Penjualan (POS)  
**Detail lokasi UI nya dimana :** Form Tambah Buah (Dropdown Select2 `#select_product`)  

**Masalahnya :**  
Saat *dropdown* diklik pertama kali (tanpa mengetik apapun), sistem memuat semua produk, termasuk yang stoknya habis (`<= 0`). Produk kosong ini memang tidak bisa diklik (*disabled*), namun memenuhi layar *default* kasir secara percuma.

**Alur kerja bug bisa dimunculkan :**  
Kasir membuka modal tambah buah dan langsung disajikan tumpukan daftar produk yang mayoritas stoknya sedang habis, mengganggu efisiensi pencarian manual.

**Dampak :**  
Menengah (*MEDIUM*). Mengurangi tingkat efisiensi (UX) navigasi kasir. Sesuai prosedur operasional yang baik, barang kosong harusnya disembunyikan kecuali benar-benar dicari.

**Saran Solusi (⏳ RENCANA PERBAIKAN):**  
Menyisipkan parameter deteksi (*conditional parameter*) di *Ajax Select2* pada `js-buah.blade.php` (misal: `hide_empty_stock: !params.term`) dan mengubah `ProductController` agar memfilter stok > 0 apabila parameter tersebut dikirimkan.

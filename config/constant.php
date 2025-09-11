<?php

return [

    'permissions' => [
        // USER
        'user' => [
            'label' => 'User',
            'actions' => [
                'index' => 'Lihat User',
                'create' => 'Buat User',
                'update' => 'Ubah User',
                'delete' => 'Hapus User',
            ],
        ],
        // ROLE
        'role' => [
            'label' => 'Role',
            'actions' => [
                'index' => 'Lihat Role',
                'create' => 'Buat Role',
                'update' => 'Ubah Role',
                'delete' => 'Hapus Role',
            ],
        ],
        // TIER
        'tier' => [
            'label' => 'Tier',
            'actions' => [
                'index' => 'Lihat Tier',
                'create' => 'Buat Tier',
                'update' => 'Ubah Tier',
                'delete' => 'Hapus Tier',
            ],
        ],
        // SETTING EXP
        'setting_exp' => [
            'label' => 'Setting Exp',
            'actions' => [
                'index' => 'Lihat Setting Exp',
                'create' => 'Buat Setting Exp',
                'update' => 'Ubah Setting Exp',
                'delete' => 'Hapus Setting Exp',
            ],
        ],
        // POINT SCHEDULE
        'point_schedule' => [
            'label' => 'Point Schedule',
            'actions' => [
                'index' => 'Lihat Point Schedule',
                'create' => 'Buat Point Schedule',
                'update' => 'Ubah Point Schedule',
                'delete' => 'Hapus Point Schedule',
            ],
        ],
        // DEPOSITO
        'deposito' => [
            'label' => 'Deposito',
            'actions' => [
                'index' => 'Lihat Depo',
                'create' => 'Buat Depo',
                'update' => 'Ubah Depo',
                'delete' => 'Hapus Depo',
            ],
        ],
        // campaign
        'campaign' => [
            'label' => 'Campaign',
            'actions' => [
                'index' => 'Lihat Campaign',
                'create' => 'Buat Campaign',
                'update' => 'Ubah Campaign',
                'delete' => 'Hapus Campaign',
            ],
        ],
        // customer-deposito
        'customer_depo' => [
            'label' => 'Customer Deposito',
            'actions' => [
                'index' => 'Lihat Customer Deposito',
            ],
        ],
        // products
        'products' => [
            'label' => 'Products',
            'actions' => [
                'index' => 'Lihat Products',
                'create' => 'Buat Products',
                'update' => 'Ubah Products',
                'delete' => 'Hapus Products',
            ],
        ],
        // category
        'category' => [
            'label' => 'Category',
            'actions' => [
                'index' => 'Lihat Category',
                'create' => 'Buat Category',
                'update' => 'Ubah Category',
                'delete' => 'Hapus Category',
            ],
        ],
        // unit
        'unit' => [
            'label' => 'Unit',
            'actions' => [
                'index' => 'Lihat Unit',
                'create' => 'Buat Unit',
                'update' => 'Ubah Unit',
                'delete' => 'Hapus Unit',
            ],
        ],
        // location
        'location' => [
            'label' => 'Location',
            'actions' => [
                'index' => 'Lihat Location',
                'create' => 'Buat Location',
                'update' => 'Ubah Location',
                'delete' => 'Hapus Location',
            ],
        ],
        // handling
        'handling' => [
            'label' => 'Penanganan',
            'actions' => [
                'index' => 'Lihat Penanganan',
                'create' => 'Buat Penanganan',
                'update' => 'Ubah Penanganan',
                'delete' => 'Hapus Penanganan',
            ],
        ],
        // department
        'department' => [
            'label' => 'Department',
            'actions' => [
                'index' => 'Lihat Department',
                'create' => 'Buat Department',
                'update' => 'Ubah Department',
                'delete' => 'Hapus Department',
            ],
        ],
        // position
        'position' => [
            'label' => 'Posisi',
            'actions' => [
                'index' => 'Lihat Posisi',
                'create' => 'Buat Posisi',
                'update' => 'Ubah Posisi',
                'delete' => 'Hapus Posisi',
            ],
        ],
        // supplier
        'supplier' => [
            'label' => 'Supplier',
            'actions' => [
                'index' => 'Lihat Supplier',
                'create' => 'Buat Supplier',
                'update' => 'Ubah Supplier',
                'delete' => 'Hapus Supplier',
            ],
        ],
        // customers
        'customer' => [
            'label' => 'Pelanggan',
            'actions' => [
                'index' => 'Lihat Pelanggan',
                'create' => 'Buat Pelanggan',
                'update' => 'Ubah Pelanggan',
                'delete' => 'Hapus Pelanggan',
            ],
        ],
        // staff
        'staff' => [
            'label' => 'Staff',
            'actions' => [
                'index' => 'Lihat Staff',
                'create' => 'Buat Staff',
                'update' => 'Ubah Staff',
                'delete' => 'Hapus Staff',
            ],
        ],
        // branch
        'branch' => [
            'label' => 'Cabang',
            'actions' => [
                'index' => 'Lihat Cabang',
                'create' => 'Buat Cabang',
                'update' => 'Ubah Cabang',
                'delete' => 'Hapus Cabang',
            ],
        ],
        // payment-method
        'payment-method' => [
            'label' => 'Metode Pembayaran',
            'actions' => [
                'index' => 'Lihat Metode Pembayaran',
                'create' => 'Buat Metode Pembayaran',
                'update' => 'Ubah Metode Pembayaran',
                'delete' => 'Hapus Metode Pembayaran',
            ],
        ],
        // account
        'account' => [
            'label' => 'Akun',
            'actions' => [
                'index' => 'Lihat Akun',
                'create' => 'Buat Akun',
                'update' => 'Ubah Akun',
                'delete' => 'Hapus Akun',
            ],
        ],
        // setting-nota
        'setting-nota' => [
            'label' => 'Setting Nota',
            'actions' => [
                'index' => 'Lihat Setting Nota',
                'create' => 'Buat Setting Nota',
                'update' => 'Ubah Setting Nota',
                'delete' => 'Hapus Setting Nota',
            ],
        ],
        // delivery-order
        'delivery-order' => [
            'label' => 'Delivery Order',
            'actions' => [
                'index' => 'Lihat Order',
            ],
        ],
        // report-transaction
        'report-transaction' => [
            'label' => 'Laporan Transaksi',
            'actions' => [
                'report-transaction' => 'Lihat Laporan Transaksi',
                'report-customer-transaction' => 'Lihat Laporan Transaksi Per Customer',
            ],
        ],
        // stock
        'product-stock' => [
            'label' => 'Stok',
            'actions' => [
                'index' => 'Lihat Stok',
                'show' => 'Buat Stok',
            ],
        ],
        // wholesale
        'wholesale' => [
            'label' => 'Kulak',
            'actions' => [
                'index' => 'Lihat Kulak',
                'create' => 'Buat Kulak',
                'update' => 'Ubah Kulak',
                'delete' => 'Hapus Kulak',
            ],
        ],
        // sortir
        'sortir' => [
            'label' => 'Sortir',
            'actions' => [
                'index' => 'Lihat Sortir',
                'save-stock' => 'Lakukan Sortir',
            ],
        ],
        // product-receipt
        'product-receipt' => [
            'label' => 'Resep Produk',
            'actions' => [
                'index' => 'Lihat Resep Produk',
                'create' => 'Buat Resep Produk',
                'update' => 'Ubah Resep Produk',
                'delete' => 'Hapus Resep Produk',
            ],
        ],
        // stock-out
        'stock-out' => [
            'label' => 'Stock Keluar',
            'actions' => [
                'index' => 'Lihat Stock Keluar',
                'create' => 'Buat Stock Keluar',
                'update' => 'Ubah Stock Keluar',
                'delete' => 'Hapus Stock Keluar',
            ],
        ],
        // stock-opname
        'stock-opname' => [
            'label' => 'Penyesuaian Stok',
            'actions' => [
                'index' => 'Lihat Penyesuaian Stok',
                'create' => 'Buat Penyesuaian Stok',
                'update' => 'Ubah Penyesuaian Stok',
                'delete' => 'Hapus Penyesuaian Stok',
            ],
        ],
        // stock-out-type
        'stock-out-type' => [
            'label' => 'Master Tipe Stock Keluar',
            'actions' => [
                'index' => 'Lihat Tipe Stock Keluar',
                'create' => 'Buat Tipe Stock Keluar',
                'update' => 'Ubah Tipe Stock Keluar',
                'delete' => 'Hapus Tipe Stock Keluar',
            ],
        ],
        // production
        'production' => [
            'label' => 'Produksi',
            'actions' => [
                'index' => 'Lihat Produksi',
                'create' => 'Buat Produksi',
                'update' => 'Ubah Produksi',
                'delete' => 'Hapus Produksi',
            ],
        ],
        // receipt
        'receipt' => [
            'label' => 'Resep',
            'actions' => [
                'index' => 'Lihat Resep',
                'create' => 'Buat Resep',
                'update' => 'Ubah Resep',
                'delete' => 'Hapus Resep',
            ],
        ],
        // parcel
        'parcel' => [
            'label' => 'Parcel',
            'actions' => [
                'index' => 'Lihat Parcel',
                'create' => 'Buat Parcel',
                'update' => 'Ubah Parcel',
                'delete' => 'Hapus Parcel',
            ],
        ],


        // POS
        'pos' => [
            'label' => 'POS',
            'actions' => [
                'index' => 'Lihat Transaksi',
                'create' => 'Buat Transaksi',
                'update' => 'Ubah Transaksi',
                'delete' => 'Hapus Transaksi',
                'save' => 'Simpan Transaksi',
                'add_payment' => 'Tambah Pembayaran',
                'remove_payment' => 'Hapus Pembayaran',
                'print_receipt' => 'Cetak Resi',
                'transaction_jus' => 'Transaksi Jus',
                'transaction_buah' => 'Transaksi Buah',
                'cek-nota' => 'Cek Nota',
                'cek-nota.draft' => 'Cek Nota Draft',
            ],
        ],
    ],

];
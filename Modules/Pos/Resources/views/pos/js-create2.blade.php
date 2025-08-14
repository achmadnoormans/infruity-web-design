@section('script')
    <script type="text/javascript">
        $('#customer_id').select2({
            placeholder: 'Pilih pelanggan',
            ajax: {
                url: '{{ route('customer.get-customer') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term
                }),
                processResults: data => {
                    const umum = {
                        id: '0',
                        name: 'Pelanggan Umum',
                        address: '-',
                        whatsapp: '-'
                    };
                    const results = data.map(item => ({
                        id: item.id,
                        name: item.name,
                        address: item.address,
                        whatsapp: item.whatsapp,
                        tier_id: item.tier_id,
                        tier_name: item.tier_name,
                        tier_style: item.tier_style || 'badge-light-secondary',
                        minimalPurchase: item.minimal_purchase || 0
                    }));
                    return {
                        results: [umum, ...results]
                    };
                }
            },
            templateResult: formatCustomerOption, // render dropdown list
            templateSelection: formatCustomerSelection // render selected item
        });

        $('#customer_id').on('select2:select', function(e) {
            const data = e.params.data;
            const tierId = data.tier_id || ''; // Pastikan Anda mengirimkan tier_id dari server jika dibutuhkan

            $('#tier_id').val(tierId); // Set ke input hidden
        });

        // Fungsi render untuk item di dropdown
        function formatCustomerOption(customer) {
            if (!customer.id) return customer.text;

            const name = customer.name ?? 'Pelanggan Umum';

            if (customer.id === '0') {
                return $(`<div style="font-size: 13px;"><strong>${name}</strong></div>`);
            }

            const whatsapp = customer.whatsapp || '-';
            const address = customer.address || '-';
            const tier_name = customer.tier_name || '-';
            const tier_id = parseInt(customer.tier_id || 0);
            const tierBadgeClass = customer.tier_style || 'badge-light-secondary'; // Ambil style dari data

            return $(`
                <div style="font-size: 13px; line-height: 1.4;">
                    <strong>${name}</strong>
                    <span class="text-muted d-block fs-7">${whatsapp}</span>
                    <span class="text-muted d-block fs-7">${address}</span>
                    <span class="badge ${tierBadgeClass} fs-7">${tier_name}</span>
                </div>
            `);
        }



        // Fungsi render untuk item terpilih
        function formatCustomerSelection(customer) {
            if (!customer.id) return customer.text;

            const name = customer.name ?? 'Pelanggan Umum';

            if (customer.id === '0') {
                return name;
            }

            const whatsapp = customer.whatsapp || '-';
            const tier_name = customer.tier_name || '-';
            return `${name} (${tier_name})`;
        }

        $('#customer_id').append(new Option('Pelanggan Umum', '0', true, true)).trigger('change');

        function posApp() {
            return {
                products: [],
                cart: [],
                parcel: [],

                // edit product
                editModal: false,
                editItem: null,
                editPrice: 0,
                editQty: 1,
                editTotal: 0,
                editDiscount: 0,
                editDiscountPercent: 0,
                editDiscountMode: 'nominal', // atau 'percent'
                editTotalFormatted: '',
                editProductName: '',
                editProductUnit: '',
                editTitle: 'Edit Product',
                minimalPurchase: 0,
                isShowGiftButton: false,

                // Add Product
                showAddModal: false,
                showGiftModal: false,
                showParcelModal: false,
                addProduct: {
                    id: null,
                    name: '',
                    unit: '',
                    price: 0,
                    hpp: 0,
                    discount: 0,
                    discountNominal: 0,
                    discountPercent: 0,
                    qty: 1
                },

                addParcel: {
                    id: null,
                    name: '',
                    harga: 0,
                    qty: 1
                },

                // Animation states
                badgeAnimation: false,
                priceAnimation: false,

                init() {
                    const self = this; // simpan konteks Alpine
                    $('#customer_id').on('select2:select', function(e) {
                        const data = e.params.data;
                        self.setMinimalPurchase(data.minimalPurchase || 0);
                    });
                },

                setMinimalPurchase(value) {
                    this.minimalPurchase = value;
                    console.log('Minimal Purchase set to:', this.minimalPurchase);
                },

                formatRupiah(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value);
                },


                get formattedPrice() {
                    return this.formatRupiah(this.modalTotalPrice);
                },

                set formattedPrice(val) {
                    const raw = val.replace(/\./g, '').replace(/[^0-9]/g, '');
                    this.modalTotalPrice = Number(raw || 0);
                    this.updateQtyFromTotal();
                },

                submitTransaction() {
                    if (!this.cart.length) {
                        alert('Keranjang masih kosong!');
                        return;
                    }

                    const customerId = document.getElementById('customer_id').value;
                    // if (!customerId) {
                    //     alert('Silakan pilih customer terlebih dahulu.');
                    //     return;
                    // }

                    const payload = {
                        customer_id: customerId,
                        items: this.cart.map(item => ({
                            product_id: item.id,
                            qty: item.qty,
                            price: item.price,
                            hpp: item.hpp,
                            discount: item.discount,
                            total_input: item.total_input
                        }))
                    };
                    fetch('/pos/submitTransaction', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log(data);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data berhasil disimpan.'
                                });
                                this.cart = []; // Kosongkan keranjang
                                document.getElementById('customer_id').value = '';
                                $('#customer_id').val(null).trigger('change');
                                window.location.href = '/pos/show/' + data.id;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan saat menyimpan data.'
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: err || 'Terjadi kesalahan saat menyimpan data.'
                            });
                        });
                },

                // Edit Product Section
                openEditModal(item) {
                    console.log('Opening edit modal for item:', item);
                    this.editItem = {
                        ...item
                    }; // salin data item
                    this.editProduct = item;
                    this.editProductName = item.name;
                    this.editProductUnit = item.unit;
                    this.editTitle = item.name;
                    this.editPrice = item.price;
                    this.editQty = item.qty;
                    this.editTotal = item.total_input || (item.qty * item.price);
                    this.editTotalFormatted = this.formatRupiah(this.editTotal);

                    this.editDiscount = item.discount || 0;


                    this.editModal = true;

                    setTimeout(() => {
                        const modal = new bootstrap.Modal(document.getElementById('editModal'));
                        modal.show();
                    }, 0);
                },

                updateEditTotalFormatted(e) {
                    const raw = e.target.value.replace(/[^\d]/g, '');
                    this.editTotal = Number(raw || 0);
                    this.editTotalFormatted = this.formatRupiah(this.editTotal);
                    this.updateQtyFromEditTotal(); // Sesuaikan qty berdasarkan harga
                },

                updateQtyFromEditTotal() {
                    if (this.editProduct && this.editProduct.price > 0) {
                        this.editQty = parseFloat((this.editTotal / this.editProduct.price).toFixed(2));
                    }
                },

                updateTotalFromEditQty() {
                    if (this.editProduct && this.editProduct.price > 0) {
                        this.editTotal = parseFloat((this.editQty * this.editProduct.price).toFixed(2));
                        this.editTotalFormatted = this.formatRupiah(this.editTotal);
                    }
                },
                calculateEditDiscountAmount() {
                    const val = parseFloat(this.editDiscount || 0);
                    console.log('Discount:', val);
                    if (val <= 100) {
                        let qty = parseFloat(this.editQty || 0);
                        let originalPrice = parseFloat(this.editPrice || 0);
                        let discount = parseFloat(this.editDiscount || 0);
                        let subtotal = qty * originalPrice;
                        return parseFloat(((subtotal || 0) * val / 100).toFixed(2)); // persen
                    } else {
                        return val; // nominal
                    }
                },
                // Update otomatis qty berdasarkan total
                updateEditQtyFromTotal() {
                    if (this.editItem && this.editItem.price > 0) {
                        this.editQty = parseFloat((this.editTotal / this.editItem.price).toFixed(2));
                    }
                },

                // Update total otomatis berdasarkan qty
                updateEditTotalFromQty() {
                    if (this.editItem) {
                        this.editTotal = parseFloat((this.editQty * this.editItem.price).toFixed(2));
                    }
                },

                updateEditDiscount() {
                    let qty = parseFloat(this.editQty || 0);
                    let originalPrice = parseFloat(this.editPrice || 0);
                    let discount = parseFloat(this.editDiscount || 0);

                    // Hitung total sebelum diskon
                    let subtotal = qty * originalPrice;

                    // Jika discount > 100 → anggap sebagai nominal (Rp)
                    // Jika ≤ 100 → anggap sebagai persen
                    let discountValue = discount > 100 ? discount : subtotal * (discount / 100);
                    let discountPercent = discount > 100 ? 0 : discount;

                    let totalAfterDiscount = subtotal - discountValue;

                    this.editTotalFormatted = this.formatRupiah(totalAfterDiscount);
                    this.editTotal = totalAfterDiscount;
                    this.editDiscountPercent = discountPercent;
                },

                saveEditToCart() {
                    const idx = this.cart.findIndex(i => i.id === this.editItem.id);
                    if (idx !== -1) {
                        const disc = this.calculateEditDiscountAmount();
                        this.cart[idx].qty = this.editQty;
                        this.cart[idx].total_input = this.editTotal;
                        this.cart[idx].discount = disc;
                        this.cart[idx].discountPercent = this.editDiscountPercent;
                    }
                    this.closeEditModal();
                },

                deleteFromCart() {
                    if (!this.editItem) return;

                    const index = this.cart.findIndex(item => item.id === this.editItem.id);
                    if (index !== -1) {
                        this.cart.splice(index, 1);
                        this.closeEditModal();
                    }
                },

                // End edit modal section

                closeEditModal() {
                    this.editModal = false;
                    const modalEl = document.getElementById('editModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                },

                // Add Product Section
                openAddModal() {
                    this.showAddModal = true;
                    setTimeout(() => {
                        const modal = new bootstrap.Modal(document.getElementById('addModal'));
                        modal.show();

                        // Init select2
                        $('#select_product').select2({
                            placeholder: 'Pilih produk',
                            dropdownParent: $('#addModal'),
                            ajax: {
                                url: '/ajax/listProduct', // ganti sesuai route
                                dataType: 'json',
                                delay: 250,
                                processResults: data => ({
                                    results: data.map(item => ({
                                        id: item.id,
                                        text: item.name,
                                        unit: item.unit,
                                        price: item.price,
                                        hpp: item.hpp,
                                    }))
                                })
                            }
                        }).on('select2:select', (e) => {
                            const data = e.params.data;
                            this.addProduct.id = data.id;
                            this.addProduct.name = data.text;
                            this.addProduct.unit = data.unit.abbreviation;
                            this.addProduct.price = data.price;
                            this.addProduct.hpp = data.hpp ?? 0;
                            subtotal = this.addProduct.qty * this.addProduct.price;
                            this.addProduct.formattedAddTotalInput = this.formatRupiah(this.addProduct
                                .total);
                            this.updateAddTotalFromQty();
                        });
                    }, 0);
                },
                closeAddModal() {
                    this.showAddModal = false;
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
                    if (modal) modal.hide();
                },
                updateAddTotalFromQty() {
                    const qty = parseFloat(this.addProduct.qty) || 0;
                    const price = parseFloat(this.addProduct.price) || 0;
                    const discount = parseFloat(this.addProduct.discount) || 0;
                    this.addProduct.total = (qty * price) - discount;
                    this.addProduct.formattedAddTotalInput = this.formatRupiah(this.addProduct.total);
                },
                updateQtyFromAddTotal(e) {
                    let raw = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
                    const inputTotal = parseFloat(raw || 0);
                    const price = parseFloat(this.addProduct.price || 1);
                    const discount = parseFloat(this.addProduct.discount || 0);

                    // Hitung qty dari total + diskon
                    const qty = (inputTotal + discount) / price;

                    this.addProduct.qty = parseFloat(qty.toFixed(2));
                    this.addProduct.total = inputTotal;
                    this.addProduct.formattedAddTotalInput = this.formatRupiah(inputTotal);
                },

                updateDiscountValue(e) {
                    let raw = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
                    let input = parseFloat(raw || 0);

                    const qty = parseFloat(this.addProduct.qty || 0);
                    const price = parseFloat(this.addProduct.price || 0);
                    const subtotal = qty * price;

                    // Cegah pembagian dengan 0
                    if (subtotal === 0) {
                        this.addProduct.discount = 0;
                        this.addProduct.discountNominal = 0;
                        return;
                    }

                    if (input > 100) {
                        // Input dianggap nominal rupiah
                        this.addProduct.discount = input;
                        this.addProduct.discountNominal = input;
                        this.addProduct.discountPercent = 0;
                    } else {
                        // Input dianggap persen
                        let diskonRupiah = (input / 100) * subtotal;
                        this.addProduct.discount = diskonRupiah;
                        this.addProduct.discountNominal = input;
                        this.addProduct.discountPercent = input;
                    }

                    // Update total setelah diskon
                    const totalAfterDiscount = subtotal - this.addProduct.discount;
                    this.addProduct.total = totalAfterDiscount;
                    this.addProduct.formattedAddTotalInput = this.formatRupiah(totalAfterDiscount);
                },
                formatRupiah(angka) {
                    return angka.toLocaleString('id-ID');
                },
                get formattedAddPrice() {
                    return this.formatRupiah(this.addProduct.price);
                },
                set formattedAddPrice(val) {
                    const raw = val.replace(/\./g, '').replace(/[^0-9]/g, '');
                    this.addProduct.price = Number(raw || 0);
                    this.updateAddTotalFromQty();
                },
                get formattedAddTotal() {
                    return this.formatRupiah(this.addProduct.qty * this.addProduct.price);
                },
                saveAddToCart() {
                    if (!this.addProduct.id) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Produk belum dipilih',
                            text: 'Silakan pilih produk terlebih dahulu.',
                        });
                        return;
                    }

                    const isExist = this.cart.some(item => item.id === this.addProduct.id);
                    if (isExist) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Produk sudah ditambahkan',
                            text: 'Produk ini sudah ada di keranjang.',
                        });
                        return;
                    }

                    const discount = Number(this.addProduct.discount || 0);
                    const total_input = this.addProduct.total;

                    this.cart.push({
                        id: this.addProduct.id,
                        name: this.addProduct.name,
                        price: this.addProduct.price,
                        hpp: this.addProduct.hpp,
                        qty: this.addProduct.qty,
                        unit: this.addProduct.unit,
                        discount: discount > 100 ? discount : (discount / 100) * total_input,
                        discountPercent: this.addProduct.discountPercent,
                        total_input: total_input,
                        typeProduct: 'product',
                    });

                    console.log('cart', this.cart);
                    this.resetAddForm();
                },

                resetAddForm() {
                    this.addProduct = {
                        id: null,
                        name: '',
                        unit: '',
                        price: 0,
                        hpp: 0,
                        discount: 0,
                        qty: 1
                    };
                    // Reset Select2 input juga
                    $('#select_product').val(null).trigger('change');
                },
                // End Add Product Section

                // Total
                totalProduk() {
                    return this.cart.reduce((sum, item) => sum + Number(item.qty), 0);
                },
                totalHargaKeseluruhan() {
                    return this.cart.reduce((sum, item) => {
                        const total = (item.total_input || (item.price * item.qty)) - (item.discount || 0);
                        return sum + total;
                    }, 0).toLocaleString('id-ID');
                },


                showCartModal: false, // di dalam return {...}
                openCartModal() {
                    this.showCartModal = true;
                    setTimeout(() => {
                        const modal = new bootstrap.Modal(document.getElementById('cartModal'));
                        modal.show();
                    }, 0);
                },

                closeCartModal() {
                    this.showCartModal = false;
                    const modalEl = document.getElementById('cartModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                },

                // Update quantity item di cart
                updateCartItemQty(itemId, newQty) {
                    if (newQty < 0.01) return; // Minimal qty 0.01

                    const item = this.cart.find(i => i.id === itemId);
                    if (item) {
                        const oldQty = item.qty;
                        item.qty = parseFloat(newQty);

                        // Recalculate total_input proportionally
                        if (item.total_input && oldQty > 0) {
                            const pricePerUnit = (item.total_input + item.discount) / oldQty;
                            item.total_input = (pricePerUnit * item.qty) - item.discount;
                        } else {
                            item.total_input = item.price * item.qty;
                        }
                    }
                },

                // Update discount item di cart
                updateCartItemDiscount(itemId, newDiscount) {
                    const item = this.cart.find(i => i.id === itemId);
                    if (item) {
                        item.discount = parseFloat(newDiscount) || 0;
                    }
                },

                // Increment quantity
                incrementQty(itemId) {
                    const item = this.cart.find(i => i.id === itemId);
                    if (item) {
                        this.updateCartItemQty(itemId, item.qty + 1);
                    }
                },

                // Decrement quantity
                decrementQty(itemId) {
                    const item = this.cart.find(i => i.id === itemId);
                    if (item && item.qty > 0.01) {
                        this.updateCartItemQty(itemId, Math.max(0.01, item.qty - 1));
                    }
                },

                // Method untuk menghitung total quantity (jumlah items)
                getTotalQuantity() {
                    return this.cart.reduce((total, item) => total + parseFloat(item.qty || 0), 0);
                },

                // Method untuk menghitung total price
                getTotalPrice() {
                    return this.cart.reduce((sum, item) => {
                        const itemTotal = (item.total_input || (item.price * item.qty)) - (item.discount || 0);
                        return sum + Math.max(0, itemTotal); // Pastikan tidak minus
                    }, 0);
                },

                // Method untuk rincian total
                diskonGlobal: 0,
                ongkirGlobal: 0,
                get subtotal() {
                    return this.cart.reduce((sum, item) => {
                        const total = (item.total_input || (item.price * item.qty) - (item.discount || 0));
                        return sum + total;
                    }, 0);
                },
                get totalHargaKeseluruhan() {
                    const diskon = this.diskonGlobal;
                    const ongkir = this.ongkirGlobal;

                    let totalSetelahDiskon = 0;
                    if (diskon <= 100) {
                        // Diskon persen
                        totalSetelahDiskon = this.subtotal - (this.subtotal * (diskon / 100));
                    } else {
                        // Diskon nominal
                        totalSetelahDiskon = this.subtotal - diskon;
                    }

                    this.checkGiftButton(totalSetelahDiskon);
                    // Ongkir harus selalu ditambahkan
                    return Math.max(totalSetelahDiskon + ongkir, 0);
                },
                updateDiskonGlobal(e) {
                    const val = parseFloat(e.target.value.replace(/[^\d]/g, '')) || 0;
                    this.diskonGlobal = val;
                },
                updateOngkirGlobal(e) {
                    const val = parseFloat(e.target.value.replace(/[^\d]/g, '')) || 0;
                    this.ongkirGlobal = val;
                },
                formatRupiah(number) {
                    number = parseFloat(number || 0);
                    return number.toLocaleString("id-ID");
                },

                // Action save Transaction
                saveTransaction() {
                    if (this.cart.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Keranjang kosong',
                            text: 'Silakan tambahkan produk terlebih dahulu!',
                        });
                        return;
                    }
                    const customerId = document.querySelector('select[name="customer_id"]').value;
                    const transactionDate = document.querySelector('input[name="date"]').value;
                    const invoiceNumber = document.querySelector('input[name="invoice_number"]').value;
                    const ongkirDate = document.querySelector('input[name="ongkir_date"]').value;
                    const ongkirTime = document.querySelector('input[name="ongkir_time"]').value;
                    const note = document.querySelector('textarea[name="note"]').value;

                    const data = {
                        customer_id: customerId,
                        date: transactionDate,
                        invoice_number: invoiceNumber,
                        items: this.cart,
                        subtotal: this.subtotal,
                        discount: this.diskonGlobal,
                        ongkir: this.ongkirGlobal,
                        ongkir_date: ongkirDate,
                        ongkir_time: ongkirTime,
                        total: this.totalHargaKeseluruhan,
                        status: 'draft',
                        note: note,
                    };

                    // Simulasi kirim ke server
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch('/pos/save-transaction', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data),
                        })
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Transaksi berhasil disimpan!',
                            });
                            // this.resetPOS(); // Reset cart dsb.
                            window.location.href = '/pos';
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menyimpan transaksi.',
                            });
                            console.error(err);
                        });
                },
                // Pembayaran 
                goToPayment(doneCallback) {
                    if (this.cart.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Keranjang kosong',
                            text: 'Silakan tambahkan produk terlebih dahulu!',
                        });
                        if (typeof doneCallback === 'function') doneCallback();
                        return;
                    }
                    const customerId = document.querySelector('select[name="customer_id"]').value;
                    const transactionDate = document.querySelector('input[name="date"]').value;
                    const invoiceNumber = document.querySelector('input[name="invoice_number"]').value;
                    const ongkirDate = document.querySelector('input[name="ongkir_date"]').value;
                    const ongkirTime = document.querySelector('input[name="ongkir_time"]').value;
                    const note = document.querySelector('textarea[name="note"]').value;

                    const data = {
                        customer_id: customerId,
                        date: transactionDate,
                        invoice_number: invoiceNumber,
                        items: this.cart,
                        parcel: this.parcel,
                        subtotal: this.subtotal,
                        discount: this.diskonGlobal,
                        ongkir: this.ongkirGlobal,
                        ongkir_date: ongkirDate,
                        ongkir_time: ongkirTime,
                        total: this.totalHargaKeseluruhan,
                        status: 'debt',
                        note: note,
                    };

                    // Simulasi kirim ke server
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch('/pos/save-transaction', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data),
                        })
                        .then(res => res.json())
                        .then(res => {
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Berhasil',
                            //     text: 'Transaksi berhasil disimpan!',
                            //     showConfirmButton: false,
                            //     timer: 1500
                            // });
                            // console.log(res);
                            // this.resetPOS(); // Reset cart dsb.
                            window.location.href = `/pos/payment/${res.transaksi_id}`;
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menyimpan transaksi.',
                            });
                            console.error(err);
                        });
                },

                resetPOS() {
                    this.cart = [];
                    this.diskonGlobal = 0;
                    this.ongkirGlobal = 0;
                    this.subtotal = 0;
                    this.totalHargaKeseluruhan = 0;
                },

                addCustomer() {
                    const modal = new bootstrap.Modal(document.getElementById('customerModal'));
                    modal.show();
                },

                saveCustomer() {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('customerModal'));
                    const name = document.querySelector('[x-model="customerName"]').value;
                    const phone = document.querySelector('[x-model="customerPhone"]').value;
                    const address = document.querySelector('[x-model="customerAddress"]').value;

                    // if (!name || !phone || !address) {
                    //     Swal.fire('Lengkapi data', 'Semua input wajib diisi.', 'warning');
                    //     return;
                    // }

                    if (!name) {
                        Swal.fire('Lengkapi data', 'Minimal Isi nama.', 'warning');
                        return;
                    }

                    fetch('/pos/customers', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                name,
                                phone,
                                address
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                modal.hide();
                                // Swal.fire('Berhasil', 'Customer berhasil ditambahkan.', 'success');

                                // Tambahkan ke Select2
                                const option = new Option(res.customer.name, res.customer.id, true, true);
                                $('#customer_id').append(option).trigger('change');
                            } else {
                                Swal.fire('Gagal', res.message ?? 'Gagal menyimpan customer.', 'error');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan.', 'error');
                        });
                },

                // Gift Modal
                openGiftModal() {
                    this.showGiftModal = true;
                    setTimeout(() => {
                        const modal = new bootstrap.Modal(document.getElementById('giftModal'));
                        modal.show();

                        // Init select2
                        $('#select_gift').select2({
                            placeholder: 'Pilih produk',
                            language: {
                                errorLoading: function() {
                                    return "Tidak ada Hadiah yang tersedia.";
                                }
                            },
                            dropdownParent: $('#giftModal'),
                            ajax: {
                                url: '/tier/get-gift/' + $('#tier_id').val(), // ganti sesuai route
                                dataType: 'json',
                                delay: 250,
                                processResults: data => ({
                                    results: data.map(item => ({
                                        id: item.id,
                                        text: item.name,
                                        unit: item.unit,
                                        price: item.price,
                                        hpp: item.hpp,
                                    }))
                                })
                            }
                        }).on('select2:select', (e) => {
                            const data = e.params.data;
                            this.addProduct.id = data.id;
                            this.addProduct.name = data.text;
                            this.addProduct.unit = data.unit.abbreviation;
                            this.addProduct.price = data.price;
                            this.addProduct.hpp = data.hpp ?? 0;
                            subtotal = this.addProduct.qty * this.addProduct.price;
                            this.addProduct.formattedAddTotalInput = this.formatRupiah(this.addProduct
                                .total);
                            this.updateAddTotalFromQty();
                        });
                    }, 0);
                },
                closeGiftModal() {
                    this.showGiftModal = false;
                    const modal = bootstrap.Modal.getInstance(document.getElementById('giftModal'));
                    if (modal) modal.hide();
                },
                saveGiftToCart() {
                    if (!this.addProduct.id) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Produk belum dipilih',
                            text: 'Silakan pilih produk terlebih dahulu.',
                        });
                        return;
                    }

                    const isExist = this.cart.some(item => item.id === this.addProduct.id);
                    if (isExist) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Produk sudah ditambahkan',
                            text: 'Produk ini sudah ada di keranjang.',
                        });
                        return;
                    }

                    const discount = Number(this.addProduct.discount || 0);
                    const total_input = this.addProduct.total;

                    this.cart.push({
                        id: this.addProduct.id,
                        name: this.addProduct.name,
                        price: 0,
                        hpp: this.addProduct.hpp,
                        qty: this.addProduct.qty,
                        unit: this.addProduct.unit,
                        discount: discount > 100 ? discount : (discount / 100) * total_input,
                        discountPercent: this.addProduct.discountPercent,
                        total_input: 0,
                        typeProduct: 'gift', // Tambahkan tipe produk
                    });

                    console.log('cart', this.cart);
                    this.resetAddForm();
                },

                checkGiftButton(total) {
                    console.log('minimalPurchase', this.minimalPurchase, 'total', total);
                    if (total > this.minimalPurchase) {
                        this.isShowGiftButton = true;
                    } else {
                        this.isShowGiftButton = false;
                    }
                },

                // Parcel Modal
                openParcelModal() {
                    this.showParcelModal = true;
                    setTimeout(() => {
                        const modal = new bootstrap.Modal(document.getElementById('parcelModal'));
                        modal.show();

                        // Init select2
                        $('#select_kemasan').select2({
                            placeholder: 'Pilih produk',
                            dropdownParent: $('#parcelModal'),
                            ajax: {
                                url: '/ajax/listProduct', // ganti sesuai route
                                dataType: 'json',
                                delay: 250,
                                processResults: data => ({
                                    results: data.map(item => ({
                                        id: item.id,
                                        text: item.name,
                                        unit: item.unit,
                                        price: item.price,
                                        hpp: item.hpp,
                                    }))
                                })
                            }
                        }).on('select2:select', (e) => {
                            const data = e.params.data;
                            this.addProduct.id = data.id;
                            this.addProduct.name = data.text;
                            this.addProduct.unit = data.unit.abbreviation;
                            this.addProduct.price = data.price;
                            this.addProduct.hpp = data.hpp ?? 0;
                            subtotal = this.addProduct.qty * this.addProduct.price;
                            this.addProduct.formattedAddTotalInput = this.formatRupiah(this.addProduct
                                .total);
                            this.updateAddTotalFromQty();
                        });
                    }, 0);
                },
                closeParcelModal() {
                    this.showParcelModal = false;
                    const modal = bootstrap.Modal.getInstance(document.getElementById('parcelModal'));
                    if (modal) modal.hide();
                },

                saveParcelToCart(index) {
                    let item = this.parcels[index];
                    console.log('Saving parcel to cart:', item);
                    if (!item.product) {
                        alert('Pilih parcel terlebih dahulu');
                        return;
                    }

                    // Akses posApp dari luar
                    let posAppInstance = document.querySelector('[x-ref=posApp]').__x.$data;

                    posAppInstance.cart.push({
                        id: item.product,
                        name: item.name,
                        qty: item.qty,
                        budget: item.price
                    });

                    this.removeParcel(index);
                },


            }
        }

        function parcelForm() {
            return {
                parcels: [],
                totalAll: 0,
                cart: [],
                qtyParcel: 1,
                budgetParcel: '',
                get totalAll() {
                    // kalau "Harga Jual" = budget per item, cukup jumlahkan price
                    return this.parcels.reduce((sum, p) => sum + (Number(p.price) || 0), 0);

                    // kalau maunya total = qty * hargaAsli per item, pakai ini:
                    // return this.parcels.reduce((sum, p) => sum + (Number(p.qty||0) * Number(p.priceAwal||0)), 0);
                },
                addParcel() {
                    this.parcels.push({
                        product: '',
                        name: '',
                        unit: '',
                        priceAwal: 0,
                        qty: 1,
                        price: 0,
                        priceFormatted: '',
                        hpp: 0
                    });
                    this.$nextTick(() => {
                        this.initSelect2();
                    });
                },
                removeParcel(index) {
                    this.parcels.splice(index, 1);
                },
                updatePrice(index) {
                    let raw = (this.parcels[index].priceFormatted || '').replace(/\D/g, '');
                    let hargaJualBaru = parseInt(raw);
                    if (isNaN(hargaJualBaru)) hargaJualBaru = 0;

                    this.parcels[index].price = hargaJualBaru;
                    this.parcels[index].priceFormatted = this.formatRupiah(hargaJualBaru);

                    let hargaAsli = parseInt(this.parcels[index].priceAwal || 0);
                    if (hargaAsli > 0) {
                        let qtyBaru = hargaJualBaru / hargaAsli;
                        this.parcels[index].qty = parseFloat(qtyBaru.toFixed(2));
                    }
                },
                updateFromQty(index) {
                    let hargaAsli = parseInt(this.parcels[index].priceAwal || 0);
                    if (hargaAsli > 0) {
                        let hargaBaru = this.parcels[index].qty * hargaAsli;
                        this.parcels[index].price = hargaBaru;
                        this.parcels[index].priceFormatted = this.formatRupiah(hargaBaru);
                    }
                },

                formatRupiah(angka) {
                    let num = parseInt(angka);
                    if (isNaN(num) || num < 0) num = 0;
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(num);
                },
                initSelect2() {
                    $('.parcel-select').select2({
                        placeholder: 'Pilih Parcel',
                        ajax: {
                            url: '/ajax/listProduct',
                            dataType: 'json',
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: data.map(item => ({
                                        id: item.id,
                                        text: item.name,
                                        unit: item.unit,
                                        price: item.price,
                                        hpp: item.hpp,
                                    }))
                                };
                            },
                            cache: true
                        }
                    }).on('select2:select', (e) => {
                        let index = $(e.target).data('index');
                        let data = e.params.data;
                        this.parcels[index].product = data.id;
                        this.parcels[index].name = data.text;
                        this.parcels[index].unit = data.unit;
                        this.parcels[index].priceAwal = data.price;
                        this.parcels[index].hpp = data.hpp;
                        this.parcels[index].price = data.price;
                        this.parcels[index].priceFormatted = this.formatRupiah(data.price);
                        this.parcels[index].qty = 1;
                    });
                },
                saveParcelToCart() {
                    const budget = document.getElementById('parcel_budget').value;
                    const qty = document.getElementById('parcel_qty').value;
                    const kemasan = $('#select_kemasan option:selected').text();
                    const parcel = {
                        id: 'parcel' + this.formatShortNumber(budget),
                        name: kemasan + '-' + this.formatShortNumber(budget),
                        price: parseInt(budget.replace(/\./g, ''), 10),
                        hpp: 0,
                        qty: qty,
                        unit: 'Parcel',
                        discount: 0,
                        discountPercent: 0,
                        total_input: 0,
                        typeProduct: 'parcel',
                    };
                    const posParcel = {
                        id: 'parcel' + this.formatShortNumber(budget),
                        data: this.parcels,
                    }

                    let posAppInstance = Alpine.$data(document.querySelector('[x-data="posApp()"]'));
                    posAppInstance.cart.push(parcel);
                    posAppInstance.parcel.push(posParcel);
                    document.getElementById('parcel_budget').value = '';
                    document.getElementById('parcel_qty').value = 1;
                    document.getElementById('parcel_jasa').value = '';
                    $('#select_kemasan').val(null).trigger('change');
                    this.parcels = [];

                    console.log("Cart sekarang:", posAppInstance.cart, posAppInstance.parcel);
                    // console.log('Parcel:', this.parcels, 'Product', parcel);
                    // this.parcels.forEach(item => {
                    //     console.log("Produk ID:", item.product);
                    //     console.log("Nama:", item.name);
                    //     console.log("Unit:", item.unit.name);
                    //     console.log("Harga Awal:", item.priceAwal);
                    //     console.log("Qty:", item.qty);
                    // });
                    // let budgetClean = parseInt(this.budgetParcel.replace(/[^0-9]/g, '')) || 0;

                    // posApp.cart.push({
                    //     id: Date.now(), // ID unik
                    //     name: 'Parcel',
                    //     qty: this.qtyParcel,
                    //     budget: budgetClean
                    // });

                    // this.closeAddModal();
                },

                formatShortNumber(num) {
                    num = parseInt(num.toString().replace(/\./g, '')) || 0; // hapus titik & jadi integer

                    if (num >= 1_000_000_000) {
                        return (num / 1_000_000_000).toFixed(1).replace(/\.0$/, '') + 'B';
                    } else if (num >= 1_000_000) {
                        return (num / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
                    } else if (num >= 1_000) {
                        return (num / 1_000).toFixed(1).replace(/\.0$/, '') + 'K';
                    }
                    return num.toString();
                },

            }
        }

        document.addEventListener('alpine:init', () => {
            window.mainCartInstance = Alpine.data('posApp', posApp);
            window.parcelFormInstance = Alpine.data('parcelForm', parcelForm);
        });
    </script>
@endsection

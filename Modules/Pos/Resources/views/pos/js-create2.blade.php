@section('script')
    <script type="text/javascript">
        $('#customer_id').select2({
            placeholder: 'Select a customer',
            ajax: {
                url: '{{ route('customer.get-customer') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term
                }),
                processResults: data => {
                    // Tambahkan pelanggan umum di atas
                    const umum = {
                        id: '0', // Gunakan string khusus jika perlu dibedakan
                        text: 'Pelanggan Umum'
                    };

                    const results = data.map(item => ({
                        id: item.id,
                        text: item.name
                    }));

                    return {
                        results: [umum, ...results]
                    };
                }
            }
        });

        $('#customer_id').append(new Option('Pelanggan Umum', '0', true, true)).trigger('change');

        function posApp() {
            return {
                products: [],
                cart: [],

                // edit product
                editModal: false,
                editItem: null,
                editQty: 1,
                editTotal: 0,
                editDiscount: 0,
                editDiscountMode: 'nominal', // atau 'percent'
                editTotalFormatted: '',
                editProductName: '',
                editProductUnit: '',
                editTitle: 'Edit Product',

                // Add Product
                showAddModal: false,
                addProduct: {
                    id: null,
                    name: '',
                    unit: '',
                    price: 0,
                    discount: 0,
                    discountNominal: 0,
                    qty: 1
                },

                // Animation states
                badgeAnimation: false,
                priceAnimation: false,

                init() {

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
                    if (val <= 100) {
                        return parseFloat(((this.editTotal || 0) * val / 100).toFixed(2)); // persen
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

                saveEditToCart() {
                    const idx = this.cart.findIndex(i => i.id === this.editItem.id);
                    if (idx !== -1) {
                        const disc = this.calculateEditDiscountAmount();
                        this.cart[idx].qty = this.editQty;
                        this.cart[idx].total_input = this.editTotal;
                        this.cart[idx].discount = disc;
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
                                        price: item.price
                                    }))
                                })
                            }
                        }).on('select2:select', (e) => {
                            const data = e.params.data;
                            this.addProduct.id = data.id;
                            this.addProduct.name = data.text;
                            this.addProduct.unit = data.unit.abbreviation;
                            this.addProduct.price = data.price;
                            this.addProduct.total = this.addProduct.qty * this.addProduct.price;
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
                    } else {
                        // Input dianggap persen
                        let diskonRupiah = (input / 100) * subtotal;
                        this.addProduct.discount = diskonRupiah;
                        this.addProduct.discountNominal = input;
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
                        qty: this.addProduct.qty,
                        unit: this.addProduct.unit,
                        discount: discount > 100 ? discount : (discount / 100) * total_input,
                        total_input: total_input
                    });

                    this.resetAddForm();
                },

                resetAddForm() {
                    this.addProduct = {
                        id: null,
                        name: '',
                        unit: '',
                        price: 0,
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
                get subtotal() {
                    return this.cart.reduce((sum, item) => {
                        const total = (item.total_input || item.price * item.qty) - (item.discount || 0);
                        return sum + total;
                    }, 0);
                },
                get totalHargaKeseluruhan() {
                    const diskon = this.diskonGlobal;
                    if (diskon <= 100) {
                        // As percent
                        return Math.round(this.subtotal - (this.subtotal * (diskon / 100)));
                    }
                    // As nominal
                    return Math.max(this.subtotal - diskon, 0);
                },
                updateDiskonGlobal(e) {
                    const val = parseFloat(e.target.value.replace(/[^\d]/g, '')) || 0;
                    this.diskonGlobal = val;
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

                    const data = {
                        customer_id: customerId,
                        date: transactionDate,
                        invoice_number: invoiceNumber,
                        items: this.cart,
                        subtotal: this.subtotal,
                        discount: this.diskonGlobal,
                        total: this.totalHargaKeseluruhan,
                        status: 'draft',
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
                goToPayment() {
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

                    const data = {
                        customer_id: customerId,
                        date: transactionDate,
                        invoice_number: invoiceNumber,
                        items: this.cart,
                        subtotal: this.subtotal,
                        discount: this.diskonGlobal,
                        total: this.totalHargaKeseluruhan,
                        status: 'debt',
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
                }


            }
        }
    </script>
@endsection

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
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                })
            }
        });


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
                    this.addProduct.total = qty * price;
                    this.addProduct.formattedAddTotalInput = this.formatRupiah(this.addProduct.total);
                },
                updateQtyFromAddTotal(e) {
                    let val = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
                    const total = parseFloat(val || 0);
                    const price = parseFloat(this.addProduct.price) || 1;

                    const qty = total / price;

                    this.addProduct.total = total;
                    this.addProduct.qty = parseFloat(qty.toFixed(2));
                    this.addProduct.formattedAddTotalInput = this.formatRupiah(total);
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

                // Method untuk animasi tambah ke keranjang
                addToCartWithAnimation(event, product) {
                    // 1. Animasi flying product
                    this.createFlyingAnimation(event, product);

                    // 2. Tambah ke keranjang
                    this.addToCart(product);

                    // 3. Animasi badge
                    this.animateBadges();

                    // 4. Animasi tombol produk
                    this.animateProductButton(product);

                    // 5. Show notification
                    this.showSuccessNotification(product);
                },

                // Animasi produk terbang ke keranjang
                createFlyingAnimation(event, product) {
                    const rect = event.currentTarget.getBoundingClientRect();

                    this.flyingProductName = product.name;
                    this.flyingProductStyle = `
                left: ${rect.left}px; 
                top: ${rect.top}px;
                z-index: 9999;
            `;

                    this.showFlyingProduct = true;

                    // Hide after animation
                    setTimeout(() => {
                        this.showFlyingProduct = false;
                    }, 800);
                },

                // Animasi badge
                animateBadges() {
                    // Animate quantity badge
                    this.badgeAnimation = true;
                    setTimeout(() => {
                        this.badgeAnimation = false;
                    }, 400);

                    // Animate price badge with delay
                    setTimeout(() => {
                        this.priceAnimation = true;
                        setTimeout(() => {
                            this.priceAnimation = false;
                        }, 500);
                    }, 200);
                },

                // Animasi tombol produk
                animateProductButton(product) {
                    // Set flag untuk animasi bounce
                    product.isAdding = true;

                    // Reset setelah animasi selesai
                    setTimeout(() => {
                        product.isAdding = false;
                    }, 300);
                },

                // Show success notification
                showSuccessNotification(product) {
                    this.notificationMessage = `${product.name} ditambahkan ke keranjang!`;
                    this.showNotification = true;

                    // Hide notification after 3 seconds
                    setTimeout(() => {
                        this.showNotification = false;
                    }, 3000);
                },
            }
        }
    </script>
@endsection

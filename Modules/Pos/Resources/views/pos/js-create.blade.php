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
                searchTerm: "",
                isLoading: false,

                showModal: false,
                modalProduct: null,
                modalQty: 1,
                modalTotalPrice: 0,
                inputMode: 'qty', // 'qty' atau 'price',
                modalDiscount: 0,
                discountMode: 'nominal', // atau 'percent'

                // Animation states
                badgeAnimation: false,
                priceAnimation: false,
                showNotification: false,
                notificationMessage: '',
                showFlyingProduct: false,
                flyingProductStyle: '',
                flyingProductName: '',

                init() {
                    this.isLoading = true;
                    fetch('/ajax/listProduct')
                        .then(res => res.json())
                        .then(data => {
                            this.products = data;
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },

                filteredProducts() {
                    if (this.searchTerm === "") return this.products;
                    return this.products.filter(p =>
                        p.name.toLowerCase().includes(this.searchTerm.toLowerCase())
                    );
                },

                openModal(product) {
                    this.modalProduct = product;
                    this.modalQty = 1;
                    this.modalTotalPrice = product.price;
                    this.inputMode = 'qty';
                    this.modalDiscount = 0;
                    this.discountMode = 'nominal';

                    this.showModal = true;

                    setTimeout(() => {
                        const modal = new bootstrap.Modal(document.getElementById('productModal'));
                        modal.show();
                    }, 0);
                },

                closeModal() {
                    this.showModal = false;
                    const modalEl = document.getElementById('productModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                },

                updateQtyFromTotal() {
                    if (this.inputMode !== 'price') return;
                    if (this.modalProduct && this.modalProduct.price > 0) {
                        this.modalQty = parseFloat((this.modalTotalPrice / this.modalProduct.price).toFixed(2));
                    }
                },

                updateTotalFromQty() {
                    if (this.inputMode !== 'qty') return;
                    if (this.modalProduct) {
                        this.modalTotalPrice = this.modalQty * this.modalProduct.price;
                    }
                },

                calculateDiscountAmount() {
                    if (this.discountMode === 'percent') {
                        return parseFloat(((this.modalTotalPrice || 0) * (this.modalDiscount || 0) / 100).toFixed(2));
                    } else {
                        return Number(this.modalDiscount || 0);
                    }
                },

                confirmAddToCart() {
                    const discount = this.calculateDiscountAmount();
                    const existing = this.cart.find(i => i.id === this.modalProduct.id);

                    const itemData = {
                        ...this.modalProduct,
                        // price: this.modalTotalPrice / this.modalQty, // harga per item
                        qty: this.modalQty,
                        discount: discount,
                        total_input: this.modalTotalPrice // harga total yang diinput user
                    };

                    if (existing) {
                        existing.qty += this.modalQty;
                        existing.discount += discount;
                        existing.total_input += this.modalTotalPrice;
                    } else {
                        this.cart.push(itemData);
                    }

                    this.closeModal();
                },

                addToCart(product) {
                    const existing = this.cart.find(i => i.id === product.id);
                    if (existing) {
                        existing.qty += 1;
                        existing.total_input += product.price; // tambahkan harga per unit ke total_input
                    } else {
                        this.cart.push({
                            ...product,
                            qty: 1,
                            discount: 0,
                            total_input: product.price // inisialisasi total_input = harga satuan
                        });
                    }
                },

                removeFromCart(id) {
                    this.cart = this.cart.filter(i => i.id !== id);
                },

                totalPrice() {
                    return this.cart.reduce((sum, item) => {
                        return sum + (item.price * item.qty - item.discount);
                    }, 0);
                },

                formatRupiah(value) {
                    return new Intl.NumberFormat('id-ID').format(Number(value));
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

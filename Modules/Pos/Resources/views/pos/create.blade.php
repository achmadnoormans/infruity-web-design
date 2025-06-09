@extends('template.root')

@section('content')
    <!--begin::Aside column-->
    <div class="w-100 flex-lg-row-auto mb-7 me-7 me-lg-10">
        <div class="d-flex flex-column gap-10">
            <!--begin::Input group-->
            <div class="fv-row">
                <!--begin::Label-->
                <label class="required form-label">Select Customer</label>
                <!--end::Label-->
                <!--begin::Editor-->
                <select class="form-select" id="customer_id" name="customer_id">
                    <option value="">Select Customer</option>
                </select>
                <!--end::Editor-->
            </div>
            <!--end::Input group-->
        </div>
        <br>
        <div x-data="posApp()" x-init="init()" id="list-product" style="height: 400px; overflow-y: auto;">
            <div class="mb-4">
                <input type="text" class="form-control" placeholder="Cari produk..." x-model="searchTerm">
            </div>
            <div class="mb-5">
                <template x-for="product in filteredProducts()" :key="product.id">
                    <button @click="addToCart(product)"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div class="card card-body mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1" x-text="product.name"></h5>
                                    <small class="text-muted">Rp <span
                                            x-text="product.price.toLocaleString()"></span></small>
                                </div>
                                <div>
                                    <i class="fa fa-plus"></i>
                                </div>
                            </div>
                        </div>
                    </button>
                </template>
            </div>

            <!-- Cart -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Keranjang</h5>
                </div>
                <div class="card-body">
                    <template x-if="cart.length === 0">
                        <p class="text-center text-muted">Belum ada produk di keranjang.</p>
                    </template>
                    <template x-for="item in cart" :key="item.id">
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong x-text="item.name"></strong>
                                <div class="small text-muted">
                                    Harga: Rp <span x-text="item.price.toLocaleString()"></span>
                                    | Qty: <input type="number" min="1"
                                        class="form-control d-inline-block w-auto ms-1" x-model.number="item.qty">
                                    | Diskon: <input type="number" min="0"
                                        class="form-control d-inline-block w-auto ms-1" x-model.number="item.discount">
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-danger" @click="removeFromCart(item.id)"><i
                                    class="fas fa-trash"></i> Hapus</button>
                        </div>
                    </template>
                    <div class="text-end mt-3">
                        <h5>Total: Rp <span x-text="totalPrice().toLocaleString()"></span></h5>
                        <button class="btn btn-success mt-2"><i class="fas fa-credit-card"></i> Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Aside column-->
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

                init() {
                    fetch('/ajax/listProduct')
                        .then(res => res.json())
                        .then(data => {
                            this.products = data;
                        });
                },

                filteredProducts() {
                    if (this.searchTerm === "") {
                        return this.products;
                    }
                    return this.products.filter(p =>
                        p.name.toLowerCase().includes(this.searchTerm.toLowerCase())
                    );
                },

                addToCart(product) {
                    const existing = this.cart.find(i => i.id === product.id);
                    if (existing) {
                        existing.qty += 1;
                    } else {
                        this.cart.push({
                            ...product,
                            qty: 1,
                            discount: 0
                        });
                    }
                },

                removeFromCart(id) {
                    this.cart = this.cart.filter(i => i.id !== id);
                },

                totalPrice() {
                    return this.cart.reduce((sum, item) => {
                        const itemTotal = (item.price * item.qty) - item.discount;
                        return sum + itemTotal;
                    }, 0);
                },
            }
        }
    </script>
@endsection
@endsection

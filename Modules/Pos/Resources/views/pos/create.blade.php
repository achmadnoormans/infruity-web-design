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
        <div x-data="posApp()" x-init="init()">
            <div class="mb-4">
                <input type="text" class="form-control" placeholder="Cari produk..." x-model="searchTerm">
            </div>
            <div x-show="isLoading" x-transition.opacity.duration.500ms class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat produk...</p>
            </div>
            <div class="mb-5" id="list-product" style="height: 400px; overflow-y: auto;">
                <template x-for="product in filteredProducts()" :key="product.id">
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div class="card card-body mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <button @click="addToCart(product)"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1" x-text="product.name"></h5>
                                        <small class="text-muted">Rp <span
                                                x-text="product.price.toLocaleString()"></span></small>
                                        <br>
                                        <span class="badge badge-light-info"
                                            x-text="product.get_stock.stock_available">Qty</span>
                                    </div>
                                </button>
                                <button @click="openModal(product)" class="btn">
                                    <div>
                                        <span>
                                            <i class="fas fa-plus-circle text-success fs-3x"></i>
                                        </span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
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
                                    | Total: Rp <span x-text="(item.total_input || 0).toLocaleString()"></span>
                                    | Qty: <input type="number" min="1"
                                        class="form-control d-inline-block w-auto ms-1" x-model.number="item.qty">
                                    | Diskon: <input type="number" min="0"
                                        class="form-control d-inline-block w-auto ms-1" x-model.number="item.discount">
                                </div>
                            </div>
                            <button class="btn btn-sm btn-danger" @click="removeFromCart(item.id)"><i
                                    class="fas fa-trash"></i></button>
                        </div>
                    </template>
                    <div class="text-end mt-3">
                        <h5>Total: Rp <span x-text="totalPrice().toLocaleString()"></span></h5>
                        <button class="btn btn-success mt-2" @click="submitTransaction()">
                            <i class="fas fa-credit-card"></i>
                            Checkout
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Produk -->
            <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true"
                x-show="showModal" style="display: none;" x-transition>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Produk</h5>
                            <button type="button" class="btn-close" @click="closeModal()"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong x-text="modalProduct?.name"></strong></p>

                            <!-- Mode Input -->
                            <div class="mb-3">
                                <label class="form-label">Mode Input</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="modeQty" value="qty"
                                        x-model="inputMode">
                                    <label class="form-check-label" for="modeQty">Qty to Price</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="modePrice" value="price"
                                        x-model="inputMode">
                                    <label class="form-check-label" for="modePrice">Price to Qty</label>
                                </div>
                            </div>

                            <!-- Harga Total -->
                            <div class="mb-3">
                                <label class="form-label">Harga Total</label>
                                <input type="text" class="form-control" x-model="formattedPrice"
                                    :disabled="inputMode !== 'price'">
                            </div>

                            <!-- Qty -->
                            <div class="mb-3">
                                <label class="form-label">Qty</label>
                                <input type="number" class="form-control" x-model.number="modalQty"
                                    @input="updateTotalFromQty()" min="0.01" step="0.01"
                                    :disabled="inputMode !== 'qty'">
                            </div>
                            <!-- Mode Diskon -->
                            <div class="mb-3">
                                <label class="form-label">Mode Diskon</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="diskonNominal" value="nominal"
                                        x-model="discountMode">
                                    <label class="form-check-label" for="diskonNominal">Rp</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="diskonPersen" value="percent"
                                        x-model="discountMode">
                                    <label class="form-check-label" for="diskonPersen">%</label>
                                </div>
                            </div>

                            <!-- Nilai Diskon -->
                            <div class="mb-3">
                                <label class="form-label">Diskon</label>
                                <input type="number" class="form-control" x-model.number="modalDiscount" min="0"
                                    step="0.01">
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Batal</button>
                            <button type="button" class="btn btn-primary" @click="confirmAddToCart()">Tambahkan</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!--end::Aside column-->
    @include('pos::pos.js-create')
@endsection

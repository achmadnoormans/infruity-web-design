@extends('template.root')

@section('content')
    <style>
        /* Animasi flying cart - produk terbang ke keranjang */
        @keyframes flyToCart {
            0% {
                transform: scale(1) translateX(0) translateY(0);
                opacity: 1;
            }

            50% {
                transform: scale(0.5) translateX(200px) translateY(-100px);
                opacity: 0.8;
            }

            100% {
                transform: scale(0.1) translateX(400px) translateY(-200px);
                opacity: 0;
            }
        }

        .fly-to-cart {
            animation: flyToCart 0.8s ease-in-out;
            pointer-events: none;
            z-index: 9999;
        }

        /* Animasi bounce untuk tombol */
        @keyframes bounceScale {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .bounce-add {
            animation: bounceScale 0.3s ease-in-out;
        }

        /* Animasi shake untuk badge */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .shake-badge {
            animation: shake 0.5s ease-in-out;
        }

        /* Animasi pulse untuk badge */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulse-badge {
            animation: pulse 0.4s ease-in-out;
        }

        /* Animasi success notification */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .notification-enter {
            animation: slideInRight 0.3s ease-out;
        }

        .notification-leave {
            animation: slideOutRight 0.3s ease-in;
        }

        /* Success notification style */
        .success-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            min-width: 200px;
        }
    </style>

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
                                <button @click="addToCartWithAnimation($event, product)"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                    :class="{ 'bounce-add': product.isAdding }">
                                    <div>
                                        <h5 class="mb-1" x-text="product.name"></h5>
                                        <small class="text-muted">Rp <span
                                                x-text="product.price.toLocaleString()"></span></small>
                                        <br>
                                        <span class="badge badge-light-info"
                                            x-text="product.get_stock?.stock_available ?? 0">Qty</span>
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

            {{-- <!-- Cart -->
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
            </div> --}}

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

            <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true"
                x-show="showCartModal" @keydown.escape.window="closeCartModal()" style="display: none;" x-transition>
                <div class="modal-dialog modal-lg modal-fullscreen-md-down">
                    <div class="modal-content h-100">
                        <div class="modal-header flex-shrink-0">
                            <h5 class="modal-title">Keranjang Belanja</h5>
                            <button type="button" class="btn-close" @click="closeCartModal()"></button>
                        </div>

                        <!-- Scrollable Body -->
                        <div class="modal-body flex-grow-1 overflow-auto p-0">
                            <div class="p-3">
                                <template x-if="cart.length === 0">
                                    <div class="text-center py-5">
                                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Keranjang kosong.</p>
                                    </div>
                                </template>

                                <div id="cart-items-container">
                                    <template x-for="(item, index) in cart" :key="item.id">
                                        <div class="card card-body mb-3 cart-item">
                                            <!-- Mobile Layout (Stack Vertically) -->
                                            <div class="d-block d-lg-none">
                                                <!-- Product Name & Price -->
                                                <div class="mb-2 pb-2 border-bottom">
                                                    <h6 class="mb-1 fw-bold" x-text="item.name"></h6>
                                                    <small class="text-muted">@ Rp <span
                                                            x-text="item.price.toLocaleString()"></span></small>
                                                </div>

                                                <!-- Quantity & Discount Row -->
                                                <div class="row mb-3">
                                                    <div class="col-7">
                                                        <label class="form-label small mb-1 fw-semibold">Quantity</label>
                                                        <div class="input-group">
                                                            <button class="btn btn-outline-secondary" type="button"
                                                                @click="decrementQty(item.id)">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" class="form-control text-center fw-bold"
                                                                :value="item.qty"
                                                                @input="updateCartItemQty(item.id, $event.target.value)"
                                                                min="0.01" step="0.01">
                                                            <button class="btn btn-outline-secondary" type="button"
                                                                @click="incrementQty(item.id)">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-5">
                                                        <label class="form-label small mb-1 fw-semibold">Diskon</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number" class="form-control"
                                                                :value="item.discount"
                                                                @input="updateCartItemDiscount(item.id, $event.target.value)"
                                                                min="0" step="1000" placeholder="0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Total & Delete Row -->
                                                <div
                                                    class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                    <div>
                                                        <span class="badge bg-primary fs-6 px-3 py-2">
                                                            Total: Rp <span
                                                                x-text="((item.total_input || (item.price * item.qty)) - item.discount).toLocaleString()"></span>
                                                        </span>
                                                    </div>
                                                    <button @click="removeFromCart(item.id)"
                                                        class="btn btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Desktop Layout (Horizontal) -->
                                            <div class="d-none d-lg-block">
                                                <div class="row align-items-center">
                                                    <!-- Product Info -->
                                                    <div class="col-lg-4">
                                                        <h5 class="mb-1" x-text="item.name"></h5>
                                                        <small class="text-muted">@ Rp <span
                                                                x-text="item.price.toLocaleString()"></span></small>
                                                    </div>

                                                    <!-- Quantity Controls -->
                                                    <div class="col-lg-3">
                                                        <label class="form-label small">Quantity</label>
                                                        <div class="input-group input-group-sm">
                                                            <button class="btn btn-outline-secondary" type="button"
                                                                @click="decrementQty(item.id)">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" class="form-control text-center"
                                                                :value="item.qty"
                                                                @input="updateCartItemQty(item.id, $event.target.value)"
                                                                min="0.01" step="0.01">
                                                            <button class="btn btn-outline-secondary" type="button"
                                                                @click="incrementQty(item.id)">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Discount Input -->
                                                    <div class="col-lg-2">
                                                        <label class="form-label small">Diskon (Rp)</label>
                                                        <input type="number" class="form-control form-control-sm"
                                                            :value="item.discount"
                                                            @input="updateCartItemDiscount(item.id, $event.target.value)"
                                                            min="0" step="1000" placeholder="0">
                                                    </div>

                                                    <!-- Total & Actions -->
                                                    <div class="col-lg-2">
                                                        <label class="form-label small">Total</label>
                                                        <div class="fw-bold text-primary">
                                                            Rp <span
                                                                x-text="((item.total_input || (item.price * item.qty)) - item.discount).toLocaleString()"></span>
                                                        </div>
                                                    </div>

                                                    <!-- Delete Button -->
                                                    <div class="col-lg-1 text-end">
                                                        <button @click="removeFromCart(item.id)"
                                                            class="btn btn-sm btn-outline-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Fixed Footer -->
                        <div class="modal-footer flex-shrink-0 bg-light border-top">
                            <!-- Mobile Footer -->
                            <div class="w-100 d-block d-lg-none">
                                <div class="row align-items-center">
                                    <div class="col-12 text-center mb-2">
                                        <h4 class="mb-0 text-primary fw-bold">
                                            Total: Rp <span x-text="getTotalPrice().toLocaleString()"></span>
                                        </h4>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-secondary w-100" @click="closeCartModal()">
                                            <i class="fas fa-times"></i> Tutup
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-success w-100" @click="submitTransaction()"
                                            :disabled="cart.length === 0">
                                            <i class="fas fa-credit-card"></i> Checkout
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Desktop Footer -->
                            <div class="d-none d-lg-flex justify-content-between align-items-center w-100">
                                <button class="btn btn-secondary" @click="closeCartModal()">
                                    <i class="fas fa-times"></i> Tutup
                                </button>
                                <div class="text-end">
                                    <h5 class="mb-2 text-primary">
                                        Total: Rp <span x-text="getTotalPrice().toLocaleString()"></span>
                                    </h5>
                                    <button class="btn btn-success btn-lg" @click="submitTransaction()"
                                        :disabled="cart.length === 0">
                                        <i class="fas fa-credit-card"></i> Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary shadow-lg position-fixed" @click="openCartModal()"
                style="bottom: 60px; right: 30px; z-index: 1050; display: flex; align-items: center; justify-content: center; position: relative; min-width: 160px; height: 50px;">
                <i class="fas fa-shopping-cart me-2"></i>
                Lihat Keranjang

                <!-- Badge quantity - POJOK KIRI ATAS -->
                <span class="badge bg-danger position-absolute rounded-pill" x-show="getTotalQuantity() > 0"
                    x-text="getTotalQuantity() + ' items'" :class="{ 'pulse-badge': badgeAnimation }"
                    style="top: -12px; left: -20px; font-size: 0.75rem; min-width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border: 2px solid white;">
                </span>

                <!-- Badge total price - POJOK KANAN ATAS -->
                <span class="badge bg-success position-absolute rounded-pill" x-show="getTotalPrice() > 0"
                    x-text="'Rp ' + getTotalPrice().toLocaleString('id-ID')" :class="{ 'shake-badge': priceAnimation }"
                    style="top: -12px; right: -30px; font-size: 0.65rem; min-width: 60px; height: 22px; display: flex; align-items: center; justify-content: center; white-space: nowrap; border: 2px solid white; max-width: 150px; overflow: hidden; text-overflow: ellipsis;">
                </span>
            </button>

            <div x-show="showNotification" x-transition:enter="notification-enter"
                x-transition:leave="notification-leave" class="success-notification">
                <i class="fas fa-check-circle"></i>
                <span x-text="notificationMessage"></span>
            </div>

            <!-- Floating product animation element -->
            <div x-show="showFlyingProduct" class="position-fixed fly-to-cart" :style="flyingProductStyle">
                <div class="bg-primary text-white px-2 py-1 rounded">
                    <i class="fas fa-box me-1"></i>
                    <span x-text="flyingProductName"></span>
                </div>
            </div>
        </div>
    </div>
    <!--end::Aside column-->
    @include('pos::pos.js-create')
@endsection

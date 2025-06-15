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
    </style>

    <!--begin::Aside column-->
    <div class="w-100 flex-lg-row-auto mb-7 me-7 me-lg-10">
        <div class="card card-body mb-3">
            <div class="d-flex flex-column gap-10 mb-3">
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
            <div class="d-flex flex-column gap-10">
                <!--begin::Input group-->
                <div class="row">
                    <div class="col">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Tanggal Transaksi</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}">
                            <!--end::Editor-->
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Nomor Faktur</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="text" class="form-control" name="invoice_number">
                            <!--end::Editor-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
            </div>
        </div>
        <div x-data="posApp()" x-init="init()">
            <div class="card card-body mb-3">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fs-5 fw-bold d-flex">Produk yang dijual</span>
                        <span class="text-danger">Diperbarui per {{ date('d/m/Y') }}</span>
                    </div>
                    <button @click="openAddModal()"
                        class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>
                </div>
                {{-- <!-- Cart --> --}}
                <div class="col-md-12" style="height: 200px; overflow-y: auto;">
                    <div>
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
                                    <div class="d-block d-lg-none" @click="openEditModal(item)">
                                        <!-- Product Name & Price -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="mb-2">
                                                <h6 class="mb-1 fw-bold" x-text="item.name"></h6>
                                                <small class="text-muted d-flex">
                                                    Qty : <span x-text="item.qty"></span>
                                                </small>
                                                <small class="text-muted d-flex">
                                                    Harga <span x-text="item.price.toLocaleString()"></span>
                                                </small>
                                                <small class="text-muted">
                                                    Discount <span x-text="item.discount.toLocaleString()"></span>
                                                </small>
                                            </div>
                                            <div class="mb-2 pb-2">
                                                <span class="fs-6 px-3 py-2">
                                                    Total: Rp <span
                                                        x-text="((item.total_input || (item.price * item.qty)) - item.discount).toLocaleString()">
                                                        ></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Desktop Layout (Horizontal) -->
                                    <div class="d-none d-lg-block" @click="openEditModal(item)">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="mb-2">
                                                <h6 class="mb-1 fw-bold" x-text="item.name"></h6>
                                                <small class="text-muted d-flex">
                                                    Qty : <span x-text="item.qty"></span>
                                                </small>
                                                <small class="text-muted d-flex">
                                                    Harga <span x-text="item.price.toLocaleString()"></span>
                                                </small>
                                                <small class="text-muted">
                                                    Discount <span x-text="item.discount.toLocaleString()"></span>
                                                </small>
                                            </div>
                                            <div class="mb-2 pb-2">
                                                <span class="fs-6 px-3 py-2">
                                                    Total: Rp <span
                                                        x-text="((item.total_input || (item.price * item.qty)) - item.discount).toLocaleString()"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-body">
                {{-- Ringkasan --}}
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex justify-content-between">
                        <span x-text="totalProduk">Total Produk</span>
                        <span class="fw-bold" x-text="totalHargaKeseluruhan"></span>
                    </div>
                </div>
            </div>

            {{-- Modal Add Product --}}
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true"
                x-show="showAddModal" style="display: none;">
                <div class="modal-dialog modal-fullscreen-sm-down">
                    <div class="modal-content" x-data>
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Produk</h5>
                            <button type="button" class="btn-close" @click="closeAddModal()"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Select Produk -->
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <select id="select_product" class="form-select"></select>
                            </div>

                            <!-- Satuan -->
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label class="form-label">Satuan</label>
                                    <input type="text" class="form-control" x-model="addProduct.unit" readonly>
                                </div>

                                <!-- Harga -->
                                <div class="col-9 mb-3">
                                    <label class="form-label">Harga</label>
                                    <input type="text" class="form-control" x-model="formattedAddPrice"
                                        @input="updateAddPriceFromFormatted" readonly>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" step="0.01" min="0"
                                    x-model="addProduct.qty" @input="updateAddTotalFromQty">
                            </div>

                            <!-- Diskon -->
                            <div class="mb-3">
                                <label class="form-label">Diskon (Rp jika > 100, % jika ≤ 100)</label>
                                <input type="text" class="form-control"
                                    :value="formatRupiah(addProduct.discountNominal || 0)" @input="updateDiscountValue">
                            </div>


                            <!-- Jumlah Harga -->
                            <div class="mb-3">
                                <label class="form-label">Jumlah Harga</label>
                                <input type="text" class="form-control" x-model="addProduct.formattedAddTotalInput"
                                    @input="updateQtyFromAddTotal">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" @click="closeAddModal()">Batal</button>
                            <button class="btn btn-primary" @click="saveAddToCart()">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Modal Edit --}}
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true"
                x-show="editModal" style="display: none;">
                <div class="modal-dialog modal-fullscreen-sm-down">
                    <div class="modal-content" x-data>
                        <div class="modal-header" style="background-color: #ff000d; color: #fff;">
                            <span class="fs-4 text-white fw-bold" x-text="editTitle"></span>
                        </div>
                        <div class="modal-body">
                            <div x-show="editItem">
                                <div class="mb-3">
                                    <label class="form-label">Nama Product</label>
                                    <input type="text" class="form-control" x-model="editProductName" readonly>
                                </div>
                                <!-- Input Qty -->
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" class="form-control" step="0.01" min="0"
                                            x-model="editProductUnit" readonly>
                                    </div>
                                    <div class="col-8">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control" step="0.01" min="0"
                                            x-model="editQty" @input="updateTotalFromEditQty">
                                    </div>
                                </div>
                                <!-- Input Mode Harga -->
                                <div class="mb-3">
                                    <label class="form-label">Harga Jual (Rp)</label>
                                    <input type="text" class="form-control" x-model="editTotalFormatted"
                                        @input="updateEditTotalFormatted" inputmode="numeric">
                                </div>
                                <!-- Diskon -->
                                <div class="mb-3">
                                    <label class="form-label">Diskon (Rp jika > 100, % jika ≤ 100)</label>
                                    <input type="number" class="form-control" x-model="editDiscount" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" @click="closeEditModal()">Batal</button>
                            <button class="btn btn-primary" @click="saveEditToCart()">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end::Aside column-->
    @include('pos::pos.js-create2')
@endsection

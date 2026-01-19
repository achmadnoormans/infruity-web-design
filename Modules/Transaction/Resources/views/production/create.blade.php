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

    <!--begin::Main column-->
    <div class="w-100 flex-lg-row-auto me-7 me-lg-10" x-data="productionApp()" x-init="init()">
        <form id="kt_ecommerce_edit_order_form" class="form"
            action="{{ isset($data) ? url(Request::segment(1) . '/' . $data->id) : url(Request::segment(1)) }}" method="POST"
            enctype="multipart/form-data" data-kt-redirect="">
            @if (isset($data))
                @method('PUT')
            @endif
            @csrf
            
            <!-- Header Information Card -->
            <div class="card card-body mb-3">
                <div class="d-flex flex-column gap-10 mb-3">
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-6">
                            <div class="fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Nomor Produksi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control" name="production_number" 
                                    value="{{ isset($data) ? $data->production_number : '#' . time() }}" readonly>
                                <!--end::Input-->
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Tanggal Produksi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="date" class="form-control" name="production_date" 
                                    value="{{ old('production_date') ?? date('Y-m-d') }}">
                                <!--end::Input-->
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                
                <div class="d-flex flex-column gap-10 mb-3">
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-9">
                            <div class="fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Pilih Produk</label>
                                <!--end::Label-->
                                <!--begin::Select-->
                                <select name="product_id" id="product_id" class="form-select">
                                    @if (isset($receipt) && $receipt != null)
                                        <option value="{{ $receipt->id }}" selected>{{ $receipt->products->name }}</option>
                                    @else
                                        <option value="">Pilih Produk</option>
                                    @endif
                                </select>
                                <!--end::Select-->
                            </div>
                        </div>
                        <div class="col-3 mt-8">
                            <button type="button" @click="refreshProduct()"
                                class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">
                                <i class="fa-solid fa-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                
                <div class="d-flex flex-column gap-10">
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-6">
                            <div class="fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">Jumlah Produksi</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                    placeholder="Masukkan Jumlah" step="0.01"
                                    value="{{ isset($data) ? $data->quantity : old('quantity', 1) }}" />
                                <!--end::Input-->
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fv-row">
                                <!--begin::Label-->
                                <label class="form-label">PIC / Penanggung Jawab</label>
                                <!--end::Label-->
                                <!--begin::Select-->
                                <select name="staff_id" id="staff_id" class="form-select">
                                    <option value="">Pilih Staff</option>
                                </select>
                                <!--end::Select-->
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                
                <!-- Hidden inputs -->
                <input type="hidden" name="submit_type" id="submit_type" value="temp">
                <input type="hidden" name="id_receipt" id="id_receipt">
                <input type="hidden" name="production_number" value="{{ $production_number }}">
                
                <!-- Dynamic ingredients inputs - populated by Alpine.js -->
                <div id="ingredients-inputs">
                    <template x-for="(ingredient, index) in ingredients" :key="ingredient.id">
                        <div>
                            <input type="hidden" :name="'ingredients[' + index + '][id]'" :value="ingredient.id">
                            <input type="hidden" :name="'ingredients[' + index + '][quantity]'" :value="ingredient.quantity">
                            <input type="hidden" :name="'ingredients[' + index + '][hpp]'" :value="ingredient.hpp">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Ingredients Selection Card -->
            <div class="card card-body mb-3">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fs-5 fw-bold d-flex">Bahan Baku yang Digunakan</span>
                        <span class="text-danger">Diperbarui per {{ date('d/m/Y') }}</span>
                    </div>
                    <div class="btn-group">
                        <button type="button"
                            class="btn btn-outline btn-outline-dashed btn-outline-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <button class="dropdown-item" type="button" @click="openAddIngredientModal()">
                                    <i class="ki-duotone ki-purchase text-success me-2 fs-5"></i> Tambah Bahan
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item" type="button" @click="loadFromRecipe()">
                                    <i class="ki-duotone ki-book text-primary me-2 fs-5"></i> Dari Resep
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Ingredients Cart -->
                @include('transaction::production.segment.ingredients-cart')
            </div>

            <!-- Summary Card -->
            <div class="card card-body mb-3">
                @include('transaction::production.segment.summary')
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-3">
                <!--begin::Button-->
                <a href="{{ url(Request::segment(1)) }}" class="btn btn-light">Batal</a>
                <!--end::Button-->

                <button type="submit" class="btn btn-secondary" onclick="setSubmitType('temp')">
                    <span class="indicator-label">Simpan Draft</span>
                    <span class="indicator-progress">Mohon tunggu...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>

                <button type="submit" class="btn btn-warning" onclick="setSubmitType('draft')">
                    <span class="indicator-label">Siap Produksi</span>
                    <span class="indicator-progress">Mohon tunggu...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>

                <button type="submit" class="btn btn-primary" onclick="setSubmitType('posting')">
                    <span class="indicator-label">Selesai Produksi</span>
                    <span class="indicator-progress">Mohon tunggu...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Button-->
            </div>
        </form>

        <!-- Modals -->
        @include('transaction::production.segment.modal-ingredient')
        @include('transaction::production.segment.modal-recipe')
    </div>
    <!--end::Main column-->
    
    @include('transaction::production.js-create')
@endsection

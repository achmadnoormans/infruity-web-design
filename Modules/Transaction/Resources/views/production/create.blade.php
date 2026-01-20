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
                                value="{{ $production_number }}" readonly>
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
                                x-model="productionQuantity" />
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
                    </ul>
                </div>
            </div>

            <!-- Ingredients Cart -->
            @include('transaction::production.segment.ingredients-cart')
        </div>

        <!-- Summary Card -->
        <div class="card card-body">
            @include('transaction::production.segment.summary')
        </div>

        <!-- Modals -->
        @include('transaction::production.segment.modal-ingredient')
        @include('transaction::production.segment.modal-recipe')
    </div>
    <!--end::Main column-->
    
    @include('transaction::production.js-create')
@endsection

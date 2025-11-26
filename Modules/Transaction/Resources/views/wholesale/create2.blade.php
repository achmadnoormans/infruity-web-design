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
    <div class="w-100 flex-lg-row-auto me-7 me-lg-10" x-data="posApp()" x-init="init()">
        <div class="card card-body mb-3">
            <div class="d-flex flex-column gap-10">
                <!--begin::Input group-->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Cabang</label>
                            <!--end::Label-->
                            <!--begin::Select-->
                            <select class="form-control" name="branch_id" id="branch_id">
                                <option value="">Pilih Cabang</option>
                            </select>
                            <!--end::Select-->
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Tanggal Transaksi</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}">
                            <!--end::Editor-->
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Nomor Faktur</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="text" class="form-control" name="invoice_number" value="{{ $invoice_number }}"
                                readonly>
                            <!--end::Editor-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
            </div>
        </div>
        <div>
            <div class="card card-body mb-3">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fs-5 fw-bold d-flex">Produk Pengadaan</span>
                        <span class="text-danger">Diperbarui per {{ date('d/m/Y') }}</span>
                    </div>
                    <div class="btn-group">
                        @if (Request::segment(3) != 'show')
                            <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-primary"
                                @click="openAddModal()">
                                <i class="fa fa-plus"></i>
                            </button>
                        @endif
                    </div>
                </div>
                {{-- <!-- Cart --> --}}
                @include('transaction::pengadaan.segment.cart')
            </div>

            <div class="card card-body">
                {{-- Ringkasan --}}
                @include('transaction::pengadaan.segment.ringkasan')
            </div>

            @include('transaction::pengadaan.segment.modal-product')
        </div>
    </div>
    <!--end::Aside column-->
    @include('transaction::pengadaan.js-create')
@endsection

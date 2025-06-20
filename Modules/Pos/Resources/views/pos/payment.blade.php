@extends('template.root')

@section('content')
    <!--begin::Aside column-->
    <div class="w-100 flex-lg-row-auto mb-7 me-7 me-lg-10" x-data="posApp()" x-init="init()">
        <div class="card card-body mb-5 bg-light-success rounded-3">
            <div class="d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bolder mb-3">Pembayaran</span>
                    <span class="mb-3">{{ $data->customer->name ?? 'Pelanggan Umum' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bolder mb-3">Total Penjualan</span>
                    <span class="fw-bolder fs-4 mb-3">Rp. {{ toNumber($data->total_price) }}</span>
                </div>
            </div>
        </div>
        <div class="card-body mb-3 mt-3">
            <div class="d-flex flex-column gap-10 mb-3">
                <div class="fv-row">
                    <label class="required form-label">Tanggal Pembayaran</label>
                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="d-flex flex-column gap-10">
                <!--begin::Input group-->
                <div class="row">
                    <div class="col">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}">
                            <!--end::Editor-->
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Nomor Faktur</label>
                            <input type="text" class="form-control" name="invoice_number" value=""
                                readonly>
                            <!--end::Editor-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
            </div>
        </div>
    </div>
    <!--end::Aside column-->
    @include('pos::pos.js-create2')
@endsection

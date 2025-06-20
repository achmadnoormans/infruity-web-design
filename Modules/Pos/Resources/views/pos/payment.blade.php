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
                    <span class="fw-bolder fs-4 mb-3">Rp. {{ toNumber($data->total) }}</span>
                </div>
            </div>
        </div>
        <div class="card card-body mb-3 mt-3">
            <div class="d-flex flex-column gap-10 mb-3">
                <div class="fv-row">
                    <label class="required form-label">Tanggal Pembayaran</label>
                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="d-flex flex-column gap-10 mb-3">
                <!--begin::Input group-->
                <div class="row">
                    <div class="col">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Tanggal Transaksi</label>
                            <select class="form-select" id="payment_id" name="payment_id">
                                <option value="">Select Payment</option>
                            </select>
                            <!--end::Editor-->
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Acc. Kas</label>
                            @php
                                $accounts = [
                                    '1' => 'Kas',
                                    '2' => 'Bank',
                                ];
                            @endphp
                            <select class="form-select" id="account_id" name="account_id">
                                <option value="">Select Kas</option>
                                @foreach ($accounts as $key => $account)
                                    <option value="{{ $key }}">{{ $account }}</option>
                                @endforeach
                            </select>
                            <!--end::Editor-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
            </div>
            <div class="d-flex flex-column mb-2">
                <div class="fv-row mb-2">
                    <label class="required form-label">Jumlah Pembayaran</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp.</span>
                        <input type="text" class="form-control" x-model="formattedTotalPayment" @input="updatePayment">
                    </div>
                </div>
                <!-- Notifikasi selisih -->
                <div class="text-end">
                    <small class="text-danger" x-show="paymentDifference < 0">
                        Kurang bayar senilai Rp. <span x-text="formatRupiah(Math.abs(paymentDifference))"></span>
                    </small>
                    <small class="text-success" x-show="paymentDifference > 0">
                        Kelebihan bayar senilai Rp. <span x-text="formatRupiah(paymentDifference)"></span>
                    </small>
                </div>
            </div>
            <div class="d-flex flex-column">
                <div class="fv-row mb-2">
                    <label class="required form-label">Cabang</label>
                    <select class="form-select" id="branch_id" name="branch_id">
                        <option value="">Select Branch</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body mb-3">
            <div class="accordion mb-4" id="accordionPembayaranSebelumnya" x-show="previousPayments.length > 0"
                x-init="loadPreviousPayments()">
                <div class="accordion-item border-0 shadow-sm rounded">
                    <h2 class="accordion-header" id="headingPembayaran">
                        <button class="accordion-button bg-light text-dark fw-bold rounded collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapsePembayaran" aria-expanded="false"
                            aria-controls="collapsePembayaran">
                            <i class="fa-solid fa-receipt me-2 text-primary"></i> Pembayaran Sebelumnya
                        </button>
                    </h2>
                    <div id="collapsePembayaran" class="accordion-collapse collapse" aria-labelledby="headingPembayaran"
                        data-bs-parent="#accordionPembayaranSebelumnya">
                        <div class="accordion-body">
                            <template x-for="(payment, index) in previousPayments" :key="index">
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Tanggal</span>
                                        <span class="fw-semibold" x-text="payment.date"></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Metode</span>
                                        <span class="fw-semibold" x-text="payment.payment_method.name"></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Jumlah</span>
                                        <span class="fw-bold text-success">Rp <span
                                                x-text="formatRupiah(payment.total)"></span></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-9">
                    <button type="submit" class="btn btn-primary w-100" @click="submitPayment()"><i
                            class="bi bi-cash-stack"></i> Bayar</button>
                </div>
                <div class="col-3">
                    <a href="{{ route('pos.printPayment', $data->id) }}" class="btn btn-success w-100">
                        <i class="fa-solid fa-print"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <!--end::Aside column-->
    @include('pos::pos.js-payment')
@endsection

@extends('template.root')
@section('content')
    <div class="container d-flex flex-column justify-content-center align-items-center text-center">
        <div class="card shadow p-4 rounded-4">
            <div class="text-success mb-3">
                <i class="bi bi-check-circle-fill" style="font-size: 4rem;"></i>
            </div>
            <h2 class="mb-3">Pembayaran Berhasil!</h2>
            <p class="mb-4">Terima kasih, pembayaran Anda telah berhasil diproses.</p>

            <div class="text-start w-100 mb-4">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <strong>Metode Pembayaran:</strong>
                    <span class="text-muted">{{ strtoupper($data->paymentMethod->name) ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <strong>Total Tagihan:</strong>
                    <span class="text-muted">{{ toNumber($data->pos->total ?? 0) }}</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <strong>Total Dibayarkan:</strong>
                    <span class="text-muted">{{ toNumber($data->total ?? 0) }}</span>
                </div>
                @if ($totalPayment >= $data->pos->total)
                    <div class="d-flex justify-content-between py-1">
                        <strong>Kembalian:</strong>
                        <span class="text-muted">{{ toNumber(($totalPayment ?? 0) - ($data->pos->total ?? 0)) }}</span>
                    </div>
                @else
                    <div class="d-flex justify-content-between py-1">
                        <strong>Kurang:</strong>
                        <span class="text-muted">{{ toNumber(($data->pos->total ?? 0) - ($totalPayment ?? 0)) }}</span>
                    </div>
                @endif
            </div>

            <a href="{{ route('pos.create') }}" class="btn btn-success rounded-pill w-100 mb-2">
                <i class="bi bi-arrow-left"></i> Transaksi Baru
            </a>
            <a href="{{ url('pos/printNota') . '/' . $data->id }}" class="btn btn-primary rounded-pill w-100">
                <i class="fa fa-print"></i> Cetak Struk
            </a>
        </div>
    </div>
@endsection

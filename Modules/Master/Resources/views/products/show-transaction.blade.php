@extends('template.root')

@section('content')
    <div>
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h3 class="fw-bold">Riwayat Transaksi HPP - {{ $product->name ?? 'Product' }}</h3>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <a href="{{ url('product-stock') }}" class="btn btn-light-primary btn-sm">
                        <i class="ki-outline ki-arrow-left fs-4"></i> Kembali
                    </a>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table table-bordered align-middle fs-6 gy-5 nowrap" id="product-transaction-table" width="100%">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-nowrap min-w-70px">Type</th>
                            <th class="text-nowrap min-w-150px">Keterangan</th>
                            <th class="text-nowrap text-center min-w-70px">Qty</th>
                            <th class="text-nowrap text-center min-w-100px">Stok Raw</th>
                            <th class="text-nowrap text-center min-w-100px">Stok Berjalan</th>
                            <th class="text-nowrap text-center min-w-120px">Harga Satuan</th>
                            <th class="text-nowrap text-center min-w-120px">Total Belanja</th>
                            <th class="text-nowrap text-center min-w-130px">Total Non Belanja</th>
                            <th class="text-nowrap text-center min-w-120px">HPP Berjalan</th>
                            <th class="text-nowrap text-center min-w-120px">Total Aset</th>
                            <th class="text-nowrap text-center min-w-120px">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
    <script type="text/javascript">
        var dataTable;
        $(document).ready(function() {
            dataTable = $('#product-transaction-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('product-transaction-data') }}",
                    data: function(d) {
                        d.product_id = {{ $product->id }}
                    }
                },
                columns: [
                    {
                        data: 'type',
                        name: 'type',
                        className: 'text-center text-nowrap'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        className: 'text-end text-nowrap'
                    },
                    {
                        data: 'qty_berjalan_raw',
                        name: 'qty_berjalan_raw',
                        className: 'text-end text-nowrap'
                    },
                    {
                        data: 'qty_berjalan',
                        name: 'qty_berjalan',
                        className: 'text-end text-nowrap'
                    },
                    {
                        data: 'harga_satuan',
                        name: 'harga_satuan',
                        className: 'text-end text-nowrap'
                    },
                    {
                        data: 'total_belanja',
                        name: 'total_belanja',
                        className: 'text-end text-nowrap'
                    },
                    {
                        data: 'total_non_belanja',
                        name: 'total_non_belanja',
                        className: 'text-end text-nowrap'
                    },
                    {
                        data: 'hpp_berjalan',
                        name: 'hpp_berjalan',
                        className: 'text-end text-nowrap'
                    },
                    {
                        data: 'total_aset_berjalan',
                        name: 'total_aset_berjalan',
                        className: 'text-end text-nowrap'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'text-end text-nowrap'
                    },
                ],
                order: [
                    [10, 'asc']
                ]
            });
        });

        function reloadDataTable() {
            if (typeof dataTable !== 'undefined') {
                dataTable.ajax.reload(null, false);
            } else {
                console.error('DataTable tidak terinisialisasi.');
            }
        }
    </script>
@endsection

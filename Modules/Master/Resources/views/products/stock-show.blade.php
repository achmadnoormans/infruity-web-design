@extends('template.root')

@section('content')
    {{-- @livewire('product-table') --}}
    <div>
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <label class="form-label fs-6 fw-semibold me-4">Cabang:</label>
                        <select class="form-select form-select-solid" data-control="select2"
                            data-hide-search="true" data-placeholder="Cabang"
                            data-kt-ecommerce-product-filter="branch" style="width: 250px;">
                            <option value="all">Semua Cabang</option>
                            @foreach ($branch as $item)
                                <option value="{{ $item->id }}">{{ ucwords($item->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="products-stock-table" width="100%">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-100px">Product</th>
                            <th class="text-end min-w-70px">Quantity</th>
                            <th class="text-end min-w-100px">Tanggal</th>
                            <th class="text-end min-w-100px">Refernce</th>
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
            dataTable = $('#products-stock-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true, // Aktifkan scroll horizontal
                // responsive: true,
                ajax: {
                    url: "{{ route('product-stock-data-show') }}",
                    data: function(d) {
                        d.url = "{{ request()->segment(1) }}";
                        d.stock_filter = $('[data-kt-ecommerce-product-filter="stock"]').val();
                        d.product_id = {{ $data->id }};
                        d.branch = $('[data-kt-ecommerce-product-filter="branch"]').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        className: 'text-end'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        className: 'text-end'
                    },
                    {
                        data: 'reff',
                        name: 'reff',
                        className: 'text-end'
                    },

                ],
                order: [
                    [2, 'desc']
                ] // Order by quantity column (index 1) in descending order
            });
            // Search manual lewat input
            $('#search').on('keyup', function() {
                dataTable.search(this.value).draw();
            });

            $('[data-kt-ecommerce-product-filter="status"]').on('change', function() {
                let val = $(this).val();

                if (val === 'all') val = ''; // kosongkan filter jika all
                dataTable.column(3).search(val).draw();
            });

            $('[data-kt-ecommerce-product-filter="stock"]').on('change', function() {
                dataTable.draw(); // trigger fetch ulang dari server
            });

            $('[data-kt-ecommerce-product-filter="branch"]').on('change', function() {
                dataTable.draw(); // trigger fetch ulang dari server
            });
        });

        function reloadDataTable() {
            // Pastikan dataTable sudah terinisialisasi sebelumnya
            if (typeof dataTable !== 'undefined') {
                dataTable.ajax.reload(null, false); // 'false' untuk tidak mereset ke halaman pertama
            } else {
                console.error('DataTable tidak terinisialisasi.');
            }
        }
    </script>
@endsection

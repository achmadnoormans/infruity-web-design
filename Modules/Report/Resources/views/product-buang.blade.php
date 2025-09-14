@extends('template.root')

@section('content')
    {{-- @livewire('product-table') --}}
    <div>
        <div class="card card-flush">
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="transaction-table" width="100%">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7">
                            <th class="text-start min-w-150px">Produk</th>
                            <th class="text-start min-w-50px">Quantity</th>
                            <th class="text-start min-w-50px">Satuan</th>
                            <th class="text-start min-w-50px">Hpp Satuan (Rp)</th>
                            <th class="text-start min-w-50px">Total Hpp (Rp)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
    @section('script')
    <script type="text/javascript">
        var dataTable;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const segment1 = "{{ Request::segment(1) }}";

        $(document).ready(function() {
            dataTable = $('#transaction-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true, // Aktifkan scroll horizontal
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },
                columnDefs: [{
                    orderable: false,
                    targets: -1 // Disable sorting for action column
                }, ],
                ajax: {
                    url: "{{ route('report-product-buang.data') }}",
                    data: function(d) {
                        d.url = "{{ request()->segment(1) }}";
                    }
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'satuan',
                        name: 'satuan'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'hpp',
                        name: 'hpp'
                    },
                    {
                        data: 'total_hpp',
                        name: 'total_hpp'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [4, 'desc']
                ]
            });
            // Search manual lewat input
            $('#search').on('keyup', function() {
                dataTable.search(this.value).draw();
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
        $("#date").flatpickr({
            altInput: !0,
            altFormat: "d F, Y",
            dateFormat: "Y-m-d"
        });

    </script>
@endsection

@endsection

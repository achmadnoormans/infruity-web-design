@extends('template.root')

@section('content')
    {{-- @livewire('product-table') --}}
    <div>
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-3 gap-2 flex-wrap flex-md-nowrap">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                        <input type="text" data-kt-ecommerce-product-filter="search" id="search"
                            class="form-control form-control-solid w-200px w-md-250px ps-12" placeholder="Cari Produk" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar ms-auto">
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                                <i class="ki-duotone ki-filter fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i></button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-gray-900 fw-bold">Pilihan Filter</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Content-->
                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                    <!--begin::Input group-->
                                    <div class="mb-3">
                                        <label class="form-label fs-6 fw-semibold">Cabang:</label>
                                        <select class="form-select form-select-solid" data-control="select2"
                                            data-hide-search="true" data-placeholder="Cabang"
                                            data-kt-ecommerce-product-filter="branch">
                                            <option value="all">Semua Cabang</option>
                                            @foreach ($branch as $item)
                                                <option value="{{ $item->id }}">{{ ucwords($item->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fs-6 fw-semibold">Kategori:</label>
                                        <select class="form-select form-select-solid" data-control="select2"
                                            data-hide-search="true" data-placeholder="Kategori"
                                            data-kt-ecommerce-product-filter="kategori">
                                            @foreach ($category as $category)
                                                <option value="{{ $category->id }}">{{ ucwords($category->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fs-6 fw-semibold">Stok:</label>
                                        <select class="form-select form-select-solid" data-control="select2"
                                            data-hide-search="true" data-placeholder="Stok"
                                            data-kt-ecommerce-product-filter="stock">
                                            <option value="all">Semua</option>
                                            <option value="ada">Ada</option>
                                            <option value="kosong">Kosong</option>
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Add product-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">

                <div id="keterangan" class="text-center">
                    <p></p>
                </div>
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="products-table" width="100%">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            {{-- <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_ecommerce_products_table .form-check-input"
                                        value="1" />
                                </div>
                            </th> --}}
                            <th class="min-w-200px">Product</th>
                            <th class="text-end min-w-70px">Hpp</th>
                            <th class="text-end min-w-70px">Stock</th>
                            <th class="d-none">Category</th>
                            <th></th>
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
            dataTable = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true, // Aktifkan scroll horizontal
                fixedColumns: {
                    leftColumns: 0, // Tidak ada kolom di sisi kiri yang dibekukan
                    rightColumns: 1 // Membekukan 1 kolom di sisi kanan (kolom action)
                },
                columnDefs: [{
                    orderable: false,
                    targets: -1 // Nonaktifkan sorting untuk kolom action
                }],
                // responsive: true,
                ajax: {
                    url: "{{ route('product-stock-data') }}",
                    data: function(d) {
                        d.url = "{{ request()->segment(1) }}";
                        d.branch = $('[data-kt-ecommerce-product-filter="branch"]').val();
                        d.stock_kategori = $('[data-kt-ecommerce-product-filter="kategori"]').val();
                        d.stock_filter = $('[data-kt-ecommerce-product-filter="stock"]').val();
                    }
                },
                columns: [
                    // {
                    //     data: 'DT_RowIndex',
                    //     name: 'DT_RowIndex'
                    // },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'hpp',
                        name: 'hpp',
                        className: 'text-end'
                    },
                    {
                        data: 'stock_available',
                        name: 'stock_available',
                        className: 'text-end'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        visible: false
                    },
                    {
                        data: 'action',
                        name: 'action'
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

            $('[data-kt-ecommerce-product-filter="stock"]').on('change', function() {
                dataTable.draw(); // trigger fetch ulang dari server
            });

            $('[data-kt-ecommerce-product-filter="branch"]').on('change', function() {
                dataTable.draw(); // trigger fetch ulang dari server
                updateKeterangan();
            });

            // Panggil keteranga di awal
            updateKeterangan();
        });

        function updateKeterangan() {
            const branchText = $('[data-kt-ecommerce-product-filter="branch"] option:selected').text();
            $('#keterangan p').html('Cabang: <span class="fw-bold">' + branchText + '</span>');
        }

        function reloadDataTable() {
            // Pastikan dataTable sudah terinisialisasi sebelumnya
            if (typeof dataTable !== 'undefined') {
                dataTable.ajax.reload(null, false); // 'false' untuk tidak mereset ke halaman pertama
            } else {
                console.error('DataTable tidak terinisialisasi.');
            }
        }

        function deleteProduct(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/products/${id}`, // Ganti dengan URL yang sesuai
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil dihapus.'
                            });

                            // Reload DataTable setelah berhasil menghapus data
                            reloadDataTable();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat menghapus data.'
                            });
                        }
                    });
                }
            });
        }

        // Handle click untuk ubah span jadi input
        $('#products-table').on('click', '.editable-price', function() {
            var $span = $(this);
            var currentValue = $span.data('value');
            var id = $span.data('id');

            var input = $('<input type="number" class="form-control form-control-sm text-end">')
                .val(currentValue)
                .blur(function() {
                    var newValue = $(this).val();
                    if (newValue != currentValue) {
                        updatePrice(id, newValue);
                    } else {
                        $span.text(currentValue).show();
                        $(this).remove();
                    }
                })
                .keypress(function(e) {
                    if (e.which === 13) { // Enter key
                        $(this).blur();
                    }
                });

            $span.hide().after(input);
            input.focus().select();
        });

        function updatePrice(id, price) {
            $.ajax({
                url: `/products/${id}/update-price`, // sesuaikan route
                type: 'PUT',
                data: {
                    price: price,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    dataTable.ajax.reload(null, false); // reload tanpa reset halaman
                },
                error: function() {
                    Swal.fire('Gagal', 'Tidak bisa memperbarui harga', 'error');
                    dataTable.ajax.reload(null, false);
                }
            });
        }
    </script>
@endsection

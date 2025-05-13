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
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                        <input type="text" data-kt-ecommerce-product-filter="search" id="search"
                            class="form-control form-control-solid w-250px ps-12" placeholder="Search Product" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <!--begin::Select2-->
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                            data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">All</option>
                            <option value="published">Published</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <!--end::Select2-->
                    </div>
                    <!--begin::Add product-->
                    <a href="{{ url('products/create') }}" class="btn btn-primary">Add Product</a>
                    <!--end::Add product-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
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
                            <th class="text-end min-w-100px">Price</th>
                            <th class="text-end min-w-70px">Limit</th>
                            <th class="text-end min-w-100px">Stock</th>
                            <th class="text-end min-w-70px"></th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        {{-- <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="1" />
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!--begin::Thumbnail-->
                                    <a href="apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                        <span class="symbol-label"
                                            style="background-image:url(assets/media//stock/ecommerce/1.png);"></span>
                                    </a>
                                    <!--end::Thumbnail-->
                                    <div class="ms-5">
                                        <!--begin::Title-->
                                        <a href="apps/ecommerce/catalog/edit-product.html"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">Product 1</a>
                                        <!--end::Title-->
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-0">
                                <span class="fw-bold">02925004</span>
                            </td>
                            <td class="text-end pe-0" data-order="40">
                                <span class="fw-bold ms-3">40</span>
                            </td>
                            <td class="text-end pe-0">258</td>
                            <td class="text-end pe-0" data-order="rating-3">
                                <div class="rating justify-content-end">
                                    <div class="rating-label checked">
                                        <i class="ki-outline ki-star fs-6"></i>
                                    </div>
                                    <div class="rating-label checked">
                                        <i class="ki-outline ki-star fs-6"></i>
                                    </div>
                                    <div class="rating-label checked">
                                        <i class="ki-outline ki-star fs-6"></i>
                                    </div>
                                    <div class="rating-label">
                                        <i class="ki-outline ki-star fs-6"></i>
                                    </div>
                                    <div class="rating-label">
                                        <i class="ki-outline ki-star fs-6"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-0" data-order="Scheduled">
                                <!--begin::Badges-->
                                <div class="badge badge-light-primary">Scheduled</div>
                                <!--end::Badges-->
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                    <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                    data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="apps/ecommerce/catalog/edit-product.html" class="menu-link px-3">Edit</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-ecommerce-product-filter="delete_row">Delete</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                        </tr> --}}
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
                        data: 'price',
                        name: 'price',
                        className: 'text-end'
                    },
                    {
                        data: 'limit',
                        name: 'limit',
                        className: 'text-end'
                    },
                    {
                        data: 'stock_available',
                        name: 'stock_available',
                        className: 'text-end'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

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

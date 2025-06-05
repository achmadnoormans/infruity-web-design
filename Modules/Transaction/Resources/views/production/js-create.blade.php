@section('script')
    <script>
        let selectedProduct = {}; // Global, satu kali saja
        var tableSelectedProduct;

        $(document).ready(function() {

            var listDatatable = $('#kt_ecommerce_edit_order_product_table').DataTable({
                processing: true,
                serverSide: true,
                scrollY: "400px",
                scrollCollapse: true,
                paging: false,
                info: false,
                ajax: {
                    url: "{{ route('wholsale.product-table-data') }}",
                    data: function(d) {
                        d.searchValue = $('#search').val();
                        d.url = "{{ request()->segment(1) }}";
                    }
                },
                columns: [
                    // {
                    //     data: 'checkbox',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `
                            <div class="d-flex align-items-center"
                                data-kt-ecommerce-edit-order-id="${row.id}"
                                data-kt-ecommerce-edit-order-receipt="${row.receipt_id}"
                                data-kt-ecommerce-edit-order-type="${row.type}"
                                data-kt-ecommerce-edit-order-price="${row.price}"
                                data-kt-ecommerce-edit-order-hpp="${row.hpp}">
                                <div class="ms-5">
                                    <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold">${data}</a>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'qty_remaining',
                        name: 'qty_remaining',
                        className: 'text-end',
                    }
                ],
                language: {
                    emptyTable: "Silahkan Search Product untuk menambahkan produk. (Product yang ditambahkan akan mempengaruhi receipt)"
                }
            });

            $('#search').on('keyup', function() {
                listDatatable.search(this.value).draw();
            });

            let selectedReceiptId = null;
            let selectedProductId = null;

            $('#product_id').on('select2:select', function(e) {
                const data = e.params.data;
                selectedReceiptId = data.data.receipt_id;
                selectedProductId = data.data.product_id; // simpan receipt_id di variabel global
                $('#id_receipt').val(selectedReceiptId);
                console.log(data);
                console.log(selectedReceiptId, selectedProductId);
            });

            // Checkbox change event

            $("#kt_ecommerce_edit_order_date").flatpickr({
                altInput: !0,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d"
            });

            $('#kt_ecommerce_edit_order_product_table').on('click', '.check-product', function() {
                let calculationType = document.querySelector('input[name="calculation_type"]:checked')
                    .value;
                const row = $(this).closest('tr');
                const checked = $(this).is(':checked');
                const productId = row.find('[data-kt-ecommerce-edit-order-id]').data(
                    'kt-ecommerce-edit-order-id');
                const type = row.find('[data-kt-ecommerce-edit-order-id]').data(
                    'kt-ecommerce-edit-order-type');
                const price = row.find('[data-kt-ecommerce-edit-order-id]').data(
                    'kt-ecommerce-edit-order-price');
                const hpp = row.find('[data-kt-ecommerce-edit-order-id]').data(
                    'kt-ecommerce-edit-order-hpp');
                const productName = row.find('a.text-gray-800').text().trim();
                // const productImage = row.find('.symbol-label').css('background-image').replace(
                //     /^url\(["']?/, '').replace(/["']?\)$/, '');
                // const price = row.find('[data-kt-ecommerce-edit-order-filter="price"]').text().trim();

                selectedProduct = {
                    id: productId,
                    name: productName,
                    // image: productImage,
                    price: price,
                    type: type,
                };
                console.log(selectedProduct);
                if (calculationType == 'weight_to_price') {
                    $('#inputProductId').val(productId);
                    $('#inputSellPrice').val(price);
                    $('#typeList').val(type);
                    $('#inputQuantity').val('');
                    $('#modalInputQty').modal('show');
                } else {
                    var url = `{{ route('production.save-ajax') }}`;
                    var form = $('#modalInputPrcForm');
                    form.attr('action', url);
                    $('#methodFieldPrc').val('POST');
                    $('#inputProductIdPrc').val(productId);
                    $('#inputProductionIdPrc').val('{{ $data->id }}');
                    $('#inputSellPricePrc').val(price);
                    $('#typeList').val(type);
                    $('#inputPrice').val('');
                    $('#modalInputPrc').modal('show');
                }

                bindFormatNumber();
            });

        });

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
                        url: `/production/delete-product/${id}`, // Ganti dengan URL yang sesuai
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Reload DataTable setelah berhasil menghapus data
                            if (typeof tableSelectedProduct !== 'undefined') {
                                tableSelectedProduct.ajax.reload(null, false);
                            }
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

        function editProduct(id) {
            var url = `{{ url('production/edit-product/${id}') }}`;
            var urlUpdate = `{{ url('production/update-product/${id}') }}`;

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let calculationType = document.querySelector('input[name="calculation_type"]:checked')
                        .value;

                    if (calculationType == 'weight_to_price') {
                        $('#inputPriceEdit').val(response.data.products.price);
                        $('#inputQuantityEdit').val(response.data.quantity);
                        $('#inputSupplierEdit').val(response.data.supplier_id).trigger('change');

                        // Set action dan method form
                        var form = $('#kt_modal_add_customer_form');
                        form.attr('action', urlUpdate);
                        $('#methodField').val('PUT');
                        $('#kt_modal_add_customer').modal('show');
                    } else {
                        var form = $('#modalInputPrcForm');
                        form.attr('action', urlUpdate);
                        $('#methodFieldPrc').val('PUT');
                        $('#inputProductIdPrc').val(response.data.product_receipt_id);
                        $('#inputProductReceiptPrc').val(response.data.id);
                        $('#inputSellPricePrc').val(response.data.products.price);
                        $('#inputQuantityPrc').val(response.data.quantity);
                        $('#inputPrice').val(response.data.nominal);
                        $('#modalInputPrc').modal('show');
                    }
                    // Set nilai input

                    bindFormatNumber();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memuat data produk.'
                    });
                }
            });
        }

        $("#kt_ecommerce_edit_order_form").submit(function() {
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
        });

        $('#product_id').select2({
            placeholder: 'Select a product',
            ajax: {
                url: '{{ route('products.get-receipt') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term
                }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.products.name,
                        data: {
                            receipt_id: item.id,
                            product_id: item.product_id,
                        }
                    }))
                })
            }
        });


        $('#staff_id').select2({
            placeholder: 'Select a staff',
            ajax: {
                url: '{{ route('staff.get-staff') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term
                }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                })
            }
        });

        $('#product_id').on('change', function() {
            const productId = $(this).val();
            const productionId = {{ $data->id }};
            var url = `{{ url('production/get-detail/${productionId}') }}`;

            $.ajax({
                url: `/production/delete-detail/${productionId}`, // Ganti dengan URL yang sesuai
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    receipt_id: productId,
                },
                success: function(response) {
                    // Reload DataTable setelah berhasil menghapus data
                    if (typeof tableSelectedProduct !== 'undefined') {
                        tableSelectedProduct.ajax.reload(null, false);
                    }
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

            $.ajax({
                url: `/production/update-product-id/${productionId}`, // Ganti dengan URL yang sesuai
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    receipt_id: productId,
                },
                success: function(response) {
                    console.log(response);
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



            // Jika DataTable sudah ada, destroy dan bersihkan
            if ($.fn.DataTable.isDataTable('#kt_ecommerce_edit_order_selected_products_table')) {
                $('#kt_ecommerce_edit_order_selected_products_table').DataTable().clear().destroy();
                // $('#kt_ecommerce_edit_order_selected_products_table').empty(); // penting!
            }

            tableSelectedProduct = $('#kt_ecommerce_edit_order_selected_products_table').DataTable({
                processing: true,
                serverSide: false,
                info: false,
                paging: false,
                ajax: url,
                fixedColumns: {
                    leftColumns: 0, // Tidak ada kolom di sisi kiri yang dibekukan
                    rightColumns: 1 // Membekukan 1 kolom di sisi kanan (kolom action)
                },
                columnDefs: [{
                    orderable: false,
                    targets: -1 // Nonaktifkan sorting untuk kolom action
                }], // Ganti dengan route untuk ambil data
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'hpp',
                        name: 'hpp'
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada produk yang dipilih."
                }
            });
        });

        function setSubmitType(type) {
            document.getElementById('submit_type').value = type;
        }

        function calculateQuantity() {
            // Ambil value dari input price
            let price = parseFloat(
                document.getElementById('inputPrice').value.replace(/[.,]/g, '')
            ) || 0;
            let sellPrice = parseFloat(
                document.getElementById('inputSellPricePrc').value.replace(/[.,]/g, '')
            ) || 0;

            // Cek biar nggak bagi nol
            let result = 0;
            if (sellPrice !== 0) {
                result = price / sellPrice;
            }

            // Set hasil ke inputQuantityPrc, fix 2 angka desimal
            document.getElementById('inputQuantityPrc').value = result.toFixed(2);
        }
        document.getElementById('inputPrice').addEventListener('keyup', calculateQuantity);

        //Weight to Price
        $('#submitQty').on('click', function() {
            const qty = parseFloat($('#inputQuantity').val());
            const sellPrice = parseFloat(unformatNumber($('#inputSellPrice').val()));
            const id = $('#inputProductId').val();

            // if (!qty || qty <= 0) {
            //     Swal.fire("Error", "Quantity harus diisi dan lebih dari 0.", "error");
            //     return;
            // }

            // Kirim data ke server via AJAX
            $.ajax({
                url: "{{ route('production.save-ajax') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: id,
                    qty: qty,
                    sell_price: sellPrice,
                    production_id: '{{ $data->id }}'
                },
                success: function(response) {
                    $('#modalInputQty').modal('hide');
                    // 6. Refresh DataTable
                    if (typeof tableSelectedProduct !== 'undefined') {
                        tableSelectedProduct.ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    var msg = 'Terjadi kesalahan saat menyimpan data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                }
            });
        });

        $('#kt_modal_add_customer_form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');
            var submitBtn = $('#kt_modal_add_customer_submit');
            // console.log(form.serialize());

            // Show loading
            submitBtn.prop('disabled', true);
            submitBtn.find('.indicator-label').hide();
            submitBtn.find('.indicator-progress').show();

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(), // gunakan FormData(form)[... jika pakai file]
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Data berhasil disimpan.',
                        showConfirmButton: false,
                        timer: 1500 // notifikasi akan hilang otomatis setelah 1.5 detik
                    }).then(() => {
                        // 1. Reset form
                        form.trigger('reset');

                        // 5. Tutup modal
                        const modalEl = document.getElementById('kt_modal_add_customer');
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        modalInstance.hide();

                        if (typeof tableSelectedProduct !== 'undefined') {
                            tableSelectedProduct.ajax.reload(null, false);
                        }
                    });
                },
                error: function(xhr) {
                    var msg = 'Terjadi kesalahan saat menyimpan data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                },
                complete: function() {
                    // Reset loading state
                    submitBtn.prop('disabled', false);
                    submitBtn.find('.indicator-label').show();
                    submitBtn.find('.indicator-progress').hide();
                }
            });
        });

        // Price to Weight
        $('#modalInputPrcForm').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');
            var submitBtn = $('#kt_modal_add_customer_submit_prc');
            console.log(form.serialize());

            // Show loading
            submitBtn.prop('disabled', true);
            submitBtn.find('.indicator-label').hide();
            submitBtn.find('.indicator-progress').show();

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize(), // gunakan FormData(form)[... jika pakai file]
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Data berhasil disimpan.',
                        showConfirmButton: false,
                        timer: 1500 // notifikasi akan hilang otomatis setelah 1.5 detik
                    }).then(() => {
                        // 1. Reset form
                        form.trigger('reset');

                        // 5. Tutup modal
                        const modalEl = document.getElementById('modalInputPrc');
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        modalInstance.hide();

                        if (typeof tableSelectedProduct !== 'undefined') {
                            tableSelectedProduct.ajax.reload(null, false);
                        }
                    });
                },
                error: function(xhr) {
                    var msg = 'Terjadi kesalahan saat menyimpan data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                },
                complete: function() {
                    // Reset loading state
                    submitBtn.prop('disabled', false);
                    submitBtn.find('.indicator-label').show();
                    submitBtn.find('.indicator-progress').hide();
                }
            });
        });

        // Additional JavaScript code for handling the modal and form submission
        @if (isset($data))
            const productionId = {{ $data->id }};
            var url = `{{ url('production/get-detail/${productionId}') }}`;
            tableSelectedProduct = $('#kt_ecommerce_edit_order_selected_products_table').DataTable({
                processing: true,
                serverSide: false,
                info: false,
                paging: false,
                ajax: url,
                fixedColumns: {
                    leftColumns: 0, // Tidak ada kolom di sisi kiri yang dibekukan
                    rightColumns: 1 // Membekukan 1 kolom di sisi kanan (kolom action)
                },
                columnDefs: [{
                    orderable: false,
                    targets: -1 // Nonaktifkan sorting untuk kolom action
                }], // Ganti dengan route untuk ambil data
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'hpp',
                        name: 'hpp'
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada produk yang dipilih."
                }
            });
        @endif
    </script>
@endsection

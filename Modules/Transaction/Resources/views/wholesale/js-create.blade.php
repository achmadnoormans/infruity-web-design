@section('script')
    <script>
        wholsaleId = {{ $data->id }};
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
                                data-kt-ecommerce-edit-order-type="${row.type}"
                                data-kt-ecommerce-edit-order-price="${row.price}">
                                <div class="ms-5">
                                    <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold">${data}</a>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'qty_remaining',
                        name: 'qty_remaining',
                        className: 'text-end pe-5'
                    }
                ]
            });

            $('#search').on('keyup', function() {
                listDatatable.search(this.value).draw();
            });

            // const table = $('#kt_ecommerce_edit_order_product_table').DataTable({
            //     order: [],
            //     scrollY: "400px",
            //     scrollCollapse: true,
            //     paging: false,
            //     info: false,
            //     columnDefs: [{
            //         orderable: false,
            //         targets: 0
            //     }]
            // });

            var url = `{{ url('wholesale/get-product/${wholsaleId}') }}`;
            console.log(url);
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
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-end',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: "Tidak ada produk yang dipilih."
                }
            });

            $('#inputSupplier').select2({
                dropdownParent: $('#modalInputQty'),
                width: '100%',
                placeholder: 'Choose supplier',
                allowClear: true
            });

            // Checkbox change event
            $('#kt_ecommerce_edit_order_product_table').on('click', '.check-product', function() {
                console.log('clik');
                const row = $(this).closest('tr');
                const checked = $(this).is(':checked');
                const productId = row.find('[data-kt-ecommerce-edit-order-id]').data(
                    'kt-ecommerce-edit-order-id');
                const type = row.find('[data-kt-ecommerce-edit-order-id]').data(
                    'kt-ecommerce-edit-order-type');
                const price = row.find('[data-kt-ecommerce-edit-order-id]').data(
                    'kt-ecommerce-edit-order-price');

                const productName = row.find('a.text-gray-800').text().trim();
                // const productImage = row.find('.symbol-label').css('background-image').replace(
                //     /^url\(["']?/, '').replace(/["']?\)$/, '');
                // const price = row.find('[data-kt-ecommerce-edit-order-filter="price"]').text().trim();

                selectedProduct = {
                    id: productId,
                    name: productName,
                    // image: productImage,
                    price: price,
                    type: type
                };
                console.log(selectedProduct);
                $('#inputProductId').val(productId);
                $('#inputSellPrice').val(price);
                $('#typeList').val(type);
                $('#inputQuantity').val('');
                $('#modalInputQty').modal('show');
            });

            // Tombol Add Product dari modal
            $('#submitQty').on('click', function() {
                const qty = parseInt($('#inputQuantity').val());
                const price = parseFloat($('#inputPrice').val());
                const sellPrice = parseFloat($('#inputSellPrice').val());
                const supplierId = $('#inputSupplier').val();
                const supplierText = $('#inputSupplier option:selected').text();
                const id = $('#inputProductId').val();
                const type = $('#typeList').val();

                if (!qty || qty <= 0) {
                    Swal.fire("Error", "Quantity harus diisi dan lebih dari 0.", "error");
                    return;
                }

                if (!price || price < 0) {
                    Swal.fire("Error", "Harga harus diisi dan tidak boleh negatif.", "error");
                    return;
                }

                if (!supplierId) {
                    Swal.fire("Error", "Supplier harus dipilih.", "error");
                    return;
                }

                const total = qty * price;

                // Kirim data ke server via AJAX
                $.ajax({
                    url: "{{ route('wholesale.save-product') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        qty: qty,
                        price: price,
                        sell_price: sellPrice,
                        supplier_id: supplierId,
                        type: type,
                        wholesale_id: wholsaleId
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

            $("#kt_ecommerce_edit_order_date").flatpickr({
                altInput: !0,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d"
            });

            tableSelectedProduct.on('draw', function() {
                let total = tableSelectedProduct.column(2, {
                    page: 'current'
                }).data().reduce(function(a, b) {
                    let cleanedB = 0;

                    if (typeof b === 'string') {
                        // Hilangkan "Rp.", spasi, dan titik
                        cleanedB = b.replace(/Rp\.?\s?/g, '').replace(/\./g, '').replace(',', '.');
                    } else {
                        cleanedB = b;
                    }

                    return parseFloat(a) + parseFloat(cleanedB || 0);
                }, 0);

                $('#totalSemuaProduk').text(total.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }));
            });
        });

        $("#kt_ecommerce_edit_order_form").submit(function() {
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
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
                        url: `/wholesale/delete-product/${id}`, // Ganti dengan URL yang sesuai
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
            var url = `{{ url('wholesale/edit-product/${id}') }}`;
            var urlUpdate = `{{ url('wholesale/update-product/${id}') }}`;

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    // Set nilai input
                    $('#inputPriceEdit').val(response.data.price);
                    $('#inputQuantityEdit').val(response.data.quantity);
                    $('#inputSupplierEdit').val(response.data.supplier_id).trigger('change');

                    // Set action dan method form
                    var form = $('#kt_modal_add_customer_form');
                    form.attr('action', urlUpdate);
                    $('#methodField').val('PUT');

                    // Tampilkan modal
                    var modal = new bootstrap.Modal(document.getElementById('kt_modal_add_customer'));
                    modal.show();
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


        $('#kt_modal_add_customer_form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');
            var submitBtn = $('#kt_modal_add_customer_submit');

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
                        text: response.message || 'Data berhasil disimpan.'
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
    </script>
@endsection

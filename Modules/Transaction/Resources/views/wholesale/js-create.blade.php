@section('script')
    <script>
        wholsaleId = {{ $data->id }};
        let selectedProduct = {}; // Global, satu kali saja
        var tableSelectedProduct;

        $(document).ready(function() {
            const table = $('#kt_ecommerce_edit_order_product_table').DataTable({
                order: [],
                scrollY: "400px",
                scrollCollapse: true,
                paging: false,
                info: false,
                columnDefs: [{
                    orderable: false,
                    targets: 0
                }]
            });

            var url = `{{ url('wholesale/get-product/${wholsaleId}') }}`;
            console.log(url);
            tableSelectedProduct = $('#kt_ecommerce_edit_order_selected_products_table').DataTable({
                processing: true,
                serverSide: false,
                ajax: url, // Ganti dengan route untuk ambil data
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
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
                        data: 'supplier',
                        name: 'supplier'
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

            $('#inputSupplier').select2({
                dropdownParent: $('#modalInputQty'),
                width: '100%',
                placeholder: 'Choose supplier',
                allowClear: true
            });

            document.querySelector('[data-kt-ecommerce-edit-order-filter="search"]').addEventListener("keyup",
                function(e) {
                    table.search(e.target.value).draw();
                });

            // Checkbox change event
            $('#kt_ecommerce_edit_order_product_table').on('change', '.form-check-input', function() {
                const row = $(this).closest('tr');
                const checked = $(this).is(':checked');
                const productId = row.find('[data-kt-ecommerce-edit-order-id]').data(
                    'kt-ecommerce-edit-order-id');
                const type = row.find('[data-kt-ecommerce-edit-order-id]').data(
                    'kt-ecommerce-edit-order-type');

                if (checked) {
                    const productName = row.find('a.text-gray-800').text().trim();
                    // const productImage = row.find('.symbol-label').css('background-image').replace(
                    //     /^url\(["']?/, '').replace(/["']?\)$/, '');
                    const price = row.find('[data-kt-ecommerce-edit-order-filter="price"]').text().trim();

                    selectedProduct = {
                        id: productId,
                        name: productName,
                        // image: productImage,
                        price: price,
                        type: type
                    };
                    console.log(selectedProduct);
                    $('#inputProductId').val(productId);
                    $('#typeList').val(type);
                    $('#inputQuantity').val('');
                    $('#modalInputQty').modal('show');
                } else {
                    $(`#kt_ecommerce_edit_order_selected_products [data-product-id="${productId}"]`)
                        .remove();
                    $(`#selected-products-hidden input[name="products[${productId}][id]"]`).remove();
                    $(`#selected-products-hidden input[name="products[${productId}][qty]"]`).remove();

                    if ($('#kt_ecommerce_edit_order_selected_products .col').length === 0) {
                        $('#kt_ecommerce_edit_order_selected_products').html(
                            `<span class="w-100 text-muted">Select one or more products from the list below by ticking the checkbox.</span>`
                        );
                    }
                }
            });

            // Tombol Add Product dari modal
            $('#submitQty').on('click', function() {
                const qty = parseInt($('#inputQuantity').val());
                const price = parseFloat($('#inputPrice').val());
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
                        supplier_id: supplierId,
                        type: type,
                        wholesale_id: wholsaleId
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan.'
                        }).then(() => {
                            $('#modalInputQty').modal('hide');
                            // 6. Refresh DataTable
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
                    }
                });
            });

            $("#kt_ecommerce_edit_order_date").flatpickr({
                altInput: !0,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d"
            });
        });

        $("form").submit(function() {
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil dihapus.'
                            });

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
                url: url, // URL untuk mengambil data produk yang akan diedit
                type: 'GET',
                success: function(response) {
                    // Isi form dengan data produk yang ada
                    console.log(response);
                    $('input[name="price"]').val(response.price);
                    $('input[name="qty"]').val(response.quantity);
                    

                    // Ubah action form untuk update
                    var form = $('#kt_modal_add_customer_form');
                    form.attr('action', urlUpdate); // URL untuk update produk
                    form.find('input[name="_method"]').remove(); // Hapus input _method jika ada
                    form.append(
                        '<input type="hidden" name="_method" value="PUT">'
                    ); // Menambahkan input _method untuk PUT

                    // Tampilkan modal untuk edit produk
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
    </script>
@endsection

@section('script')
    <script>
        let selectedProduct = {}; // Global, satu kali saja

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

                if (checked) {
                    const productName = row.find('a.text-gray-800').text().trim();
                    // const productImage = row.find('.symbol-label').css('background-image').replace(
                    //     /^url\(["']?/, '').replace(/["']?\)$/, '');
                    const price = row.find('[data-kt-ecommerce-edit-order-filter="price"]').text().trim();

                    selectedProduct = {
                        id: productId,
                        name: productName,
                        // image: productImage,
                        price: price
                    };

                    $('#inputProductId').val(productId);
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

                // Remove placeholder
                $('#kt_ecommerce_edit_order_selected_products span.text-muted').remove();

                // Cek dan hapus duplikat
                $(`#kt_ecommerce_edit_order_selected_products [data-product-id="${id}"]`).remove();
                $(`#selected-products-hidden input[name="products[${id}][id]"]`).remove();
                $(`#selected-products-hidden input[name="products[${id}][qty]"]`).remove();
                $(`#selected-products-hidden input[name="products[${id}][price]"]`).remove();
                $(`#selected-products-hidden input[name="products[${id}][supplier_id]"]`).remove();

                // Tambah tampilan produk terpilih
                // Cek apakah ini produk pertama, jika iya hapus placeholder
                const $tbody = $('#kt_ecommerce_edit_order_selected_products_body');
                $tbody.find('tr.text-muted').remove();
                const html = `
                    <tr data-product-id="${selectedProduct.id}">
                        <td>
                            <div class="fw-bold">${selectedProduct.name}</div>
                        </td>
                        <td>${qty}</td>
                        <td>Rp. ${price.toLocaleString()}</td>
                        <td>Rp. ${total.toLocaleString()}</td>
                        <td>${supplierText}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-danger btn-remove-product" data-product-id="${selectedProduct.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $tbody.append(html);

                // Tambah input hidden untuk dikirim ke server
                const hiddenInputs = `
                    <input type="hidden" name="products[${id}][id]" value="${id}">
                    <input type="hidden" name="products[${id}][qty]" value="${qty}">
                    <input type="hidden" name="products[${id}][price]" value="${price}">
                    <input type="hidden" name="products[${id}][supplier_id]" value="${supplierId}">
                `;
                $('#selected-products-hidden').append(hiddenInputs);

                // Tutup modal
                $('#modalInputQty').modal('hide');
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

        $(document).on('click', '.btn-remove-product', function() {
            const id = $(this).data('product-id');

            $(`#kt_ecommerce_edit_order_selected_products_body tr[data-product-id="${id}"]`).remove();
            $(`#selected-products-hidden input[name="products[${id}][id]"]`).remove();
            $(`#selected-products-hidden input[name="products[${id}][qty]"]`).remove();
            $(`#selected-products-hidden input[name="products[${id}][price]"]`).remove();
            $(`#selected-products-hidden input[name="products[${id}][supplier_id]"]`).remove();

            // Jika sudah tidak ada produk, tampilkan placeholder
            if ($('#kt_ecommerce_edit_order_selected_products_body tr').length === 0) {
                $('#kt_ecommerce_edit_order_selected_products_body').append(`
            <tr class="text-muted text-center">
                <td colspan="6">Select one or more products from the list below by ticking the checkbox.</td>
            </tr>
        `);
            }
        });
    </script>
@endsection

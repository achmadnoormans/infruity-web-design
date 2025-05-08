@section('script')
    <script>
        let selectedProduct = {}; // Global, satu kali saja
        const previouslySelected = @json($selectedProducts);

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
                    const productImage = row.find('.symbol-label').css('background-image').replace(
                        /^url\(["']?/, '').replace(/["']?\)$/, '');
                    const price = row.find('[data-kt-ecommerce-edit-order-filter="price"]').text().trim();

                    selectedProduct = {
                        id: productId,
                        name: productName,
                        image: productImage,
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
                const qty = $('#inputQuantity').val();
                const id = $('#inputProductId').val();

                if (!qty || qty <= 0) {
                    alert("Quantity harus diisi dan lebih dari 0.");
                    return;
                }

                // Remove placeholder
                $('#kt_ecommerce_edit_order_selected_products span.text-muted').remove();

                // Cek dan hapus dulu jika sudah ada produk ini sebelumnya (prevent duplikat)
                $(`#kt_ecommerce_edit_order_selected_products [data-product-id="${id}"]`).remove();
                $(`#selected-products-hidden input[name="products[${id}][id]"]`).remove();
                $(`#selected-products-hidden input[name="products[${id}][qty]"]`).remove();

                // Tambah ke list
                const html = `
                <div class="col mb-4" data-product-id="${selectedProduct.id}">
                    <div class="border p-3 rounded bg-light">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50px me-3">
                                <span class="symbol-label" style="background-image:url('${selectedProduct.image}');"></span>
                            </div>
                            <div>
                                <div class="fw-bold">${selectedProduct.name}</div>
                                <div class="text-muted">Qty: ${qty}</div>
                                <div class="text-muted">Price: Rp. ${selectedProduct.price}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                $('#kt_ecommerce_edit_order_selected_products').append(html);

                // Tambah hidden input
                const hiddenInput = `
                <input type="hidden" name="products[${selectedProduct.id}][id]" value="${selectedProduct.id}">
                <input type="hidden" name="products[${selectedProduct.id}][quantity]" value="${qty}">
            `;
                $('#selected-products-hidden').append(hiddenInput);

                $('#modalInputQty').modal('hide');
            });

            $("#kt_ecommerce_edit_order_date").flatpickr({
                altInput: !0,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d"
            });

            if (previouslySelected.length > 0) {
                $('#kt_ecommerce_edit_order_selected_products span.text-muted').remove(); // hapus placeholder

                previouslySelected.forEach(item => {
                    const html = `
                        <div class="col mb-4" data-product-id="${item.id}">
                            <div class="border p-3 rounded bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label" style="background-image:url('${item.image}');"></span>
                                    </div>
                                    <div>
                                        <div class="fw-bold">${item.name}</div>
                                        <div class="text-muted">Qty: ${item.qty}</div>
                                        <div class="text-muted">Price: Rp. ${item.price}</div>
                                    </div>
                                </div>
                                <!-- hidden inputs -->
                                <input type="hidden" name="products[${item.id}][id]" value="${item.id}">
                                <input type="hidden" name="products[${item.id}][quantity]" value="${item.qty}">
                            </div>
                        </div>
                    `;
                    $('#kt_ecommerce_edit_order_selected_products').append(html);

                    // Optional: centang juga checkbox-nya
                    $(`input.form-check-input[data-product-id="${item.id}"]`).prop('checked', true);
                });
            }
        });

        $(document).on('click', '.remove-product', function() {
            const productId = $(this).data('product-id');

            // Hapus produk dari tampilan
            $(`.product-item[data-product-id="${productId}"]`).remove();

            // Bisa juga kirim request untuk menghapus produk dari database
            // Misalnya, menggunakan Ajax untuk menghapus dari database
            $.ajax({
                url: '/wholesale-product/' + productId, // Endpoint untuk hapus
                method: 'DELETE',
                success: function(response) {
                    console.log('Product removed successfully');
                }
            });
        });

        $("form").submit(function() {
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
        });
    </script>
@endsection

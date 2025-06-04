@section('script')
    <script>
        let selectedProduct = {}; // Global, satu kali saja
        var tableSelectedProduct;

        $(document).ready(function() {

            // Checkbox change event

            $("#kt_ecommerce_edit_order_date").flatpickr({
                altInput: !0,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d"
            });

        });

        $("#kt_ecommerce_edit_order_form").submit(function() {
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
        });

        function addVariant() {
            let html = `
            <tr>
                <td>
                    <select name="product_receipt_id[]" class="form-select mb-2 select2_product">
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" name="ingredients_quantity[]" value="" class="form-control mb-2" placeholder="Product quantity" />
                </td>                    
                <td class="text-end">
                    <button type="button" class="btn btn-icon btn-danger remove_variant">
                        <i class="ki-outline ki-cross fs-2"></i>
                    </button>
                </td>
            </tr>
            `;
            $('#kt_ecommerce_edit_order_selected_products_body').append(html);
            $('#kt_ecommerce_edit_order_selected_products_body .select2_product').select2({
                placeholder: 'Select product',
                ajax: {
                    url: "{{ route('ajax.getProduct') }}",
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
            $('#variant_table').on('click', '.remove_variant', function() {
                $(this).closest('tr').remove();
            }); // Re-bind ke elemen baru setelah append
        }
        $('#variant_table').on('click', '.remove_variant', function() {
            $(this).closest('tr').remove();
        });

        $('#product_id').select2({
            placeholder: 'Select a product',
            ajax: {
                url: '{{ route('products.get-product-receipt') }}',
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

            $.ajax({
                url: "{{ route('products.get-receipt') }}",
                type: 'GET',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    // console.log(response);
                    // Bersihkan isi sebelumnya jika perlu
                    $('#kt_ecommerce_edit_order_selected_products_body').empty();

                    // Loop hasil response
                    response.forEach(item => {
                        let html = `
                            <tr>
                                <td>
                                    <select name="product_receipt_id[]" class="form-select mb-2 select2_product">
                                        <option value="${item.id}" selected>${item.ingredients.name}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="ingredients_quantity[]" value="${item.quantity || ''}" class="form-control mb-2" placeholder="Product quantity" />
                                </td>                    
                                <td class="text-end">
                                    <button type="button" class="btn btn-icon btn-danger remove_variant">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                    </button>
                                </td>
                            </tr>
                            `;
                        $('#kt_ecommerce_edit_order_selected_products_body').append(html);
                    });

                    $('#kt_ecommerce_edit_order_selected_products_body .select2_product').select2({
                        placeholder: 'Select product',
                        ajax: {
                            url: "{{ route('ajax.getProduct') }}",
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
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });

        function setSubmitType(type) {
            document.getElementById('submit_type').value = type;
        }

        $('#quantity').on('change', function() {
            const quantity = $(this).val();
            const productId = $('#product_id').val();

            $.ajax({
                url: "{{ route('products.get-receipt') }}",
                type: 'GET',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    // console.log(response);
                    // Bersihkan isi sebelumnya jika perlu
                    $('#kt_ecommerce_edit_order_selected_products_body').empty();

                    // Loop hasil response
                    response.forEach(item => {
                        let html = `
                            <tr>
                                <td>
                                    <select name="product_receipt_id[]" class="form-select mb-2 select2_product">
                                        <option value="${item.id}" selected>${item.ingredients.name}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="ingredients_quantity[]" value="${item.quantity * quantity || ''}" class="form-control mb-2" placeholder="Product quantity" />
                                </td>                    
                                <td class="text-end">
                                    <button type="button" class="btn btn-icon btn-danger remove_variant">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                    </button>
                                </td>
                            </tr>
                            `;
                        $('#kt_ecommerce_edit_order_selected_products_body').append(html);
                    });

                    $('#kt_ecommerce_edit_order_selected_products_body .select2_product').select2({
                        placeholder: 'Select product',
                        ajax: {
                            url: "{{ route('ajax.getProduct') }}",
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
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });

        @if (isset($production_detail) && $production_detail->count() > 0)
            const response = {!! json_encode($production_detail) !!};
            console.log(response);
            $('#kt_ecommerce_edit_order_selected_products_body').empty();

            // Loop hasil response
            response.forEach(item => {
                let html = `
                            <tr>
                                <td>
                                    <select name="product_receipt_id[]" class="form-select mb-2 select2_product">
                                        <option value="${item.product_id}" selected>${item.products.name}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="ingredients_quantity[]" value="${item.quantity || ''}" class="form-control mb-2" placeholder="Product quantity" />
                                </td>                    
                                <td class="text-end">
                                    <button type="button" class="btn btn-icon btn-danger remove_variant">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                    </button>
                                </td>
                            </tr>
                            `;
                $('#kt_ecommerce_edit_order_selected_products_body').append(html);
            });

            $('#kt_ecommerce_edit_order_selected_products_body .select2_product').select2({
                placeholder: 'Select product',
                ajax: {
                    url: "{{ route('ajax.getProduct') }}",
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
        @endif
    </script>
@endsection
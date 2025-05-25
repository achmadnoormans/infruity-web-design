@extends('template.root')

@section('content')
    <form id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row"
        action="{{ isset($data) ? url(Request::segment(1) . '/' . $data->id) : url(Request::segment(1)) }}" method="POST"
        enctype="multipart/form-data" data-kt-redirect="">
        @if (isset($data))
            @method('PUT')
        @endif
        @csrf
        <!--begin::Aside column-->
        <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
            <!--begin::Order details-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Production Details</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Production ID</label>
                            <!--end::Label-->
                            <!--begin::Auto-generated ID-->
                            <div class="fw-bold fs-3">#{{ isset($data) ? $data->production_number : '14364' }}</div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Production Name</label>
                            <!--end::Label-->
                            <!--begin::Auto-generated ID-->
                            <select name="product_id" id="product_id" class="form-select mb-2">
                                @if (isset($selectedProduct) && $selectedProduct != null)
                                    <option value="{{ $selectedProduct->id }}" selected>{{ $selectedProduct->name }}
                                    </option>
                                @endif
                            </select>
                            <input type="hidden" name="submit_type" id="submit_type" value="draft">
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Quantity</label>
                            <!--end::Label-->
                            <!--begin::Auto-generated ID-->
                            <input type="number" name="quantity" id="quantity" class="form-control mb-2"
                                placeholder="Enter Quantity"
                                value="{{ isset($data) ? $data->quantity : old('quantity') }}" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Production Date</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input id="kt_ecommerce_edit_order_date" name="production_date" placeholder="Select a date"
                                class="form-control mb-2" value="{{ old('production_date') ?? date('Y-m-d') }}" />
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set the date of the production to process.</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--end::Card header-->
            </div>
            <!--end::Order details-->
        </div>
        <!--end::Aside column-->
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
            <!--begin::Order details-->
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Pilih Bahan</h2>
                    </div>
                </div>
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <!--begin::Table-->
                        <div class="table table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-3 mb-5" id="variant_table">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="kt_ecommerce_edit_order_selected_products_body">
                                </tbody>
                            </table>
                        </div>
                        <!--end::Input group-->
                        <button class="variant btn btn-light-primary btn-sm mb-10" type="button" onclick="addVariant()">
                            <i class="ki-outline ki-plus fs-2"></i>Tambah Product
                        </button>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Card header-->
            </div>
            <!--end::Order details-->

            <div class="d-flex justify-content-end">
                <!--begin::Button-->
                <a href="{{ url(Request::segment(1)) }}" id="kt_ecommerce_edit_order_cancel"
                    class="btn btn-light me-5">Cancel</a>
                <!--end::Button-->

                <button type="submit" class="btn btn-primary me-2" onclick="setSubmitType('draft')">
                    <span class="indicator-label">Draft</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>

                <button type="submit" class="btn btn-primary" onclick="setSubmitType('posting')">
                    <span class="indicator-label">Posting</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Button-->
            </div>
        </div>
        <!--end::Main column-->
    </form>
    {{-- @include('transaction::wholesale.js-create') --}}
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

@endsection

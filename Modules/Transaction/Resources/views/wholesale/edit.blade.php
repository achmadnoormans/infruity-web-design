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
                        <h2>Order Details</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Order ID</label>
                            <!--end::Label-->
                            <!--begin::Auto-generated ID-->
                            <div class="fw-bold fs-3">#14364</div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Supplier</label>
                            <!--end::Label-->
                            <!--begin::Select2-->
                            <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                                data-placeholder="Select an option" name="supplier_id"
                                id="kt_ecommerce_edit_order_supplier">
                                <option></option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ $data->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Select2-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set the date of the order to process.</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Order Date</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input id="kt_ecommerce_edit_order_date" name="order_date" placeholder="Select a date"
                                class="form-control mb-2" value="{{ $data->order_date }}">
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set the date of the order to process.</div>
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
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Select Products</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Add products to this order</label>
                            <!--end::Label-->
                            <!--begin::Selected products-->
                            <div class="row row-cols-1 row-cols-xl-3 row-cols-md-2 border border-dashed rounded pt-3 pb-1 px-2 mb-5 mh-300px overflow-scroll"
                                id="kt_ecommerce_edit_order_selected_products">
                                <!--begin::Empty message-->

                                <!--end::Empty message-->
                            </div>
                            <div id="selected-products-hidden"></div>
                            <!--begin::Selected products-->
                            {{-- <!--begin::Total price-->
                            <div class="fw-bold fs-4">Total Cost: $
                                <span id="kt_ecommerce_edit_order_total_price">0.00</span>
                            </div>
                            <!--end::Total price--> --}}
                        </div>
                        <!--end::Input group-->
                        <!--begin::Separator-->
                        <div class="separator"></div>
                        <!--end::Separator-->
                        <!--begin::Search products-->
                        <div class="d-flex align-items-center position-relative mb-n7">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-ecommerce-edit-order-filter="search"
                                class="form-control form-control-solid w-100 w-lg-50 ps-12" placeholder="Search Products" />
                        </div>
                        <!--end::Search products-->
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5"
                            id="kt_ecommerce_edit_order_product_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-25px pe-2"></th>
                                    <th class="min-w-200px">Product</th>
                                    <th class="min-w-100px text-end pe-5">Qty Remaining</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    data-product-id={{ $product->id }} />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center"
                                                data-kt-ecommerce-edit-order-filter="product"
                                                data-kt-ecommerce-edit-order-id="{{ $product->id }}">
                                                <!--begin::Thumbnail-->
                                                <a href="{{ url('products/' . $product->id) }}" class="symbol symbol-50px">
                                                    <span class="symbol-label"
                                                        style="background-image:url({{ asset('storage/' . $product->image) }});"></span>
                                                </a>
                                                <!--end::Thumbnail-->
                                                <div class="ms-5">
                                                    <!--begin::Title-->
                                                    <a href="{{ url('products/' . $product->id) }}"
                                                        class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $product->name }}</a>
                                                    <!--end::Title-->
                                                    <!--begin::Price-->
                                                    <div class="fw-semibold fs-7">Price: Rp.
                                                        <span
                                                            data-kt-ecommerce-edit-order-filter="price">{{ toNumber($product->price) }}</span>
                                                    </div>
                                                    <!--end::Price-->
                                                    <!--begin::SKU-->
                                                    <div class="text-muted fs-7">SKU: {{ $product->sku }}</div>
                                                    <!--end::SKU-->
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-5" data-order="42">
                                            <span class="fw-bold ms-3">42</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Card header-->
            </div>
            <!--end::Order details-->
            <!--begin::Order details-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Wholesale Note</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Billing address-->
                    <div class="d-flex flex-column gap-5 gap-md-7">
                        <textarea name="description" id="description" class="form-control form-control-solid" rows="5"
                            placeholder="Enter your notes here...">{{ isset($data) ? $data->description : '' }}</textarea>
                    </div>
                    <!--end::Billing address-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Order details-->
            <!-- Modal for Quantity Input -->
            <div class="modal fade" id="modalInputQty" tabindex="-1" aria-labelledby="modalInputQtyLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalInputQtyLabel">Input Quantity</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="inputProductId">
                            <div class="mb-3">
                                <label for="inputQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="inputQuantity"
                                    placeholder="Enter quantity" min="1">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="submitQty">Add Product</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <!--begin::Button-->
                <a href="apps/ecommerce/catalog/products.html" id="kt_ecommerce_edit_order_cancel"
                    class="btn btn-light me-5">Cancel</a>
                <!--end::Button-->
                <!--begin::Button-->
                <button type="submit" id="kt_ecommerce_edit_order_submit" class="btn btn-primary">
                    <span class="indicator-label">Save Changes</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Button-->
            </div>
        </div>
        <!--end::Main column-->
    </form>
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
                <input type="hidden" name="products[${selectedProduct.id}][qty]" value="${qty}">
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
    </script>
@endsection
@endsection

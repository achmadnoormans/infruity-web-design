@extends('template.root')

@section('content')
    <form id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row"
        data-kt-redirect="apps/ecommerce/sales/listing.html">
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
                            <label class="required form-label">Payment Method</label>
                            <!--end::Label-->
                            <!--begin::Select2-->
                            <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                                data-placeholder="Select an option" name="payment_method"
                                id="kt_ecommerce_edit_order_payment">
                                <option></option>
                                <option value="cod">Cash on Delivery</option>
                                <option value="visa">Credit Card (Visa)</option>
                                <option value="mastercard">Credit Card (Mastercard)</option>
                                <option value="paypal">Paypal</option>
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
                            <label class="required form-label">Shipping Method</label>
                            <!--end::Label-->
                            <!--begin::Select2-->
                            <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                                data-placeholder="Select an option" name="shipping_method"
                                id="kt_ecommerce_edit_order_shipping">
                                <option></option>
                                <option value="none">N/A - Virtual Product</option>
                                <option value="standard">Standard Rate</option>
                                <option value="express">Express Rate</option>
                                <option value="speed">Speed Overnight Rate</option>
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
                                class="form-control mb-2" value="" />
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
                                <span class="w-100 text-muted">Select one or more products from the list below by ticking
                                    the checkbox.</span>
                                <!--end::Empty message-->
                            </div>
                            <!--begin::Selected products-->
                            <!--begin::Total price-->
                            <div class="fw-bold fs-4">Total Cost: $
                                <span id="kt_ecommerce_edit_order_total_price">0.00</span>
                            </div>
                            <!--end::Total price-->
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
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="1" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center" data-kt-ecommerce-edit-order-filter="product"
                                            data-kt-ecommerce-edit-order-id="product_1">
                                            <!--begin::Thumbnail-->
                                            <a href="apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                <span class="symbol-label"
                                                    style="background-image:url(assets/media//stock/ecommerce/1.png);"></span>
                                            </a>
                                            <!--end::Thumbnail-->
                                            <div class="ms-5">
                                                <!--begin::Title-->
                                                <a href="apps/ecommerce/catalog/edit-product.html"
                                                    class="text-gray-800 text-hover-primary fs-5 fw-bold">Product 1</a>
                                                <!--end::Title-->
                                                <!--begin::Price-->
                                                <div class="fw-semibold fs-7">Price: $
                                                    <span data-kt-ecommerce-edit-order-filter="price">27.00</span>
                                                </div>
                                                <!--end::Price-->
                                                <!--begin::SKU-->
                                                <div class="text-muted fs-7">SKU: 02141002</div>
                                                <!--end::SKU-->
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-5" data-order="42">
                                        <span class="fw-bold ms-3">42</span>
                                    </td>
                                </tr>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" value="1" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center"
                                                data-kt-ecommerce-edit-order-filter="product"
                                                data-kt-ecommerce-edit-order-id="product_{{ $product->id }}">
                                                <!--begin::Thumbnail-->
                                                <a href="apps/ecommerce/catalog/edit-product.html"
                                                    class="symbol symbol-50px">
                                                    <span class="symbol-label"
                                                        style="background-image:url({{ asset('storage/' . $product->image) }});"></span>
                                                </a>
                                                <!--end::Thumbnail-->
                                                <div class="ms-5">
                                                    <!--begin::Title-->
                                                    <a href="apps/ecommerce/catalog/edit-product.html"
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
                        <h2>Delivery Details</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Billing address-->
                    <div class="d-flex flex-column gap-5 gap-md-7">
                        <!--begin::Title-->
                        <div class="fs-3 fw-bold mb-n2">Billing Address</div>
                        <!--end::Title-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column flex-md-row gap-5">
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Address Line 1</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control" name="billing_order_address_1" placeholder="Address Line 1"
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <div class="flex-row-fluid">
                                <!--begin::Label-->
                                <label class="form-label">Address Line 2</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control" name="billing_order_address_2"
                                    placeholder="Address Line 2" />
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column flex-md-row gap-5">
                            <div class="flex-row-fluid">
                                <!--begin::Label-->
                                <label class="form-label">City</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control" name="billing_order_city" placeholder="" value="" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">Postcode</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control" name="billing_order_postcode" placeholder=""
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="required form-label">State</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control" name="billing_order_state" placeholder="" value="" />
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Checkbox-->
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="" id="same_as_billing"
                                checked="checked" />
                            <label class="form-check-label" for="same_as_billing">Shipping address is the same as
                                billing address</label>
                        </div>
                        <!--end::Checkbox-->
                        <!--begin::Shipping address-->
                        <div class="d-none d-flex flex-column gap-5 gap-md-7" id="kt_ecommerce_edit_order_shipping_form">
                            <!--begin::Title-->
                            <div class="fs-3 fw-bold mb-n2">Shipping Address</div>
                            <!--end::Title-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="fv-row flex-row-fluid">
                                    <!--begin::Label-->
                                    <label class="form-label">Address Line 1</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control" name="kt_ecommerce_edit_order_address_1"
                                        placeholder="Address Line 1" value="" />
                                    <!--end::Input-->
                                </div>
                                <div class="flex-row-fluid">
                                    <!--begin::Label-->
                                    <label class="form-label">Address Line 2</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control" name="kt_ecommerce_edit_order_address_2"
                                        placeholder="Address Line 2" />
                                    <!--end::Input-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column flex-md-row gap-5">
                                <div class="flex-row-fluid">
                                    <!--begin::Label-->
                                    <label class="form-label">City</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control" name="kt_ecommerce_edit_order_city" placeholder=""
                                        value="" />
                                    <!--end::Input-->
                                </div>
                                <div class="fv-row flex-row-fluid">
                                    <!--begin::Label-->
                                    <label class="form-label">Postcode</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control" name="kt_ecommerce_edit_order_postcode" placeholder=""
                                        value="" />
                                    <!--end::Input-->
                                </div>
                                <div class="fv-row flex-row-fluid">
                                    <!--begin::Label-->
                                    <label class="form-label">State</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control" name="kt_ecommerce_edit_order_state" placeholder=""
                                        value="" />
                                    <!--end::Input-->
                                </div>
                            </div>
                            <!--end::Input group-->

                        </div>
                        <!--end::Shipping address-->
                    </div>
                    <!--end::Billing address-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Order details-->
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
        $(document).ready(function() {
            // Inisialisasi DataTable dan simpan ke variabel
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

            // Tambahkan event listener untuk filter pencarian
            document.querySelector('[data-kt-ecommerce-edit-order-filter="search"]').addEventListener("keyup",
                function(e) {
                    table.search(e.target.value).draw();
                });
        });
    </script>
@endsection
@endsection

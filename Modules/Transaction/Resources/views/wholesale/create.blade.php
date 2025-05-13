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
                            <div class="fw-bold fs-3">#{{ isset($data) ? $data->order_number : '14364' }}</div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Order Date</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input id="kt_ecommerce_edit_order_date" name="order_date" placeholder="Select a date"
                                class="form-control mb-2" value="{{ old('order_date') ?? date('Y-m-d') }}" />
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
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
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
                                                <input class="form-check-input" type="checkbox" value="1" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center"
                                                data-kt-ecommerce-edit-order-filter="product"
                                                data-kt-ecommerce-edit-order-id="{{ $product->id }}"
                                                data-kt-ecommerce-edit-order-type="{{ $product->type }}">
                                                <div class="ms-5">
                                                    <!--begin::Title-->
                                                    <a href="#"
                                                        class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $product->name }}</a>
                                                    <!--end::Title-->
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-5" data-order="42">
                                            <span class="fw-bold ms-3">0</span>
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
                            <div class="table table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-3 mb-5"
                                    id="kt_ecommerce_edit_order_selected_products_table">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-200px">Product</th>
                                            <th class="min-w-100px">Qty</th>
                                            <th class="min-w-100px">Price</th>
                                            <th class="min-w-100px">Total</th>
                                            <th class="min-w-150px">Supplier</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="kt_ecommerce_edit_order_selected_products_body">
                                        <tr class="text-muted text-center">
                                            <td colspan="6">Select one or more products from the list below by ticking
                                                the
                                                checkbox.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tempat hidden input -->
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
                            <h5 class="modal-title" id="modalInputQtyLabel">Input Product Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="inputProductId">
                            <input type="hidden" id="typeList">

                            <div class="mb-3">
                                <label for="inputSupplier" class="form-label">Supplier</label>
                                <select id="inputSupplier" class="form-select" data-control="select2">
                                    <option selected disabled>Choose supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="inputQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="inputQuantity"
                                    placeholder="Enter quantity" min="1">
                            </div>

                            <div class="mb-3">
                                <label for="inputPrice" class="form-label">Price</label>
                                <input type="number" class="form-control" id="inputPrice" placeholder="Enter price"
                                    min="0" step="0.01">
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
    <!-- Modal for Quantity Input -->
    <div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-labelledby="modalEditQtyLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form" action="{{ url(Request::segment(1)) }}" id="kt_modal_add_customer_form"
                    data-kt-redirect="#">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditQtyLabel">Edit Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" id="methodField" value="">
                        <div class="mb-3">
                            <label for="inputSupplier" class="form-label">Supplier</label>
                            <select id="inputSupplierEdit" class="form-select" data-control="select2" name="supplier_id">
                                <option selected disabled>Choose supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="inputQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="inputQuantityEdit" name="qty"
                                placeholder="Enter quantity" min="1" name="qty">
                        </div>

                        <div class="mb-3">
                            <label for="inputPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="inputPriceEdit" name="price"
                                placeholder="Enter price" min="0" step="0.01">
                        </div>
                    </div>
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_customer_cancel"
                            class="btn btn-light me-3">Discard</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->

                </form>
            </div>
        </div>
    </div>
    @include('transaction::wholesale.js-create')
@endsection

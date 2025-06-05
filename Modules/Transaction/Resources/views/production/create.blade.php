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
                            <div class="fw-bold fs-3">#{{ isset($data) ? $data->code : '14364' }}</div>
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
                            <input type="hidden" name="id_receipt" id="id_receipt">
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
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Pic</label>
                            <!--end::Label-->
                            <!--begin::Auto-generated ID-->
                            <select name="staff_id" id="staff_id" class="form-select mb-2">

                            </select>
                            <!--end::Input-->
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
                        <!--begin::Input group-->
                        <div class="fv-row mb-2">
                            <!--begin::Label-->
                            <label class="fs-6 fw-semibold mb-2">Type Perhitungan
                                <span class="ms-1" data-bs-toggle="tooltip"
                                    title="Pilih tipe perhitungan yang akan digunakan">
                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span></label>
                            <!--End::Label-->
                            <!--begin::Row-->
                            <div class="row row-cols-2 row-cols-md-2 row-cols-lg-1 row-cols-xl-2 g-9" data-kt-buttons="true"
                                data-kt-buttons-target="[data-kt-button='true']">
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Option-->
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex text-start p-6"
                                        data-kt-button="true">
                                        <!--begin::Radio-->
                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="calculation_type"
                                                value="weight_to_price" />
                                        </span>
                                        <!--end::Radio-->
                                        <!--begin::Info-->
                                        <span class="ms-5">
                                            <span class="fs-4 fw-bold text-gray-800 d-block">Weight to Price</span>
                                        </span>
                                        <!--end::Info-->
                                    </label>
                                    <!--end::Option-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Option-->
                                    <label
                                        class="btn btn-outline btn-outline-dashed btn-active-light-primary active d-flex text-start p-6"
                                        data-kt-button="true">
                                        <!--begin::Radio-->
                                        <span
                                            class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                            <input class="form-check-input" type="radio" name="calculation_type"
                                                value="price_to_weight" checked="checked" />
                                        </span>
                                        <!--end::Radio-->
                                        <!--begin::Info-->
                                        <span class="ms-5">
                                            <span class="fs-4 fw-bold text-gray-800 d-block">Price to Weight</span>
                                        </span>
                                        <!--end::Info-->
                                    </label>
                                    <!--end::Option-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Search products-->
                        <div class="d-flex align-items-center position-relative mb-n7">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-ecommerce-edit-order-filter="search"
                                class="form-control form-control-solid w-100 w-lg-50 ps-12" id="search"
                                placeholder="Search Products" />
                        </div>
                        <!--end::Search products-->
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5"
                            id="kt_ecommerce_edit_order_product_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    {{-- <th class="w-25px pe-2"></th> --}}
                                    <th class="min-w-200px">Product</th>
                                    <th class="min-w-100px text-end pe-5">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">

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
                        <h2>Preview Products</h2>
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
                                            <th class="min-w-100px">Hpp</th>
                                            <th class="min-w-100px">Total</th>
                                            <th class="min-w-100px text-end"></th>
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
                        </div>
                        <!--end::Input group-->
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

    <div class="modal fade" id="modalInputQty" tabindex="-1" aria-labelledby="modalInputQtyLabel" aria-hidden="true">
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
                        <label for="inputQuantity" class="form-label">Quantity</label>
                        <input type="number" step="0.01" class="form-control" id="inputQuantity"
                            placeholder="Enter quantity">
                    </div>

                    <div class="mb-3">
                        <label for="inputPrice" class="form-label">Harga Jual</label>
                        <input type="text" class="form-control format-number" id="inputSellPrice"
                            placeholder="Enter price" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitQty">Add Product</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Quantity Input -->
    <div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-labelledby="modalEditQtyLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form" action="{{ url('production') }}" id="kt_modal_add_customer_form"
                    data-kt-redirect="#">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditQtyLabel">Edit Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" id="methodField" value="">
                        <div class="mb-3">
                            <label for="inputQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="inputQuantityEdit" name="qty"
                                placeholder="Enter quantity" step="0.01" name="qty">
                        </div>

                        <div class="mb-3">
                            <label for="inputPrice" class="form-label">Harga Jual</label>
                            <input type="text" class="form-control format-number" id="inputPriceEdit"
                                name="sell_price" placeholder="Enter price" min="0">
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
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalInputPrc" tabindex="-1" aria-labelledby="modalEditQtyLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form" action="{{ url('production') }}" id="modalInputPrcForm"
                    data-kt-redirect="#">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInputPrcLabel">Edit Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" id="methodFieldPrc" value="">
                        <input type="hidden" id="inputProductIdPrc" name="product_id">
                        <input type="hidden" id="inputReceiptIdPrc" name="receipt_id">
                        <input type="hidden" id="inputProductionIdPrc" name="production_id">
                        <input type="hidden" id="typeList">
                        <div class="mb-3">
                            <label for="inputPrice" class="form-label">Masukkan Harga</label>
                            <input type="text" class="form-control format-number" id="inputPrice"
                                placeholder="Enter price" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="inputQuantity" class="form-label">Quantity</label>
                            <input type="number" name="qty" step="0.01" class="form-control"
                                id="inputQuantityPrc" placeholder="Enter quantity" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="inputPrice" class="form-label">Harga Jual</label>
                            <input type="text" name="sell_price" class="form-control format-number"
                                id="inputSellPricePrc" placeholder="Enter price" min="0" readonly>
                        </div>
                    </div>
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_customer_cancel"
                            class="btn btn-light me-3">Discard</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_add_customer_submit_prc" class="btn btn-primary">
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
    @include('transaction::production.js-create')
@endsection

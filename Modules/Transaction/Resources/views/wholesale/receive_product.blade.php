@extends('template.root')

@section('content')
    <form id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row"
        action="{{ route('wholesale-save-receive') }}" method="POST" enctype="multipart/form-data" data-kt-redirect="">
        @csrf
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

                        <!--begin::Separator-->
                        <div class="separator"></div>
                        <!--end::Separator-->
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5"
                            id="kt_ecommerce_edit_order_product_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Product</th>
                                    <th class="min-w-50px text-end pe-5">Quantity</th>
                                    <th class="min-w-100px pe-5">Price</th>
                                </tr>
                            </thead>
                            <input type="hidden" name="wholesale_id" id="wholesale_id" value="{{ $data->id }}" />
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($selectedProducts as $product)
                                    {{-- {{ dd($product) }} --}}
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center"
                                                data-kt-ecommerce-edit-order-filter="product"
                                                data-kt-ecommerce-edit-order-id="{{ $product['id'] }}">
                                                <!--begin::Thumbnail-->
                                                <a href="{{ url('products/' . $product['id']) }}"
                                                    class="symbol symbol-50px">
                                                    <span class="symbol-label"
                                                        style="background-image:url({{ asset('storage/' . $product['image']) }});"></span>
                                                </a>
                                                <!--end::Thumbnail-->
                                                <div class="ms-5">
                                                    <!--begin::Title-->
                                                    <a href="{{ url('products/' . $product['id']) }}"
                                                        class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $product['name'] }}</a>
                                                    <!--end::Title-->
                                                    <!--begin::SKU-->
                                                    <div class="text-muted fs-7">SKU: {{ $product['sku'] }}</div>
                                                    <!--end::SKU-->
                                                    <!--begin::Unit-->
                                                    <div class="text-muted fs-7">Product Unit: <span
                                                            class="badge badge-light-success">{{ $product['unit'] }}</span>
                                                    </div>
                                                    <!--end::Unit-->
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-5" data-order="42">
                                            <input type="number" class="form-control form-control-solid text-end"
                                                name="products[{{ $product['id'] }}][quantity]" value="{{ $product['qty'] }}"
                                                placeholder="0" />
                                        </td>
                                        <td class="pe-5" data-order="42">
                                            <input type="number" class="form-control form-control-solid"
                                                name="products[{{ $product['id'] }}][price]" value="" placeholder="0" />
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
@endsection

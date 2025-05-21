@extends('template.root')

@section('content')
    <form id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row"
        action="{{ route('sortir.save-stock') }}" method="POST" enctype="multipart/form-data" data-kt-redirect="">
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
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Selected products-->
                            <div class="row row-cols-1 row-cols-xl-3 row-cols-md-2 border border-dashed rounded pt-3 pb-1 px-2 mb-5 mh-300px overflow-scroll"
                                id="kt_ecommerce_edit_order_selected_products">
                                <!--begin::Empty message-->
                                <div class="col mb-4" data-product-id="1">
                                    <div class="border p-3 rounded bg-light">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-bold fs-1">{{ $product->name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-4">
                                    <div class="border p-3 rounded bg-light">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-3">
                                                {{-- <span class="symbol-label"
                                                    style="background-image:url({{ asset('storage/' . $product->product->image) }});"></span> --}}
                                            </div>
                                            <div>
                                                <div class="fw-bold fs-1 stock">{{ $product->stock_available }}</div>
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Empty message-->
                            </div>
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
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5"
                            id="kt_ecommerce_edit_order_product_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px">Product</th>
                                    <th class="min-w-50px text-end pe-5">Quantity</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($productChild as $product)
                                    {{-- {{ dd($product) }} --}}
                                    <tr>
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
                                                    <!--begin::SKU-->
                                                    <div class="text-muted fs-7">SKU: {{ $product->sku }}</div>
                                                    <!--end::SKU-->
                                                    <!--begin::Unit-->
                                                    <div class="text-muted fs-7">Product Unit: <span
                                                            class="badge badge-light-success">{{ $product->unit->abbreviation }}</span>
                                                    </div>
                                                    <!--end::Unit-->
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-5" data-order="42">
                                            <input type="number"
                                                class="form-control form-control-solid text-end quantity-input"
                                                name="quantity[{{ $product->id }}]" value="" placeholder="0" />
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center" data-kt-ecommerce-edit-order-filter="product"
                                            data-kt-ecommerce-edit-order-id="{{ $product->id }}">
                                            <!--begin::Thumbnail-->
                                            <a href="#" class="symbol symbol-50px">
                                                <span class="symbol-label" style="background-image:url('');"></span>
                                            </a>
                                            <!--end::Thumbnail-->
                                            <div class="ms-5">
                                                <!--begin::Title-->
                                                <a href="#" class="text-danger text-hover-primary fs-5 fw-bold">Produk
                                                    Buang</a>
                                                <!--end::Title-->
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-5" data-order="42">
                                        <input type="number"
                                            class="form-control form-control-solid text-end quantity-input" name="buang"
                                            value="" placeholder="0" />
                                    </td>
                                </tr>
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
                <a href="{{ url(Request::segment(1)) }}" id="kt_ecommerce_edit_order_cancel"
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
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.quantity-input');
            const stockEl = document.querySelector('.stock');

            const initialStock = parseInt(stockEl.textContent.trim()) || 0;

            function updateStock() {
                let totalQty = 0;
                inputs.forEach(input => {
                    totalQty += parseInt(input.value) || 0;
                });

                // Jika melebihi stok
                if (totalQty > initialStock) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok tidak cukup',
                        text: `Total yang diminta (${totalQty}) melebihi stok tersedia (${initialStock})`,
                    });

                    // Reset input yang menyebabkan kelebihan
                    this.value = '';
                    return;
                }

                const remainingStock = Math.max(0, initialStock - totalQty);
                stockEl.textContent = remainingStock;
            }

            inputs.forEach(input => {
                input.addEventListener('input', updateStock);
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
@endsection

@extends('template.root')

@section('content')
    <form id="add_product_form" class="form d-flex flex-column flex-lg-row"
        action="{{ isset($data) ? url(Request::segment(1) . '/' . $data->id) : url(Request::segment(1)) }}" method="POST"
        enctype="multipart/form-data" data-kt-redirect="">
        @if (isset($data))
            @method('PUT')
        @endif
        @csrf
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
            <!--begin::Order details-->
            <div class="card card-flush py-4">
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Billing address-->
                    <div class="d-flex flex-column gap-5 gap-md-7">
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Header</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <div id="kt_ecommerce_add_product_description" name="kt_ecommerce_add_product_description"
                                class="min-h-200px mb-2"></div>
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set a description to the product for better visibility.</div>
                            <input type="hidden" name="description" id="description_input">
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Billing address-->
                </div>
                <!--end::Card body-->
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
        $(document).ready(function() {
            // Initialize Select2 for province
            const quillElement = document.getElementById('kt_ecommerce_add_product_description');

            if (quillElement) {
                quill = new Quill(quillElement, {
                    modules: {
                        toolbar: [
                            [{
                                header: [1, 2, false]
                            }],
                            ['bold', 'italic', 'underline'],
                            ['image', 'code-block']
                        ]
                    },
                    placeholder: "Type your text here...",
                    theme: "snow"
                });
            }

            quill.root.innerHTML = `{!! $data->header ?? old('description') !!}`; // Set konten awal

            document.getElementById('add_product_form').addEventListener('submit', function() {
                const description = document.getElementById('description_input');
                description.value = quill.root.innerHTML; // Ambil konten HTML
            });
        });
    </script>
@endsection
@endsection

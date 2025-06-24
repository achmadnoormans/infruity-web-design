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
            <div class="fv-row">
                <!--begin::Label-->
                <label class="fs-6 fw-semibold mb-2">Type
                    <span class="ms-1" data-bs-toggle="tooltip"
                        title="Select a discount type that will be applied to this product">
                        <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span></label>
                <!--End::Label-->
                <!--begin::Row-->
                <div class="row row-cols-2 row-cols-md-6 row-cols-lg-1 row-cols-xl-2 g-9" data-kt-buttons="true"
                    data-kt-buttons-target="[data-kt-button='true']">
                    <!--begin::Col-->
                    <div class="col">
                        <!--begin::Option-->
                        <label
                            class="btn btn-outline btn-outline-dashed btn-active-light-primary {{ isset($data) && $data->is_using_logo == 1 ? 'active' : '' }} d-flex text-start p-6"
                            data-kt-button="true">
                            <!--begin::Radio-->
                            <span
                                class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                <input class="form-check-input" type="radio" name="logo_option" value="1"
                                    {{ isset($data) && $data->is_using_logo == 1 ? 'checked' : '' }} />

                            </span>
                            <!--end::Radio-->
                            <!--begin::Info-->
                            <span class="ms-5">
                                <span class="fs-4 fw-bold text-gray-800 d-block">With Logo</span>
                            </span>
                            <!--end::Info-->
                        </label>
                        <!--end::Option-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col">
                        <!--begin::Option-->
                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary {{ isset($data) && $data->is_using_logo == 0 ? 'active' : '' }} d-flex text-start p-6"
                            data-kt-button="true">
                            <!--begin::Radio-->
                            <span
                                class="form-check form-check-custom form-check-solid form-check-sm align-items-start mt-1">
                                <input class="form-check-input" type="radio" name="logo_option" value="2"
                                    {{ isset($data) && $data->is_using_logo == 0 ? 'checked' : '' }} />
                            </span>
                            <!--end::Radio-->
                            <!--begin::Info-->
                            <span class="ms-5">
                                <span class="fs-4 fw-bold text-gray-800 d-block">No Logo</span>
                            </span>
                            <!--end::Info-->
                        </label>
                        <!--end::Option-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--begin::Thumbnail settings-->
            <div id="logo_upload_container" class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>Logo</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body text-center pt-0">
                    <!--begin::Image input-->
                    <!--begin::Image input placeholder-->
                    <style>
                        .image-input-placeholder {
                            background-image: url({{ isset($data) && isset($data->logo) ? asset('storage/' . $data->logo) : asset('assets/media/svg/files/blank-image.svg') }});
                        }

                        [data-bs-theme="dark"] .image-input-placeholder {
                            background-image: url({{ isset($data) && isset($data->logo) ? asset('storage/' . $data->logo) : asset('assets/media/svg/files/blank-image-dark.svg') }});
                        }
                    </style>
                    <!--end::Image input placeholder-->
                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                        data-kt-image-input="true">
                        <!--begin::Preview existing avatar-->
                        <div class="image-input-wrapper w-150px h-150px"></div>
                        <!--end::Preview existing avatar-->
                        <!--begin::Label-->
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                            <i class="ki-outline ki-pencil fs-7"></i>
                            <!--begin::Inputs-->
                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="avatar_remove" />
                            <!--end::Inputs-->
                        </label>
                        <!--end::Label-->
                        <!--begin::Cancel-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                            <i class="ki-outline ki-cross fs-2"></i>
                        </span>
                        <!--end::Cancel-->
                        <!--begin::Remove-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                            <i class="ki-outline ki-cross fs-2"></i>
                        </span>
                        <!--end::Remove-->
                    </div>
                    <!--end::Image input-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files
                        are accepted</div>
                    <!--end::Description-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Thumbnail settings-->
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
                            <div id="header_editor" name="header_editor" class="min-h-150px mb-2"></div>
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set a header nota.</div>
                            <input type="hidden" name="header" id="header_input">
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Footer</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <div id="footer_editor" name="footer_editor" class="min-h-150px mb-2"></div>
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set a footer nota.</div>
                            <input type="hidden" name="footer" id="footer_input">
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
            const headerEditor = document.getElementById('header_editor');
            const footerEditor = document.getElementById('footer_editor');

            if (headerEditor) {
                header = new Quill(headerEditor, {
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

            if (footerEditor) {
                footer = new Quill(footerEditor, {
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

            header.root.innerHTML = `{!! $data->header ?? old('description') !!}`; // Set konten awal
            footer.root.innerHTML = `{!! $data->footer ?? old('description') !!}`; // Set konten awal

            document.getElementById('add_product_form').addEventListener('submit', function() {
                const headerData = document.getElementById('header_input');
                const footerData = document.getElementById('footer_input');
                headerData.value = header.root.innerHTML; // Ambil konten HTML
                footerData.value = footer.root.innerHTML; // Ambil konten HTML
            });

            $('input[name="logo_option"]').on('change', function() {
                const selectedValue = $(this).val();
                console.log(selectedValue);
                if (selectedValue == '1') {
                    $('#logo_upload_container').show();
                } else {
                    $('#logo_upload_container').hide();
                }
            });

            // Trigger langsung saat page load agar sesuai kondisi awal
            $('input[name="logo_option"]:checked').trigger('change');
        });
    </script>
@endsection
@endsection

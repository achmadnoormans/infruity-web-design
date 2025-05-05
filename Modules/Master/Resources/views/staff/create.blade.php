@extends('template.root')

@section('content')
    <form id="add_product_form" class="form d-flex flex-column flex-lg-row"
        action="{{ isset($data) ? url(Request::segment(1) . '/' . $data->id) : url(Request::segment(1)) }}" method="POST"
        enctype="multipart/form-data" data-kt-redirect="">
        @if (isset($data))
            @method('PUT')
        @endif
        @csrf
        <!--begin::Aside column-->
        <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
            <!--begin::Order details-->
            <div class="card card-flush py-4 mb-7">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Staff Details</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Staff ID</label>
                            <!--end::Label-->
                            <!--begin::Auto-generated ID-->
                            <div class="fw-bold fs-3">#{{ isset($data) ? $data->nik : '' }}</div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Gender</label>
                            <!--end::Label-->
                            <!--begin::Select2-->
                            <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                                data-placeholder="Select an option" name="gender" id="kt_ecommerce_edit_order_shipping">
                                <option></option>
                                <option value="male" {{ isset($data) && $data->gender == 'male' ? 'selected' : '' }}>Pria
                                </option>
                                <option value="female" {{ isset($data) && $data->gender == 'female' ? 'selected' : '' }}>
                                    Wanita</option>
                            </select>
                            <!--end::Select2-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set Gender of the customer.</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Date In</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input id="kt_ecommerce_edit_order_date" name="date_in" placeholder="Select a date"
                                class="form-control mb-2" value="{{ $data->date_in ?? old('date_in') }}" />
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set the birth of date of the customer.</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--end::Card header-->
            </div>
            <!--end::Order details-->
            <!--begin::Status-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>Status</h2>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <div class="rounded-circle bg-success w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
                    </div>
                    <!--begin::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Select2-->
                    <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                        data-placeholder="Select an option" id="kt_ecommerce_add_product_status_select" name="status">
                        <option></option>
                        <option value="aktif" selected="{{ isset($data) && $data->status == 'aktif' ? 'selected' : '' }}">
                            Aktif</option>
                        <option value="nonaktif"
                            selected="{{ isset($data) && $data->status == 'nonaktif' ? 'selected' : '' }}">Nonaktif</option>
                    </select>
                    <!--end::Select2-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Set the product status.</div>
                    <!--end::Description-->
                    <!--begin::Datepicker-->
                    <div class="d-none mt-10">
                        <label for="kt_ecommerce_add_product_status_datepicker" class="form-label">Select publishing date
                            and time</label>
                        <input class="form-control" id="kt_ecommerce_add_product_status_datepicker"
                            placeholder="Pick date & time" />
                    </div>
                    <!--end::Datepicker-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Status-->
        </div>
        <!--end::Aside column-->
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
            <!--begin::Order details-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Customer Details</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Billing address-->
                    <div class="d-flex flex-column gap-5 gap-md-7">
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Staff Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="staff_name" class="form-control mb-2" placeholder="Staff name"
                                value="{{ $data->name ?? old('name') }}" />
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Name of Staff.
                            </div>
                            <!--end::Description-->
                        </div>
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">NIK</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="nik" class="form-control mb-2" placeholder="357505xxx"
                                value="{{ $data->nik ?? old('nik') }}" />
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Contact of Staff.
                            </div>
                            <!--end::Description-->
                        </div>
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Contact</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="contact" class="form-control mb-2" placeholder="085xxxx"
                                value="{{ $data->contact ?? old('contact') }}" />
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Contact of Staff.
                            </div>
                            <!--end::Description-->
                        </div>
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Department</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select mb-2" name="department" id="department"
                                data-placeholder="Select a Department">
                                @if (isset($data->department_id))
                                    <option value="{{ $department->id }}" selected>{{ $department->name }}</option>
                                @endif
                            </select>
                            <!--end::Input-->
                        </div>
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Position</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select mb-2" name="position" id="position"
                                data-placeholder="Select a Position">
                                @if (isset($data->position_id))
                                    <option value="{{ $position->id }}" selected>{{ $position->name }}</option>
                                @endif
                            </select>
                            <!--end::Input-->
                        </div>
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Email</label>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">@</span>
                                <input type="text" class="form-control" placeholder="noorman@example.com"
                                    name="email" aria-label="Email" aria-describedby="basic-addon1"
                                    value="{{ $data->email ?? old('email') }}" />
                            </div>
                            <!--end::Input group-->
                        </div>
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Description</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <div id="kt_ecommerce_add_product_description" name="kt_ecommerce_add_product_description"
                                class="min-h-200px mb-2"></div>
                            <input type="hidden" name="description" id="description_input">
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set a description to the product for better visibility.
                            </div>
                            <!--end::Description-->
                        </div>
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
    @include('master::staff.js-create')
@endsection

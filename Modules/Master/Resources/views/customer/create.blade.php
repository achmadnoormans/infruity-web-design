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
                    <div class="d-flex flex-column gap-10">
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Customer ID</label>
                            <!--end::Label-->
                            <!--begin::Auto-generated ID-->
                            <div class="fw-bold fs-3">#{{ isset($data) ? $data->code : $customerNumber }}</div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Customer Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="customer_name" class="form-control mb-2" placeholder="Customer name"
                                value="{{ $data->name ?? old('customer_name') }}" />
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Name of Customer.
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Whatsapp</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="phone" class="form-control mb-2" placeholder="Whatsapp"
                                value="{{ $data->whatsapp ?? old('phone') }}" />
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Name of Customer.
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Gender</label>
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
                            <label class="form-label">Birth of Date</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input id="kt_ecommerce_edit_order_date" name="birth_of_date" placeholder="Select a date"
                                class="form-control mb-2" value="{{ $data->birth_of_date ?? old('birth_of_date') }}" />
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
                        <!--begin::Title-->
                        <div class="fs-3 fw-bold mb-n2">Address</div>
                        <!--end::Title-->
                        <!--begin::Input group-->
                        <div class="input-group input-group-solid flex-nowrap">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-map fs-3"><span class="path1"></span><span
                                        class="path2"></span><span class="path3"></span><span class="path4"></span><span
                                        class="path5"></span><span class="path6"></span></i>
                            </span>
                            <div class="overflow-hidden flex-grow-1">
                                <select class="form-select rounded-start-0 border-start" name="province" id="province"
                                    data-placeholder="Select an province">
                                    @if (isset($data->province))
                                        <option value="{{ $province->id }}" selected>{{ $province->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column flex-md-row gap-5">
                            <div class="fv-row flex-row-fluid">
                                <!--begin::Label-->
                                <label class="form-label">City</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select mb-2" name="city" id="city"
                                    data-placeholder="Select a city">
                                    @if (isset($data->city))
                                        <option value="{{ $city->id }}" selected>{{ $city->name }}</option>
                                    @endif
                                </select>
                                <!--end::Input-->
                            </div>
                            <div class="flex-row-fluid">
                                <!--begin::Label-->
                                <label class="form-label">District</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select mb-2" name="district" id="district"
                                    data-placeholder="Select a district">
                                    @if (isset($district))
                                        <option value="{{ $district->id }}" selected>{{ $district->name }}</option>
                                    @endif
                                </select>
                                <!--end::Input-->
                            </div>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Detailed Address</label>
                            <!--end::Label-->
                            <textarea class="form-control" name="address" id="address" cols="30" rows="5">{{ $data->address ?? old('address') }}</textarea>
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Set detailed Address.
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Email</label>
                            <!--end::Label-->
                            <!--begin::Input group-->
                            <div class="input-group mb-5">
                                <span class="input-group-text" id="basic-addon1">@</span>
                                <input type="text" class="form-control" placeholder="noorman@example.com" name="email"
                                    aria-label="Email" aria-describedby="basic-addon1"
                                    value="{{ $data->email ?? old('email') }}" />
                            </div>
                            <!--end::Input group-->
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
    @include('master::customer.js-create')
@endsection

@extends('template.root')

@section('content')
    <!--begin::Sidebar-->
    <div class="flex-column flex-lg-row-auto mb-10">
        <!--begin::Card-->
        <div class="card mb-5 mb-xl-8">
            <!--begin::Card body-->
            <div class="card-body pt-15">
                <!--begin::Summary-->
                <div class="d-flex flex-center flex-column mb-5">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-150px symbol-circle mb-7">
                        <img src="assets/media/avatars/300-1.jpg" alt="image" />
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Name-->
                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">{{ $data->code }}</a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <a href="#" class="fs-5 fw-semibold text-muted text-hover-primary mb-6">{{ $data->email }}</a>
                    <!--end::Email-->
                </div>
                <!--end::Summary-->
                <!--begin::Details toggle-->
                <div class="d-flex flex-stack fs-4 py-3">
                    <div class="fw-bold">Details</div>
                    <!--begin::Badge-->
                    <div class="badge badge-light-info d-inline">{{ $data->status }}</div>
                    <!--begin::Badge-->
                </div>
                <!--end::Details toggle-->
                <div class="separator separator-dashed my-3"></div>
                <!--begin::Details content-->
                <div class="pb-5 fs-6">
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Nama</div>
                    <div class="text-gray-600">{{ $data->name }}</div>
                    <div class="fw-bold mt-5">Whatsapp</div>
                    <div class="text-gray-600">
                        <a href="#" class="text-gray-600 text-hover-primary">{{ $data->whatsapp }}</a>
                    </div>
                    <div class="fw-bold mt-5">Province</div>
                    <div class="text-gray-600">{{ $province->name ?? '' }}</div>
                    <div class="fw-bold mt-5">City</div>
                    <div class="text-gray-600">{{ $city->name ?? '' }}</div>
                    <div class="fw-bold mt-5">Kecamatan</div>
                    <div class="text-gray-600">{{ $district->name }}</div>
                    <div class="fw-bold mt-5">Tanggal Masuk</div>
                    <div class="text-gray-600">{{ dateindo($data->birth_of_date) }}
                        ({{ \Carbon\Carbon::parse($data->birth_of_date)->diffInYears() }})</div>
                    <div class="fw-bold mt-5">Jenis Kelamin</div>
                    <div class="text-gray-600">{{ $data->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                    <div class="fw-bold mt-5">Created By</div>
                    <div class="text-gray-600">{{ $data->user->nm_user }}</div>
                    <div class="fw-bold mt-5">Created At</div>
                    <div class="text-gray-600">{{ dateindo($data->created_at) }} </div>
                    <div class="fw-bold mt-5">Updated_by By</div>
                    <div class="text-gray-600"></div>
                    <div class="fw-bold mt-5">Lasst Updated At</div>
                    <div class="text-gray-600">{{ dateindo($data->updated_at) }} </div>
                    <!--begin::Details item-->
                </div>
                <!--end::Details content-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <div class="d-flex justify-content-end">
        <!--begin::Button-->
        <a href="{{ url(Request::segment(1)) }}" id="kt_ecommerce_edit_order_cancel" class="btn btn-light me-5">Cancel</a>
        <!--end::Button-->
    </div>
    <!--end::Sidebar-->
@endsection

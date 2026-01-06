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
                    <div class="symbol symbol-150px symbol-circle mb-7 overflow-hidden">
                        @if ($data->image)
                            <a href="{{ asset('storage/' . $data->image) }}" target="_blank" class="d-block w-100 h-100">
                                <img src="{{ asset('storage/' . $data->image) }}" alt="{{ $data->name }}"
                                    style="width: 200px; height: 200px; object-fit: cover;" />
                            </a>
                        @else
                            @php
                                $colors = ['warning', 'success', 'info', 'primary'];
                                $color = $colors[$data->id % count($colors)];
                                $initial = strtoupper(substr($data->name ?? '', 0, 1));
                            @endphp
                            <div class="symbol-label fs-1 bg-light-{{ $color }} text-{{ $color }}">
                                {{ $initial }}
                            </div>
                        @endif
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Name-->
                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">{{ $data->name }}</a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <a href="#" class="fs-5 fw-semibold text-muted text-hover-primary mb-6">{{ $data->contact }}</a>
                    <!--end::Email-->
                </div>
                <!--end::Summary-->
                <!--begin::Details toggle-->
                <div class="d-flex flex-stack fs-4 py-3">
                    <div class="fw-bold">Detail</div>
                    <!--begin::Badge-->
                    <div class="badge badge-light-info d-inline">{{ $data->status }}</div>
                    <!--begin::Badge-->
                </div>
                <!--end::Details toggle-->
                <div class="separator separator-dashed my-3"></div>
                <!--begin::Details content-->
                <div class="pb-5 fs-6">
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">NIK</div>
                    <div class="text-gray-600">{{ $data->nik }}</div>
                    <div class="fw-bold mt-5">Email</div>
                    <div class="text-gray-600">
                        <a href="#" class="text-gray-600 text-hover-primary">{{ $data->email }}</a>
                    </div>
                    <div class="fw-bold mt-5">Departemen</div>
                    <div class="text-gray-600">{{ $data->department->name ?? '' }}</div>
                    <div class="fw-bold mt-5">Jabatan</div>
                    <div class="text-gray-600">{{ $data->position->name ?? '' }}</div>
                    <div class="fw-bold mt-5">Jenis Kelamin</div>
                    <div class="text-gray-600">{{ $data->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                    <div class="fw-bold mt-5">Tanggal Masuk</div>
                    <div class="text-gray-600">{{ dateindo($data->date_in) }}
                        ({{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }})</div>
                    <div class="fw-bold mt-5">Dibuat Oleh</div>
                    <div class="text-gray-600">{{ $data->user->nm_user }}</div>
                    <div class="fw-bold mt-5">Dibuat Pada</div>
                    <div class="text-gray-600">{{ dateindo($data->created_at) }} </div>
                    <div class="fw-bold mt-5">Diperbarui Oleh</div>
                    <div class="text-gray-600">{{ $data->user->nm_user }}</div>
                    <div class="fw-bold mt-5">Terakhir Diperbarui Pada</div>
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
        <a href="{{ url(Request::segment(1)) }}" id="kt_ecommerce_edit_order_cancel" class="btn btn-light me-5">Batal</a>
        <!--end::Button-->
    </div>
    <!--end::Sidebar-->
@endsection

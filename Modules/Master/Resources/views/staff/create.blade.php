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
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <!--begin::Thumbnail settings-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>Foto Profil</h2>
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
                            background-image: url({{ isset($data) && isset($data->image) ? asset('storage/' . $data->image) : asset('assets/media/svg/files/blank-image.svg') }});
                        }

                        [data-bs-theme="dark"] .image-input-placeholder {
                            background-image: url({{ isset($data) && isset($data->image) ? asset('storage/' . $data->image) : asset('assets/media/svg/files/blank-image-dark.svg') }});
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
                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ganti foto">
                            <i class="ki-outline ki-pencil fs-7"></i>
                            <!--begin::Inputs-->
                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="avatar_remove" />
                            <!--end::Inputs-->
                        </label>
                        <!--end::Label-->
                        <!--begin::Cancel-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batal">
                            <i class="ki-outline ki-cross fs-2"></i>
                        </span>
                        <!--end::Cancel-->
                        <!--begin::Remove-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus foto">
                            <i class="ki-outline ki-cross fs-2"></i>
                        </span>
                        <!--end::Remove-->
                    </div>
                    <!--end::Image input-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Tentukan foto profil staff. Hanya berkas gambar dengan ekstensi *.png,
                        *.jpg, dan *.jpeg yang diterima.</div>
                    @error('avatar')
                        <div class="text-danger fs-7">{{ $message }}</div>
                    @enderror
                    <!--end::Description-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Thumbnail settings-->
            <!--begin::Order details-->
            <div class="card card-flush py-4 mb-7">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>Detail Staff</h2>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">ID Staff</label>
                            <!--end::Label-->
                            <!--begin::Auto-generated ID-->
                            <div class="fw-bold fs-3">#{{ isset($data) ? $data->nik : '' }}</div>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Jenis Kelamin</label>
                            <!--end::Label-->
                            <!--begin::Select2-->
                            <select class="form-select mb-2 @error('gender') is-invalid @enderror" data-control="select2"
                                data-hide-search="true" data-placeholder="Pilih opsi" name="gender"
                                id="kt_ecommerce_edit_order_shipping">
                                <option></option>
                                <option value="male" {{ old('gender', $data->gender ?? '') == 'male' ? 'selected' : '' }}>
                                    Pria
                                </option>
                                <option value="female"
                                    {{ old('gender', $data->gender ?? '') == 'female' ? 'selected' : '' }}>
                                    Wanita</option>
                            </select>
                            @error('gender')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                            <!--end::Select2-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Atur jenis kelamin staff.</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Tanggal Bergabung</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            @php
                                $tanggal = date('Y-m-d');
                                if (isset($data->date_in)) {
                                    $tanggal = date('Y-m-d', strtotime($data->date_in));
                                }
                                if (old('date_in')) {
                                    $tanggal = old('date_in');
                                }
                            @endphp
                            <input id="kt_ecommerce_edit_order_date" name="date_in" placeholder="Pilih tanggal"
                                class="form-control mb-2 @error('date_in') is-invalid @enderror"
                                value="{{ $tanggal }}" />
                            @error('date_in')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Atur tanggal bergabung staff.</div>
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
                    <select class="form-select mb-2 @error('status') is-invalid @enderror" data-control="select2"
                        data-hide-search="true" data-placeholder="Pilih opsi" id="kt_ecommerce_add_product_status_select"
                        name="status">
                        <option value="aktif" {{ old('status', $data->status ?? '') == 'aktif' ? 'selected' : '' }}>
                            Aktif</option>
                        <option value="nonaktif" {{ old('status', $data->status ?? '') == 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif</option>
                    </select>
                    @error('status')
                        <div class="text-danger fs-7">{{ $message }}</div>
                    @enderror
                    <!--end::Select2-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Atur status staff.</div>
                    <!--end::Description-->
                    <!--begin::Datepicker-->
                    <div class="d-none mt-10">
                        <label for="kt_ecommerce_add_product_status_datepicker" class="form-label">Pilih tanggal dan waktu
                            publikasi</label>
                        <input class="form-control" id="kt_ecommerce_add_product_status_datepicker"
                            placeholder="Pilih tanggal & waktu" />
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
                        <h2>Detail Staff</h2>
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
                            <label class="required form-label">Nama Lengkap Staff</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="staff_name"
                                class="form-control mb-2 @error('staff_name') is-invalid @enderror"
                                placeholder="Nama Staff" value="{{ old('staff_name', $data->name ?? '') }}" />
                            @error('staff_name')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Nama Staff.
                            </div>
                            <!--end::Description-->
                        </div>
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Nama Panggilan</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="nickname"
                                class="form-control mb-2 @error('nickname') is-invalid @enderror"
                                placeholder="Nama Panggilan" value="{{ old('nickname', $data->nickname ?? '') }}" />
                            @error('nickname')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Nama Panggilan Staff.
                            </div>
                            <!--end::Description-->
                        </div>
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">NIK</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="nik"
                                class="form-control mb-2 @error('nik') is-invalid @enderror" placeholder="357505xxx"
                                value="{{ old('nik', $data->nik ?? '') }}" />
                            @error('nik')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">NIK Staff.
                            </div>
                            <!--end::Description-->
                        </div>
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">No. Telepon</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="contact"
                                class="form-control mb-2 @error('contact') is-invalid @enderror" placeholder="085xxxx"
                                value="{{ old('contact', $data->contact ?? '') }}" />
                            @error('contact')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                            <!--end::Input-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">No. Telepon Staff.
                            </div>
                            <!--end::Description-->
                        </div>
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Departemen</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select mb-2 @error('department') is-invalid @enderror" name="department"
                                id="department" data-placeholder="Pilih Departemen">
                                @if (isset($department))
                                    <option value="{{ $department->id }}" selected>{{ $department->name }}</option>
                                @endif
                            </select>
                            <!--end::Input-->
                        </div>
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label">Posisi</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select class="form-select mb-2 @error('position') is-invalid @enderror" name="position"
                                id="position" data-placeholder="Pilih Posisi">
                                @if (isset($position))
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
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="noorman@example.com" name="email" aria-label="Email"
                                    aria-describedby="basic-addon1" value="{{ old('email', $data->email ?? '') }}" />
                            </div>
                            @error('email')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                            <!--end::Input group-->
                        </div>
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Deskripsi</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <div id="kt_ecommerce_add_product_description" name="kt_ecommerce_add_product_description"
                                class="min-h-200px mb-2 @error('description') is-invalid @enderror"></div>
                            <input type="hidden" name="description" id="description_input"
                                value="{{ old('description', $data->description ?? '') }}">
                            @error('description')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                            <!--end::Editor-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">Deskripsi singkat tentang staff.
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
                <a href="{{ url(Request::segment(1)) }}" id="kt_ecommerce_edit_order_cancel"
                    class="btn btn-light me-5">Batal</a>
                <!--end::Button-->
                <!--begin::Button-->
                <button type="submit" id="kt_ecommerce_edit_order_submit" class="btn btn-primary">
                    <span class="indicator-label">Simpan</span>
                    <span class="indicator-progress">Tunggu...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Button-->
            </div>
        </div>
        <!--end::Main column-->
    </form>
    @include('master::staff.js-create')
@endsection

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
        <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10 mb-5">
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <h3 class="card-title">Atur Jadwal Reset Automatis</h3>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Billing address-->
                    <div class="d-flex flex-column gap-5 gap-md-7">
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Tanggal Mulai</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="date" class="form-control" name="start_date" id="start_date"
                                value="{{ old('start_date', isset($data->start_date) ? $data->start_date : '') }}">
                            <!--end::Editor-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Frekuensi</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <select name="frequency" id="frequency" class="form-control">
                                @foreach ($frequencies as $item)
                                    <option value="{{ $item->value }}"
                                        {{ old('frequency', isset($data->frequency) ? $data->frequency : '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Editor-->
                        </div>
                        <!--end::Input group-->
                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <!--begin::Label-->
                                <label class="form-label">Selesai setelah</label>
                                <!--end::Label-->
                                <!--begin::Editor-->
                                <select name="break" id="break" class="form-control">
                                    <option value="1"
                                        {{ old('break', isset($data->break) ? $data->break : '') == 1 ? 'selected' : '' }}>
                                        Tanggal</option>
                                    <option value="2"
                                        {{ old('break', isset($data->break) ? $data->break : '') == 2 ? 'selected' : '' }}>
                                        Selamanya</option>
                                </select>
                                <!--end::Editor-->
                            </div>
                            <div class="col-md-6">
                                <!--begin::Label-->
                                <label class="form-label">Tanggal Selesai</label>
                                <!--end::Label-->
                                <!--begin::Editor-->
                                <input type="date" class="form-control" name="end_date" id="end_date"
                                    value="{{ old('end_date', isset($data->end_date) ? $data->end_date : '') }}">
                                <!--end::Editor-->
                            </div>
                        </div>
                    </div>
                    <!--end::Billing address-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Order details-->
        </div>
        <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10 mb-10">
            <div class="card card-flush py-4">
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Billing address-->
                    <div class="d-flex flex-column gap-5 gap-md-7">
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Pilih Skala</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            @php
                                $skalaOptions = [1000, 100, 10, 1, 0.1, 0.01, 0.001];
                                $selectedSkala = old('skala', isset($exp->skala) ? $exp->skala : 1);
                            @endphp
                            <select name="skala" id="skala" class="form-control">
                                @foreach ($skalaOptions as $option)
                                    <option value="{{ $option }}" {{ $selectedSkala == $option ? 'selected' : '' }}>
                                        {{ $option }}</option>
                                @endforeach
                            </select>
                            <!--end::Editor-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div>
                            <!--begin::Label-->
                            <label class="form-label">Masukkan Angka</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="number" class="form-control" name="value"
                                value="{{ old('value', isset($exp->value) ? $exp->value : '1') }}">
                            <!--end::Editor-->
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
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const frequencySelect = document.getElementById('frequency');
            const endDateInput = document.getElementById('end_date');
            const breakSelect = document.getElementById('break');

            function updateEndDate() {
                console.log('updateEndDate');
                const startDateStr = startDateInput.value;
                const frequencyMonths = parseInt(frequencySelect.value);
                const breakVal = breakSelect.value;

                if (breakVal === '2') {
                    // endDateInput.value = '';
                    endDateInput.readOnly = true;
                    // return;
                } else {
                    endDateInput.readOnly = false;
                }

                console.log(startDateStr, frequencyMonths, breakVal);
                if (!startDateStr || isNaN(frequencyMonths)) {
                    endDateInput.value = '';
                    return;
                }

                const startDate = new Date(startDateStr);
                const endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + frequencyMonths);
                console.log(endDate);

                // Format YYYY-MM-DD
                const yyyy = endDate.getFullYear();
                const mm = String(endDate.getMonth() + 1).padStart(2, '0');
                const dd = String(endDate.getDate()).padStart(2, '0');
                endDateInput.value = `${yyyy}-${mm}-${dd}`;
            }

            // Event listeners
            startDateInput.addEventListener('change', updateEndDate);
            frequencySelect.addEventListener('change', updateEndDate);
            breakSelect.addEventListener('change', updateEndDate);

            // Jalankan sekali di awal jika nilai sudah terisi
            updateEndDate();
        });
    </script>
@endsection
@endsection

@extends('template.root')

@section('content')
    {{-- @livewire('product-table') --}}
    <div>
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                        <input type="text" data-kt-ecommerce-product-filter="search" id="search"
                            class="form-control form-control-solid w-250px ps-12" placeholder="Search Transaction" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <!--begin::Add product-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_add_customer">Add Tier</button>
                    <!--end::Add product-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="transaction-table" width="100%">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            {{-- <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_ecommerce_sortir_table .form-check-input"
                                        value="1" />
                                </div>
                            </th> --}}
                            <th class="text-start min-w-100px">Name</th>
                            <th class="text-start min-w-100px">Level</th>
                            <th class="text-start min-w-100px">Min Exp</th>
                            <th class=""></th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600"></tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
    <div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="{{ url(Request::segment(1)) }}" id="kt_modal_add_customer_form"
                    data-kt-redirect="#">
                    @csrf
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_customer_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Add a Tier</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body py-10 px-lg-17">
                        <!--begin::Scroll-->
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true"
                            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                            data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                            <div id="logo_upload_container" class="card card-flush py-4">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>Icon</h2>
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
                                            background-image: url({{ isset($data) && isset($data->icon) ? asset('storage/' . $data->icon) : asset('assets/media/svg/files/blank-image.svg') }});
                                        }

                                        [data-bs-theme="dark"] .image-input-placeholder {
                                            background-image: url({{ isset($data) && isset($data->icon) ? asset('storage/' . $data->icon) : asset('assets/media/svg/files/blank-image-dark.svg') }});
                                        }
                                    </style>
                                    <!--end::Image input placeholder-->
                                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                                        data-kt-image-input="true">
                                        <!--begin::Preview existing avatar-->
                                        <div class="image-input-wrapper w-150px h-150px"></div>
                                        <!--end::Preview existing avatar-->
                                        <!--begin::Label-->
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="Change avatar">
                                            <i class="ki-outline ki-pencil fs-7"></i>
                                            <!--begin::Inputs-->
                                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="avatar_remove" />
                                            <!--end::Inputs-->
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Cancel-->
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            title="Cancel avatar">
                                            <i class="ki-outline ki-cross fs-2"></i>
                                        </span>
                                        <!--end::Cancel-->
                                        <!--begin::Remove-->
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            title="Remove avatar">
                                            <i class="ki-outline ki-cross fs-2"></i>
                                        </span>
                                        <!--end::Remove-->
                                    </div>
                                    <!--end::Image input-->
                                    <!--begin::Description-->
                                    <div class="text-muted fs-7">Set icon of tier</div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <br>
                            <!--begin::Input group-->
                            <div class="fv-row mb-15">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" placeholder=""
                                    name="name" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-15">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Level</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" class="form-control form-control-solid" placeholder=""
                                    name="level" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-15">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold mb-2">Min Exp</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid format-number" placeholder=""
                                    name="exp" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-15">
                                <label class="fs-6 fw-semibold mb-2">Color</label>
                                <select name="style" id="style" class="form-control form-select">
                                    <option value="badge-light-primary" data-badge="badge-light-primary">Primary</option>
                                    <option value="badge-light-secondary" data-badge="badge-light-secondary">Secondary</option>
                                    <option value="badge-light-success" data-badge="badge-light-success">Success</option>
                                    <option value="badge-light-danger" data-badge="badge-light-danger">Danger</option>
                                    <option value="badge-light-warning" data-badge="badge-light-warning">Warning</option>
                                    <option value="badge-light-info" data-badge="badge-light-info">Info</option>
                                    <option value="badge-light" data-badge="badge-light">Light</option>
                                    <option value="badge-light-dark" data-badge="badge-light-dark">Dark</option>
                                </select>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_customer_cancel"
                            class="btn btn-light me-3">Discard</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
@section('script')
    <script type="text/javascript">
        var dataTable;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const segment1 = "{{ Request::segment(1) }}";

        $(document).ready(function() {
            dataTable = $('#transaction-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true, // Aktifkan scroll horizontal
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },
                columnDefs: [{
                    orderable: false,
                    targets: -1 // Disable sorting for action column
                }, ],
                ajax: {
                    url: "{{ route('tier.data') }}",
                    data: function(d) {
                        d.url = "{{ request()->segment(1) }}";
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'level',
                        name: 'level'
                    },
                    {
                        data: 'exp',
                        name: 'exp'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [1, 'asc']
                ]
            });
            // Search manual lewat input
            $('#search').on('keyup', function() {
                dataTable.search(this.value).draw();
            });

            $('#style').select2({
                templateResult: function(data) {
                    if (!data.id) return data.text;

                    const badgeClass = $(data.element).data('badge') || 'badge-light';
                    return $('<span class="badge ' + badgeClass + '">' + data.text + '</span>');
                },
                templateSelection: function(data) {
                    if (!data.id) return data.text;

                    const badgeClass = $(data.element).data('badge') || 'badge-light';
                    return $('<span class="badge ' + badgeClass + '">' + data.text + '</span>');
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                width: '100%' // agar mengikuti lebar
            });

            document.getElementById('kt_modal_add_customer_cancel').addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah form reset langsung
                Swal.fire({
                    text: "Are you sure you would like to cancel?",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, cancel it!",
                    cancelButtonText: "No, return",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tutup modal manual
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'kt_modal_add_customer'));
                        modal.hide();
                        document.getElementById('kt_modal_add_customer_form').reset();
                    }
                });
            });

            $('#kt_modal_add_customer_form').on('submit', function(e) {
                e.preventDefault();

                var form = $(this)[0];
                var url = $(this).attr('action');
                var submitBtn = $('#kt_modal_add_customer_submit');

                var formData = new FormData(form); // Gunakan FormData agar file bisa ikut terkirim

                // Show loading
                submitBtn.prop('disabled', true);
                submitBtn.find('.indicator-label').hide();
                submitBtn.find('.indicator-progress').show();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Reset form, tutup modal, dan refresh DataTable
                            $('#kt_modal_add_customer_form').trigger('reset');
                            $('#kt_modal_add_customer_form').find(
                                'input[name="_method"]').remove();
                            $('#kt_modal_add_customer_form').attr('action',
                                `/${segment1}`);
                            $('#kt_modal_add_customer_header h2').text('Tambah Tier');
                            const modal = bootstrap.Modal.getInstance(document
                                .getElementById('kt_modal_add_customer'));
                            if (modal) modal.hide();
                            if (typeof dataTable !== 'undefined') dataTable.ajax.reload(
                                null, false);
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menyimpan data.'
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        submitBtn.find('.indicator-label').show();
                        submitBtn.find('.indicator-progress').hide();
                    }
                });
            });

        });

        function reloadDataTable() {
            // Pastikan dataTable sudah terinisialisasi sebelumnya
            if (typeof dataTable !== 'undefined') {
                dataTable.ajax.reload(null, false); // 'false' untuk tidak mereset ke halaman pertama
            } else {
                console.error('DataTable tidak terinisialisasi.');
            }
        }

        function deleteProduct(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/tier/${id}`, // Ganti dengan URL yang sesuai
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil dihapus.',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Reload DataTable setelah berhasil menghapus data
                            reloadDataTable();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat menghapus data.'
                            });
                        }
                    });
                }
            });
        }

        function editProduct(id) {
            $.ajax({
                url: `/tier/${id}/edit`, // URL untuk mengambil data produk yang akan diedit
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    // Isi form dengan data produk yang ada
                    $('input[name="name"]').val(response.name);
                    $('input[name="exp"]').val(response.exp);
                    $('input[name="level"]').val(response.level);
                    // Tampilkan gambar jika ada
                    if (response.icon) {
                        $('.image-input-wrapper').css('background-image', `url(/storage/${response.icon})`);
                    }

                    // Ubah action form untuk update
                    var form = $('#kt_modal_add_customer_form');
                    form.attr('action', `/tier/${id}`); // URL untuk update produk
                    form.find('input[name="_method"]').remove(); // Hapus input _method jika ada
                    form.append(
                        '<input type="hidden" name="_method" value="PUT">'
                    ); // Menambahkan input _method untuk PUT

                    // Tampilkan modal untuk edit produk
                    var modal = new bootstrap.Modal(document.getElementById('kt_modal_add_customer'));
                    modal.show();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memuat data produk.'
                    });
                }
            });
        }

        $("#date").flatpickr({
            altInput: !0,
            altFormat: "d F, Y",
            dateFormat: "Y-m-d"
        });

        $('#kt_modal_add_customer').on('shown.bs.modal', function() {
            $('#product_id').select2({
                placeholder: 'Select a product',
                dropdownParent: $('#kt_modal_add_customer'),
                ajax: {
                    url: '{{ route('ajax.stock-available') }}',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.name,
                            stock_available: item.stock_available
                        }))
                    })
                }
            }).on('select2:select', function(e) {
                const data = e.params.data;
                $('input[name="quantity"]').val(data.stock_available || 0);
            });

        });

        function format(rowData) {
            return `
                <div class="accordion-content">
                    <form class="accordion-form" data-id="${rowData.id}">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Atur Benefit</h3>
                            </div>
                            <div class="card-body">
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-text-start">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>
                                                Diskon Setiap Transaksi
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" name="discount_transaction" value="${rowData.discount_transaction || ''}" placeholder="Diskon (Rp jika > 100, % jika ≤ 100)" />
                                    </div>
                                </div>
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-text-start">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>
                                                Gratis Product
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-select select-product" multiple name="free_product_id[]" style="width:100%;"></select>
                                    </div>
                                </div>
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-text-start">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>
                                                Minimal Pembelian
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" name="minimal_purchase" value="${rowData.minimal_purchase || ''}" placeholder="Minimal Pembelian" />
                                    </div>
                                </div>
                                <div class="row fv-row mb-7 fv-plugins-icon-container">
                                    <div class="col-md-3 text-md-text-start">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>
                                                Hadiah Ulang Tahun
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-check form-switch mt-3">
                                            <input class="form-check-input" type="checkbox" name="birthday_gift" id="birthday_gift" ${rowData.birthday_gift == 1 ? 'checked' : ''} value="1">
                                            <label class="form-check-label" for="birthday_gift">
                                                Aktifkan Hadiah Ulang Tahun
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row fv-row mb-7 fv-plugins-icon-container align-items-center">
                                    <div class="col-md-3 text-md-text-start">
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>
                                                Promo Gabungan
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-check form-switch mt-3">
                                            <input class="form-check-input" type="checkbox" name="combo_promo" id="combo_promo" ${rowData.combo_promo == 1 ? 'checked' : ''} value="1">
                                            <label class="form-check-label" for="combo_promo">
                                                Aktifkan Promo Gabungan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            `;
        }

        $('#transaction-table tbody').on('click', 'tr', function(e) {
            if ($(e.target).closest('button, a').length) return;

            let tr = $(this);
            let row = dataTable.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                console.log(row.data());
                row.child(format(row.data())).show();
                tr.addClass('shown');

                // Aktifkan select2 setelah konten ditambahkan ke DOM
                let select = tr.next().find('.select-product');

                select.select2({
                    ajax: {
                        url: '/ajax/listProduct',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.id,
                                    text: item.name
                                }))
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Pilih Produk',
                    minimumInputLength: 1
                });

                // 👇 Tambahkan ini untuk mengisi default value (selected)
                let freeProducts = row.data().freeProduct || [];
                console.log(freeProducts);
                freeProducts.forEach(prod => {
                    let option = new Option(prod.name, prod.id, true, true);
                    select.append(option);
                });

                select.trigger('change');

            }
        });

        // Submit form di dalam accordion
        $('#transaction-table tbody').on('submit', '.accordion-form', function(e) {
            e.preventDefault();
            let form = $(this);
            let id = form.data('id');
            let data = form.serialize();

            $.ajax({
                url: `/tier/${id}/save-detail`,
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    alert('Data berhasil disimpan!');
                    if (typeof dataTable !== 'undefined') dataTable.ajax.reload(
                        null, false);
                },
                error: function() {
                    alert('Gagal menyimpan!');
                }
            });
        });
    </script>
@endsection
@endsection

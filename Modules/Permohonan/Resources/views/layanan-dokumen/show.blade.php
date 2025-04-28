@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'List Form Layanan')
@section('add-page')
    <a href="javascript:void(0)" class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#modal_create">
        <i class="fa fa-plus"></i> Tambah
    </a>
@endsection
@section('content')
    <div class="table-responsive signal-table custom-scrollbar">
        <table class="table table-hover" id="table-data">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Document</th>
                    <th scope="col">Status</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>
                            {{ $item->nama_document ?? '' }}
                        </td>                        
                        <td>
                            {{ $item->status ?? '' }}
                        </td>
                        <td>
                            {{ $item->keterangan ?? '' }}
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" title="Edit" class="btn btn-sm btn-warning btn-edit"
                                data-url="{{ url(Request::segment(1), $item->id) }}" data-temp="{{ json_encode($item) }}">
                                <span><i class="fa fa-edit"></i></span>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger btn-delete" type="button"
                                data-url="{{ url(Request::segment(1)) . '/' . $item->id }}">
                                <span><i class="fa fa-times"></i></span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade bd-example-modal-lg" id="modal_create" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myExtraLargeModal">Tambah {{ Request::segment(1) }}</h4>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body dark-modal">
                    <form action="{{ url(Request::segment(1)) }}" method="POST" id="modal_create_form">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="">
                        <input type="hidden" name="id_layanan" id="id_layanan" value="{{ Request::segment(2) }}">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="nm_layanan">Nama Document</label>
                            <input class="form-control" id="nama_document" name="nama_document" type="text" value=""
                                placeholder="Ex : Fotocopy KK">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="nm_layanan">Status</label>
                            <input class="form-control" id="status" name="status" type="text" value=""
                                placeholder="Ex : required">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="nm_layanan">Keterangan</label>
                            <input class="form-control" id="keterangan" name="keterangan" type="text" value=""
                                placeholder="">
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> <i class="fa fa-check"></i>
                                Submit</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@section('script')
    <script>
        const tableData = $('#table-data');
        tableData.on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const parent = e.target.closest('tr');
            var urlnya = $(this).data('url');
            var token = '{{ csrf_token() }}';
            const namaData = parent.querySelectorAll('td')[1].innerText;

            Swal.fire({
                text: "Are you sure you want to delete " + namaData + "?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function(result) {
                if (result.value) {
                    Swal.fire({
                        text: "Deleting " + namaData,
                        icon: "info",
                        buttonsStyling: false,
                        showConfirmButton: false,
                        timer: 1000
                    }).then(function() {
                        $.ajax({
                            type: "DELETE",
                            url: urlnya,
                            data: {
                                _token: token
                            },
                            success: function(data) {
                                location.reload();
                            },
                            error: function(err) {
                                location.reload();
                            }
                        });
                    });
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: namaData + " was not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        });

        tableData.on('click', '.btn-edit', function(e) {
            e.preventDefault();
            var urlnya = $(this).data('url');
            var data = $(this).data('temp');
            $("#modal_create_form").attr("action", urlnya);
            $("#_method").val("PUT");
            $("#nama_document").val(data.nama_document);
            $("#status").val(data.status);
            $("#keterangan").val(data.keterangan);
            $("#modal_create").modal('show');
        });
    </script>
@endsection
@endsection

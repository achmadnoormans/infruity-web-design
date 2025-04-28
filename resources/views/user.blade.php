@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'List User')
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
                    <th scope="col">Nama User</th>
                    <th scope="col">Role</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $item->nm_user ?? '' }}</td>
                        <td>
                            @foreach ($item->roleUser as $roleUser)
                                {{ $roleUser->role->nm_role ?? '' }}
                            @endforeach
                        </td>
                        <td>{{ $item->email ?? '' }}</td>
                        <td class="text-center">
                            <a href="javascript:void(0)" title="Edit" class="btn btn-sm btn-warning btn-edit"
                                data-url="{{ url(Request::segment(1), $item->id_user) }}"
                                data-temp="{{ json_encode($item) }}">
                                <span><i class="fa fa-edit"></i></span>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger btn-delete" type="button"
                                data-url="{{ url(Request::segment(1)) . '/' . $item->id_user }}">
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
                    <form action="{{ Request::segment(1) }}" method="POST" id="modal_create_form">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="nm_user">Nama User</label>
                            <input class="form-control" id="full_name" name="full_name" type="text" value=""
                                placeholder="Nama User">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="email" value=""
                                placeholder="admin@admin.com">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="email">Password</label>
                            <input class="form-control" id="password" name="password" type="password" value=""
                                placeholder="******">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="email">Role User</label>
                            <select name="id_role" id="id_role" class="form-control">
                                @foreach ($role as $role)
                                    <option value="{{ $role->id_role }}">{{ $role->nm_role }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id_ru" id="id_ru">
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
            const namaData = parent.querySelectorAll('td')[0].innerText;

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
            console.log(data);
            $("#modal_create_form").attr("action", urlnya);
            $("#_method").val("PUT");
            $("#full_name").val(data.nm_user);
            $("#email").val(data.email);
            $("#id_role").val(data.role_user[0].id_role);
            $("#id_ru").val(data.role_user[0].id_ru);
            $("#modal_create").modal('show');
        });
    </script>
@endsection
@endsection

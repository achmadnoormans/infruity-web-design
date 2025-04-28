@extends('template.root')
@section('page-name', 'arsip')
@section('title-page', 'Arsip Berkas')
@section('content')
@section('add-page')
    <a href="{{ url('arsip/create') }}" class="btn btn-md btn-primary">
        <i class="fa fa-plus"></i> Tambah
    </a>
@endsection
<div class="table-container">
    <table id="usersTable" class="table responsive table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemohon :</th>
                <th>Alamat Persil</th>
                <th>Tanggal Pengajuan</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    $(document).ready(function() {
        $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('arsip.get-data') }}",
                data: function(d) {
                    d.url = "{{ request()->segment(1) }}";
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nama_pemohon',
                    name: 'nama_pemohon'
                },
                {
                    data: 'alamat_persil',
                    name: 'alamat_persil'
                },
                {
                    data: 'tanggal_pengajuan',
                    name: 'tanggal_pengajuan'
                },
                {
                    targets: -1, // Target kolom aksi
                    data: null,
                    render: function(data, type, row) {
                        return `
                                <div class="common-flex light-dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">Aksi</button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-block">
                                        <li>
                                            <a class="dropdown-item active" href="{{ url('arsip') }}/` + row.id + `/edit"><i class="fa-solid fa-pencil"></i> Edit</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item active" href="{{ url('arsip') }}/` + row.id + `/detail"><i class="fa-solid fa-eye"></i> Show</a>
                                        </li>
                                        
                                        <!-- Add other dropdown items based on your logic -->
                                    </ul>
                                </div>
                            `;
                    }
                }

            ]
        });
    });
</script>
@endsection

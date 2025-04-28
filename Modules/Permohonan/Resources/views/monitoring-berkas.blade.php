@extends('template.root')
@section('page-name', 'permohonan')
@section('title-page', 'Monitoring Berkas')
@section('add-page')
    <a href="{{ 'cetak-berkas' }}?url={{ request()->segment(1) }}" class="btn btn-sm btn-success"><i class="fa fa-print"></i>
        Cetak
        Data</a>
@endsection
@section('content')
    <div class="table-container">
        <table id="usersTable" class="table responsive table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Pengajuan</th>
                    <th>No Daftar</th>
                    <th>Nama Pemohon :</th>
                    <th>Jenis Permohonan :</th>
                    <th>Posisi Berkas Sekarang</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    <br>
    @isset($history)
        <h3>History</h3>
        <br>
        <table class="table table-bordered" id="table-data">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Tanggal Proses</th>
                    <th scope="col">User</th>
                    <th scope="col">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ isset($item->tgl_status) ? dateindo($item->tgl_status) : '-' }} <br> {{ $item->created_at }}
                        </td>
                        <td>
                            @if ($permohonan->id_layanan == 7 && $item->id_role == 8)
                                Petugas Bo
                            @else
                                @if ($item->id_status == 1 && $item->id_role != 99)
                                    Petugas Bo
                                @else
                                    {{ ucwords(strtolower(string: $item->nm_role)) }}
                                @endif
                            @endif
                        </td>
                        <td>{{ ucwords(strtolower($item->keterangan ?? 'Tanpa Keterangan')) }}</td>
                    </tr>
                @endforeach
                @if ($permohonan->id_status < 11)
                    <tr>
                        <td><span class="badge bg-info">Sekarang</span></td>
                        <td>{{ isset($item->tgl_status) ? dateindo($item->tgl_status) : '-' }}</td>
                        <td>Petugas Bo</td>
                        <td><span class="badge bg-success">On Progress</span></td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endisset
    @include('permohonan::js-permohonan')
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('monitoring-proses-data') }}",
                    data: function(d) {
                        d.id_permohonan = $('#id_permohonan').val();
                        d.nm_pemohon = $('#nm_pemohon').val();
                        d.alamat_persil = $('#alamat_persil').val();
                        d.id_layanan = $('#id_layanan').val();
                        d.id_status = $('#id_status').val();
                        d.url = "{{ request()->segment(1) }}";
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'tanggal_pengajuan',
                        name: 'tanggal_pengajuan'
                    },
                    {
                        data: 'no_permohonan',
                        name: 'no_permohonan'
                    },
                    {
                        data: 'nama_pemohon',
                        name: 'nama_pemohon'
                    },
                    {
                        data: 'nm_layanan',
                        name: 'nm_layanan'
                    },
                    {
                        data: 'posisi',
                        name: 'posisi'
                    },
                    {
                        data: 'status_berkas',
                        name: 'status_berkas'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                ]
            });
        });
    </script>
@endsection

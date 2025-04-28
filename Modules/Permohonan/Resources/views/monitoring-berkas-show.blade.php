@extends('template.root')
@section('page-name', 'permohonan')
@section('title-page', 'Monitoring Berkas')
@section('content')
    <div class="table-responsive">
        <table class="table table-bordered" width="100%">
            <thead>
                <tr>
                    <th>No Daftar</th>
                    <th>Nama Pemohon</th>
                    <th>Jenis Permohonan</th>
                    <th>Posisi Berkas Sekarang</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="{{ url('permohonan') . '/' . $data->id . '/verifikasi' }}">{{ $data->no_permohonan }} <br>
                            {{ dateindo($data->tanggal_pengajuan) }} </a></td>
                    <td>{{ $data->nama_pemohon }} <br> {{ $data->alamat_persil }} </td>
                    <td>{{ $data->nm_layanan }} </td>
                    <td><span class="text-muted">Status : {{ ucwords(strtolower($data->nama_status)) }}</span> <br>
                        <b>({{ $data->nm_role }})</b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
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
                        @if ($data->id_layanan == 7 && $item->id_role == 8)
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
            @if ($data->id_status < 11)
                <tr>
                    <td><span class="badge bg-info">Sekarang</span></td>
                    <td>{{ isset($item->tgl_status) ? dateindo($item->tgl_status) : '-' }}</td>
                    <td>Petugas Bo</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection

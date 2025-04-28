@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'List Surat Pengurangan IPT')
@section('content')
    <div class="table-responsive signal-table custom-scrollbar">
        <table class="table table-hover" id="table-data">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">No Surat</th>
                    <th scope="col">No Permohonan</th>
                    <th scope="col">Tipe Permohonan</th>
                    <th scope="col">Nama Pemegang IPT</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <th>
                            <a href="{{ url(Request::segment(1)) . '/' . $item->id_surat . '/cetak-surat' }}">
                                {{ $item->nomer_surat ? '500.16.7.2 /' . $item->nomer_surat . '.SK/436.8.2/' . date('Y', strtotime($item->tgl_surat)) : '' }}
                            </a>
                            <br>
                            Dibuat : {{ isset($item->tgl_surat) ? dateindo($item->tgl_surat) : '-' }}
                        </th>
                        <th>
                            {{ $item->no_permohonan ?? '' }}<br>
                            {{ ucwords(strtolower($item->nama_pemohon)) }}
                        </th>
                        <td>{{ $item->type }}</td>
                        <td>
                            {{ $item->nama_pemegang_ipt }}
                            <br>
                            {{ $item->alamat_persil }}
                        </td>
                        <td>
                            <a class="btn btn-primary"
                                href="{{ url(Request::segment(1)) . '/' . $item->id_surat . '/cetak-surat' }}"><i
                                    class="fa-solid fa-print"></i></a>
                            <a class="btn btn-info"
                                href="{{ url('ipt-pengurangan') . '/' . $item->id_permohonan . '/verifikasi#data-syarat' }}"><i
                                    class="fa-solid fa-file"></i></a>
                            @if ($item->id_status <= 3 && check_access('create-surat'))
                                <a class="btn btn-danger btn-sm"
                                    href="{{ url(Request::segment(1)) . '/' . $item->id_surat . '/edit' }}"><i
                                        class="fa-solid fa-pencil"></i> </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('permohonan::surat-keterangan.js-surat')
@endsection

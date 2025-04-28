@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'List Permohonan')
@section('add-page')
    <a href="{{ url('permohonan/create') }}" class="btn btn-md btn-primary">
        <i class="fa fa-plus"></i> Tambah
    </a>
@endsection
@section('content')
    <div class="table-responsive signal-table custom-scrollbar">
        <table class="table table-hover" id="table-data">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">No Permohonan</th>
                    <th scope="col">Nama Pemohon</th>
                    <th scope="col">Tipe Permohonan</th>
                    <th scope="col">Posisi Sekarang</th>
                    <th scope="col">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        @php
                            $url = url(Request::segment(1)) . '/' . $item->id . '/detail';
                            if (Session('role')['id_role'] != 99) {
                                $url = url(Request::segment(1)) . '/' . $item->id . '/verifikasi';
                            }
                        @endphp
                        <th><a style="{{ $item->last_status == 99 ? 'color:red' : '' }}" href="{{ $url }}">
                                {{ $item->no_permohonan ?? '' }} {{ $item->last_status == 99 ? '(Reject) - ' . $item->last_keterangan : '' }}
                            </a>
                        </th>
                        <td>
                            {{ $item->nama_pemohon ?? '' }}
                        </td>
                        <td>{{ $item->layanan->nm_layanan ?? '' }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bg-light-{{ $item->class_color ?? '' }} font-{{ $item->class_color ?? '' }}"
                                    data-feather="{{ $item->icon }}"></i><span
                                    class="font-{{ $item->class_color ?? '' }}">{{ ucwords(strtolower($item->nm_role ?? '')) }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="common-flex light-dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">Opsi</button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-block">
                                    <li>
                                        <a class="dropdown-item active"
                                            href="{{ url(Request::segment(1)) . '/' . $item->id . '/detail' }}"><i
                                                class="fa-solid fa-eye"></i> Show</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item active"
                                            href="{{ url(Request::segment(1)) . '/' . $item->id . '/history' }}"><i
                                                class="fa-solid fa-history"></i> History</a>
                                    </li>
                                    @if (in_array($item->id_status, [99, 100]))
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ url('permohonan/' . $item->id . '/edit') }}"><i
                                                    class="fa-solid fa-edit"></i>
                                                Edit </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item btn-delete"
                                                data-url="{{ url(Request::segment(1)) . '/' . $item->id }}"
                                                data-kode="{{ $item->no_permohonan }}">
                                                <i class="fas fa-trash-alt"></i>
                                                Hapus</a>
                                        </li>
                                    @endif
                                    @if (check_access('cetak-permohonan'))
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ url('permohonan/' . $item->id . '/cetak-permohonan') }}"><i
                                                    class="fa-solid fa-print"></i>
                                                Cetak Permohonan </a>
                                        </li>
                                    @endif
                                    @if (check_access('cetak-formulir'))
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ url('permohonan/' . $item->id . '/cetak-formulir') }}"><i
                                                    class="fa-solid fa-print"></i>
                                                Cetak Formulir </a>
                                        </li>
                                    @endif
                                    @if ($item->id_status == 10 && Session('role')['id_role'] != 99)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ url('surat/' . $item->id_surat . '/cetak-surat') }}"><i
                                                    class="fa-solid fa-print"></i>
                                                Cetak Surat (Dokumen) </a>
                                        </li>
                                    @endif
                                    @if (check_access('show-bap') && ($item->id_status >= 3 && !in_array($item->id_status, [99, 100])))
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ url('permohonan/' . $item->id . '/show-bap') }}"><i
                                                    class="fa-solid fa-print"></i>
                                                Cetak BAP </a>
                                        </li>
                                    @endif
                                    @if (check_access('verifikasi') && !in_array($item->id_status, [10, 99, 100]))
                                        <li>
                                            <a class="dropdown-item active"
                                                href="{{ url(Request::segment(1)) . '/' . $item->id . '/verifikasi' }}"><i
                                                    class="fa-solid fa-check"></i>
                                                Verifikasi</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('permohonan::js-permohonan')
@endsection

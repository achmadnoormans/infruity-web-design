@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'List Permohonan Saya')
@section('add-page')
    <a href="{{ url('dashboard') }}" class="btn btn-md btn-primary">
        <i class="fa fa-plus"></i> Buat Permohonan
    </a>
@endsection
@section('content')
    <div class="row">
        @foreach ($data as $item)
            <div class="col-12">
                <div class="card widget-1">
                    <div class="card-header {{ $item->type == 'permohonan-sk' ? 'bg-light-primary' : 'bg-light-success' }}">
                        <div class="header-top">
                            <h5>{{ $item->type == 'permohonan-sk' ? 'Permohonan Surat Keterangan' : 'Permohonan Pengurangan IPT' }}
                            </h5>
                            <div class="card-header-right-icon">
                                <div class="dropdown icon-dropdown">
                                    <button class="btn dropdown-toggle" id="activityButton" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="icon-more-alt"></i></button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="activityButton">
                                        <a class="dropdown-item"
                                            href="{{ $item->type == 'permohonan-sk' ? url('permohonan') . '/' . $item->id . '/detail' : url('ipt-pengurangan') . '/' . $item->id . '/detail' }}"><i
                                                class="fa-solid fa-eye"></i> Show</a>
                                        <a class="dropdown-item"
                                            href="{{ $item->type == 'permohonan-sk' ? url('permohonan') . '/' . $item->id . '/history' : url('ipt-pengurangan') . '/' . $item->id . '/history' }}"><i
                                                class="fa-solid fa-history"></i> History</a>
                                        @if (in_array($item->id_status, [99, 100]))
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ $item->type == 'permohonan-sk' ? url('permohonan') . '/' . $item->id . '/edit' : url('ipt-pengurangan') . '/' . $item->id . '/edit' }}"><i
                                                        class="fa-solid fa-edit"></i>
                                                    Edit </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item btn-delete"
                                                    data-url="{{ $item->type == 'permohonan-sk' ? url('permohonan') . '/' . $item->id : url('ipt-pengurangan') . '/' . $item->id }}"
                                                    data-kode="{{ $item->no_permohonan }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                    Hapus</a>
                                            </li>
                                        @endif
                                        @if (check_access('cetak-permohonan'))
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ $item->type == 'permohonan-sk' ? url('permohonan') . '/' . $item->id . '/cetak-permohonan' : url('ipt-pengurangan') . '/' . $item->id . '/cetak-permohonan' }}"><i
                                                        class="fa-solid fa-print"></i>
                                                    Cetak Permohonan </a>
                                            </li>
                                        @endif
                                        @if (check_access('cetak-formulir'))
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ $item->type == 'permohonan-sk' ? url('permohonan') . '/' . $item->id . '/cetak-formulir' : url('ipt-pengurangan') . '/' . $item->id . '/cetak-formulir' }}"><i
                                                        class="fa-solid fa-print"></i>
                                                    Cetak Formulir </a>
                                            </li>
                                        @endif
                                        @if ($item->id_status >= 11 && $item->id_status < 99)
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ url('surat') . '/' . $item->id_surat . '/cetak-surat' }}"><i
                                                        class="fa-solid fa-print"></i>
                                                    Cetak Surat </a>
                                            </li>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a
                        href="{{ $item->type == 'permohonan-sk' ? url('permohonan') . '/' . $item->id . '/detail' : url('ipt-pengurangan') . '/' . $item->id . '/detail' }}">
                        <div class="card-body">
                            <div class="widget-content">
                                <div class="widget-round success">
                                    <div class="bg-round">
                                        <svg class="fill-success">
                                            <use href="{{ asset('cuba/svg/icon-sprite.svg#c-invoice') }}"> </use>
                                        </svg>
                                        <svg class="half-circle svg-fill">
                                            <use href="{{ asset('cuba/svg/icon-sprite.svg#halfcircle') }}"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h6>{{ $item->no_permohonan }}</h6>
                                    <span class="f-light">{{ dateindo($item->tanggal_pengajuan) }}</span>
                                    <br>
                                    <span
                                        class="f-light">{{ $item->type == 'permohonan-sk' ? 'Jenis Permohonan' : 'Jenis Pengurangan' }}
                                        : {{ ucwords(strtolower($item->nm_layanan)) }}</span>
                                    <br>
                                    <span class="f-light">Status saat ini : </span> <span
                                        class="badge badge-light-{{ $item->class_color }}">{{ ucwords(strtolower($item->nama_status)) }}</span>
                                    <br>
                                    <span class="f-light">Step Selanjutnya : </span> <span
                                        class="badge badge-light-{{ $item->next_status_class_color }}">{{ ucwords(strtolower($item->next_status_name)) }}</span>
                                </div>
                            </div>
                            <div class="font-success f-w-500"><span class="txt-success"></span></div>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection

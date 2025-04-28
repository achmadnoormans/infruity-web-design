@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Verifikasi Permohonan Pengurangan IPT')
@section('content')
    <div>
        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary active" id="icon-home-tab"
                    data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true"> <i
                        class="icofont icofont-man-in-glasses"></i>Data Pemohon</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-syarat-tab"
                    data-bs-toggle="tab" href="#data-syarat" role="tab" aria-controls="contact-icon"
                    aria-selected="false" tabindex="-1"><i class="fa-solid fa-file-image"></i>Data Syarat</a></li>
            @if (isset($bap))
                <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-bap-tab"
                        data-bs-toggle="tab" href="#data-bap" role="tab" aria-controls="contact-icon"
                        aria-selected="false" tabindex="-1"><i class="fa-solid fa-camera"></i>Preview BAP</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-skrd-tab"
                        data-bs-toggle="tab" href="#data-skrd" role="tab" aria-controls="contact-icon"
                        aria-selected="false" tabindex="-1"><i class="fa-solid fa-file"></i>Preview SKRD Lama</a></li>
            @endif
            @if (isset($surat))
                <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-surat-tab"
                        data-bs-toggle="tab" href="#data-surat" role="tab" aria-controls="contact-icon"
                        aria-selected="false" tabindex="-1"><i class="fa-solid fa-envelope"></i>Preview Surat</a></li>
            @endif
        </ul>
        <div class="tab-content" id="icon-tabContent">
            <div class="tab-pane fade active show" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                <br><br>
                <div class="d-flex justify-content-between">
                    <h3>Detail Pemohon</h3>
                </div>
                <hr>
                <table class="table">
                    <tr>
                        <td>No Permohonan</td>
                        <td>:</td>
                        <td>{{ strtoupper($data->no_permohonan) }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Permohonan</td>
                        <td>:</td>
                        <td><b>Pengurangan {{ strtoupper($data->type ?? '') }}</b></td>
                    </tr>
                    <tr>
                        <td>Tgl Pengajuan</td>
                        <td>:</td>
                        <td>{{ dateindo($data->tanggal_pengajuan) }}</td>
                    </tr>
                    @foreach ($form as $item)
                        <tr>
                            <td>{{ ucwords(strtolower($item->nama_form)) }}</td>
                            <td>:</td>
                            @php
                                $index = change_form($item->nama_form);
                            @endphp
                            <td>{{ strtoupper($data->$index ?? '') }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="tab-pane fade" id="data-syarat" role="tabpanel" aria-labelledby="data-syarat-tab">
                <br><br>
                <div class="">
                    <h3>Detail Dokumen Pemohon</h3>
                </div>
                <hr>
                <table class="table">
                    @foreach ($document as $item)
                        <tr>
                            <td>{{ ucwords(strtolower($item->nama_document)) }}</td>
                            <td>:</td>
                            @php
                                $index = change_form($item->nama_document);
                            @endphp
                            @if (isset($dataDocument[$index]))
                                <td>
                                    <a href="{{ route('show-document', [
                                        'pdf' => $dataDocument[$index],
                                    ]) }}"
                                        target="_blank" class="badge badge-light-success">
                                        <span>Ada Document</span>
                                    </a>
                                </td>
                                <td>
                                    <a
                                        href="{{ route('show-document', [
                                            'pdf' => $dataDocument[$index],
                                        ]) }}">
                                        <i class="fa-solid fa-file-image"></i>
                                    </a>
                                </td>
                            @else
                                <td>
                                    <span class="badge badge-light-danger">Tidak ada Document</span>
                                </td>
                            @endif

                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="tab-pane fade" id="data-bap" role="tabpanel" aria-labelledby="data-bap-tab">
                @include('permohonan::segment.bap')
            </div>
            <div class="tab-pane fade" id="data-skrd" role="tabpanel" aria-labelledby="data-skrd-tab">
                @include('permohonan::segment.skrd')
            </div>
            <div class="tab-pane fade" id="data-surat" role="tabpanel" aria-labelledby="data-bap-tab">
                @include('permohonan::segment.surat-keterangan')
            </div>
        </div>
    </div>
    @include('permohonan::layouts.action-pengurangan')
    @include('permohonan::layouts.modal-pengurangan')

@section('script')
    <script>
        function verifikasi(params, modal, status) {
            $('#id_status' + modal).val(status);
            $('#' + modal + '-modal').modal('show');
        }
    </script>
@endsection
@endsection

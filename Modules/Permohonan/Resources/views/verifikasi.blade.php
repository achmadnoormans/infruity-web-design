@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Verifikasi Permohonan')
@section('content')
    <div>
        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary active" id="icon-home-tab"
                    data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true"> <i
                        class="icofont icofont-man-in-glasses"></i>Data Pemohon</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-syarat-tab"
                    data-bs-toggle="tab" href="#data-syarat" role="tab" aria-controls="contact-icon"
                    aria-selected="false" tabindex="-1"><i class="fa-solid fa-file-image"></i>Data Syarat</a></li>
            @if (isset($arsip))
                <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-arsip-tab"
                        data-bs-toggle="tab" href="#data-arsip" role="tab" aria-controls="contact-icon"
                        aria-selected="false" tabindex="-1"><i class="fa-solid fa-folder-open"></i>Preview Arsip</a></li>
            @endif
            @if (isset($bap))
                <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-bap-tab"
                        data-bs-toggle="tab" href="#data-bap" role="tab" aria-controls="contact-icon"
                        aria-selected="false" tabindex="-1"><i class="fa-solid fa-camera"></i>Preview BAP</a></li>
            @endif
            @if (isset($surat))
                <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-surat-tab"
                        data-bs-toggle="tab" href="#data-surat" role="tab" aria-controls="contact-icon"
                        aria-selected="false" tabindex="-1"><i class="fa-solid fa-envelope"></i>Preview Surat</a></li>
            @endif
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-history-tab"
                    data-bs-toggle="tab" href="#data-history" role="tab" aria-controls="contact-icon"
                    aria-selected="false" tabindex="-1"><i class="fa-solid fa-file-image"></i>History</a></li>
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
                        <td><b>{{ strtoupper($data->layanan->nm_layanan ?? '') }}</b></td>
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
                    <span style="color: {{ $data->is_lengkap == 1 ? 'green' : 'red' }}">
                        {{ $data->is_lengkap == 1 ? '(Dokumen Lengkap dan Sudah di Verifikasi)' : '(Dokumen Belum Di Verifikasi Tim)' }}
                    </span>
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
                                    <a href="{{ route('show-document', [
                                        'pdf' => $dataDocument[$index],
                                    ]) }}"
                                        target="_blank">
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
            <div class="tab-pane fade" id="data-history" role="tabpanel" aria-labelledby="data-history-tab">
                <br><br>
                <div class="">
                    <h3>History</h3>
                </div>
                <hr>
                <div class="table-responsive signal-table custom-scrollbar">
                    <table class="table table-hover" id="table-data">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Status</th>
                                <th scope="col">Tanggal Status</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $key => $item)
                                <tr class="{{ $item->id_status == 99 ? 'bg-danger' : '' }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ ucwords(strtolower($item->nm_status)) }}</td>
                                    <td>{{ isset($item->tgl_status) ? dateindo($item->tgl_status) : '-' }}</td>
                                    <td>{{ ucwords(strtolower($item->keterangan ?? 'Tanpa Keterangan')) }}</td>
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
                                </tr>
                            @endforeach
                            @if ($data->id_status < 11)
                                <tr>
                                    <td><span class="badge bg-info">Sekarang</span></td>
                                    <td>{{ $data->nm_role }}</td>
                                    <td>{{ isset($item->tgl_status) ? dateindo($item->tgl_status) : '-' }}</td>
                                    <td><span class="badge bg-success">On Progress</span></td>
                                    <td>-</td>
                                </tr>
                            @elseif ($data->id_status == 11)
                                <tr>
                                    <td><span class="badge bg-info">Sekarang</span></td>
                                    <td>{{ $data->nm_role }}</td>
                                    <td>{{ isset($item->tgl_status) ? dateindo($item->tgl_status) : '-' }}</td>
                                    <td><span class="badge bg-success">Surat Jawaban Bisa diUnduh 1x</span></td>
                                    <td>
                                        <a href="{{ route('show-document', [
                                            'pdf' => $surat->file,
                                        ]) }}"
                                            target="_blank">
                                            <i class="fa-solid fa-cloud-download"></i> Download Surat
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="data-arsip" role="tabpanel" aria-labelledby="data-arsip-tab">
                @include('permohonan::segment.cek-arsip')
            </div>
            <div class="tab-pane fade" id="data-bap" role="tabpanel" aria-labelledby="data-bap-tab">
                @include('permohonan::segment.bap')
            </div>
            <div class="tab-pane fade" id="data-surat" role="tabpanel" aria-labelledby="data-bap-tab">
                @include('permohonan::segment.surat-new')
            </div>
        </div>
    </div>
    @include('permohonan::layouts.action')
    @include('permohonan::layouts.modal')

@section('script')
    <script>
        function verifikasi(params, modal, status) {
            $('#id_status' + modal).val(status);
            $('#' + modal + '-modal').modal('show');
        }

        $('.pilihan-status').on('change', function() {
            const value = $(this).val();
            if (value == 1) {
                $('#petugas-survey').hide();
            } else {
                $('#petugas-survey').show();
            }
        });
    </script>
@endsection
@endsection

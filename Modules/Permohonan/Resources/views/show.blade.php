@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Detail Permohonan')
@section('add-page')
@endsection
@section('content')
    <style>
        .running-text {
            overflow: hidden;
            position: relative;
        }

        .running-text span {
            display: inline-block;
            white-space: nowrap;
            animation: jalan 40s linear infinite;
        }

        @keyframes jalan {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(-100%);
            }
        }
    </style>
    <div>
        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary active" id="icon-home-tab"
                    data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true"> <i
                        class="icofont icofont-man-in-glasses"></i>Data Permohonan</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-syarat-tab"
                    data-bs-toggle="tab" href="#data-syarat" role="tab" aria-controls="contact-icon"
                    aria-selected="false" tabindex="-1"><i class="fa-solid fa-file-image"></i>History</a></li>
        </ul>
    </div>
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
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                        <span class="badge badge-light-success">{{ $data->status->nama_status }}</span>
                    </td>
                </tr>
            </table>
            <div class="col-md-12 text-end">
                <a href="{{ url('permohonan/' . $data->id . '/cetak-formulir') }}" target="_blank" class="btn btn-primary">
                    <i class="fa-solid fa-cloud-download"></i> Downlod Formulir
                </a>
            </div>
            <br><br>

            <div class="d-flex">
                <h3>Detail Document</h3>
                <div class="container py-2">
                    <div class="running-text">
                        <span>❗ Pastikan Berkas <b>SESUAI FORM</b>, Berkas <b>LENGKAP</b> dan
                            <b>TIDAK TERPOTONG</b>, Berkas Harus <b>BISA DIBACA</b>, Apabila tidak sesuai maka akan di
                            <b style="color: red">KEMBALIKAN</b>
                            ❗</span>
                    </div>
                </div>

            </div>
            @if ($data->id_status == 99)
                <p class="text-danger">(Data Ditolak, Periksa Lagi Document Anda)</p>
            @endif
            <hr>

            <table class="table">
                @foreach ($document as $item)
                    <tr>
                        <td>{{ ucwords(strtolower($item->nama_document)) }} {{ $item->status == 'required' ? '*' : '' }}
                        </td>
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
                                @if (in_array($data->id_status, [99, 100]))
                                    <button class="btn btn-success me-3"
                                        onclick="upload_formulir('{{ ucwords(strtolower($item->nama_document)) }}', '{{ $index }}')"><i
                                            class="fa-solid fa-cloud-upload"></i>
                                    </button>
                                @endif
                            </td>
                        @else
                            <td>
                                <span class="badge badge-light-danger">Tidak ada Document</span>
                            </td>
                            <td>
                                @if (in_array($data->id_status, [99, 100]))
                                    <button class="btn btn-success me-3"
                                        onclick="upload_formulir('{{ ucwords(strtolower($item->nama_document)) }}', '{{ $index }}')"><i
                                            class="fa-solid fa-cloud-upload"></i>
                                    </button>
                                @endif
                            </td>
                        @endif

                    </tr>
                @endforeach
            </table>
            <br>
            <div>
                Keterangan :
                <ul>
                    <li>*) Document Wajib Diupload</li>
                </ul>
            </div>

        </div>
        <div class="tab-pane fade" id="data-syarat" role="tabpanel" aria-labelledby="data-syarat-tab">
            <br><br>
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
    </div>
    <div class="col-md-12 text-end mt-3">
        <div class="col-sm-4 offset-sm-8 d-flex">
            @if ($data->id_status == 99)
                <button class="btn btn-primary me-3" onclick="submit_ulang()"><i class="fas fa-check"></i>
                    Submit Ulang</button>
            @endif
            @if (isset($dataDocument['formulir']) && $data->id_status == 1 && $data->id_status <= 11)
                <a href="{{ url('permohonan/' . $data->id . '/cetak-permohonan') }}" target="_blank"
                    class="btn btn-sm btn-primary">
                    <i class="fa fa-print"></i>Bukti Permohonan
                </a>
            @endif
            @if ($data->id_status == 100)
                <button type="submit" class="btn btn-primary" style="margin-right: 8px" onclick="submit()">
                    <i class="fa-solid fa-cloud-upload"></i>
                    Daftar
                </button>
            @endif
            <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i>
                Kembali</a>
        </div>
    </div>
    @include('permohonan::layouts.modal-upload-formulir')
@endsection

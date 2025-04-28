@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Detail Permohonan Pengurangan IPT')
@section('add-page')
@endsection
@section('content')
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
            <table class="table">
                <tr>
                    <td>No Permohonan</td>
                    <td>:</td>
                    <td>{{ strtoupper($data->no_permohonan) }}</td>
                </tr>
                <tr>
                    <td>Jenis Pengurangan</td>
                    <td>:</td>
                    <td><b>{{ strtoupper($data->type) }}</b></td>
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
            <br><br>

            <h3>Detail Document</h3>
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
            <div class="card p-3" style="background-color: rgb(214, 213, 213); text-align: justify;">
                Keterangan :
                <ul>
                    <li>*) Document Wajib Diupload</li>
                    <li>*) <b>Dokumen Pendukung</b> Harus sesuai dengan jenis permohonan antara lain :</li>
                    <br>
                    <li>1. Foto / Scan, Kartu Tanda Anggota dan/atau bukti yang menyatakan bahwa pemohon
                        adalah veteran pejuang kemerdekaan, veteran pembela kemerdekaan, penerima tanda jasa bintang gerilya
                        bagi
                        pemohon yang berasal dari
                        anggota veteran atau suami/isteri/janda/duda veteran</li>
                    <li>2. Foto / Scan, bukti yang menyatakan bahwa pemohon adalah pensiunan Pegawai Negeri
                        sipil/Prajurit Tentara Nasional Indonesia/Anggota Kepolisian Negara Republik
                        Indonesia atau suami/isteri/janda/duda pensiunan</li>
                    <li>3. Foto / Scan proposal kegiatan atau dokumen lain yang dipersamakan bagi pemohon untuk kegiatan
                        yang
                        bersifat
                        sosial/keagamaan</li>
                    <li>4. Foto / Scan, surat keterangan waris, apabila pemohon adalah ahli waris dari wajib retribusi, dan
                        surat
                        kuasa dari para ahli waris apabila permohonan diajukan oleh salah satu dari ahli waris</li>
                    <li>5. Foto / Scan, bukti yang menyatakan bahwa pemohon adalah pensiunan Pegawai Negeri
                        sipil/Prajurit Tentara Nasional Indonesia/Anggota Kepolisian Negara Republik
                        Indonesia atau suami/isteri/janda/duda pensiunan</li>
                    <li>6. surat keterangan penghasilan dari tempat pemohon bekerja yang ditandatangani oleh
                        pemimpin tempat kerja atau surat pernyataan penghasilan yang diketahui oleh Lurah</li>
                    <li>7. Untuk MBR, Jika Dia Swasta Melampirkan Surat Keterangan Tidak Mampu Rt / Rw, atau Melampirkan
                        Dokumen
                        Dari DINSOS</li>
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
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ ucwords(strtolower($item->nm_status)) }}</td>
                                <td>{{ isset($item->tgl_status) ? dateindo($item->tgl_status) : '-' }}</td>
                                <td>{{ ucwords(strtolower($item->keterangan ?? 'Tanpa Keterangan')) }}</td>
                                <td>{{ ucwords(strtolower($item->nama_verifikator)) }}</td>
                            </tr>
                        @endforeach
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
            @if ($data->id_status == 1)
                <a href="{{ url(Request::segment(1) . '/' . $data->id . '/cetak-permohonan') }}" target="_blank"
                    class="btn btn-sm btn-primary" style="margin-right: 30px">
                    <i class="fa fa-print"></i>Bukti Permohonan
                </a>
            @endif
            @if ($data->id_status == 100)
                <button type="button" class="btn btn-primary" onclick="submit()" style="margin-right: 8px">
                    <i class="fa-solid fa-cloud-upload"></i>
                    Daftar
                </button>
            @endif
            <a class="btn btn-light" href="{{ url(Request::segment(1)) }}"><i class="far fa-times-circle"></i>
                Kembali</a>
        </div>
    </div>
    @include('permohonan::layouts.modal-upload-formulir')
@endsection

@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Show Arsip Berkas')
@section('content')
    <div>
        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary active" id="icon-home-tab"
                    data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true"> <i
                        class="icofont icofont-man-in-glasses"></i>Data Pemohon</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-syarat-tab"
                    data-bs-toggle="tab" href="#data-syarat" role="tab" aria-controls="contact-icon"
                    aria-selected="false" tabindex="-1"><i class="fa-solid fa-file-image"></i>Data Syarat</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-bap-tab"
                    data-bs-toggle="tab" href="#data-bap" role="tab" aria-controls="contact-icon" aria-selected="false"
                    tabindex="-1"><i class="fa-solid fa-camera"></i>Preview BAP</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link txt-secondary" id="data-surat-tab"
                    data-bs-toggle="tab" href="#data-surat" role="tab" aria-controls="contact-icon"
                    aria-selected="false" tabindex="-1"><i class="fa-solid fa-envelope"></i>Preview Surat</a></li>
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
                        <td>Nama Pemohon</td>
                        <td>:</td>
                        <td>{{ strtoupper($data->nama_pemohon) }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Permohonan</td>
                        <td>:</td>
                        <td><b>Status Tanah</b></td>
                    </tr>
                    <tr>
                        <td>Alamat Persil</td>
                        <td>:</td>
                        <td>{{ strtoupper($data->alamat_persil) }}</td>
                    </tr>
                    <tr>
                        <td>Tgl Pengajuan</td>
                        <td>:</td>
                        <td>{{ dateindo($data->tanggal_pengajuan) }}</td>
                    </tr>
                </table>
            </div>
            <div class="tab-pane fade" id="data-syarat" role="tabpanel" aria-labelledby="data-syarat-tab">
                <br><br>
                <iframe
                    src="{{ route('show-document', [
                        'pdf' => $data->arsipDocument->document_persyaratan,
                    ]) }}"
                    width="100%" height="600px" frameborder="0"></iframe>
            </div>
            <div class="tab-pane fade" id="data-bap" role="tabpanel" aria-labelledby="data-bap-tab">
                <br><br>
                <iframe
                    src="{{ route('show-document', [
                        'pdf' => $data->arsipDocument->document_bap,
                    ]) }}"
                    width="100%" height="600px" frameborder="0"></iframe>
            </div>
            <div class="tab-pane fade" id="data-surat" role="tabpanel" aria-labelledby="data-bap-tab">
                <br><br>
                <iframe
                    src="{{ route('show-document', [
                        'pdf' => $data->arsipDocument->document_surat,
                    ]) }}"
                    width="100%" height="600px" frameborder="0"></iframe>
            </div>
        </div>
    </div>

@section('script')
    <script>
        function verifikasi(params, modal, status) {
            $('#id_status' + modal).val(status);
            $('#' + modal + '-modal').modal('show');
        }
    </script>
@endsection
@endsection

@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Buat Konsep Surat Fotocopy IPT')
@section('add-page')
@section('content')
    <form action="@if (Request::segment(3) == 'create-surat') {{ url('surat') }}@else{{ url('surat', $surat->id) }} @endif"
        method="POST">
        @if (Request::segment(3) == 'edit')
            {{ method_field('PUT') }}
        @endif
        @csrf
        <input type="hidden" name="id_permohonan" id="id_permohonan" value="{{ Request::segment(2) }}">
        <p style="text-align: justify">
            <span style="margin-left: 50px">Sehubungan</span> dengan surat saudara tanggal
            {{ isset($data->tanggal_pengajuan) ? dateindo($data->tanggal_pengajuan) : '-' }}
            perihal permohonan
            Fotocopy Surat Izin Pemakaian Tanah yang terletak di <b><input type="text" name="alamat_persil"
                    value="{{ $surat->alamat_persil ?? $arsip->alamat_persil }}" placeholder="Alamat Persil" style="width:700px"></b> maka
            dengan
            ini dapat disampaikan sebagai berikut :
        </p>
        <textarea id="editor1" name="isi" cols="30" rows="10">
            @php
                if (isset($surat->isi)) {
                    echo $surat->isi;
                } else {
                    echo '<p> 1. Sesuai dengan data di Badan Pengelolaan Keuangan dan Aset Daerah Kota
Surabaya bahwa persil '. $arsip->alamat_persil .' telah terbit Izin
Pemakaian Tanah dengan Nomor : '. $arsip->no_persil .' tanggal ' . dateindo($arsip->tanggal_ipt) . ' atas nama ' . $arsip->nama_pemegang_ijin . '.</p>';
                }
            @endphp
        </textarea>
        <p style="text-align: justify">
            2. Surat keterangan ini dipergunakan untuk keperluan pengurusan surat kehilangan
            kepolisian.
        </p>
        <p>
            3. Apabila dikemudian hari ada sengketa / tuntutan dari pihak lain maka sepenuhnya
            menjadi tanggung jawab pemohon.
        </p>
        <p>
            4. Apabila surat keterangan ini terdapat kekeliruan akan dilakukan perbaikan sesuai
            ketentuan yang berlaku.
        </p>
        <p>
            Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>
        <div class="card-footer text-end">
            <div class="col-sm-9 offset-sm-3">
                <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Simpan</button>
                <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i> Batal</a>
            </div>
        </div>
    </form>
@endsection

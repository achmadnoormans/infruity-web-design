@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Buat Konsep Surat Rekomendasi Iklan Mandiri')
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
            <span style="margin-left: 50px">Berdasarkan</span> surat keterangan tanda lapor kehilangan dari Polrestabes
            Surabaya
            No.{{ $data->nomor_kehilangan_dari_kepolisian ?? '____' }} tanggal
            {{ isset($data->tanggal_pengajuan) ? dateindo($data->tanggal_pengajuan) : '____' }} telah hilang sebuah
            buku Surat Izin Pemakaian Tanah (IPT) No. <b><input type="text" name="no_persil"
                    value="{{ $surat->no_persil ?? '' }}" placeholder="No Persil" style="width:500px"></b> tanggal
            <b><input type="date" name="tgl_ipt" value="{{ $surat->tgl_ipt ?? '' }}" placeholder="Tanggal IPT"></b>
            dengan
            letak
            persil di <b><input type="text" name="alamat_persil" value="{{ $surat->alamat_persil ?? '' }}"
                    placeholder="Alamat Persil" style="width:500px"></b> atas nama <b><input type="text"
                    name="nama_pemegang_ipt" value="{{ $surat->nama_pemegang_ipt ?? '' }}" placeholder="Nama Pemegang IPT"
                    style="width:500px"></b>.
            Saat ini
            Sdr. {{ $data->nama_pemohon ?? '_______' }}
            akan mengajukan permohonan Izin Pemakaian Tanah di lokasi dimaksud ke Badan Pengelolaan Keuangan dan Aset
            Daerah.
        </p>
        <textarea id="editor1" name="isi" cols="30" rows="10">
            @php
                if (isset($surat->isi)) {
                    echo $surat->isi;
                } else {
                    echo '<p>Ketik Surat Disini</p>';
                }
            @endphp
        </textarea>
        <p style="text-align: justify">
            <span style="margin-left: 50px">Terhadap</span> permohonan penerbitan surat Izin Pemakaian Tanah di persil
            dimaksud atas nama <b>Sdr.
                {{ ucwords(strtolower($data->nama_pemohon ?? '')) }}</b> maka apabila terdapat pihak-pihak yang
            keberatan
            terhadap pengajuan permohonan, agar mengajukan keberatan ke Badan Pengelolaan Keuangan dan Aset Daerah Kota
            Surabaya paling lambat 30 (tiga puluh) hari terhitung sejak tanggal pengumuman ini diterbitkan.
        </p>
        <div class="card-footer text-end">
            <div class="col-sm-9 offset-sm-3">
                <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Simpan</button>
                <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i> Batal</a>
            </div>
        </div>
    </form>
@endsection

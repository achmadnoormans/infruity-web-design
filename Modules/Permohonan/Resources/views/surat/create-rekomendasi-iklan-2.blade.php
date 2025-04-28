@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Buat Konsep Surat Balik Nama Mandiri')
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
            <span style="margin-left: 50px">Sehubungan</span> dengan surat permohonan balik nama Izin Pemakaian Tanah
            Nomor :
            <b><input type="text" name="no_persil" value="{{ $surat->no_persil ?? $arsip->no_persil }}" placeholder="No Persil"
                    style="width:500px"></b> tanggal
            <b><input type="date" name="tgl_ipt" value="{{ $surat->tgl_ipt ?? $arsip->tanggal_ipt }}" placeholder="Tanggal IPT"></b> yang
            terletak
            di <b><input type="text" name="alamat_persil" value="{{ $surat->alamat_persil ?? $arsip->alamat_persil }}"
                    placeholder="Alamat Persil" style="width:500px"></b> dari {{ $data->nama_pemohon ?? '' }}
            tanggal {{ isset($data->tanggal_pengajuan) ? dateindo($data->tanggal_pengajuan) : '_____' }}, maka Badan
            Pengelolaan Keuangan dan Aset Daerah Kota Surabaya akan menerbitkan Izin
            Pemakaian Tanah kepada Sdr. {{ ucwords(strtolower($data->nama_pemohon ?? '')) }} dengan letak persil tanah
            <b><input type="text" name="alamat_persil" value="{{ $surat->alamat_persil ?? $arsip->alamat_persil }}"
                    placeholder="Alamat Persil" style="width:500px"></b>
            mendasarkan pada :
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
            dimaksud atas nama <b><input type="text" name="nama" value="{{ $surat->list_nama ?? '' }}"
                    placeholder="Nama nama" style="width:500px"></b> maka apabila terdapat pihak-pihak yang keberatan
            terhadap
            pengajuan permohonan, agar mengajukan keberatan ke Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya
            paling lambat 30 (tiga puluh) hari terhitung sejak tanggal pengumuman ini diterbitkan.
        </p>
        <div class="card-footer text-end">
            <div class="col-sm-9 offset-sm-3">
                <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Simpan</button>
                <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i> Batal</a>
            </div>
        </div>
    </form>
@section('script')
    <script>
        var wrapper = $("#daftar-nama");
        var add_button = $("#add-nama");
        $(add_button).click(function(e) {
            e.preventDefault();
            $(wrapper).append(`
                        <div class="mb-3 row">
                <label class="col-sm-2">Nama</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="nama[]" id="nama" placeholder="">
                </div>
                <button type="button" id="delete-nama" class="btn btn-sm col-sm-1 btn-danger delete">x</button>
            </div>
    `);
            $(wrapper).on("click", ".delete", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            })
        });
    </script>
@endsection
@endsection

@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Buat Konsep Surat')
@section('add-page')
@section('content')
    <form
        action="@if (Request::segment(2) == 'create-surat-kolektif') {{ url(Request::segment(1) . '/' . 'save-surat-kolektif') }}@else{{ url(Request::segment(1) . '/update-surat-kolektif', $surat->id) }} @endif"
        method="POST">
        @if (Request::segment(3) == 'edit')
            {{ method_field('PUT') }}
        @endif
        @csrf
        @if (Request::segment(2) == 'create-surat-kolektif')
            <div class="mb-3">
                <h6>List Permohonan yang belum dibuatkan Surat : </h6>
            </div>
            <div class="mb-3">
                <table class="table">
                    <th>No</th>
                    <th>No Permohonan</th>
                    <th>Nama Pemohon</th>
                    <th>Jenis Permohonan</th>
                    @foreach ($data as $key => $item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->permohonan->no_permohonan }}</td>
                            <td>{{ $item->permohonan->nama_pemohon }}</td>
                            <td>{{ $item->permohonan->layanan->nm_layanan }}</td>
                            <td>
                                <input type="checkbox" name="id_permohonan[]" id="id_permohonan" value="{{ $item->id_permohonan }}" checked>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
        <textarea id="editor1" name="isi" cols="30" rows="10">
            @php
                if (isset($surat->isi)) {
                    echo $surat->isi;
                } else {
                    echo '<p>Ketik Surat Disini</p>';
                }
            @endphp
        </textarea>
        <div class="card-footer text-end">
            <div class="col-sm-9 offset-sm-3">
                <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Simpan</button>
                <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i> Batal</a>
            </div>
        </div>
    </form>
@endsection

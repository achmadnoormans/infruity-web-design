@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Buat Konsep Surat')
@section('add-page')
@section('content')
    <form action="@if (Request::segment(3) == 'create-surat') {{ url('surat') }}@else{{ url('surat', $surat->id) }} @endif"
        method="POST" enctype="multipart/form-data">
        @if (Request::segment(3) == 'edit')
            {{ method_field('PUT') }}
        @endif
        @csrf
        <input type="hidden" name="id_permohonan" id="id_permohonan" value="{{ Request::segment(2) }}">
        <div class="col-md-12 mb-3">
            <label class="form-label" for="tgl">Upload File Surat</label>
            <input class="form-control" id="file_surat" name="file_surat" type="file">
        </div>
        <div class="card-footer text-end">
            <div class="col-sm-9 offset-sm-3">
                <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Simpan</button>
                <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i> Batal</a>
            </div>
        </div>
    </form>
@endsection

@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Buat Arsip')
@section('content')
    <div class="col-md-12">
        <form action="{{ url(Request::segment(1)) }}" method="POST" class="form theme-form" enctype="multipart/form-data">
            @csrf
            <div class="card-body custom-input">
                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-sm-3">Nama Pemohon</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" name="nama_pemohon" id="nama_pemohon"
                                    placeholder="Ex : Nama Pemohon" value="{{ old('nama_pemohon') ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-sm-3">Alamat Persil</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" name="alamat_persil" id="alamat_persil"
                                    placeholder="Ex : Jimerto 25-27" value="{{ old('alamat_persil') ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-sm-3">Tanggal Pengajuan</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="date" name="tanggal_pengajuan" id="tanggal_pengajuan"
                                    placeholder="" value="{{ old('tanggal_pengajuan') ?? date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-sm-3">Document Persyaratan</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="file" name="document_persyaratan" id="document_persyaratan"
                                    placeholder="" value="{{ old('document_persyaratan') ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-sm-3">Document BAP</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="file" name="document_bap" id="document_bap"
                                    placeholder="" value="{{ old('document_bap') ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
                            <label class="col-sm-3">Document Surat</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="file" name="document_surat" id="document_surat"
                                    placeholder="" value="{{ old('document_surat') ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="col-sm-9 offset-sm-3">
                    <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Next</button>
                    <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i>
                        Batal</a>
                </div>
            </div>
        </form>
    </div>
@endsection

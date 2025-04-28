@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Edit Permohonan')
@section('content')
    <div class="col-md-12">
        @if (isset($form) && count($form) > 0)
            <form action="{{ url(Request::segment(1), $data->id) }}" method="POST" class="form theme-form"
                enctype="multipart/form-data">
                {{ method_field('PUT') }}
                @csrf
                <div class="card-body custom-input">
                    <input type="hidden" name="tipe" value="{{ $data->id_layanan ?? '' }}">
                    <div class="row">
                        <div class="col">
                            @foreach ($form as $item)
                                <div class="mb-3 row">
                                    <label class="col-sm-3">{{ ucwords(strtolower($item->nama_form)) }}</label>
                                    <div class="col-sm-9">
                                        @php
                                            $index = change_form($item->nama_form);
                                        @endphp
                                        <input class="form-control" type="{{ $item->type }}"
                                            name="{{ change_form($item->nama_form) }}"
                                            id="{{ change_form($item->nama_form) }}"
                                            placeholder="{{ ucwords(strtolower($item->nama_form)) }}"
                                            value="{{ $data->$index ?? old($index) }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- <hr>
                    <div class="row">
                        <div class="col">
                            @foreach ($document as $item)
                                <div class="mb-3 row">
                                    <label class="col-sm-3">{{ ucwords(strtolower($item->nama_document)) }}</label>
                                    <div class="col-sm-6">
                                        @php
                                            $index = change_form($item->nama_document);
                                        @endphp
                                        <input class="form-control" type="file"
                                            name="{{ change_form($item->nama_document) }}"
                                            id="{{ change_form($item->nama_document) }}"
                                            placeholder="{{ ucwords(strtolower($item->nama_document)) }}"
                                            value="{{ $dataDocument[$index] ?? '' }}">
                                    </div>
                                    <div class="col-sm-3">
                                        lihat gambar
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div> --}}
                </div>
                <div class="card-footer text-end">
                    <div class="col-sm-9 offset-sm-3">
                        <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Update</button>
                        <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i>
                            Batal</a>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection

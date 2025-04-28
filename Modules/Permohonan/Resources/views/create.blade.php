@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Buat Permohonan')
@section('content')
    <div class="col-md-12">
        <form method="GET" action="{{ url(Request::segment(1) . '/' . Request::segment(2)) }}" class="form theme-form">
            @csrf
            <div class="row mb-5 pl-2">
                <div class="col-md-6">
                    <label class="form-label" for="tipe layanan">Tipe Layanan</label>
                    <select name="tipe" class="form-control" id="id_tipe">
                        <option value="">-- Pilih Layanan --</option>
                        @foreach ($layanan as $item)
                            <option value="{{ $item->id_layanan }}"
                                {{ isset($tipe) && $tipe == $item->id_layanan ? 'selected' : '' }}>
                                {{ strtoupper($item->nm_layanan) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mt-4">
                    <button class="btn btn-primary"><i class="fa-solid fa-search"></i> Process</button>
                </div>
            </div>
        </form>
        @if (isset($form) && count($form) > 0)
            <div class="col-md-12 text-center">
                <h5>
                    <b>Form Permohonan </b><br>
                    {{ $selectedLayanan->nm_layanan }}
                </h5>
            </div>
            <form action="{{ url(Request::segment(1)) }}" method="POST" class="form theme-form"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body custom-input">
                    <input type="hidden" name="tipe" value="{{ $tipe ?? '' }}">
                    <div class="row">
                        <div class="col">
                            @foreach ($form as $item)
                                <div class="mb-3 row">
                                    <label class="col-sm-3">{{ ucwords(strtolower($item->nama_form)) }}</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="{{ $item->type }}"
                                            name="{{ change_form($item->nama_form) }}"
                                            id="{{ change_form($item->nama_form) }}"
                                            placeholder="{{ ucwords(strtolower($item->nama_form)) }}"
                                            value="{{ old(change_form($item->nama_form)) ?? '' }}"
                                            {{ $item->status ?? 'disabled' }}>
                                    </div>
                                </div>
                            @endforeach
                            @if (in_array($tipe, [2, 3]))
                                <div class="mb-3 row">
                                    <label class="col-sm-3">Jenis Iklan</label>
                                    <div class="col-sm-9">
                                        <select name="jenis_iklan" id="jenis_iklan" class="form-control">
                                            @foreach ($jenisIklan as $key => $item)
                                                <option value="{{ $key }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- <hr>
                    <div class="row">
                        <div class="col">
                            @foreach ($document as $item)
                                <div class="mb-3 row">
                                    <label class="col-sm-3">{{ ucwords(strtolower($item->nama_document)) }}
                                        <span
                                            style="color: red">{{ isset($item->keterangan) ? '( ' . $item->keterangan . ' )' : '' }}</span></label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="file"
                                            name="{{ change_form($item->nama_document) }}"
                                            id="{{ change_form($item->nama_document) }}"
                                            placeholder="{{ ucwords(strtolower($item->nama_document)) }}" {{ $item->status ?? 'disabled' }}>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div> --}}
                </div>
                <div class="card-footer text-end">
                    <div class="col-sm-9 offset-sm-3">
                        <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Next</button>
                        <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i>
                            Batal</a>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection

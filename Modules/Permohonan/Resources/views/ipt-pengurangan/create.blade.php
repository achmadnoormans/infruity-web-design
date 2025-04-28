@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Buat Permohonan Pengurangan IPT')
@section('content')
    <div class="col-md-12">
        @if (isset($form) && count($form) > 0)
            <form action="{{ url(Request::segment(1)) }}" method="POST" class="form theme-form" enctype="multipart/form-data">
                @csrf
                <div class="card-body custom-input">
                    <input type="hidden" name="tipe" value="{{ $tipe ?? '' }}">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3 row">
                                <label class="col-sm-3">Jenis Pengurangan</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="">-- Pilih Jenis Pengurangan --</option>
                                        @foreach ($type as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
        @endif
    </div>
@endsection

@section('script')
    <script>
        $('#type').select2();
    </script>
@endsection
@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'List Layanan')
@section('add-page')
    <a href="{{ url('layanan/create') }}" class="btn btn-md btn-primary">
        <i class="fa fa-plus"></i> Tambah
    </a>
@endsection
@section('content')
    <div class="table-responsive signal-table custom-scrollbar">
        <table class="table table-hover" id="table-data">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Layanan</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>
                            {{ $item->nm_layanan ?? '' }}
                        </td>
                        <td>
                            <a class="btn btn-primary" href="{{ url('layanan-dokumen/' . $item->id_layanan . '/detail') }}"><i
                                    class="fa-solid fa-eye"></i>
                                Detail </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

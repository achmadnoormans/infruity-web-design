@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'List Status Dokumen')
@section('add-page')
    <a href="{{ url('status-dokumen/create') }}" class="btn btn-md btn-primary">
        <i class="fa fa-plus"></i> Tambah
    </a>
@endsection
@section('content')
    <div class="table-responsive signal-table custom-scrollbar">
        <table class="table table-hover" id="table-data">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>
                            {{ $item->nama_status ?? '' }}
                        </td>
                        <td>
                            <div class="common-flex light-dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">Action</button>
                                <ul class="dropdown-menu dropdown-menu-dark dropdown-block">
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ url('status-dokumen/' . $item->id_status . '/edit') }}"><i
                                                class="fa-solid fa-edit"></i>
                                            Edit </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item btn-delete"
                                            data-url="{{ url(Request::segment(1)) . '/' . $item->id_status }}"
                                            data-kode="{{ $item->nama_status }}">
                                            <i class="fas fa-trash-alt"></i>
                                            Hapus</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

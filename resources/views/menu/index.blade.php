@extends('template.root')
@section('page-name', Request::segment(1))
@section('content')
    <form method="GET" action="{{ url(Request::segment(1) . '/filter') }}" enctype="multipart/form-data" id="form-select">
        {{-- @include('template.filter-collapse') --}}
        @csrf
        <div class="row mt-1">
            <div class="col-md-12">
                <table class="table table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Menu</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data1 as $key => $menu1)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ strtoupper($menu1->nm_menu) }}</td>
                                <td>
                                    <a href="{{ url('menus') . '/' . $menu1->id_menu . '/edit' }}"
                                        class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip"
                                        data-placement="bottom" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="{{ url('menus') . '/' . $menu1->id_menu . '/edit' }}"
                                        class="btn btn-sm btn-warning" style="color: #fff" data-toggle="tooltip"
                                        data-placement="bottom" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
@endsection

@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'History Permohonan')
@section('content')
    {{-- <div class="animated-timeline">
        <div class="timeline-block">
            <div class="each-year">
                <div class="title">History Proses</div>
                @foreach ($history as $item)
                    <div class="timeline-event">
                        <div class="timeline-desc">
                            <h6 class="pb-1">{{ ucwords(strtolower($item->nm_status)) }}:</h6>
                            <span>({{ dateindo($item->tgl_status) }})</span><br>
                            <span>{{ ucwords(strtolower($item->keterangan ?? 'Tanpa Keterangan')) }}.</span><br><br>
                            <span>Oleh : {{ ucwords(strtolower($item->nama_verifikator)) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div> --}}
    <div class="table-responsive signal-table custom-scrollbar">
        <table class="table table-hover" id="table-data">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Status</th>
                    <th scope="col">Tanggal Status</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">User</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ ucwords(strtolower($item->nm_status)) }}</td>
                        <td>{{ isset($item->tgl_status) ? dateindo($item->tgl_status) : '-' }}</td>
                        <td>{{ ucwords(strtolower($item->keterangan ?? 'Tanpa Keterangan')) }}</td>
                        @if (Session('role')['id_role'] != 99)
                            <td>{{ ucwords(strtolower($item->user->roleUser->role->nm_role)) }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

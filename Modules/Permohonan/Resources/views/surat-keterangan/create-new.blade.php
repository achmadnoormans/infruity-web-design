@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'Buat Konsep Surat Keputusan')
@section('add-page')
@section('content')
    <form method="GET" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}"
        class="form theme-form">
        @csrf
        <div class="row mb-5 pl-2">
            <div class="col-md-3">
                @php
                    $nominal = [30, 50, 75];
                @endphp
                <label class="form-label" for="nominal_sk">Nominal Pengurangan</label>
                <select name="nominal_pengurangan" class="form-control" id="id_tipe">
                    <option value="">-- Pilih Nominal --</option>
                    @php
                        if (!isset($nominal_pengurangan)) {
                            $nominal_pengurangan = isset($surat->nominal_pengurangan) ?? null;
                        }
                    @endphp
                    @foreach ($nominal as $item)
                        <option value="{{ $item }}"
                            {{ isset($nominal_pengurangan) && $nominal_pengurangan == $item ? 'selected' : '' }}>
                            {{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-9 mt-4">
                <button class="btn btn-primary"><i class="fa-solid fa-search"></i> Generate SK</button>
            </div>
        </div>
    </form>
    <form
        action="@if (Request::segment(3) == 'create-surat') {{ url('surat-keterangan') }}@else{{ url('surat-keterangan', $surat->id) }} @endif"
        method="POST">
        @if (Request::segment(3) == 'edit')
            {{ method_field('PUT') }}
        @endif
        @csrf
        @php
            $pengurangan = $nominal_pengurangan / 100;
        @endphp
        <input type="hidden" name="id_permohonan" id="id_permohonan" value="{{ Request::segment(2) }}">
        <input type="hidden" name="nominal_pengurangan" id="nominal_pengurangan" value="{{ $nominal_pengurangan }}">
        @php
            if (!isset($surat)) {
                $surat = new \stdClass();
                $surat->nominal_pengurangan = $nominal_pengurangan;
            }
        @endphp
        <table width="100%">
            @switch($data->type)

                @case('PENSIUNAN PNS/TNI/POLRI')
                @case('VETERAN')
                @case('SUAMI/ISTRI/JANDA/DUDA VETERAN')
                @case('SUAMI/ISTRI/JANDA/DUDA PENSIUNAN')
                    @include('permohonan::surat-keterangan.create.pensiunan')
                @break

                @case('20 TAHUN')
                    @include('permohonan::surat-keterangan.create.20tahun')
                @break

                @case('RUMAH TINGGAL < 200')
                    @include('permohonan::surat-keterangan.create.rumah200')
                @break

                @case('PENDIDIKAN')
                @case('KESEHATAN')
                @case('SOSIAL')
                @case('USAHA')
                    @include('permohonan::surat-keterangan.create.pendidikan')
                @break

                @case('MBR')
                    @include('permohonan::surat-keterangan.create.mbr')
                @break

                @default
                    @include('permohonan::surat-keterangan.create.pendidikan')
            @endswitch
            @if (!in_array($data->type, ['PENDIDIKAN', 'KESEHATAN', 'SOSIAL', 'USAHA']))
                @include('permohonan::surat-keterangan.type.pasal-selanjutnya')
            @else
                @include('permohonan::surat-keterangan.type.pasal-selanjutnya-khusus')
            @endif
        </table>
        @include('permohonan::surat-keterangan.type.table-skrd')

        <table width="100%">
            <tr>
                <td width="60%"></td>
                <td class="text-right">
                    <div class="text-center" style="line-height: 4px">
                        <p style="text-align: left">Ditetapkan di Surabaya
                        <p style="text-align: left">pada tanggal,
                            {{ isset($surat->tgl_surat) ? dateindo($surat->tgl_surat) : '____________' }}
                        </p>
                        <br>
                        <p><b>a.n WALIKOTA SURABAYA</b></p>
                        <p><b>Kepala Badan</b></p>
                        @if (isset($surat->tgl_surat))
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/cuba/images/logo/stempel.png'))) }}"
                                alt="Watermark" style="height: 100px;" class="overlay-image">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/cuba/images/logo/ttd.png'))) }}"
                                alt="Watermark" style="width: 100px;">
                        @else
                            <p style="height: 70px"></p>
                        @endif
                        <p><u><b>Dra. WIWIEK WIDAYATI</b></u></p>
                        <p><b>Pembina Utama Muda</b></p>
                        <p>NIP. 19670516 199302 2 001</p>
                    </div>
                </td>
            </tr>
        </table>
        <div class="card-footer text-end">
            <div class="col-sm-9 offset-sm-3">
                <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Simpan</button>
                <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i> Batal</a>
            </div>
        </div>
    </form>
@endsection

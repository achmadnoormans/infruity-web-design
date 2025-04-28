<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ URL::asset('cuba/images/logo/logo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ URL::asset('cuba/images/logo/logo.png') }}" type="image/x-icon">
    <title>Cetak BAP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vendors/font-awesome.css') }}">
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin-left: 3cm;
                /* size: landscape; */
            }
        }

        body {
            font-family: sans-serif !important;
            /* line-height: 15px; */
            font-size: 13px;
            /* font-weight: bold; */
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .tr-bold {
            font-weight: bold !important;
        }

        .container {
            margin-left: 50px;
            margin-right: 50px;
        }

        .table1 {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 12px;
        }

        .table1 th {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .table1 td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 3px;
        }

        .text-success {
            text-align: center;
            color: green;
            font-weight: bold;
        }

        .text-danger {
            text-align: center;
            color: red;
            font-weight: bold;
        }

        .heading {
            text-align: center;
            font-size: 14px;
            border: 0px
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <td>
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/cuba/images/logo/logo-surabaya.png'))) }}"
                    style="height: 90px">
            </td>
            <td class="text-center">
                <span style="font-size: 18px"><b>PEMERINTAH KOTA SURABAYA</b></span><br>
                <span><b>UPTSA Timur</b> : Jl. Menur No. 30 C, Surabaya</span><br>
                <span><b>UPTSA Pusat</b> : Jl. Tunjungan No.1-3 Genteng, Surabaya</span><br>
                <span>Telp. (031) 5982284 (UPTSA Timur), 031-99001779 (UPTSA Pusat)</span>
            </td>
        </tr>
    </table>
    <hr>
    <div>
        <p class="text-center" style="font-size:15px;"><b>BERITA ACARA PENINJAUAN LOKASI </b></p>
    </div>
    <div class="container">
        <div>
            <p style="text-align: justify">Pada hari ini Selasa tanggal Empat bulan Satu tahun Dua Ribu Dua Puluh Tiga,
                telah diadakan peninjauan
                lokasi sehubungan dengan adanya permohonan <b>{{ $data->layanan->nm_layanan }}</b> Izin Pemakaian Tanah
                yang
                diajukan oleh</p>
        </div>
        <table width="100%">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><b>{{ strtoupper($data->nama_pemohon ?? '') }}</b></td>
            </tr>
            <tr>
                <td>Persil</td>
                <td>:</td>
                <td>{{ $data->alamat_pemohon ?? '' }}</td>
            </tr>
            <tr>
                <td>Nomor IPT</td>
                <td>:</td>
                <td>{{ $bap->no_ipt ?? '' }}</td>
            </tr>
            <tr>
                <td>Tanggal IPT</td>
                <td>:</td>
                <td>{{ isset($bap->tanggal_ipt) ? dateindo($bap->tanggal_ipt) : '' }}</td>
            </tr>
            <tr>
                <td>Luas</td>
                <td>:</td>
                <td>{{ $bap->luas ?? '' }}</td>
            </tr>
            <tr>
                <td>Peruntukan</td>
                <td>:</td>
                <td>{{ $bap->peruntukan ?? '' }}</td>
            </tr>
            <tr>
                <td>Peruntukan</td>
                <td>:</td>
                <td>{{ $bap->penggunaan ?? '' }}</td>
            </tr>
            <tr>
                <td><b>Foto Lokasi</b></td>
                <td>:</td>
                <td></td>
            </tr>
        </table>
        <br>
        <div class="text-center">
            @php
                if (Storage::exists($bap->file ?? 'fc')) {
                    $path = $bap->file;
                    $full_path = Storage::path($path);
                    $base64 = base64_encode(Storage::get($path));
                    $image = 'data:' . mime_content_type($full_path) . ';base64,' . $base64;
                    $data->foto = $image;
                }
            @endphp
            <img src="{{ $data->foto }}" style="height: 200px">
        </div>
        <div>
            <p><b>Catatan : </b>Berdasarkan peninjauan lokasi persil tersebut dipergunakan untuk
                {{ $bap->penggunaan ?? '' }}
            </p>
        </div>
        <table width="100%" style="margin-top: 50px">
            <tr>
                <td class="text-center" width="50%">Petugas Survey</td>
                <td class="text-center" width="50%">Penunjuk Lokasi</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: bottom" height="70px">
                    {{ $history->nama_verifikator ?? '____________' }}
                </td>
                <td style="text-align: center; vertical-align: bottom">____________</td>
            </tr>
        </table>
        <br>
        <div class="text-center" style="line-height: 4px">
            <p>Mengetahui,</p>
            <p>Ketua Tim Kerja Pemanfaatan dan</p>
            <p>Pemindahtanganan BMD</p>
            <p style="height: 70px"></p>
            <p><u><b>MIMIN MISDYAWATI, S.T.</b></u></p>
            <p><b>Penata Tk I</b></p>
            <p>NIP. 19710415 200604 2 019</p>
        </div>
    </div>
</body>

</html>

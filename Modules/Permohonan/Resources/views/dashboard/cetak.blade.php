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
    <title>Cetak Data</title>
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
            padding: 5px;
        }

        .table1 td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
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
                <span style="font-size: 16px"><b>PEMERINTAH KOTA SURABAYA</b></span><br>
                <span style="font-size: 18px"><b>BADAN PENGELOLAAN KEUANGAN
                        DAN ASET DAERAH
                    </b></span><br>
                <span><b>Jl. Jimerto No. 25-27 Lantai 2-3 Surabaya 60272</b></span><br>
                <span><b>Telp. (031) 5312144 Psw. 140, 213 FAX (031) 5353782</b></span><br>
                <span>Laman surabaya.go.id, Pos-el: bpkad@surabaya.go.id</span>
            </td>
        </tr>
    </table>
    <hr>
    <div>
        <p class="text-center" style="font-size:15px;"><b>{{ $statusBerkas }}</b></p>
    </div>
    <div class="container">

        <table width="100%" class="table1">
            <thead>
                <th>No</th>
                <th>No Daftar</th>
                <th>Nama Pemohon</th>
                <th>Jenis Permohonan</th>
                <th>Status</th>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->no_permohonan }} <br> {{ dateindo($item->tanggal_pengajuan) }} </td>
                        <td>{{ $item->nama_pemohon }} <br> {{ $item->alamat_persil }} </td>
                        <td>{{ ucwords(strtolower($item->nm_layanan)) }}</td>
                        <td class="{{ $item->id_status == 11 ? 'text-success' : 'text-danger' }}">{{ $item->id_status == 11 ? 'Berkas Selesai' : 'Belum Selesai' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>

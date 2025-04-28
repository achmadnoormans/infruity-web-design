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
    <title>Cetak Keterangan Arsip</title>
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
    <div>
        <p class="text-center" style="font-size:15px;"><b>KETERANGAN PERSIL</b></p>
    </div>
    <div class="container">
        <div>
            <p style="text-align: justify">Sehubungan dengan permohonan Saudara : </p>
        </div>
        <table width="100%">
            <tr>
                <td width="200px">Nama Pemohon</td>
                <td>:</td>
                <td><b>{{ strtoupper($data->permohonan->nama_pemohon ?? '') }}</b></td>
            </tr>
            <tr>
                <td>Alamat Persil</td>
                <td>:</td>
                <td>{{ $data->alamat_persil ?? '' }}</td>
            </tr>
            <tr>
                <td>Nama Pemegang Persil</td>
                <td>:</td>
                <td>{{ $data->nama_pemegang_ijin ?? '' }}</td>
            </tr>
            <tr>
                <td>No. Ijin Pemakaian Tanah</td>
                <td>:</td>
                <td>{{ $data->no_persil ?? '' }}</td>
            </tr>
            <tr>
                <td>Tanggal Ijin Pemakaian Tanah</td>
                <td>:</td>
                <td>{{ isset($data->tanggal_ipt) ? dateindo($data->tanggal_ipt) : '' }}</td>
            </tr>
            <tr>
                <td>Keperluan</td>
                <td>:</td>
                <td>Permohonan {{ $data->permohonan->layanan->nm_layanan ?? '-' }}</td>
            </tr>
            <tr>
                <td style="vertical-align: top">Keterangan</td>
                <td style="vertical-align: top">:</td>
                <td>{!! nl2br(e($data->keterangan)) !!}</td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 50px">
            <tr>
                <td class="text-center" width="50%">
                    <div class="text-center" style="line-height: 4px">
                        {{-- <p>Mengetahui,</p> --}}
                        {{-- <p>Ketua Tim Kerja Umum dan Kepegawaian</p> --}}
                        <p style="height: 70px"></p>
                        {{-- <p><u><b>NURIDA YUNIASTATIN, SH MM</b></u></p> --}}
                        {{-- <p>NIP. 196906202001122003</p> --}}
                    </div>
                </td>
                <td class="text-center" width="50%">
                    <div class="text-center" style="line-height: 4px">
                        <p></p>
                        <p>Petugas Arsip</p>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/cuba/images/logo/ttd-bu-ratih.png'))) }}"
                            alt="Watermark" style="width: 100px;">
                        <p><u><b>FAJAR RATIH KUSUWASTUTI</b></u></p>
                        <p>NIP. 197302182006042008</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>

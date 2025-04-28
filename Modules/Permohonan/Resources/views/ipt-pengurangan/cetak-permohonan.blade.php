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
    <title>Cetak Permohonan Pengurangan IPT</title>
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
        <p class="text-center" style="font-size:15px;"><b>TANDA TERIMA BERKAS PERMOHONAN </b></p>
    </div>
    <div class="container">
        <table width="100%">
            <tr>
                <td>No Permohonan</td>
                <td>:</td>
                <td><b>{{ strtoupper($data->no_permohonan) }}</b></td>
            </tr>
            <tr>
                <td>Tgl Pengajuan</td>
                <td>:</td>
                <td>{{ dateindo($data->tanggal_pengajuan) }}</td>
            </tr>
            @foreach ($form as $item)
                <tr>
                    <td>{{ ucwords(strtolower($item->nama_form)) }}</td>
                    <td>:</td>
                    @php
                        $index = change_form($item->nama_form);
                    @endphp
                    <td>{{ strtoupper($data->$index ?? '') }}</td>
                </tr>
            @endforeach
            <tr>
                <td>Jenis Permohonan</td>
                <td>:</td>
                <td><b>PENGURANGAN TAGIHAN IPT {{ $data->type }}</b></td>
            </tr>
        </table>
        <div>
            <p>Data Permohonan Saudara sudah terdaftar dengan Nomor <b>{{ strtoupper($data->no_permohonan) }}</b>
                (harap untuk dicatat dan menyimpan
                bukti pendaftaran ini)</p>
            <p>
                Tanda terima ini hanya sebagai bukti bahwa Saudara telah melakukan pendaftaran online dan bukan
                sebagai bukti bahwa berkas Saudara sudah lengkap dan benar.
            </p>
            <p>
                Saudara akan menerima informasi lebih lanjut melalui email setelah berkas permohonan di-verifikasi oleh
                petugas Unit Pelayanan Terpadu Satu Atap (UPTSA) sesuai tenggat waktu yang telah ditetapkan oleh
                regulasi yang berlaku (tidak termasuk pending / penundaan proses perizinan).
            </p>
        </div>
        <div>
            <p class="text-left" style="font-size:14px;"><b>SYARAT YANG DILAMPIRKAN : </b></p>
        </div>
        <table width="100%" class="table1">
            <thead>
                <th>NO</th>
                <th>PERSYARATAN</th>
                <th>ADA / TIDAK ADA</th>
            </thead>
            <tbody>
                @foreach ($document as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ ucwords(strtolower($item->nama_document)) }}</td>
                        @php
                            $index = change_form($item->nama_document);
                        @endphp
                        <td class="text-center">
                            @if (isset($dataDocument[$index]))
                                <span class="text-success">ADA</span>
                            @else
                                <span class="text-danger">TIDAK ADA</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>

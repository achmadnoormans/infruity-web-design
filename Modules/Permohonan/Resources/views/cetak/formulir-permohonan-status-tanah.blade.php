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
    <title>Cetak Formulir</title>
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
            text-align: justify;
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
    <div class="container">
        <table width="100%">
            <tr>
                <td width="50%" style="vertical-align: top">
                    <table>
                        <tr>
                            <td style="vertical-align: top">Perihal</td>
                            <td style="vertical-align: top">:</td>
                            <td>Permohonan Status Tanah</td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table>
                        <tr>
                            <td style="text-align: right">Surabaya, {{ dateindo(date('Y-m-d')) }}</td>
                        </tr>
                        <tr>
                            <td>Kepada Yth :</td>
                        </tr>
                        <tr>
                            <td>Kepala Badan Pengelolaan Keuangan dan Aset Daerah Pemerintah Kota Surabaya</td>
                        </tr>
                        <tr>
                            <td>di Surabaya</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <p>Dengan Hormat, </p>
        <p>Saya yang bertanda tangan di bawah ini :</p>
        <table width="100%">
            <tr>
                <td width="30%">Nama</td>
                <td width="5%">:</td>
                <td width="65%"><b>{{ strtoupper($data->nama_pemohon) }}</b></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><b>{{ strtoupper($data->alamat_pemohon) }}</b></td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td><b>{{ strtoupper($data->pekerjaan_pemohon) }}</b></td>
            </tr>
            <tr>
                <td>No Telpn</td>
                <td>:</td>
                <td><b>{{ strtoupper($data->telepon_pemohon) }}</b></td>
            </tr>
        </table>
        <div>
            <p>Bersama ini kami mengajukan Permohonan Status Tanah sebagai berikut :</p>
        </div>
        <table width="100%">
            <tr>
                <td width="30%">Alamat Persil</td>
                <td width="5%">:</td>
                <td width="65%"><b>{{ strtoupper($data->alamat_persil) }}</b></td>
            </tr>
            <tr>
                <td width="30%">Kelurahan</td>
                <td width="5%">:</td>
                <td width="65%"><b>{{ strtoupper($data->kelurahan) }}</b></td>
            </tr>
            <tr>
                <td width="30%">Kecamatan</td>
                <td width="5%">:</td>
                <td width="65%"><b>{{ strtoupper($data->kecamatan) }}</b></td>
            </tr>
            <tr>
                <td width="30%">Kota</td>
                <td width="5%">:</td>
                <td width="65%"><b>{{ strtoupper($data->kota) }}</b></td>
            </tr>
        </table>
        <div>
            <p>
                Surat permohonan ini kami ajukan hanya untuk permohonan status tanah dan tidak akan kami pergunakan
                selain sebagaimana tujuan permohonan ini dibuat.</p>
            <p>
                Sebagai bahan pertimbangan saya akan melampirkan fotocopy data-data. Besar harapan kami agar permohonan
                ini dikabulkan, dan atas kebijaksanaanya kami ucapkan terima kasih sebesar-besarnya
            </p>
        </div>
        <table width="100%" style="margin-top: 70px">
            <tr>
                <td class="text-center" width="50%"></td>
                <td class="text-center" width="50%">Pemohon</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: bottom" height="70px"></td>
                <td style="text-align: center; vertical-align: bottom">{{ $data->nama_pemohon }}</td>
            </tr>
        </table>
        <br><br><br>
        <p>Sebagai kelengkapan permohonan ini dilampirkan :</p>
        <div style="line-height: 5px">
            @foreach ($document as $key => $item)
                @if (isset($item->status))
                    <p>{{ $key + 1 }}. {{ ucwords(strtolower($item->nama_document)) }}</p>
                @endif
            @endforeach
        </div>
    </div>
</body>

</html>

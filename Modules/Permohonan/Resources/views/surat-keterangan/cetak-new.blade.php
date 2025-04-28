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
    <title>Cetak Konsep Surat</title>
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
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            font-size: 12pt;
        }

        table td {
            text-align: justify;
            vertical-align: top;
            padding-top: 8px;
        }

        .table1 {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 14px;
        }

        .table1 th {
            border: 1px solid black;
            border-collapse: collapse;
            padding-left: 5px;
            padding-right: 5px;
        }

        .table1 td {
            border: 1px solid black;
            border-collapse: collapse;
            padding-left: 5px;
            padding-right: 5px;
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

        .overlay-image {
            position: absolute;
            transform: translate(-50%, -50%);
            z-index: 10;
            width: 100px;
            height: 100px;
            margin-left: 70px;
            opacity: 0.8;
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
    <div class="container">
        <div style="margin-top : 15px">
            <p class="text-center" style="font-size:12pt;"><b>KEPUTUSAN KEPALA BADAN PENGELOLAAN KEUANGAN DAN ASET
                    DAERAH</b></p>
            <p class="text-center" style="font-size:12pt; margin-top: -15px;"><b>KOTA SURABAYA</b></p>
            <p class="text-center" style="font-size:12pt; margin-top: -15px;"><b>Nomor : 100.3.3.3/
                    {{ isset($surat->nomer_surat) ? $surat->nomer_surat : '_____' }}/ 436.8.2/
                    {{ date('Y') }}</b></p>
            <p class="text-center" style="font-size:12pt;"><b>TENTANG : </b></p>
            <p class="text-center" style="font-size:12pt; margin-top: -15px;"><b>PEMBERIAN PENGURANGAN RETRIBUSI IZIN
                    PEMAKAIAN TANAH</b>
            </p>
            <p class="text-center" style="font-size:12pt; margin-top: -15px;"><b>DI {{ $skrd->persil }}</b></p>
            <p class="text-center" style="font-size:12pt;"><b>DENGAN RAHMAT TUHAN YANG MAHA ESA </b></p>
            <p class="text-center" style="font-size:12pt; margin-top: -15px"><b>KEPALA BADAN PENGELOLAAN KEUANGAN DAN
                    ASET DAERAH KOTA
                    SURABAYA </b></p>
        </div>
        <table width="100%">
            @switch($data->type)
                @case('PENSIUNAN PNS/TNI/POLRI')
                @case('VETERAN')

                @case('SUAMI/ISTRI/JANDA/DUDA VETERAN')
                @case('SUAMI/ISTRI/JANDA/DUDA PENSIUNAN')
                    @include('permohonan::surat-keterangan.type.pensiunan')
                @break

                @case('20 TAHUN')
                    @include('permohonan::surat-keterangan.type.20tahun')
                @break

                @case('RUMAH TINGGAL < 200')
                    @include('permohonan::surat-keterangan.type.rumah200')
                @break

                @case('KESEHATAN')
                @case('PENDIDIKAN')

                @case('SOSIAL')
                @case('USAHA')
                    @include('permohonan::surat-keterangan.type.pendidikan')
                @break

                @case('MBR')
                    @include('permohonan::surat-keterangan.type.mbr')
                @break

                @default
                    @include('permohonan::surat-keterangan.type.pendidikan')
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
        {{-- <br><br><br>
        <table width="50%" class="table1">
            <tr>
                <td colspan="2" style="text-align: center">Paraf Hirarki</td>
            </tr>
            <tr>
                <td width="80%">Sekretaris Badan Pengelolaan Keuangan dan Aset Daerah</td>
                <td width="20%"></td>
            </tr>
            <tr>
                <td>Kepala Bidang Penatausahaan, Pemanfaatan dan Pemindahtanganan Barang Milik Daerah pada BPKAD</td>
                <td></td>
            </tr>
            <tr>
                <td>Ketua Tim Kerja Pemanfaatan dan Pemindahtanganan Barang Milik Daerah</td>
                <td></td>
            </tr>
        </table> --}}
    </div>
</body>

</html>

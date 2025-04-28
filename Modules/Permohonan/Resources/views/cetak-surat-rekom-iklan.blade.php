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
            font-family: 'Times New Roman', Times, serif
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
        <div style="line-height: 5px; margin-top : 15px">
            <p class="text-center" style="font-size:20px;"><b><u>PENGUMUMAN</u> </b></p>
            <p class="text-center">Nomor : 500.16.7.2 / {{ $surat->nomer_surat ?? '_____' }}/ 436.8.2 /
                {{ date('Y') }}</p>
        </div>
        <div style="text-align: justify">
            <p style="text-align: justify">
                <span style="margin-left: 50px">Berdasarkan</span> surat keterangan tanda lapor kehilangan dari
                Polrestabes
                Surabaya
                No.{{ $data->nomor_kehilangan_dari_kepolisian ?? '--' }} tanggal
                {{ isset($data->tanggal_pengajuan) ? dateindo($data->tanggal_pengajuan) : '______' }} telah hilang sebuah
                buku Surat Izin Pemakaian Tanah (IPT) No. {{ $surat->no_persil ?? '______' }} tanggal
                {{ isset($surat->tgl_ipt) ? dateindo($surat->tgl_ipt) : '______' }} dengan
                letak
                persil di {{ isset($surat->alamat_persil) && $surat->alamat_persil != "" ? $surat->alamat_persil : '_________' }} atas nama
                {{ isset($surat->nama_pemegang_ipt) && $data->nama_pemegang_ipt != "" ? $data->nama_pemegang_ipt : '_________' }}.
                Saat ini
                Sdr. {{ $data->nama_pemohon ?? '--' }}
                akan mengajukan permohonan Izin Pemakaian Tanah di lokasi dimaksud ke Badan Pengelolaan Keuangan dan
                Aset
                Daerah.
            </p>
        </div>
        <table width="100%">
            <tr>
                <td width="60%"></td>
                <td class="text-right">
                    <div class="text-center" style="line-height: 4px">
                        <p>Surabaya, {{ isset($surat->tgl_surat) ? dateindo($surat->tgl_surat) : '____________' }}
                        </p>
                        <p>KEPALA BADAN</p>
                        <p style="height: 70px"></p>
                        <p><u><b>Dra. WIWIEK WIDAYATI</b></u></p>
                        <p><b>Pembina Utama Muda</b></p>
                        <p>NIP. 196705161993022001</p>
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

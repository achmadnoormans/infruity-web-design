<style>
    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 16px;
        margin: 0;
    }

    .header h2 {
        font-size: 14px;
        margin: 0;
    }

    .header h3 {
        font-size: 12px;
        margin: 0;
    }

    .content {
        width: 100%;
        border-collapse: collapse;
    }

    .content th,
    .content td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
    }

    .content th {
        background-color: #f2f2f2;
    }

    .content .no-border {
        border: none;
    }

    .content .text-right {
        text-align: right;
    }

    .content .text-center {
        text-align: center;
    }

    .content .no-padding {
        padding: 0;
    }

    .content .no-border-top {
        border-top: none;
    }

    .content .no-border-bottom {
        border-bottom: none;
    }

    .content .no-border-left {
        border-left: none;
    }

    .content .no-border-right {
        border-right: none;
    }

    .signature {
        margin-top: 20px;
        text-align: right;
    }

    .signature img {
        width: 100px;
    }

    .qr-code {
        text-align: left;
    }

    .qr-code img {
        width: 100px;
    }

    .footer2 {
        margin-top: 20px;
    }
</style>
@if (isset($skrd) && $skrd != [])
    <div class="container mt-5">
        <div class="header">
            <h1>
                PEMERINTAH KOTA SURABAYA
            </h1>
            <h2>
                SURAT KETETAPAN RETRIBUSI DAERAH (SKRD)
            </h2>
            <h3>
                NO. SKRD : {{ $bap->no_skrd }}
            </h3>
        </div>
        <table class="content">
            <tr>
                <td class="no-border">
                    ID Persil
                </td>
                <td class="no-border">
                    : {{ $skrd->id_persil }}
                </td>
                <td class="no-border text-right">
                    MASA
                </td>
                <td class="no-border">
                    : {{ count($skrd->retribusi) }} ({{ terbilang(count($skrd->retribusi)) }}) TAHUN
                </td>
            </tr>
            <tr>
                <td class="no-border">
                    NAMA
                </td>
                <td class="no-border">
                    : {{ $skrd->pemegang }}
                </td>
                <td class="no-border text-right"></td>
                <td class="no-border"></td>
            </tr>
            <tr>
                <td class="no-border">
                    ALAMAT PERSIL
                </td>
                <td class="no-border">
                    : {{ $skrd->persil }}
                </td>
                <td class="no-border">
                </td>
                <td class="no-border">
                </td>
            </tr>
            <tr>
                <td class="no-border">
                    LUAS
                </td>
                <td class="no-border">
                    : {{ $skrd->luas }} m
                    <sup>
                        2
                    </sup>
                </td>
                <td class="no-border">
                </td>
                <td class="no-border">
                </td>
            </tr>
            <tr>
                <td class="no-border">
                    KELAS JALAN
                </td>
                <td class="no-border">
                    : ({{ $skrd->kelas }})
                </td>
                <td class="no-border">
                </td>
                <td class="no-border">
                </td>
            </tr>
            <tr>
                <td class="no-border">
                    PERUNTUKAN
                </td>
                <td class="no-border">
                    : ZONA PERDAGANGAN DAN JASA
                </td>
                <td class="no-border">
                </td>
                <td class="no-border">
                </td>
            </tr>
            <tr>
                <td class="no-border">
                    PENGGUNAAN
                </td>
                <td class="no-border">
                    : RUMAH TINGGAL
                </td>
                <td class="no-border">
                </td>
                <td class="no-border">
                </td>
            </tr>
            <tr>
                <td class="no-border">
                    TANGGAL JATUH TEMPO
                </td>
                <td class="no-border">
                    : {{ dateindo($skrd->tgl_jatuh_tempo) }}
                </td>
                <td class="no-border">
                </td>
                <td class="no-border">
                </td>
            </tr>
        </table>
        @php
            $detail = json_decode(json_encode($skrd->retribusi));
        @endphp
        <table class="content">
            <tr>
                <th>
                    NO.
                </th>
                <th>
                    KODE REKENING
                </th>
                <th>
                    URAIAN RETRIBUSI
                </th>
                <th>
                    JUMLAH (Rp)
                </th>
            </tr>
            <tr>
                <td class="text-center">
                    1.
                </td>
                <td class="text-center">
                    4 1 02 02 01 0002
                </td>
                <td>
                    Retribusi Penyewaan Tanah Tahun
                    {{ count($detail) > 1 ? $detail[0]->tahun . ' s.d ' . $detail[count($detail) - 1]->tahun : $detail[0]->tahun }}
                </td>
                <td class="text-right">
                    {{ 'Rp. ' . toNumber(array_sum(array_column($detail, 'ret')) + array_sum(array_column($detail, 'den'))) }}
                </td>
            </tr>
        </table>
        <div class="footer2">
            <p>
                PERHATIAN :
            </p>
            <ol>
                <li>
                    Pembayaran retribusi Izin Pemakaian Tanah wajib disetorkan ke NO VirtualAccount : 1088712031885525,
                    Tgl
                    Exp VirtualAccount : 26-01-2025
                </li>
                <li>
                    Pembayaran retribusi sebagaimana dimaksud angka 1 (satu) wajib disetorkan paling lambat 3 (tiga)
                    Hari
                    Kerja setelah SKRD diterima.
                </li>
                <li>
                    Apabila SKR ini tidak atau kurang dibayar lewat tanggal jatuh tempo dikenakan sanksi administratif
                    berupa bunga 2% per bulan.
                </li>
            </ol>
        </div>
        <table class="content">
            <tr>
                <th>
                    PERIODE
                </th>
                <th style="text-align: right">
                    POKOK RETRIBUSI
                </th>
                <th style="text-align: right">
                    BUNGA
                </th>
                <th style="text-align: right">
                    JUMLAH
                </th>
                <th>
                    KETERANGAN
                </th>
            </tr>
            @php
                $totalRetribusi = 0;
                $totalDenda = 0;
            @endphp
            @foreach ($detail as $item)
                <tr>
                    <td class="text-center">
                        {{ $item->tahun }}
                    </td>
                    <td class="text-right">
                        {{ toNumber($item->ret) }}
                    </td>
                    <td class="text-right">
                        {{ toNumber($item->den) }}
                    </td>
                    <td class="text-right">
                        {{ toNumber($item->ret + $item->den) }}
                    </td>
                    <td class="text-center">
                        -
                    </td>
                </tr>
                @php
                    $totalRetribusi += $item->ret;
                    $totalDenda += $item->den;
                @endphp
            @endforeach
            <tr>
                <td class="text-center">
                    JUMLAH
                </td>
                <td class="text-right">
                    Rp. {{ toNumber($totalRetribusi) }}
                </td>
                <td class="text-right">
                    Rp. {{ toNumber($totalDenda) }}
                </td>
                <td class="text-right">
                    Rp. {{ toNumber($totalRetribusi + $totalDenda) }}
                </td>
                <td class="text-center">
                    -
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center">JUMLAH KESELURUHAN RETRIBUSI YANG HARUS DIBAYAR</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center">Rp. {{ toNumber($totalRetribusi + $totalDenda) }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: center"> {{ terbilang($totalRetribusi + $totalDenda) }}
                </td>
            </tr>
        </table>
        <div class="signature">
            <p>
                Surabaya, {{ dateindo(date('Y-m-d')) }}
            </p>
            <p>
                Kepala Bidang Penatausahaan, Pemanfaatan dan Pemindatanganan Barang Milik Daerah
            </p>
            <p>
                Badan Pengelolaan Keuangan dan Aset Daerah
            </p>
            <div class="qr-code">
                <img alt="QR Code" height="100"
                    src="https://storage.googleapis.com/a1aa/image/NdpJQ9mQKb5LI962aogk05GnOXtFZRqu2xK7VPRbq3guCfDKA.jpg"
                    width="100" />
            </div>
            <p>
                DIMAS NUSWANTORO, S.Kom
            </p>
            <p>
                Penata Tk. I
            </p>
            <p>
                NIP: 198103142009021002
            </p>
        </div>
    </div>
@else
    <div class="container text-danger">
        Tidak dapat mengambil data Persil, Periksa Kembali NO SK / ID Persil yang dikirm
    </div>
@endif

</body>

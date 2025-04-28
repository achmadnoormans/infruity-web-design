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
    <form action="@if (Request::segment(3) == 'create-surat') {{ url('surat-keterangan') }}@else{{ url('surat', $surat->id) }} @endif"
        method="POST">
        @if (Request::segment(3) == 'edit')
            {{ method_field('PUT') }}
        @endif
        @csrf
        @php
            $pengurangan = $nominal_pengurangan / 100;
            $isi =
                '
            <table align="center" cellspacing="0" class="Table" style="border-collapse:collapse; border:none; width:93.0%">
                <tbody>
                    <tr>
                        <td style="width:15.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span vertical-align: top;><span ><strong>Menimbang</strong></span></span></p>
                        </td>
                        <td style="width:2.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span vertical-align: top;><span >:</span></span></p>
                        </td>
                        <td style="width:2.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span vertical-align: top;><span >a.</span></span></p>
                        </td>
                        <td style="width:80.0%"; vertical-align: top;>
                        <p style="margin-left:0cm; margin-right:0cm"><span vertical-align: top;><span >Bahwa tanah yang terletak di ' .
                $skrd->persil .
                ' Surabaya merupakan tanah aset Pemerintah Kota Surabaya yang diajukan permohonan Izin Pemakaian Tanah (IPT) atas nama ' .
                $skrd->pemegang .
                ' dengan penggunaan ' .
                $data->penggunaan .
                '</span></span></p>
                        </td>
                        <td vertical-align: top;>
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >b.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >bahwa Sdr. ' .
                $data->nama_pemohon .
                ' mengajukan permohonan pengurangan retribusi melalui secara online dengan nomor permohonan ' .
                $data->no_permohonan .
                ' Tanggal ' .
                dateindo($data->tanggal_pengajuan) .
                '</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >c.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >bahwa berdasarkan permohonan tersebut telah diterbitkan Surat Ketetapan Retribusi Daerah (SKRD) No. 3519/ST.TAHUN/UPTSA-T/2025 tanggal 14 Januari 2025 sebesar Rp (Seratus Empat Puluh Lima Ribu Lima Ratus Empat Puluh Lima).</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >d.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >bahwa berdasarkan Peraturan Daerah Kota Surabaya Nomor 7 Tahun 2023 tentang Pajak Retribusi dan Retribusi Daerah jo. Peraturan Walikota Surabaya Nomor 43 Tahun 2024 tentang Pelaksanaan Peraturan Daerah Kota Surabaya Nomor 7 Tahun 2023 tentang Pajak Retribusi dan Retribusi Daerah Pada Retribusi Jasa Usaha, diberikan pengurangan sebesar 50% (tiga puluh persen)</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >e.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >bahwa berdasarkan pertimbangan sebagaimana dimaksud dalam huruf a, huruf b, huruf dan huruf d maka perlu menetapkan Keputusan Kepala Badan Pengelolaan Keuangan dan Aset Daerah tentang Pemberian Pengurangan Retribusi Pemakaian Tanah di Jalan IKAN MUNGSING VI / 79 Surabaya.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style=" width:15.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span ><strong>Mengingat</strong></span></span></p>
                        </td>
                        <td style=" width:2.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >:</span></span></p>
                        </td>
                        <td style=" width:2.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >1.</span></span></p>
                        </td>
                        <td style=" width:80.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Undang-Undang Nomor 16 Tahun 1950 tentang Pembentukan Daerah Kota Besar dalam Lingkungan Propinsi Jawa Timur/Jawa Tengah/Jawa Berat dan Daerah Istimewa Yogyakarta sebagaimana telah diubah dengan UndangUndang Nomor 2 Tahun 1965 (Lembaran Negara Tahun 1965 Nomor 19 Tambahan Lembaran Negara Nomor 2730);</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >2.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Undang-Undang Nomor 12 Tahun 2011 tentang Pembentukan Peraturan Perundang-undangan (Lembaran Negara Tahun 2011 Nomor 82 Tambahan Lembaran Negara Nomor 5234) sebagaimana telah diubah beberapa kali terakhir dengan Undang-Undang Nomor 13 Tahun 2022 (Lembaran Negara Republik Indonesia Tahun 2022 Nomor 143 Tambahan Lembaran Negara Republik Indonesia Nomor 6801);</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >3.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Undang-Undang Nomor 23 Tahun 2014 tentang Pemerintahan Daerah (Lembaran Negara Tahun 2014 Nomor 244 Tambahan Lembaran Negara Nomor 5587) sebagaimana telah diubah beberapa kali terakhir dengan Undang-Undang Nomor 9 Tahun 2015 tentang Perubahan Kedua atas Undang-Undang Nomor 23 Tahun 2014 tentang Pemerintahan Daerah (Lembaran Negara Tahun 2015 Nomor 58 Tambahan Lembaran Negara Nomor 5679);</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >4.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Undang-Undang Nomor 1 Tahun 2022 tentang Hubungan Keuangan Antara Pemerintah Pusat dan Pemerintah Daerah (Lembaran Negara Tahun 2022 Nomor 4 Tambahan Lembaran Negara Nomor 6757);</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >5.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Peraturan Menteri Dalam Negeri Nomor 80 Tahun 2015 tentang Pembentukan Produk Hukum Daerah (Berita Negara Tahun 2015 Nomor 2036) sebagaimana telah diubah dengan Peraturan Menteri Dalam Negeri Nomor 120 Tahun 2018 tentang Pembentukan Produk Hukum Daerah (Berita Negara Republik Indonesia Tahun 2018 Nomor 157);</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >6.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Peraturan Daerah Kota Surabaya Nomor 3 Tahun 2016 tentang Izin Pemakaian Tanah (Lembaran Daerah Kota Surabaya Tahun 2016 Nomor 3 Tambahan Lembaran Daerah Kota Surabaya Nomor 3);</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >7.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Peraturan Daerah Kota surabaya Nomor 14 Tahun 2016 tentang Pembentukan dan Susunan Perangkat Daerah Kota Surabaya (Lembaran Daerah Kota Surabaya Tahun 2016 Nomor 12 Tambahan Lembaran Daerah Kota Surabaya Nomor 10) sebagaimana telah diubah dengan Peraturan Daerah Kota Surabaya Nomor 3 Tahun 2021 (Lembaran Daerah Kota Surabaya Tahun 2021 Nomor 3 Tambahan Lembaran Daerah Kota Surabaya Nomor 3);</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >8.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Peraturan Daerah Kota Surabaya Nomor 7 Tahun 2023 tentang Pajak Retribusi dan Retribusi Daerah (Lembaran Daerah Kota Surabaya Tahun 2023 Nomor 7);</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >9.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Peraturan Walikota Surabaya Nomor 89 Tahun 2021 tentang Kedudukan, Susunan Organisasi, Uraian Tugas Dan Fungsi Serta Tata Kerja Badan Pengelolaan Keuangan Dan Aset Daerah Kota Surabaya (Berita Daerah Kota Surabaya Tahun 2021 Nomor 89);</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >10.</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Peraturan Walikota Surabaya Nomor 43 Tahun 2024 tentang Pelaksanaan Peraturan Daerah Nomor 7 Tahun 2023 tentang Pajak Daerah dan Retribusi Daerah Pada Retribusi Jasa Usaha (Berita Daerah Kota Surabaya Nomor 43 Tahun 2024).</span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm; vertical-align: top;"><span ><span ><strong>MEMUTUSKAN</strong></span></span></p>
                        </td>
                        <td style="vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style=" width:15.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span ><strong>Menetapkan</strong></span></span></p>
                        </td>
                        <td style=" width:2.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >:</span></span></p>
                        </td>
                        <td colspan="3" style=" width:80.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span ><strong>KEPUTUSAN KEPALA BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH KOTA SURABAYA TENTANG PEMBERIAN PENGURANGAN IZIN RETRIBUSI PEMAKAIAN TANAH DALAM RANGKA ------ Di ' .
                $skrd->persil .
                ' SURABAYA.</strong></span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style=" width:15.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span ><strong>KESATU</strong></span></span></p>
                        </td>
                        <td style=" width:2.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >:</span></span></p>
                        </td>
                        <td colspan="3" style=" width:80.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Memberikan pengurangan retribusi izin pemakaian tanah kepada subyek retribusi sebagai berikut:</span></span></p>

                        <table cellspacing="0" class="table-izin" style="border-collapse:collapse; border:undefined">
                            <tbody>
                                <tr>
                                    <td>Nama Subyek Retribusi</td>
                                    <td>:</td>
                                    <td>Sdr. ' .
                $skrd->pemegang .
                '</td>
                                </tr>
                                <tr>
                                    <td>Obyek Retribusi</td>
                                    <td>:</td>
                                    <td>' .
                $skrd->persil .
                '</td>
                                </tr>
                                <tr>
                                    <td>Surat Ketetapan Retribusi</td>
                                    <td>:</td>
                                    <td>3519/ST.TAHUN/UPTSA-T/2025</td>
                                </tr>
                            </tbody>
                        </table>
                        </td>
                    </tr>
                    <tr>
                        <td style=" width:15.0%">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span ><strong>KEDUA</strong></span></span></p>
                        </td>
                        <td style=" width:2.0%">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >:</span></span></p>
                        </td>
                        <td colspan="2" style=" width:80.0%">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Pengurangan retribusi pemakaian tanah sebagaimana dimaksud pada diktum Kesatu diberikan sebesar 50% (tiga puluh persen) dari jumlah pokok retribusi sehingga retribusi yang harus dibayar adalah sebagai berikut :</span></span><br />
                        &nbsp;</p>

                        <table cellspacing="0" class="table-retribusi" style="border-collapse:collapse; border:undefined">
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>
                                    <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;Sebelum Pengurangan&nbsp;</span></span></p>
                                    </td>
                                    <td>
                                    <p style="margin-left:0cm; margin-right:0cm"><span ><span >&nbsp;Setelah Pengurangan&nbsp;</span></span></p>
                                    </td>
                                </tr>';
            $totalRetribusi = 0;
            $totalRetribusiPengurangan = 0;
            foreach ($skrd->retribusi as $key => $value) {
                $isi .=
                    '
                                    <tr>
                                        <td style="background-color:#d0d0d0"> Pokok Retribusi Tahun ' .
                    $value->tahun .
                    '</td>
                                        <td style="background-color:#d0d0d0">
                                            Rp. ' .
                    toNumber($value->ret) .
                    '</td>
                                        <td style="background-color:#d0d0d0">
                                            Rp. ' .
                    toNumber($value->ret * $pengurangan) .
                    '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Denda Tahun ' .
                    $value->tahun .
                    '</td>
                                        <td>
                                           Rp. ' .
                    toNumber($value->den) .
                    '</td>
                                        <td>
                                            Rp. ' .
                    toNumber($value->den * $pengurangan) .
                    '</td>
                                    </tr>
                                    ';
                $totalRetribusi += $value->ret + $value->den;
                $totalRetribusiPengurangan += $value->ret * $pengurangan + $value->den * $pengurangan;
            }
            $isi .=
                '
                                <tr>
                                    <td style="background-color:#d0d0d0"><strong>Jumlah</strong></td>
                                    <td style="background-color:#d0d0d0"><strong>Rp. ' .
                toNumber($totalRetribusi) .
                '</strong</td>
                                    <td style="background-color:#d0d0d0"><strong>Rp. ' .
                toNumber($totalRetribusiPengurangan) .
                '</strong></td>
                                </tr>
            ';
            $isi .= '
                            </tbody>
                        </table>
                        </td>
                        <td style="vertical-align: top;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style=" width:15.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span ><strong>KETIGA</strong></span></span></p>
                        </td>
                        <td style=" width:2.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >:</span></span></p>
                        </td>
                        <td colspan="3" style=" width:80.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Apabila subjek retribusi membayar retribusi pemakaian tanah melebihi tanggal jatuh tempembayaran maka akan dikenakan denda sebesar 1% setiap bulan</span></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td style=" width:15.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span ><strong>KEEMPAT</strong></span></span></p>
                        </td>
                        <td style=" width:2.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >:</span></span></p>
                        </td>
                        <td colspan="3" style=" width:80.0%; vertical-align: top;">
                        <p style="margin-left:0cm; margin-right:0cm"><span ><span >Keputusan Kepala Badan ini mulai berlaku pada tanggal ditetapkan.</span></span></p>
                        </td>
                    </tr>
                </tbody>
            </table>';
        @endphp
        <input type="hidden" name="id_permohonan" id="id_permohonan" value="{{ Request::segment(2) }}">
        <input type="hidden" name="nominal_pengurangan" id="nominal_pengurangan" value="{{ $nominal_pengurangan }}">
        <textarea id="editor1" name="isi" cols="30" rows="10">
            @php
                if (isset($surat->isi)) {
                    echo $surat->isi;
                } else {
                    echo $isi;
                }
            @endphp
        </textarea>
        <div class="card-footer text-end">
            <div class="col-sm-9 offset-sm-3">
                <button class="btn btn-primary me-3" type="submit"><i class="fas fa-save"></i> Simpan</button>
                <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i> Batal</a>
            </div>
        </div>
    </form>
@endsection

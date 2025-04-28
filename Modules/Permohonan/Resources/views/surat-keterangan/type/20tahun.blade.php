<tr>
    <td width="100px" style="vertical-align: top"><b>Menimbang</b></td>
    <td width="20px" style="vertical-align: top">a.</td>
    <td>bahwa tanah yang terletak di {{ $surat->alamat_persil ?? $data->alamat_persil }} merupakan tanah aset
        Pemerintah Kota
        Surabaya yang diajukan permohonan Izin Pemakaian Tanah (IPT) atas {{ $surat->nama_pemegang_ipt }}
        dalam hal
        ini diwakili oleh {{ $data->nama_pemohon }} dengan penggunaan {{ $bap->penggunaan }}.</td>
</tr>
<tr>
    <td></td>
    <td width="20px" style="vertical-align: top">b.</td>
    <td>bahwa Sdr. {{ $data->nama_pemohon }} adalah adalah pemegang Izin Pemakaian Tanah selama 20 tahun secara
        berturut-turut dan dalam hal terjadi pewarisan, jangka waktu 20 (dua puluh) tahun berturut-turut dihitung sejak
        pewaris memperoleh Izin Pemakaian Tanah untuk pertama kali.
    </td>
</tr>
@php
    $retribusi = array_sum(array_column($skrd->retribusi, 'ret'));
    $denda = array_sum(array_column($skrd->retribusi, 'den'));
@endphp
<tr>
    <td></td>
    <td width="20px" style="vertical-align: top">c.</td>
    <td>bahwa Sdr. {{ $surat->nama_pemegang_ipt }} mengajukan permohonan
        pengurangan retribusi Izin
        Pemakaian Tanah
        (IPT)
        periode {{ $surat->periode }} pada Surat Ketetapan
        Retribusi Daerah (SKRD) No.
        {{ $bap->no_skrd }} tanggal {{ dateindo($bap->created_at) }} sebesar Rp. {{ toNumber($retribusi + $denda) }}
        ({{ terbilang($retribusi + $denda) }}).
    </td>
</tr>

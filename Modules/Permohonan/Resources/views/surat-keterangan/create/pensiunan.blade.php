<tr>
    <td width="100px" style="vertical-align: top"><b>Menimbang</b></td>
    <td width="20px" style="vertical-align: top">a.</td>
    <td>bahwa tanah yang terletak di <input type="text" class="form-control" name="alamat_persil" width="50%"
            value="{{ $surat->alamat_persil ?? $skrd->persil }}"> merupakan tanah aset
        Pemerintah Kota
        Surabaya yang diajukan permohonan Izin Pemakaian Tanah (IPT) atas <input type="text" class="form-control"
            name="nama_pemegang_ipt" width="50%" value="{{ $surat->nama_pemegang_ipt ?? $skrd->pemegang }}">
        dalam hal
        ini diwakili oleh {{ $data->nama_pemohon }} dengan penggunaan {{ $bap->penggunaan }}.</td>
</tr>
<tr>
    <td></td>
    <td width="20px" style="vertical-align: top">b.</td>
    <td>bahwa Sdr. {{ $data->nama_pemohon }} adalah <input type="text" class="form-control" name="list_nama"
            width="50%" value="{{ $surat->list_nama ?? '' }}">,
        dibuktikan dengan <input type="text" class="form-control" name="bukti" width="50%"
            value="{{ $surat->bukti ?? '' }}">.
    </td>
</tr>
<tr>
    <td></td>
    <td width="20px" style="vertical-align: top">c.</td>
    <td>bahwa Sdr. {{ $surat->nama_pemegang_ipt ?? $data->nama_pemegang_ipt }} mengajukan permohonan
        pengurangan retribusi Izin
        Pemakaian Tanah
        (IPT)
        periode <input type="text" class="form-control" name="periode" width="50%"
            value="{{ $surat->periode ?? '' }}"> pada Surat
        Ketetapan
        Retribusi Daerah (SKRD) No.
        112103/ST.TAHUN/UPTSA-T/2024 tanggal 30 Mei 2024 sebesar Rp. 92.310.704,00 (sembilan puluh dua juta tiga
        ratus sepuluh ribu tujuh ratus empat koma nol rupiah).
    </td>
</tr>

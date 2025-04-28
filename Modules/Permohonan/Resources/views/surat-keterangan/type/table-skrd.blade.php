<div style="margin-left: 130px">
    <table class="table1 table-hover" width="100%">
        <thead>
            <th></th>
            <th>Sebelum Pengurangan</th>
            <th>Setelah Pengurangan</th>
        </thead>
        @php
            $totalRetribusi = 0;
            $totalRetribusiPengurangan = 0;
        @endphp
        @foreach ($skrd->retribusi as $value)
            <tr>
                <td>Pokok Retribusi {{ $value->tahun }}</td>
                <td class="text-right">{{ toNumber($value->ret) }}</td>
                <td class="text-right">{{ toNumber($value->ret * ($surat->nominal_pengurangan / 100)) }}</td>
            </tr>
            <tr>
                <td>Denda {{ $value->tahun }}</td>
                <td class="text-right">{{ toNumber($value->den) }}</td>
                <td class="text-right">{{ toNumber($value->den * ($surat->nominal_pengurangan / 100)) }}</td>
            </tr>
            @php
                $totalRetribusi += $value->ret + $value->den;
                $totalRetribusiPengurangan +=
                    $value->ret * ($surat->nominal_pengurangan / 100) + $value->den * ($surat->nominal_pengurangan / 100);
            @endphp
        @endforeach
        <tr>
            <td>Jumlah</td>
            <td class="text-right">{{ toNumber($totalRetribusi) }}</td>
            <td class="text-right">{{ toNumber($totalRetribusiPengurangan) }}</td>
        </tr>
    </table>
</div>
<table>
    <tr>
        <td width="100px" style="vertical-align: top"><b>KETIGA</b></td>
        <td width="20px" style="vertical-align: top">:</td>
        <td>Keputusan Kepala Badan ini mulai berlaku pada tanggal ditetapkan</td>
    </tr>
</table>

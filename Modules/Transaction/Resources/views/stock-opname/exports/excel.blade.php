<table style="border-collapse: collapse; width: 100%;">
    <tr>
        <th colspan="5" style="text-align: center; font-weight: bold; font-size: 16px;">LAPORAN STOK OPNAME {{ strtoupper($dateStr) }}</th>
    </tr>
    <tr>
        <th colspan="5" style="text-align: center; font-size: 14px; font-weight: normal;">CABANG {{ strtoupper($branchStr) }}</th>
    </tr>
    <tr>
        <th colspan="5"></th>
    </tr>
    <tr>
        <th style="background-color: #34A853; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000; width: 80px;">No</th>
        <th style="background-color: #34A853; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000; width: 300px;">Nama Item</th>
        <th style="background-color: #34A853; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000; width: 150px;">Stok Sistem</th>
        <th style="background-color: #34A853; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000; width: 150px;">Stok Fisik</th>
        <th style="background-color: #34A853; color: #ffffff; text-align: center; font-weight: bold; border: 1px solid #000000; width: 150px;">Selisih</th>
    </tr>
    @foreach($data as $index => $row)
        @php
            $isPending = $row->status === 'pending';
            $selisih = (float)$row->difference;
            $bg = '';
            if ($selisih < 0) {
                $bg = 'background-color: #F4CCCC;';
            } elseif ($selisih > 0) {
                $bg = 'background-color: #D9EAD3;';
            }
            $rowBg = $isPending ? 'background-color: #FFF5F8;' : '';
            $stockText = $isPending ? 'Sistem' : str_replace('.', ',', (float)$row->stock) . ' ' . ($row->product->unit->abbreviation ?? $row->product->unit->name ?? '');
        @endphp
        <tr style="{{ $rowBg }}">
            <td style="text-align: center; border: 1px solid #d9d9d9;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #d9d9d9;">
                {{ $row->product->name ?? '-' }}
                @if($isPending)
                    <span style="color: #f1416c;">(Belum Selesai)</span>
                @endif
            </td>
            <td style="text-align: center; border: 1px solid #d9d9d9; {{ $isPending ? 'color: #f1416c; font-style: italic;' : '' }}">{{ $stockText }}</td>
            <td style="text-align: center; border: 1px solid #d9d9d9;">{{ str_replace('.', ',', (float)$row->real_stock) . ' ' . ($row->product->unit->abbreviation ?? $row->product->unit->name ?? '') }}</td>
            <td style="text-align: center; border: 1px solid #d9d9d9; {{ $bg }}">{{ str_replace('.', ',', $selisih) . ' ' . ($row->product->unit->abbreviation ?? $row->product->unit->name ?? '') }}</td>
        </tr>
    @endforeach
</table>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Laporan Stok Opname</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; padding: 20px; background: #f5f8fa; margin: 0; }
        .preview-container { max-width: 1000px; margin: 0 auto; background: #ffffff; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.05); border-radius: 8px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; font-size: 14px; }
        th, td { border: 1px solid #e4e6ef; padding: 10px 12px; text-align: left; }
        th { background-color: #34A853; color: white; text-align: center; font-weight: 600; border-color: #34A853; }
        .text-center { text-align: center; }
        .bg-danger-light { background-color: #ffe2e5; color: #f1416c; font-weight: 600; }
        .bg-success-light { background-color: #e8fff3; color: #50cd89; font-weight: 600; }
        .header-title { text-align: center; font-weight: bold; font-size: 20px; margin-bottom: 5px; color: #181c32; }
        .header-subtitle { text-align: center; font-size: 15px; margin-bottom: 30px; color: #7e8299; }
        .action-buttons { text-align: right; margin-bottom: 20px; }
        .btn { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; font-family: inherit; transition: background-color 0.3s; }
        .btn-primary { background: #009ef7; color: white; }
        .btn-primary:hover { background: #008be1; }
        .btn-light { background: #f5f8fa; color: #5e6278; margin-left: 8px; }
        .btn-light:hover { background: #e4e6ef; color: #3f4254; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; background: white; }
            .preview-container { box-shadow: none; padding: 0; margin: 0; max-width: 100%; border-radius: 0; }
            th { background-color: #34A853 !important; -webkit-print-color-adjust: exact; color: white !important; }
            .bg-danger-light { background-color: #F4CCCC !important; -webkit-print-color-adjust: exact; color: #000 !important; font-weight: normal; }
            .bg-success-light { background-color: #D9EAD3 !important; -webkit-print-color-adjust: exact; color: #000 !important; font-weight: normal; }
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="action-buttons no-print">
            <button onclick="window.print()" class="btn btn-primary">
                Cetak Laporan
            </button>
            <button onclick="window.close()" class="btn btn-light">
                Tutup Preview
            </button>
        </div>

        <div class="header-title">LAPORAN STOK OPNAME {{ strtoupper($dateStr) }}</div>
        <div class="header-subtitle">CABANG {{ strtoupper($branchStr) }}</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Item</th>
                    <th style="width: 150px;">Stok Sistem</th>
                    <th style="width: 150px;">Stok Fisik</th>
                    <th style="width: 150px;">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $row)
                    @php
                        $isPending = $row->status === 'pending';
                        $selisih = (float)$row->difference;
                        $bgClass = '';
                        if ($selisih < 0) {
                            $bgClass = 'bg-danger-light';
                        } elseif ($selisih > 0) {
                            $bgClass = 'bg-success-light';
                        }
                    @endphp
                    <tr style="{{ $isPending ? 'background-color: #fff5f8;' : '' }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            {{ $row->product->name ?? '-' }}
                            @if($isPending)
                                <span style="color: #f1416c; font-size: 11px; margin-left: 5px; font-weight: bold;">(Belum Selesai)</span>
                            @endif
                        </td>
                        <td class="text-center" style="{{ $isPending ? 'color: #f1416c; font-weight: bold; font-style: italic;' : '' }}">
                            {{ $isPending ? 'Sistem' : str_replace('.', ',', (float)$row->stock) }}
                        </td>
                        <td class="text-center">{{ str_replace('.', ',', (float)$row->real_stock) }}</td>
                        <td class="text-center {{ $bgClass }}">{{ str_replace('.', ',', $selisih) }}</td>
                    </tr>
                @endforeach
                @if(count($data) === 0)
                    <tr>
                        <td colspan="5" class="text-center" style="color: #a1a5b7; padding: 20px;">Tidak ada data stok opname yang ditemukan</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <title>Receipt - Invoice #12345</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto';
            background-color: #fff;
            padding: 20px;
            line-height: 1.3;
            color: #5f5d5d;
        }

        .receipt-container {
            max-width: 300px;
            margin: 0 auto;
            background: white;
            border: 1px solid #000;
        }

        .receipt-header {
            text-align: center;
            padding: 15px 10px;
            border-bottom: 1px dashed #000;
        }

        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;

        }

        .store-info {
            display: block;
            /* Biar setiap .store-info tampil di baris baru */
            font-size: 12px;
            line-height: 1.4;
        }

        .receipt-body {
            padding: 15px 10px;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-divider {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 12px;
        }

        .info-label {
            font-weight: normal;
        }

        .info-value {
            font-weight: normal;
        }

        .customer-section {
            margin: 15px 0;
        }

        .customer-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;

        }

        .customer-name {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .customer-details {
            font-size: 11px;
        }

        .items-header {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0 8px 0;

        }

        .items-table {
            width: 100%;
            font-size: 11px;
        }

        .items-table-header {
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .table-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .item-name {
            flex: 1;
            padding-right: 5px;
        }

        .item-qty {
            width: 70px;
            text-align: center;
        }

        .item-price {
            width: 60px;
            text-align: right;
        }

        .item-total {
            width: 70px;
            text-align: right;
        }

        .summary-section {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #000;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .summary-label {
            font-weight: normal;
        }

        .summary-value {
            font-weight: normal;
        }

        .total-row {
            border-top: 1px dashed #000;
            padding-top: 15px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 16px;
        }

        .receipt-footer {
            text-align: center;
            padding: 15px 10px;
            border-top: 1px dashed #000;
            font-size: 13px;
        }

        .thank-you {
            font-weight: bold;
            margin-bottom: 8px;

        }

        .footer-note {
            margin-bottom: 3px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .print-button {
            background: #ffffff;
            color: #333;
            border: 2px solid #333;
            padding: 10px 15px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .print-button:hover {
            background-color: #333;
            color: #fff;
        }

        @media print {
            .button-group {
                display: none;
            }
        }


        /* Responsive Design */
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .receipt-container {
                max-width: 100%;
                border: none;
            }

            .store-name {
                font-size: 16px;
            }

            .items-table {
                font-size: 10px;
            }

            .item-price,
            .item-total {
                width: 55px;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                border: none;
                max-width: 100%;
            }

            .print-button {
                display: none;
            }

            .receipt-header,
            .receipt-body,
            .receipt-footer {
                padding: 10px 5px;
            }
        }

        .receipt-header2 {
            display: flex;
            align-items: center;
            /* Vertical center alignment */
            gap: 10px;
            /* Space between logo dan teks */
            padding: 15px 10px;
            text-align: center;
            /* Pastikan teks rata kiri */
        }

        .receipt-logo {
            max-width: 60px;
            /* Logo fix width */
            height: auto;
        }

        .receipt-text .store-name {
            font-size: 18px;
            font-weight: bold;
            display: block;
            /* Biar turun sendiri */
            margin-bottom: 5px;
        }

        .receipt-text .store-info {
            font-size: 12px;
            line-height: 1.4;
            display: block;
            /* Biar masing-masing turun */
        }

        .progress-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 999px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
            height: 25px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            width: var(--progress);
            background: linear-gradient(90deg, #28a745, #a2ff86);
            border-radius: 999px 0 0 999px;
            display: flex;
            align-items: center;
            padding-left: 10px;
            color: white;
            font-weight: bold;
            transition: width 1s ease-in-out;
            position: absolute;
            left: 0;
            top: 0;
        }

        .progress-label {
            position: absolute;
            right: 10px;
            color: #333;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .info-row.align-center {
            align-items: center;
        }

        .progress-inline-container {
            flex: 1;
            position: relative;
            height: 18px;
            background-color: #e0e0e0;
            border-radius: 999px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            overflow: hidden;
            margin-left: 10px;
        }

        .progress-inline-bar {
            height: 100%;
            width: var(--progress);
            background: linear-gradient(90deg, #28a745, #a2ff86);
            border-radius: 999px;
            transition: width 1s ease-in-out;
        }

        .progress-inline-label {
            position: absolute;
            right: 8px;
            color: #333;
            font-size: 0.75rem;
            font-weight: bold;
            z-index: 2;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header -->
        @if ($setting->is_using_logo && $setting->logo)
            <div class="receipt-header2">
                <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="receipt-logo">
                <div class="receipt-text">
                    <span class="store-name">{{ $setting->brand_name }}</span>
                    <span class="store-info">{{ $setting->brand_address }}</span>
                    <span class="store-info">{{ $setting->brand_social_media }}</span>
                </div>
            </div>
        @else
            <div class="receipt-header">
                <span class="store-name">{{ $setting->brand_name }}</span>
                <span class="store-info">{{ $setting->brand_address }}</span>
                <span class="store-info">{{ $setting->brand_social_media }}</span>
            </div>
        @endif

        <!-- Body -->
        <div class="receipt-body">
            <!-- Invoice Info -->
            <div class="section">
                @if ($setting->is_using_cashier)
                    <div class="info-row">
                        <span class="info-label">Kasir</span>
                        <span class="info-value">{{ $data->user->nm_user ?? 'Admin' }}</span>
                    </div>
                @endif
                @if ($setting->is_using_date)
                    <div class="info-row">
                        <span class="info-label">Waktu</span>
                        <span class="info-value">{{ date('d M Y, H:i', strtotime($data->created_at)) }}</span>
                    </div>
                @endif
                @if ($setting->is_using_invoice_number)
                    <div class="info-row">
                        <span class="info-label">No. Nota</span>
                        <span class="info-value">{{ $data->invoice_number }}</span>
                    </div>
                @endif
                @if ($setting->is_using_customer)
                    @php
                        $currentExp = $tier->customer_exp;
                        $maxExp = $tier->max_exp;
                        $percent = min(100, ($currentExp / $maxExp) * 100); // pastikan max 100%
                    @endphp
                    <div class="info-row">
                        <span class="info-label">Pelanggan</span>
                        <span class="info-value">{{ $data->customer->name ?? 'Umum' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Class</span>
                        <span class="info-value">{{ $tier->tier_name }} Member</span>
                    </div>
                    <div class="info-row align-center">
                        <span class="info-label">Progress</span>
                        <div class="progress-inline-container">
                            <div class="progress-inline-bar" style="--progress: {{ $percent }}%;"></div>
                            <span class="progress-inline-label">{{ number_format($percent, 1) }}%</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="section-divider"></div>

            <div style="text-align: center; font-size: 16px; margin: 10px 0; color:#000;">
                @if ($data->status == 'paid')
                    <span>### LUNAS ###</span>
                @else
                    <span>### BELUM LUNAS ###</span>
                @endif
            </div>

            <div class="section-divider"></div>

            <div style="text-align: left; font-size: 12px; margin: 10px 0;">
                @isset($detail)
                    @foreach ($detail as $item)
                        <table width="100%">
                            <tr>
                                <td colspan="2">{{ $item->product->name }}</td>
                            </tr>
                            <tr>
                                <td>
                                    {{ tonumberround($item->price) }} x
                                    {{ $item->quantity . ' (' . $item->product->unit->abbreviation . ')' }}
                                </td>
                                <td style="text-align: right">{{ tonumberround($item->subtotal) }}</td>
                            </tr>
                            @isset($item->discount)
                                @if ($item->discount > 0)
                                    <tr>
                                        <td>Diskon</td>
                                        <td style="text-align: right">-{{ tonumberround($item->discount) }}</td>
                                    </tr>
                                @endif
                            @endisset
                            <tr>
                                <td></td>
                            </tr>
                        </table>
                    @endforeach
                @endisset
            </div>

            <!-- Summary -->
            <div class="summary-section">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>{{ tonumberround($data->total + $data->discount) }}</span>
                </div>
                @if ($data->discount > 0)
                    <div class="summary-row">
                        <span>Diskon</span>
                        <span>-{{ tonumberround($data->discount) }}</span>
                    </div>
                @endif
                <div class="summary-row total-row">
                    <span>Total ({{ count($detail) }} Produk)</span>
                    <span>{{ tonumberround($data->total) }}</span>
                </div>
            </div>
            <div class="summary-section ">
                <div class="summary-row">
                    <span>Bayar</span>
                    <span>{{ tonumberround($payment->total) }}</span>
                </div>
                @if (isset($payment->return) && $payment->return > 0)
                    <div class="summary-row">
                        <span>Kembalian</span>
                        <span>{{ tonumberround($payment->return) }}</span>
                    </div>
                @else
                    <div class="summary-row">
                        <span>Kurang</span>
                        <span>{{ tonumberround($payment->remaining) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            {!! $setting->footer !!}
        </div>
    </div>

    <div class="button-group">
        <button class="print-button" onclick="window.print()">Cetak Receipt</button>
        <button class="print-button" onclick="window.location.href='{{ route('pos.index') }}'">Kembali</button>
        <button class="print-button" onclick="downloadReceiptAsPNG()">Download PNG</button>
        <button class="print-button" onclick="sendReceiptToWA()">Kirim ke WhatsApp</button>
    </div>



    <script>
        // Auto print jika ada parameter print=true di URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            setTimeout(() => {
                window.print();
            }, 500);
        }

        // Fungsi untuk print dengan auto close
        function printAndClose() {
            window.print();
            setTimeout(() => {
                window.close();
            }, 1000);
        }

        function downloadReceiptAsPNG() {
            const receipt = document.querySelector('.receipt-container');
            html2canvas(receipt, {
                scale: 2, // Lebih tajam
                backgroundColor: '#fff',
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'receipt_{{ $data->invoice_number ?? 'invoice' }}.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        }

        function sendReceiptToWA() {
            const receipt = document.querySelector('.receipt-container');
            html2canvas(receipt, {
                scale: 2,
                backgroundColor: '#fff',
            }).then(canvas => {
                const base64Image = canvas.toDataURL('image/png');

                fetch('/upload-receipt', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            image: base64Image
                        }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.url) {
                            const message = encodeURIComponent(
                                `Halo, berikut bukti transaksi Anda:\n${data.url}`);
                            const phone = '6281230607050'; // Ganti dengan nomor tujuan
                            const waUrl = `https://wa.me/${phone}?text=${message}`;
                            window.open(waUrl, '_blank');
                        } else {
                            alert('Gagal upload gambar.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat mengirim ke WhatsApp.');
                    });
            });
        }
    </script>
</body>

</html>

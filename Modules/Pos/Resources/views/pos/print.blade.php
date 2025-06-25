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
            font-family: 'Roboto', Arial, Helvetica, sans-serif;
            background-color: #fff;
            padding: 20px;
            line-height: 1.3;
            color: #000;
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
            font-weight: bold;
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
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header -->
        @if ($setting->is_using_logo && $setting->logo)
            <div class="receipt-header">
                <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" style="max-width: 20%; height: auto;">
            </div>
        @else
            <div class="receipt-header">
                {!! $setting->header !!}
            </div>
        @endif

        <!-- Body -->
        <div class="receipt-body">
            <!-- Invoice Info -->
            <div class="section">
                <div class="info-row">
                    <span class="info-label">Kasir</span>
                    <span class="info-value">{{ $data->user->nm_user ?? 'Admin' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu</span>
                    <span class="info-value">{{ date('d M Y, H:i', strtotime($data->created_at)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Nota</span>
                    <span class="info-value">{{ $data->invoice_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pelanggan</span>
                    <span class="info-value">{{ $data->customer->name ?? 'Umum' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Class</span>
                    <span class="info-value">Silver Member</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Exp</span>
                    <span class="info-value">50/100</span>
                </div>
            </div>

            <div class="section-divider"></div>

            <div style="text-align: center; font-size: 20px; margin: 10px 0;">
                @if ($data->status == 'paid')
                    <strong>## LUNAS ##</strong>
                @else
                    <strong>## BELUM LUNAS ##</strong>
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
                <div class="summary-row">
                    <span>Diskon</span>
                    <span>-{{ tonumberround($data->discount) }}</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total ({{ count($detail) }} Produk)</span>
                    <span>{{ tonumberround($data->total) }}</span>
                </div>
            </div>
            <div class="summary-section ">
                <div class="summary-row">
                    <span>Bayar</span>
                    <span>-{{ tonumberround($data->paid) }}</span>
                </div>
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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Invoice #12345</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
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
            text-transform: uppercase;
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
            text-transform: uppercase;
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
            text-transform: uppercase;
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
            margin-bottom: 3px;
            font-size: 12px;
        }

        .summary-label {
            font-weight: normal;
        }

        .summary-value {
            font-weight: normal;
        }

        .total-row {
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 8px;
            font-weight: bold;
            font-size: 13px;
        }

        .receipt-footer {
            text-align: center;
            padding: 15px 10px;
            border-top: 1px dashed #000;
            font-size: 11px;
        }

        .thank-you {
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .footer-note {
            margin-bottom: 3px;
        }

        .print-button {
            background: #fff;
            color: #000;
            border: 2px solid #000;
            padding: 10px 20px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            margin: 20px auto;
            display: block;
            font-family: 'Courier New', monospace;
            text-transform: uppercase;
        }

        .print-button:hover {
            background: #f0f0f0;
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
        <div class="receipt-header">
            <div class="store-name">in!fruity</div>
            <div class="store-info">
                Jl. Merdeka No. 123, Surabaya<br>
                Telp: (031) 123-4567<br>
            </div>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <!-- Invoice Info -->
            <div class="section">
                <div class="info-row">
                    <span class="info-label">No. Nota</span>
                    <span class="info-value">: {{ $data->invoice_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value">: {{ dateindo($data->date) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kasir</span>
                    <span class="info-value">: {{ $data->user->nm_user ?? 'Admin' }}</span>
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

            <!-- Items -->
            <div class="items-header">Detail Pembelian</div>

            <div class="items-table">
                <div class="items-table-header">
                    <div class="table-row">
                        <div class="item-name"><strong>Item</strong></div>
                        <div class="item-qty"><strong>Qty</strong></div>
                        <div class="item-price"><strong>Harga</strong></div>
                        <div class="item-total"><strong>Total</strong></div>
                    </div>
                </div>

                @isset($detail)
                    @foreach ($detail as $item)
                        <div class="table-row">
                            <div class="item-name">{{ $item->product->name }}</div>
                            <div class="item-qty">
                                {{ $item->quantity . ' (' . $item->product->unit->abbreviation . ')' }}
                            </div>
                            <div class="item-price">{{ tonumberround($item->price) }}</div>
                            <div class="item-total">{{ tonumberround($item->subtotal) }}</div>
                        </div>
                    @endforeach
                @endisset
            </div>

            <!-- Summary -->
            <div class="summary-section">
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">Rp {{ tonumberround($data->total + $data->discount) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Diskon</span>
                    <span class="summary-value">Rp {{ tonumberround($data->discount) }}</span>
                </div>
                <div class="summary-row total-row">
                    <span class="summary-label">TOTAL BAYAR</span>
                    <span class="summary-value">Rp {{ tonumberround($data->total) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Dibayar</span>
                    <span class="summary-value">Rp {{ tonumberround($data->paid) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <div class="thank-you">Terima Kasih</div>
            <div class="footer-note">Jangan lupa beli lagi</div>
            <div class="footer-note">Powered by : Infruity</div>
            <div class="footer-note">{{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>

    <button class="print-button" onclick="window.print()">Cetak Receipt</button>
    <button class="print-button" onclick="window.location.href='{{ route('pos.index') }}'">Kembali</button>

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
    </script>
</body>

</html>

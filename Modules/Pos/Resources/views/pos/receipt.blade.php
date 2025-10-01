<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Invoice #{{ $data->invoice_number ?? $data->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.4;
        }

        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .receipt-header {
            background: linear-gradient(135deg, #86af8f 0%, #099204 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .store-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .store-info {
            font-size: 14px;
            opacity: 0.9;
        }

        .receipt-body {
            padding: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .invoice-info {
            display: grid;
            /* grid-template-columns: 1fr 1fr; */
            gap: 10px;
            font-size: 14px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: #333;
            font-weight: bold;
        }

        .customer-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #86af8f;
        }

        .customer-name {
            font-weight: bold;
            color: #333;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .customer-details {
            color: #666;
            font-size: 14px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th {
            background: #f8f9fa;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e9ecef;
            font-size: 12px;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .item-qty {
            text-align: center;
            color: #666;
        }

        .item-price {
            text-align: right;
            font-weight: 600;
            color: #333;
        }

        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .summary-label {
            color: #666;
        }

        .summary-value {
            font-weight: 600;
            color: #333;
        }

        .total-row {
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
            margin-top: 10px;
        }

        .total-row .summary-label {
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }

        .total-row .summary-value {
            font-weight: bold;
            color: #667eea;
            font-size: 16px;
        }

        .receipt-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #e9ecef;
        }

        .thank-you {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .print-button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin: 20px auto;
            display: block;
            transition: background 0.3s ease;
        }

        .print-button:hover {
            background: #5a6fd8;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .receipt-container {
                max-width: 100%;
            }

            .invoice-info {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .items-table th,
            .items-table td {
                padding: 8px 4px;
                font-size: 12px;
            }

            .store-name {
                font-size: 20px;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        {{-- <!-- Header -->
        <div class="receipt-header">
            <div class="store-name">{{ $setting->brand_name ?? 'in!fruity' }}</div>
            <div class="store-info">
                {{ $setting->brand_address ?? 'Jl. Merdeka No. 123, Surabaya' }}<br>
                Telp: {{ $setting->brand_phone ?? '(031) 123-4567' }}
            </div>
        </div> --}}

        <!-- Body -->
        <div class="receipt-body">
            <!-- Invoice Info -->
            <div class="section">
                <div class="section-title">Informasi Transaksi</div>
                <div class="invoice-info">
                    <div class="info-item">
                        <span class="info-label">No:</span>
                        <span
                            class="info-value">#{{ $data->invoice_number ?? 'INV-' . str_pad($data->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value">{{ $data->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Waktu:</span>
                        <span class="info-value">{{ $data->created_at->format('H:i') }} WIB</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kasir:</span>
                        <span class="info-value">{{ $data->cashier_name ?? 'Admin' }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            @if ($data->customer)
                <div class="section">
                    <div class="section-title">Informasi Pelanggan</div>
                    <div class="customer-info">
                        <div class="customer-name">{{ $data->customer->name ?? 'No Name' }}</div>
                        <div class="customer-details">
                            @if ($data->customer->phone)
                                Telp: {{ $data->customer->phone }}<br>
                            @endif
                            @if ($data->customer->email)
                                Email: {{ $data->customer->email }}
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($data->ongkir && $data->ongkir > 0)
                <div class="section" style="margin-top: 20px">
                    <div class="section-title">Informasi Pengiriman</div>
                    <div class="customer-info">
                        <div class="customer-name"></div>
                        <div class="customer-details">
                            Pesanan dikirim oleh : {{ $data->courier->name ?? '-' }}<br>
                            ke : {{ $data->ongkir_address ?? '-' }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Items -->
            <div class="section">
                <div class="section-title">Detail Pembelian</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th style="text-align: right">Harga</th>
                            <th style="text-align: right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotal = 0; @endphp
                        @foreach ($detail as $item)
                            @php
                                $itemTotal =
                                    isset($item->discount) && $item->discount > 0
                                        ? $item->subtotal + $item->discount * $item->quantity
                                        : $item->subtotal;
                                $subtotal += $itemTotal;
                            @endphp
                            <tr>
                                <td class="item-name">{{ $item->type == 'parcel' ? $item->product->description : $item->product->name }}</td>
                                <td class="item-qty">{{ $item->quantity }}</td>
                                <td class="item-price">{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="item-price">{{ number_format($itemTotal, 0, ',', '.') }}</td>
                            </tr>
                            @if ($item->discount && $item->discount > 0)
                                <tr>
                                    <td colspan="2">Diskon</td>
                                    <td style="text-align: right">{{ number_format($item->discount, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: right">
                                        {{ number_format($item->discount * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @php
                                    $subtotal -= $item->discount * $item->quantity;
                                @endphp
                            @endif
                            @if(isset($parcelDetail) && (count($parcelDetail) > 0))
                                <tr>
                                    <td colspan="4" style="border: none;  padding: 0 !important;" class="item-name">
                                        List Bahan :
                                    </td>
                                </tr>
                                @foreach ($parcelDetail as $key => $parcel)
                                    @if ($parcel->production_id == $item->product_id)
                                        <tr style="border: none;">
                                            <td colspan="4"
                                                style="border: none;  padding-top: 0 !important;
                                            padding-bottom: 0 !important;"
                                                class="item-name">
                                                {{ $key + 1 }}.
                                                {{ $parcel->product->name ?? '-' }}
                                                ({{ $parcel->quantity }}
                                                {{ $parcel->product->unit->abbreviation ?? '-' }})
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            @php
                $discount = $data->discount;
                $ongkir_discount = $data->ongkir_discount;
                if ($discount <= 100) {
                    $discount = ($discount / 100) * $subtotal;
                }
                if ($ongkir_discount <= 100) {
                    $ongkir_discount = ($ongkir_discount / 100) * $data->ongkir;
                }
            @endphp
            <div class="summary">
                <div class="summary-row">
                    <span class="summary-label">Subtotal:</span>
                    <span class="summary-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($discount > 0)
                    <div class="summary-row">
                        <span class="summary-label">Diskon:</span>
                        <span class="summary-value">-Rp
                            {{ number_format($discount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $subtotal -= $discount;
                    @endphp
                @endif
                @if ($data->ongkir && $data->ongkir > 0)
                    <div class="summary-row">
                        <span class="summary-label">Ongkir:</span>
                        <span class="summary-value">Rp {{ number_format($data->ongkir, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $subtotal += $data->ongkir;
                    @endphp
                @endif
                @if ($ongkir_discount > 0)
                    <div class="summary-row">
                        <span class="summary-label">Diskon Ongkir:</span>
                        <span class="summary-value">-Rp
                            {{ number_format($ongkir_discount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $subtotal -= $ongkir_discount;
                    @endphp
                @endif
                <div class="summary-row total-row">
                    <span class="summary-label">TOTAL:</span>
                    <span class="summary-value">Rp
                        {{ number_format($data->total_amount ?? $subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- <button class="print-button" onclick="window.print()">🖨️ Cetak Receipt</button> --}}
    <a class="print-button" href="{{ url(Request::segment(1)) }}"
        style="text-align: center; text-decoration: none;">Kembali</a>

    <script>
        // Auto print ketika halaman dimuat (opsional)
        // window.onload = function() {
        //     window.print();
        // }

        // Auto print jika ada parameter print=true di URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            window.print();
        }

        const displayInput = document.getElementById('paid_amount_display');
        const hiddenInput = document.getElementById('paid_amount');
        const changeOutput = document.getElementById('change_amount');

        // Ubah ini ke nilai total sebenarnya
        const total = {{ $data->total ?? $subtotal }};

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function parseRupiah(rupiahString) {
            return parseInt(rupiahString.replace(/[^\d]/g, '')) || 0;
        }

        displayInput.addEventListener('input', function() {
            let numericValue = parseRupiah(this.value);

            // Set hidden input untuk dikirim ke backend
            hiddenInput.value = numericValue;

            // Format ulang input yang ditampilkan
            this.value = formatRupiah(numericValue);

            // Hitung dan tampilkan kembalian
            const change = numericValue - total;
            changeOutput.value = change >= 0 ? formatRupiah(change) : 'Rp 0';
        });
    </script>
</body>

</html>

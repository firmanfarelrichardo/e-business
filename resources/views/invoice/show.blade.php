<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    
    <style>
        /* Base Thermal Printer Styles */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        .receipt-container {
            width: 100%;
            max-width: 80mm; /* Standard Thermal Size 80mm */
            padding: 5mm;
            background: #fff;
        }

        h1, h2, h3, h4, h5, p {
            margin: 0;
            padding: 0;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .font-bold { font-weight: bold; }
        .text-sm { font-size: 10px; }
        .text-lg { font-size: 16px; }
        .text-xl { font-size: 20px; }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .divider-solid {
            border-top: 1px solid #000;
            margin: 5px 0;
        }

        .mb-2 { margin-bottom: 8px; }
        .mt-2 { margin-top: 8px; }
        .my-2 { margin: 8px 0; }
        .mt-4 { margin-top: 16px; }

        /* Flex Utilities */
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .items-center { align-items: center; }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            vertical-align: top;
            padding: 2px 0;
        }
        .td-qty { width: 15%; }
        .td-item { width: 50%; }
        .td-price { width: 35%; text-align: right; }

        /* Print Media Query */
        @media print {
            body { background: transparent; display: block; }
            .receipt-container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            .no-print { display: none !important; }
        }

        /* Screen Only (Preview Styling) */
        @media screen {
            body { background: #f3f4f6; padding: 20px; }
            .receipt-container {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                border-radius: 4px;
            }
            .print-btn {
                display: block;
                width: 100%;
                max-width: 80mm;
                margin: 0 auto 20px auto;
                background: #96A78D; /* brand-primary */
                color: #fff;
                border: none;
                padding: 10px;
                border-radius: 8px;
                font-family: sans-serif;
                font-weight: bold;
                cursor: pointer;
                text-align: center;
            }
            .print-btn:hover { background: #7c8e73; }
        }
    </style>
</head>
<body>

    <div>
        <button onclick="window.print()" class="print-btn no-print">
            Cetak Struk (Thermal)
        </button>

        <div class="receipt-container">
            
            <!-- Header -->
            <div class="text-center mb-2">
                <h2 class="text-xl font-bold">SINERGI</h2>
                <p class="text-sm">ATK & Jasa Fotocopy</p>
                <p class="text-sm">Jl. Raya Sukamaju No. 123</p>
                <p class="text-sm">Telp: 0812-3456-7890</p>
            </div>

            <div class="divider"></div>

            <!-- Order Info -->
            <div class="mb-2 text-sm">
                <div class="flex justify-between">
                    <span>No:</span>
                    <span>{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tgl:</span>
                    <span>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir:</span>
                    <span>{{ $order->employee ? $order->employee->name : 'Sistem' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pelanggan:</span>
                    <span>{{ $order->user->name }}</span>
                </div>
            </div>

            <!-- Queue Number -->
            <div class="divider-solid"></div>
            <div class="text-center my-2">
                <p class="text-sm">Nomor Antrean</p>
                <h1 style="font-size: 32px; margin: 4px 0;">{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}</h1>
            </div>
            <div class="divider-solid"></div>

            <!-- Items -->
            <div class="mt-2 mb-2">
                <table>
                    @foreach($order->items as $item)
                        @php
                            $itemName = $item->product_brand_id ? $item->productBrand->product->name : $item->service->name;
                        @endphp
                        <tr>
                            <td colspan="3" class="font-bold">{{ $itemName }}</td>
                        </tr>
                        <tr>
                            <td class="td-qty">{{ $item->quantity }}x</td>
                            <td class="td-item">@ {{ number_format($item->price_per_unit, 0, ',', '.') }}</td>
                            <td class="td-price">{{ number_format($item->subtotal_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="divider"></div>

            <!-- Totals -->
            <div class="mt-2 mb-2">
                <div class="flex justify-between font-bold text-lg">
                    <span>TOTAL</span>
                    <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mt-2">
                    <span>Status:</span>
                    <span>{{ strtoupper($order->status) }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Footer -->
            <div class="text-center mt-4 mb-2">
                <p class="font-bold">Terima Kasih</p>
                <p class="text-sm">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
            </div>

            <!-- Optional Barcode Area -->
            <div class="text-center mt-4">
                <p class="text-sm">-- {{ $order->id }} --</p>
            </div>

        </div>
    </div>

    <!-- Auto print on load -->
    <script>
        window.onload = function() {
            // Uncomment line below to auto-print when page loads
            // window.print();
        }
    </script>
</body>
</html>
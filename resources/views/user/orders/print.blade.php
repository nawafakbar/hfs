<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->invoice_number }}</title>
    <!-- Kita pakai Bootstrap CDN agar styling tetap rapi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .invoice-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 40px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .invoice-header {
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            font-weight: 700;
            color: #198754; /* Warna hijau brand */
        }
        .invoice-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9em;
            color: #6c757d;
        }
        /* Style untuk menyembunyikan tombol saat di-print */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff;
            }
            .invoice-container {
                margin: 0;
                border: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        <!-- Tombol Cetak -->
        <div class="text-end mb-3 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                Cetak Halaman Ini
            </button>
        </div>

        <!-- Header -->
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <h1 class="mb-0">INVOICE</h1>
            <span class="fs-5">BGD Hydrofarm</span>
        </div>

        <!-- Info Order & Customer -->
        <div class="row mb-4">
            <div class="col-md-6">
                <strong>Ditagih Kepada:</strong><br>
                {{ $order->user->name }}<br>
                {{ $order->user->email }}<br>
                {{ $order->user->phone_number }}<br>
                {{ $order->shipping_address }}
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <strong>Invoice:</strong> #{{ $order->invoice_number }}<br>
                <strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y') }}<br>
                <strong>Status:</strong> <span class="text-uppercase fw-bold">{{ $order->status }}</span>
            </div>
        </div>

        <!-- Detail Item -->
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th scope="col">Nama Produk</th>
                    <th scope="col" class="text-end">Harga</th>
                    <th scope="col" class="text-center">Kuantitas</th>
                    <th scope="col" class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="fw-bold">

            {{-- Total Harga Barang --}}
            <tr>
                <td colspan="3" class="text-end">Total Harga Barang</td>
                <td class="text-end">
                    Rp {{ number_format($order->subtotal ?? ($order->total_amount - $order->shipping_cost), 0, ',', '.') }}
                </td>
            </tr>

            {{-- Ongkos Kirim --}}
            <tr>
                <td colspan="3" class="text-end">Ongkos Kirim</td>
                <td class="text-end">
                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                </td>
            </tr>

            <tr>
                <td colspan="4" class="p-1"></td>
            </tr>

            {{-- Total Bayar --}}
            <tr class="table-light">
                <td colspan="3" class="text-end">Total Bayar</td>
                <td class="text-end">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </td>
            </tr>

        </tfoot>

        </table>

        <!-- Footer -->
        <div class="invoice-footer">
            <p>Terima kasih telah berbelanja di BGD Hydrofarm!</p>
        </div>
    </div>

    <!-- Script untuk memicu print dialog (opsional, karena ada tombol) -->
    {{-- <script>
        window.onload = function() {
            window.print();
        }
    </script> --}}
</body>
</html>
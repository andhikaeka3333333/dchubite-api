<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Kategori {{ $category_name }} - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tfoot td {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Laporan Kategori: {{ $category_name }}</h2>
    <p>Tanggal: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah Terjual</th>
                <th>Harga Jual / Item</th>
                <th>Subtotal</th>
                <th>Harga Modal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->total_quantity }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_cost, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td>{{ $totalQuantity }}</td>
                <td></td>
                <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;">Total Profit:</td>
                <td>Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <h4>Ringkasan:</h4>
    <ul>
        <li><strong>Kategori:</strong> {{ $category_name }}</li>
        <li><strong>Tanggal:</strong> {{ $date }}</li>
        <li><strong>Jumlah Produk Terjual:</strong> {{ $totalQuantity }}</li>
        <li><strong>Penjualan Kotor (Omzet):</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</li>
        <li><strong>Modal:</strong> Rp {{ number_format($totalCost, 0, ',', '.') }}</li>
        <li><strong>Penjualan Bersih (Laba):</strong> Rp {{ number_format($totalProfit, 0, ',', '.') }}</li>
    </ul>

</body>

</html>

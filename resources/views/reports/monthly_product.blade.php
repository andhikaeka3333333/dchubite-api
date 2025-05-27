<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produk Bulanan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Produk Bulanan</h2>
    <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga Jual</th>
                <th>Jumlah Terjual</th>
                <th>Total Pendapatan</th>
                <th>Total Modal</th>
                <th>Laba</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($items as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->total_quantity }}</td>
                    <td>Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_cost, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_revenue - $item->total_cost, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">TOTAL</th>
                <th>{{ $totalQuantity }}</th>
                <th>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                <th>Rp {{ number_format($totalCost, 0, ',', '.') }}</th>
                <th>Rp {{ number_format($totalProfit, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>

<h2>Laporan Penjualan Mingguan</h2>

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Penjualan Kotor (Omzet)</th>
            <th>Modal</th>
            <th>Penjualan Bersih (Laba)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reports as $report)
            <tr>
                <td>{{ $report->report_date }}</td>
                <td>Rp {{ number_format($report->total_revenue, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($report->total_cost, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($report->total_profit, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>
<h4>Total Keseluruhan Mingguan</h4>
<ul>
    <li><strong>Penjualan Kotor:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</li>
    <li><strong>Modal:</strong> Rp {{ number_format($totalCost, 0, ',', '.') }}</li>
    <li><strong>Penjualan Bersih:</strong> Rp {{ number_format($totalProfit, 0, ',', '.') }}</li>
</ul>

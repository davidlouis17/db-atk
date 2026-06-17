<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris Summary</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1a1d20; }
        .header p { margin: 5px 0; color: #666; }
        .filter-info { margin-bottom: 20px; font-size: 14px; }
        .stats-container { margin-bottom: 30px; }
        .stat-box { width: 18%; display: inline-block; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; text-align: center; margin-right: 1%; }
        .stat-label { font-size: 10px; text-transform: uppercase; color: #666; font-weight: bold; }
        .stat-value { font-size: 18px; font-weight: bold; color: #1a1d20; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #dee2e6; padding: 10px; text-align: left; font-size: 12px; }
        .table th { background-color: #f1f1f1; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

<div class="header">
    <h1>Gudang ATK - Laporan Inventaris</h1>
    <p>Ringkasan Pergerakan Stok dan Status Barang</p>
</div>

<div class="filter-info">
    <strong>Periode:</strong> {{ $filters['start_date'] }} s/d {{ $filters['end_date'] }}<br>
    <strong>Tipe Laporan:</strong> {{ ucfirst($filters['report_type']) }}<br>
    <strong>Tanggal Cetak:</strong> {{ date('d F Y H:i') }}
</div>

<div class="stats-container">
    <div class="stat-box">
        <div class="stat-label">Total Items</div>
        <div class="stat-value">{{ $totalItems }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Stock In</div>
        <div class="stat-value">{{ $stockIn }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Stock Out</div>
        <div class="stat-value">{{ $stockOut }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Low Stock</div>
        <div class="stat-value">{{ $lowStock }}</div>
    </div>
    <div class="stat-box">
        <div class="stat-label">Out of Stock</div>
        <div class="stat-value">{{ $outOfStock }}</div>
    </div>
</div>

<h3>Ringkasan Pergerakan (Chart Data)</h3>
<table class="table">
    <thead>
        <tr>
            <th>Label (Waktu)</th>
            <th>Stock In</th>
            <th>Stock Out</th>
        </tr>
    </thead>
    <tbody>
        @foreach($chartData['labels'] as $index => $label)
        <tr>
            <td>{{ $label }}</td>
            <td>{{ $chartData['stock_in'][$index] }}</td>
            <td>{{ $chartData['stock_out'][$index] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Dicetak secara otomatis oleh Sistem Inventaris ATK - {{ date('Y') }}
</div>

</body>
</html>

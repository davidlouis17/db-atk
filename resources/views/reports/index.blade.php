<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris - Stok ATK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { min-height: 100vh; background: #1a1d20; color: white; padding: 20px; }
        .nav-link { color: #adb5bd; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: white; background: #343a40; border-radius: 8px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card-stat { border-radius: 12px; border: none; }
        .filter-section { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; }
        .btn-export { border-radius: 8px; font-weight: 500; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar d-none d-md-block">
            <h4 class="text-center mb-4 py-3 border-bottom border-secondary">Gudang ATK</h4>
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="/admin/barangs" class="nav-link"><i class="bi bi-box-seam me-2"></i> Manajemen Barang</a>
                </li>
                <li class="nav-item">
                    <a href="/admin/riwayat-stoks" class="nav-link"><i class="bi bi-arrow-left-right me-2"></i> Transaksi Stok</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link active"><i class="bi bi-file-earmark-text me-2"></i> Laporan</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Laporan Inventaris</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('reports.export.csv', request()->all()) }}" class="btn btn-outline-success btn-export">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
                    </a>
                    <a href="{{ route('reports.export.pdf', request()->all()) }}" class="btn btn-danger btn-export">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="filter-section shadow-sm">
                <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $filters['start_date'] }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $filters['end_date'] }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Report Type</label>
                        <select name="report_type" class="form-select">
                            <option value="daily" {{ $filters['report_type'] == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $filters['report_type'] == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ $filters['report_type'] == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-gear-wide-connected me-1"></i> Generate Report
                        </button>
                    </div>
                </form>
                @if ($errors->any())
                    <div class="alert alert-danger mt-3 mb-0 py-2">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-2">
                    <div class="card card-stat bg-white p-3 border-start border-4 border-primary">
                        <div class="text-muted small fw-bold text-uppercase">Total Items</div>
                        <div class="fs-3 fw-bold">{{ $totalItems }}</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card card-stat bg-white p-3 border-start border-4 border-success">
                        <div class="text-muted small fw-bold text-uppercase">Stock In</div>
                        <div class="fs-3 fw-bold">{{ $stockIn }}</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card card-stat bg-white p-3 border-start border-4 border-info">
                        <div class="text-muted small fw-bold text-uppercase">Stock Out</div>
                        <div class="fs-3 fw-bold">{{ $stockOut }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat bg-white p-3 border-start border-4 border-warning">
                        <div class="text-muted small fw-bold text-uppercase">Low Stock</div>
                        <div class="fs-3 fw-bold">{{ $lowStock }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat bg-white p-3 border-start border-4 border-danger">
                        <div class="text-muted small fw-bold text-uppercase">Out of Stock</div>
                        <div class="fs-3 fw-bold">{{ $outOfStock }}</div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="card p-4 shadow-sm">
                <h5 class="fw-bold mb-4">Stock In vs Stock Out</h5>
                <div style="height: 400px;">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('stockChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Stock In',
                    data: {!! json_encode($chartData['stock_in']) !!},
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Stock Out',
                    data: {!! json_encode($chartData['stock_out']) !!},
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });
</script>

</body>
</html>

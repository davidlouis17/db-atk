<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Stok ATK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #212529; color: white; padding: 20px; }
        .card-stat { border: none; border-radius: 15px; transition: transform 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar d-none d-md-block">
            <h4 class="text-center mb-4">Gudang ATK</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white bg-primary rounded"><i class="bi bi-speedometer2"></i> Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white"><i class="bi bi-box-seam"></i> Manajemen Barang</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-white"><i class="bi bi-arrow-left-right"></i> Transaksi Stok</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('reports.index') }}" class="nav-link text-white"><i class="bi bi-file-earmark-text"></i> Laporan</a>
                </li>

            </ul>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Ringkasan Inventaris</h2>
                <span class="badge bg-light text-dark p-2 border">Admin: Richie/Daud</span>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card card-stat bg-primary text-white p-3 shadow-sm">
                        <div class="d-flex justify-content-between">
                            <div><h5>Total Jenis</h5><h3>150</h3></div>
                            <i class="bi bi-box fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat bg-success text-white p-3 shadow-sm">
                        <div class="d-flex justify-content-between">
                            <div><h5>Stok Aman</h5><h3>132</h3></div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat bg-warning text-dark p-3 shadow-sm">
                        <div class="d-flex justify-content-between">
                            <div><h5>Stok Menipis</h5><h3>12</h3></div>
                            <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stat bg-danger text-white p-3 shadow-sm">
                        <div class="d-flex justify-content-between">
                            <div><h5>Stok Habis</h5><h3>6</h3></div>
                            <i class="bi bi-x-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 card p-4 shadow-sm">
                <h5 class="mb-4">Daftar Barang Perlu Perhatian</h5>
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok Sisa</th>
                            <th>Batas Min</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Kertas A4 80gr</td>
                            <td>Kertas</td>
                            <td><span class="text-danger fw-bold">5</span></td>
                            <td>20</td>
                            <td><span class="badge bg-warning text-dark">Menipis</span></td>
                            <td><button class="btn btn-sm btn-outline-primary">Restock</button></td>
                        </tr>
                        <tr>
                            <td>Tinta Printer HP Black</td>
                            <td>Tinta</td>
                            <td><span class="text-danger fw-bold">0</span></td>
                            <td>5</td>
                            <td><span class="badge bg-danger">Habis</span></td>
                            <td><button class="btn btn-sm btn-outline-primary">Restock</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
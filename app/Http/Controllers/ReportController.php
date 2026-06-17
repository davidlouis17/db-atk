<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Ambil nilai filter dari request dengan default bulan ini.
     */
    private function getFilters(Request $request): array
    {
        return [
            'start_date'  => $request->get('start_date', Carbon::now()->startOfMonth()->toDateString()),
            'end_date'    => $request->get('end_date', Carbon::now()->endOfMonth()->toDateString()),
            'report_type' => $request->get('report_type', 'daily'),
        ];
    }

    /**
     * Hitung statistik & data chart berdasarkan filter.
     */
    private function buildReportData(array $filters): array
    {
        $startDate = $filters['start_date'] . ' 00:00:00';
        $endDate   = $filters['end_date']   . ' 23:59:59';

        $totalItems = Barang::count();

        $stockIn = RiwayatStok::where('jenis', 'masuk')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('jumlah');

        $stockOut = RiwayatStok::where('jenis', 'keluar')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('jumlah');

        $lowStock   = Barang::whereColumn('stok', '<', 'batas_minimum')->where('stok', '>', 0)->count();
        $outOfStock = Barang::where('stok', 0)->count();

        // ---- Grouping berdasarkan report_type ----
        $groupExpr = match ($filters['report_type']) {
            'weekly'  => "YEARWEEK(created_at, 1)",
            'monthly' => "DATE_FORMAT(created_at, '%Y-%m')",
            default   => "DATE(created_at)",           // daily
        };

        $movements = RiwayatStok::select([
                DB::raw("$groupExpr AS label"),
                DB::raw("SUM(CASE WHEN jenis = 'masuk'  THEN jumlah ELSE 0 END) AS masuk"),
                DB::raw("SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) AS keluar"),
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw($groupExpr)
            ->orderByRaw($groupExpr)
            ->get();

        $chartData = [
            'labels'    => $movements->pluck('label')->values()->toArray(),
            'stock_in'  => $movements->pluck('masuk')->values()->toArray(),
            'stock_out' => $movements->pluck('keluar')->values()->toArray(),
        ];

        if (empty($chartData['labels'])) {
            $chartData = ['labels' => ['Tidak ada data'], 'stock_in' => [0], 'stock_out' => [0]];
        }

        return compact('totalItems', 'stockIn', 'stockOut', 'lowStock', 'outOfStock', 'chartData');
    }

    // =========================================================
    // Halaman utama laporan (opsional, standalone tanpa Filament)
    // =========================================================
    public function index(Request $request)
    {
        $request->validate([
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'report_type' => 'nullable|in:daily,weekly,monthly',
        ]);

        $filters = $this->getFilters($request);
        $data    = $this->buildReportData($filters);

        return view('reports.index', array_merge($data, ['filters' => $filters]));
    }

    // =========================================================
    // Export CSV
    // =========================================================
    public function exportCsv(Request $request)
    {
        $filters = $this->getFilters($request);

        $startDate = $filters['start_date'] . ' 00:00:00';
        $endDate   = $filters['end_date']   . ' 23:59:59';

        // Semua barang + agregasi riwayat sesuai filter
        $barangs = Barang::select('id', 'nama_barang', 'kategori', 'stok')
            ->with(['riwayat_stoks' => function ($q) use ($startDate, $endDate) {
                $q->select('barang_id', 'jenis', 'jumlah')
                  ->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get();

        $filename = 'laporan_inventaris_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($barangs, $filters) {
            $handle = fopen('php://output', 'w');

            // BOM agar Excel membaca UTF-8 dengan benar
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header info
            fputcsv($handle, ['Laporan Inventaris ATK']);
            fputcsv($handle, ['Periode', $filters['start_date'] . ' s/d ' . $filters['end_date']]);
            fputcsv($handle, ['Tipe', ucfirst($filters['report_type'])]);
            fputcsv($handle, ['Dibuat', now()->format('d-m-Y H:i')]);
            fputcsv($handle, []); // baris kosong

            // Kolom
            fputcsv($handle, ['Nama Barang', 'Kategori', 'Stok Masuk', 'Stok Keluar', 'Stok Sekarang']);

            foreach ($barangs as $barang) {
                $masuk  = $barang->riwayat_stoks->where('jenis', 'masuk')->sum('jumlah');
                $keluar = $barang->riwayat_stoks->where('jenis', 'keluar')->sum('jumlah');

                fputcsv($handle, [
                    $barang->nama_barang,
                    $barang->kategori,
                    $masuk,
                    $keluar,
                    $barang->stok,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    // =========================================================
    // Export PDF
    // =========================================================
    public function exportPdf(Request $request)
    {
        $filters  = $this->getFilters($request);
        $data     = $this->buildReportData($filters);

        $pdf = Pdf::loadView('reports.pdf', array_merge($data, ['filters' => $filters]));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'laporan_summary_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}

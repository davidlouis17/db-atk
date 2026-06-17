<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Barang;
use App\Models\RiwayatStok;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Report extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected string $view = 'filament.pages.report';

    protected static ?string $title = 'Laporan Inventaris';

    protected static ?string $navigationLabel = 'Laporan';
    protected static ?int $navigationSort = 4;

    public $start_date;
    public $end_date;
    public $report_type = 'daily';

    public function mount()
    {
        $this->start_date = request('start_date', Carbon::now()->startOfMonth()->toDateString());
        $this->end_date = request('end_date', Carbon::now()->endOfMonth()->toDateString());
        $this->report_type = request('report_type', 'daily');
    }

    public function updateReport()
    {
        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|in:daily,weekly,monthly',
        ]);

        return redirect()->to(route('filament.admin.pages.report', [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'report_type' => $this->report_type,
        ]));
    }

    protected function getViewData(): array
    {
        $startDate = $this->start_date . ' 00:00:00';
        $endDate = $this->end_date . ' 23:59:59';

        // Statistics
        $totalItems = Barang::count();
        $stockIn = RiwayatStok::where('jenis', 'masuk')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('jumlah');
        $stockOut = RiwayatStok::where('jenis', 'keluar')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('jumlah');
        $lowStock = Barang::whereColumn('stok', '<', 'batas_minimum')
            ->where('stok', '>', 0)
            ->count();
        $outOfStock = Barang::where('stok', 0)->count();

        // Chart Data
        $groupBy = match ($this->report_type) {
            'daily' => "DATE(created_at)",
            'weekly' => "YEARWEEK(created_at)",
            'monthly' => "DATE_FORMAT(created_at, '%Y-%m')",
        };

        $movements = RiwayatStok::select([
                DB::raw("$groupBy as label"),
                DB::raw("SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END) as masuk"),
                DB::raw("SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) as keluar"),
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $chartData = [
            'labels' => $movements->pluck('label')->toArray(),
            'stock_in' => $movements->pluck('masuk')->toArray(),
            'stock_out' => $movements->pluck('keluar')->toArray(),
        ];

        if (empty($chartData['labels'])) {
            $chartData = ['labels' => ['No Data'], 'stock_in' => [0], 'stock_out' => [0]];
        }

        return [
            'totalItems' => $totalItems,
            'stockIn' => $stockIn,
            'stockOut' => $stockOut,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'chartData' => $chartData,
            'filters' => [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'report_type' => $this->report_type,
            ]
        ];
    }
}

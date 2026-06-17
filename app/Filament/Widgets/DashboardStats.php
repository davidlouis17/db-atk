<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalBarang = \App\Models\Barang::count();
        $stokTersedia = \App\Models\Barang::sum('stok');
        
        $barangMenipis = \App\Models\Barang::whereColumn('stok', '<', 'batas_minimum')
                                            ->where('stok', '>', 0)
                                            ->count();

        $barangHabis = \App\Models\Barang::where('stok', '<=', 0)->count();

        return [
            Stat::make('Total Barang', $totalBarang)
                ->description('Jumlah macam barang yang terdaftar')
                ->icon('heroicon-o-cube'),
            Stat::make('Total Stok', $stokTersedia)
                ->description('Total unit barang tersedia')
                ->icon('heroicon-o-clipboard-document-list'),
            Stat::make('Stok Menipis', $barangMenipis)
                ->description('Segera lakukan restock')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('warning'),
            Stat::make('Stok Habis', $barangHabis)
                ->description('Barang tidak tersedia')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}

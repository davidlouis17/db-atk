<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Filters --}}
        <x-filament::card>
            <form wire:submit.prevent="updateReport">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                        <input type="date" wire:model="start_date" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                        <input type="date" wire:model="end_date" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Report Type</label>
                        <select wire:model="report_type"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-primary-500 focus:border-primary-500 text-sm px-3 py-2">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
                            Generate Report
                        </button>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="mt-3 text-sm text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </x-filament::card>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <x-filament::card>
                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Total Items</p>
                <p class="text-3xl font-bold mt-1">{{ $totalItems }}</p>
            </x-filament::card>
            <x-filament::card>
                <p class="text-xs font-bold uppercase text-green-600">Stock In</p>
                <p class="text-3xl font-bold mt-1 text-green-600">{{ $stockIn }}</p>
            </x-filament::card>
            <x-filament::card>
                <p class="text-xs font-bold uppercase text-blue-600">Stock Out</p>
                <p class="text-3xl font-bold mt-1 text-blue-600">{{ $stockOut }}</p>
            </x-filament::card>
            <x-filament::card>
                <p class="text-xs font-bold uppercase text-yellow-600">Low Stock</p>
                <p class="text-3xl font-bold mt-1 text-yellow-600">{{ $lowStock }}</p>
            </x-filament::card>
            <x-filament::card>
                <p class="text-xs font-bold uppercase text-red-600">Out of Stock</p>
                <p class="text-3xl font-bold mt-1 text-red-600">{{ $outOfStock }}</p>
            </x-filament::card>
        </div>

        {{-- Export Buttons --}}
        <div class="flex justify-end gap-3">
            <a href="/reports/export/csv?start_date={{ $start_date }}&end_date={{ $end_date }}&report_type={{ $report_type }}"
               target="_blank"
               style="display:inline-flex;align-items:center;gap:6px;background:#16a34a;color:white;font-size:14px;font-weight:500;border-radius:8px;padding:8px 16px;text-decoration:none;">
                ⬇ Export CSV
            </a>
            <a href="/reports/export/pdf?start_date={{ $start_date }}&end_date={{ $end_date }}&report_type={{ $report_type }}"
               target="_blank"
               style="display:inline-flex;align-items:center;gap:6px;background:#dc2626;color:white;font-size:14px;font-weight:500;border-radius:8px;padding:8px 16px;text-decoration:none;">
                📄 Export PDF
            </a>
        </div>

        {{-- Chart --}}
        <x-filament::card>
            <h3 class="text-lg font-bold mb-4">Stock In vs Stock Out</h3>
            <div style="position: relative; height: 350px;">
                <canvas id="stockChart"></canvas>
            </div>
        </x-filament::card>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            renderChart();
        });
        document.addEventListener('livewire:navigated', function () {
            renderChart();
        });

        function renderChart() {
            const existing = Chart.getChart('stockChart');
            if (existing) existing.destroy();

            const ctx = document.getElementById('stockChart');
            if (!ctx) return;

            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [
                        {
                            label: 'Stock In',
                            data: {!! json_encode($chartData['stock_in']) !!},
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22,163,74,0.12)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#16a34a',
                        },
                        {
                            label: 'Stock Out',
                            data: {!! json_encode($chartData['stock_out']) !!},
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37,99,235,0.10)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#2563eb',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(156,163,175,0.15)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    </script>
</x-filament-panels::page>

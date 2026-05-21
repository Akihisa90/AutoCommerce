<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use App\Models\Kategori;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProdukChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Produk per Kategori';
    protected static ?int $sort = 0;

    protected function getData(): array
    {
        $data = Kategori::withCount('produk')->get();

        $labels = $data->pluck('nama')->toArray();
        $counts = $data->pluck('produk_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Produk',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6',
                        '#ef4444', '#06b6d4', '#f97316', '#ec4899',
                        '#84cc16', '#6366f1',
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#fff',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

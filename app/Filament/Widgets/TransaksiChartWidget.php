<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TransaksiChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Transaksi';
    protected static ?int $sort = -1;

    protected function getData(): array
    {
        $data = Transaksi::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as revenue')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $data->pluck('date')->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
        $counts = $data->pluck('count')->toArray();
        $revenue = $data->pluck('revenue')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $counts,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Pendapatan (x Rp 1000)',
                    'data' => array_map(fn ($r) => round($r / 1000, 0), $revenue),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

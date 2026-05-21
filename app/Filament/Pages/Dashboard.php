<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TransaksiChartWidget;
use App\Filament\Widgets\ProdukChartWidget;
use App\Filament\Widgets\TransaksiTerbaruWidget;
use App\Filament\Widgets\StokMenipisWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            TransaksiChartWidget::class,
            ProdukChartWidget::class,
            TransaksiTerbaruWidget::class,
            StokMenipisWidget::class,
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $totalPendapatan = Transaksi::where('status', 'selesai')->sum('total');
        $transaksiBulanIni = Transaksi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $transaksiSebelumnya = Transaksi::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $pendapatanBulanIni = Transaksi::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'selesai')
            ->sum('total');
        $pendapatanSebelumnya = Transaksi::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('status', 'selesai')
            ->sum('total');

        $pendingCount = Transaksi::where('status', 'pending')->count();
        $pendingSebelumnya = Transaksi::where('status', 'pending')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Bulan ini: Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'))
                ->descriptionIcon($pendapatanBulanIni >= $pendapatanSebelumnya ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($pendapatanBulanIni >= $pendapatanSebelumnya ? 'success' : 'danger'),
            Stat::make('Total Transaksi', Transaksi::count())
                ->description($transaksiBulanIni . ' bulan ini')
                ->descriptionIcon($transaksiBulanIni >= $transaksiSebelumnya ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($transaksiBulanIni >= $transaksiSebelumnya ? 'success' : 'danger'),
            Stat::make('Menunggu Pembayaran', $pendingCount)
                ->description('Perlu diproses')
                ->color('warning'),
            Stat::make('Total Produk', Produk::count())
                ->description('Stok tersedia: ' . Produk::sum('stok'))
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
            Stat::make('Total Pengguna', User::count())
                ->description(User::whereMonth('created_at', now()->month)->count() . ' baru bulan ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Kategori', Kategori::count())
                ->description('Kategori produk')
                ->descriptionIcon('heroicon-m-tag')
                ->color('gray'),
        ];
    }
}

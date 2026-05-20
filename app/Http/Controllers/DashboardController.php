<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $produkTerbaru = Produk::with('kategori')
            ->where('stok', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $bestSeller = Produk::with('kategori')
            ->where('stok', '>', 0)
            ->orderBy('terjual', 'desc')
            ->limit(6)
            ->get();

        $flashSale = Produk::where('stok', '>', 0)
            ->whereNotNull('harga_diskon')
            ->where('harga_diskon', '<', \DB::raw('harga'))
            ->limit(4)
            ->get();

        $kategori = Kategori::with(['produk' => function ($q) {
                $q->whereNotNull('gambar')->inRandomOrder()->limit(1);
            }])
            ->withCount('produk')
            ->get();

        $produkDiskon = Produk::where('stok', '>', 0)
            ->whereNotNull('harga_diskon')
            ->where('harga_diskon', '<', \DB::raw('harga'))
            ->orderBy('harga_diskon', 'asc')
            ->limit(4)
            ->get();

        // User's recent orders if any
        $pesananTerbaru = null;
        if ($user) {
            $pesananTerbaru = Transaksi::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        }

        return view('dashboard.index', compact(
            'produkTerbaru', 'bestSeller', 'flashSale',
            'kategori', 'produkDiskon', 'pesananTerbaru'
        ));
    }
}

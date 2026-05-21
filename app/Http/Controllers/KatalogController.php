<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                    ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter rentang harga
        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        // Filter brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter warna
        if ($request->filled('warna')) {
            $query->where('warna', $request->warna);
        }

        // Filter rating minimum
        if ($request->filled('rating_min')) {
            $query->where('rating', '>=', $request->rating_min);
        }

        // Filter hanya stok tersedia
        if ($request->filled('stok_tersedia')) {
            $query->where('stok', '>', 0);
        }

        // Filter gratis ongkir
        if ($request->filled('gratis_ongkir')) {
            $query->where('gratis_ongkir', true);
        }

        // Filter same day delivery
        if ($request->filled('same_day')) {
            $query->where('same_day_delivery', true);
        }

        // Filter label khusus
        if ($request->filled('label')) {
            $query->where('label', $request->label);
        }

        // Sorting
        $sort = $request->get('sort', 'relevansi');
        switch ($sort) {
            case 'harga_terendah':
                $query->orderBy('harga', 'asc');
                break;
            case 'harga_tertinggi':
                $query->orderBy('harga', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc')->orderBy('review_count', 'desc');
                break;
            case 'terbaru':
                $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'terlaris':
                $query->orderBy('terjual', 'desc');
                break;
            case 'review':
                $query->orderBy('review_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 12);
        $produks = $query->paginate($perPage)->withQueryString();

        $kategori = Kategori::with(['produk' => function ($q) {
            $q->whereNotNull('gambar')->inRandomOrder()->limit(1);
        }])->get();

        // Ambil semua brand unik untuk filter
        $brands = Produk::whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        // Ambil semua warna unik untuk filter
        $warnaOptions = Produk::whereNotNull('warna')
            ->where('warna', '!=', '')
            ->distinct()
            ->orderBy('warna')
            ->pluck('warna');

        // Produk untuk rekomendasi (best seller)
        $rekomendasi = Produk::where('stok', '>', 0)
            ->orderBy('terjual', 'desc')
            ->limit(6)
            ->get();

        // Banner flash sale products
        $flashSale = Produk::where('label', 'flash_sale')
            ->where('stok', '>', 0)
            ->orderBy('harga_diskon', 'asc')
            ->limit(4)
            ->get();

        return view('katalog.index', compact(
            'produks', 'kategori', 'brands', 'warnaOptions',
            'rekomendasi', 'flashSale'
        ));
    }

    public function show(Produk $produk)
    {
        $produk->increment('dilihat');
        $produk->load(['reviews.user']);

        $produkTerkait = Produk::where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $produk->id)
            ->where('stok', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('katalog.show', compact('produk', 'produkTerkait'));
    }
}

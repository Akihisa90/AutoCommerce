<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Keranjang;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    public function tambah(Produk $produk, Request $request)
    {
        $jumlah = $request->input('jumlah', 1);

        // Validasi jumlah tidak melebihi stok
        if ($jumlah > $produk->stok) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $item = auth()->user()->keranjang()->where('produk_id', $produk->id)->first();
        if ($item) {
            $newQty = $item->jumlah + $jumlah;
            if ($newQty > $produk->stok) {
                return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $produk->stok);
            }
            $item->increment('jumlah', $jumlah);
        } else {
            auth()->user()->keranjang()->create(['produk_id' => $produk->id, 'jumlah' => $jumlah]);
        }
        return back()->with('success', 'Produk ditambahkan.');
    }

    public function index()
    {
        $items = auth()->user()->keranjang()->with('produk')->get();
        $total = $items->sum(fn($i) => $i->produk->harga * $i->jumlah);
        return view('keranjang.index', compact('items', 'total'));
    }
    public function update(Keranjang $keranjang, Request $request)
    {
        abort_if($keranjang->user_id !== auth()->id(), 403);
        $request->validate(['jumlah' => 'required|integer|min:1']);
        $keranjang->update(['jumlah' => $request->jumlah]);
        return back()->with('success', 'Keranjang berhasil diupdate');
    }

    public function hapus(Keranjang $keranjang)
    {
        abort_if($keranjang->user_id !== auth()->id(), 403);
        $keranjang->delete();
        return back()->with('success', 'Produk dihapus dari keranjang');
    }
}

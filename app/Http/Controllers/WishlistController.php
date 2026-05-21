<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlists()->with('produk.kategori')->paginate(12);
        return view('katalog.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request, Produk $produk)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('produk_id', $produk->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'success' => true,
                'in_wishlist' => false,
                'message' => 'Dihapus dari wishlist'
            ]);
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'produk_id' => $produk->id
            ]);
            return response()->json([
                'success' => true,
                'in_wishlist' => true,
                'message' => 'Ditambahkan ke wishlist'
            ]);
        }
    }

    public function remove(Produk $produk)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('produk_id', $produk->id)
            ->delete();

        return redirect()->back()->with('success', 'Dihapus dari wishlist');
    }

    public function count()
    {
        if (!auth()->check()) {
            return response()->json(['count' => 0]);
        }

        $count = auth()->user()->wishlists()->count();
        return response()->json(['count' => $count]);
    }
}

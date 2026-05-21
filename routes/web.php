<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\{KatalogController, KeranjangController, CheckoutController, PesananController, WishlistController, DashboardController};

/*
| 1. ROUTE PUBLIK (Bisa diakses tanpa login)
*/
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('katalog');
});

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
Route::get('/produk/{produk}', [KatalogController::class, 'show'])->name('produk.show');

// Serve storage files (fallback for when symlink doesn't work)
Route::get('/storage/{path}', function ($path) {
    $disk = Storage::disk('public');
    if ($disk->exists($path)) {
        return response()->file($disk->path($path), [
            'Content-Type' => $disk->mimeType($path),
        ]);
    }
    abort(404);
})->where('path', '.*');

/*
| 2. ROUTE USER (Membutuhkan Login)
*/
Route::middleware('auth')->group(function () {

    // --- Dashboard ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Keranjang Belanja ---
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/{produk}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::delete('/keranjang/{keranjang}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::patch('/keranjang/{keranjang}', [KeranjangController::class, 'update'])->name('keranjang.update');

    // --- Checkout ---
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/validate-voucher', [CheckoutController::class, 'validateVoucher'])->name('checkout.validate-voucher');

    // --- Pesanan Saya ---
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{pesanan}/cancel', [PesananController::class, 'cancel'])->name('pesanan.cancel');
    Route::post('/pesanan/{pesanan}/review', [PesananController::class, 'submitReview'])->name('pesanan.review');
    Route::post('/pesanan/{pesanan}/upload-bukti', [PesananController::class, 'uploadBukti'])->name('pesanan.upload.bukti');

    // --- Profil User ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Wishlist ---
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{produk}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{produk}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
});


require __DIR__ . '/auth.php';

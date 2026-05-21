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

// Serve storage files via /files/ route (works on Laravel Cloud where symlink may not work)
Route::get('/files/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        $dir = dirname($fullPath);
        $debug = [
            'requested_path' => $fullPath,
            'dir_exists' => is_dir($dir),
            'dir_contents' => is_dir($dir) ? array_values(scandir($dir)) : [],
            'storage_app_exists' => is_dir(storage_path('app')),
            'storage_app_public_exists' => is_dir(storage_path('app/public')),
        ];
        return response()->json(['error' => 'File not found', 'debug' => $debug], 404);
    }
    
    return response()->file($fullPath, [
        'Content-Type' => mime_content_type($fullPath),
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*');

// Redirect old /storage/ URLs to /files/ for backward compatibility
Route::get('/storage/{path}', function ($path) {
    return redirect('/files/' . $path, 301);
})->where('path', '.*');

// Debug route for storage (remove after debugging)
Route::get('/storage-debug', function () {
    $link = public_path('storage');
    return response()->json([
        'symlink_is_link' => is_link($link),
        'symlink_target' => is_link($link) ? readlink($link) : null,
        'symlink_exists' => file_exists($link),
        'storage_app_public_exists' => is_dir(storage_path('app/public')),
        'storage_produk_exists' => is_dir(storage_path('app/public/produk')),
        'storage_produk_files' => is_dir(storage_path('app/public/produk')) ? array_values(scandir(storage_path('app/public/produk'))) : [],
        'storage_app_public_files' => is_dir(storage_path('app/public')) ? array_values(scandir(storage_path('app/public'))) : [],
    ]);
});

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

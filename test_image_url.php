<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

$produk = Produk::first();
echo "=== Image URL Test ===\n";
echo "Gambar column: " . $produk->gambar . "\n";
echo "Storage URL: " . Storage::disk('public')->url($produk->gambar) . "\n";
echo "Direct path: " . public_path('storage/' . $produk->gambar) . "\n";
echo "File exists: " . (file_exists(public_path('storage/' . $produk->gambar)) ? 'YES' : 'NO') . "\n";
echo "File size: " . (file_exists(public_path('storage/' . $produk->gambar)) ? filesize(public_path('storage/' . $produk->gambar)) : 'N/A') . " bytes\n";

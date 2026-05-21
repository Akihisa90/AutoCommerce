<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Voucher;
use App\Models\ProdukReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = auth()->user()->transaksi()
            ->with('detailTransaksi')
            ->latest()
            ->get();
        return view('pesanan.index', compact('pesanan'));
    }

    public function show(Transaksi $pesanan)
    {
        abort_if($pesanan->user_id !== auth()->id(), 403);
        $pesanan->load('detailTransaksi.produk.reviews');
        return view('pesanan.show', compact('pesanan'));
    }

    public function cancel(Transaksi $pesanan)
    {
        abort_if($pesanan->user_id !== auth()->id(), 403);

        if ($pesanan->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan dengan status pending yang dapat dibatalkan.');
        }

        DB::transaction(function () use ($pesanan) {
            foreach ($pesanan->detailTransaksi as $detail) {
                $detail->produk->increment('stok', $detail->jumlah);
            }

            if ($pesanan->voucher_id) {
                $voucher = Voucher::find($pesanan->voucher_id);
                if ($voucher && $voucher->jumlah_dipakai > 0) {
                    $voucher->decrement('jumlah_dipakai');
                }
            }

            $pesanan->update(['status' => 'dibatalkan']);
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function submitReview(Request $request, Transaksi $pesanan)
    {
        abort_if($pesanan->user_id !== auth()->id(), 403);

        if ($pesanan->status !== 'selesai') {
            return back()->with('error', 'Hanya pesanan yang sudah selesai yang dapat diberi rating.');
        }

        $request->validate([
            'detail_transaksi_id' => 'required|exists:detail_transaksi,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $detail = $pesanan->detailTransaksi()->where('id', $request->detail_transaksi_id)->first();
        if (!$detail) {
            return back()->with('error', 'Produk tidak ditemukan dalam pesanan ini.');
        }

        $existingReview = ProdukReview::where('user_id', auth()->id())
            ->where('produk_id', $detail->produk_id)
            ->where('transaksi_id', $pesanan->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan rating untuk produk ini.');
        }

        ProdukReview::create([
            'user_id' => auth()->id(),
            'produk_id' => $detail->produk_id,
            'transaksi_id' => $pesanan->id,
            'detail_transaksi_id' => $detail->id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        $detail->produk->updateRating();

        return back()->with('success', 'Rating dan ulasan berhasil ditambahkan!');
    }

    public function uploadBukti(Request $request, Transaksi $pesanan)
    {
        abort_if($pesanan->user_id !== auth()->id(), 403);

        $request->validate([
            'bukti_pembayaran' => 'required|image|max:2048',
        ]);

        if ($pesanan->status !== 'pending') {
            return back()->with('error', 'Pesanan sudah diproses atau dibatalkan.');
        }

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', config('filesystems.cloud', 'public'));
        $pesanan->update(['bukti_pembayaran' => $path]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }
}

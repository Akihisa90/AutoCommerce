<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\PengaturanPembayaran;
use App\Models\Voucher;

class CheckoutController extends Controller
{
    public function create()
    {
        $items = auth()->user()->keranjang()->with('produk')->get();
        $subtotal = $items->sum('subtotal');

        $qrisQrImage = PengaturanPembayaran::get('qris', 'qr_image', '');
        $qrisDescription = PengaturanPembayaran::get('qris', 'description', 'Scan QR Code di bawah untuk pembayaran');

        $bankAccounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $name = PengaturanPembayaran::get('transfer_bank', "bank_{$i}_name");
            $number = PengaturanPembayaran::get('transfer_bank', "bank_{$i}_number");
            $holder = PengaturanPembayaran::get('transfer_bank', "bank_{$i}_holder");
            if ($name && $number && $holder) {
                $bankAccounts[] = [
                    'bank_name' => $name,
                    'account_number' => $number,
                    'account_holder' => $holder,
                ];
            }
        }

        $danaPhone = PengaturanPembayaran::get('dana', 'phone_number', '');
        $danaName = PengaturanPembayaran::get('dana', 'account_name', '');
        $danaQrImage = PengaturanPembayaran::get('dana', 'qr_image', '');

        return view('checkout.create', compact(
            'items', 'subtotal',
            'qrisQrImage', 'qrisDescription',
            'bankAccounts',
            'danaPhone', 'danaName', 'danaQrImage'
        ));
    }

    public function validateVoucher(Request $request)
    {
        $request->validate([
            'kode_voucher' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('kode', strtoupper($request->kode_voucher))->first();
        $userId = auth()->id();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak ditemukan.'
            ]);
        }

        if (!$voucher->isValid()) {
            $status = $voucher->getStatusBadge();
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak berlaku. Status: ' . $status['label']
            ]);
        }

        if (!$voucher->canApply($request->subtotal, $userId)) {
            if ($userId && $voucher->max_penggunaan_per_user > 0) {
                $userUsage = $voucher->getUserUsageCount($userId);
                if ($userUsage >= $voucher->max_penggunaan_per_user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah menggunakan voucher ini sebanyak ' . $userUsage . ' kali. Maksimal: ' . $voucher->max_penggunaan_per_user . ' kali.'
                    ]);
                }
            }
            return response()->json([
                'success' => false,
                'message' => 'Minimum pembelian untuk voucher ini adalah Rp ' . number_format($voucher->min_pembelian, 0, ',', '.')
            ]);
        }

        $discount = $voucher->calculateDiscount($request->subtotal);
        $remainingUserQuota = $voucher->getRemainingUserQuota($userId);

        return response()->json([
            'success' => true,
            'voucher_id' => $voucher->id,
            'kode' => $voucher->kode,
            'nama' => $voucher->nama,
            'tipe_diskon' => $voucher->tipe_diskon,
            'nilai_diskon' => $voucher->nilai_diskon,
            'max_diskon' => $voucher->max_diskon,
            'diskon' => $discount,
            'remaining_user_quota' => $remainingUserQuota,
            'message' => 'Voucher berhasil diterapkan!'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|in:cod,qris,dana,transfer_bank',
            'sub_tipe_pembayaran' => 'nullable|in:nomor_hp,qr_code',
            'bukti_pembayaran' => 'nullable|image|max:2048',
            'voucher_id' => 'nullable|exists:vouchers,id',
        ]);

        $items = auth()->user()->keranjang()->with('produk')->get();
        if ($items->isEmpty())
            return back()->with('error', 'Keranjang kosong.');

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        DB::transaction(function () use ($request, $items, $buktiPath) {
            $subtotal = $items->sum(fn($i) => $i->produk->harga * $i->jumlah);
            $diskonVoucher = 0;
            $voucherId = null;
            $userId = auth()->id();

            if ($request->voucher_id) {
                $voucher = Voucher::find($request->voucher_id);
                if ($voucher && $voucher->canApply($subtotal, $userId)) {
                    $diskonVoucher = $voucher->calculateDiscount($subtotal);
                    $voucherId = $voucher->id;
                    $voucher->increment('jumlah_dipakai');
                }
            }

            $totalBayar = max(0, $subtotal - $diskonVoucher);

            $subTipe = $request->metode_pembayaran === 'dana'
                ? ($request->sub_tipe_pembayaran ?? 'nomor_hp')
                : null;

            $status = 'pending';

            $transaksi = Transaksi::create([
                'user_id' => auth()->id(),
                'voucher_id' => $voucherId,
                'nama_pembeli' => $request->nama_pembeli,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'metode_pembayaran' => $request->metode_pembayaran,
                'sub_tipe_pembayaran' => $subTipe,
                'bukti_pembayaran' => $buktiPath,
                'total' => $subtotal,
                'diskon_voucher' => $diskonVoucher,
                'total_bayar' => $totalBayar,
                'status' => $status,
            ]);

            foreach ($items as $item) {
                if ($item->produk->stok < $item->jumlah)
                    throw new \Exception("Stok {$item->produk->nama} tidak cukup.");

                $hargaSatuan = $item->produk->harga;
                $subtotalItem = $hargaSatuan * $item->jumlah;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item->produk_id,
                    'nama_produk' => $item->produk->nama,
                    'harga_satuan' => $hargaSatuan,
                    'jumlah' => $item->jumlah,
                    'subtotal' => $subtotalItem,
                ]);
                $item->produk->decrement('stok', $item->jumlah);
            }
            auth()->user()->keranjang()->delete();
        });

        return redirect()->route('pesanan.index')->with('success', 'Checkout berhasil!');
    }
}

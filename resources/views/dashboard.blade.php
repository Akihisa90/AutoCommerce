@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Hero Banner -->
<section class="py-5" style="background: linear-gradient(135deg, #0F2E25 0%, #1B4D3E 50%, #2D6B54 100%);">
    <div class="container py-4">
        <h1 class="text-white fw-bold mb-2" data-aos="fade-right">
            <i class="bi bi-speedometer2 me-2 text-accent"></i>Dashboard
        </h1>
        <p class="text-white-50 lead" data-aos="fade-right" data-aos-delay="100">
            Selamat datang, <strong>{{ Auth::user()->nama }}</strong>!
        </p>
    </div>
</section>

@if(Auth::user()->role === 'admin')
<!-- Admin Dashboard -->
<section class="py-5">
    <div class="container">
        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <!-- Total Produk -->
            <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted text-uppercase small mb-1">Total Produk</p>
                                <h2 class="fw-bold mb-0">{{ \App\Models\Produk::count() }}</h2>
                                <p class="text-success small mb-0 mt-1">
                                    <i class="bi bi-box-seam me-1"></i>Stok total: {{ \App\Models\Produk::sum('stok') }}
                                </p>
                            </div>
                            <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                <i class="bi bi-box-seam fs-3 text-primary"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/admin') }}" class="card-footer bg-transparent border-0 text-primary text-decoration-none small py-2">
                        <i class="bi bi-arrow-right-circle me-1"></i>Kelola Produk &rarr;
                    </a>
                </div>
            </div>

            <!-- Total Kategori -->
            <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted text-uppercase small mb-1">Total Kategori</p>
                                <h2 class="fw-bold mb-0">{{ \App\Models\Kategori::count() }}</h2>
                                <p class="text-muted small mb-0 mt-1">
                                    <i class="bi bi-tag me-1"></i>Kategori aktif
                                </p>
                            </div>
                            <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                <i class="bi bi-tags fs-3 text-success"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/admin') }}" class="card-footer bg-transparent border-0 text-success text-decoration-none small py-2">
                        <i class="bi bi-arrow-right-circle me-1"></i>Kelola Kategori &rarr;
                    </a>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted text-uppercase small mb-1">Total Transaksi</p>
                                <h2 class="fw-bold mb-0">{{ \App\Models\Transaksi::count() }}</h2>
                                <p class="text-warning small mb-0 mt-1">
                                    <i class="bi bi-clock me-1"></i>Pending: {{ \App\Models\Transaksi::where('status', 'pending')->count() }}
                                </p>
                            </div>
                            <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                                <i class="bi bi-receipt fs-3 text-warning"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/admin') }}" class="card-footer bg-transparent border-0 text-warning text-decoration-none small py-2">
                        <i class="bi bi-arrow-right-circle me-1"></i>Kelola Transaksi &rarr;
                    </a>
                </div>
            </div>

            <!-- Total Pelanggan -->
            <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted text-uppercase small mb-1">Total Pelanggan</p>
                                <h2 class="fw-bold mb-0">{{ \App\Models\User::where('role', 'user')->count() }}</h2>
                                <p class="text-info small mb-0 mt-1">
                                    <i class="bi bi-people me-1"></i>User terdaftar
                                </p>
                            </div>
                            <div class="p-3 bg-info bg-opacity-10 rounded-3">
                                <i class="bi bi-people fs-3 text-info"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/admin') }}" class="card-footer bg-transparent border-0 text-info text-decoration-none small py-2">
                        <i class="bi bi-arrow-right-circle me-1"></i>Kelola Pengguna &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Pendapatan Section -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-currency-dollar me-2 text-success"></i>Pendapatan
                        </h5>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted small mb-1">Pending</p>
                                    <h4 class="fw-bold text-warning mb-0">
                                        Rp {{ number_format(\App\Models\Transaksi::where('status', 'pending')->sum('total') ?? 0, 0, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted small mb-1">Diproses</p>
                                    <h4 class="fw-bold text-info mb-0">
                                        Rp {{ number_format(\App\Models\Transaksi::whereIn('status', ['diproses', 'dikirim'])->sum('total') ?? 0, 0, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted small mb-1">Selesai</p>
                                    <h4 class="fw-bold text-success mb-0">
                                        Rp {{ number_format(\App\Models\Transaksi::where('status', 'selesai')->sum('total') ?? 0, 0, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terbaru -->
            <div class="col-lg-4" data-aos="fade-left">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-clock-history me-2"></i>Transaksi Terbaru
                        </h5>
                        @php
                            $transaksiTerbaru = \App\Models\Transaksi::with('user')->latest()->limit(5)->get();
                        @endphp
                        @forelse($transaksiTerbaru as $trx)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom {{ !$loop->last ? '' : 'border-0' }}">
                            <div>
                                <p class="mb-0 fw-bold small">#TRX{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}</p>
                                <p class="text-muted small mb-0">{{ $trx->nama_pembeli }}</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ match($trx->status) { 'pending' => 'warning', 'diproses' => 'info', 'dikirim' => 'primary', 'selesai' => 'success', 'dibatalkan' => 'danger', default => 'secondary' } }}">
                                    {{ $trx->status }}
                                </span>
                                <p class="mb-0 fw-bold small">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center small">Belum ada transaksi.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Stok Rendah -->
        <div class="row" data-aos="fade-up">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Stok Produk Rendah
                        </h5>
                        @php
                            $stokRendah = \App\Models\Produk::with('kategori')->where('stok', '<=', 10)->orderBy('stok')->limit(10)->get();
                        @endphp
                        @forelse($stokRendah as $produk)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom {{ !$loop->last ? '' : 'border-0' }}">
                            <div class="d-flex align-items-center">
                                @if($produk->gambar)
                                <img src="{{ $produk->imageUrl() }}" alt="{{ $produk->nama }}"
                                    class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-image text-muted small"></i>
                                </div>
                                @endif
                                <div class="ms-3">
                                    <p class="mb-0 fw-bold small">{{ $produk->nama }}</p>
                                    <p class="text-muted small mb-0">{{ $produk->kategori->nama ?? 'Umum' }}</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $produk->stok <= 0 ? 'danger' : ($produk->stok <= 5 ? 'warning' : 'info') }}">
                                    Stok: {{ $produk->stok }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center small">Semua stok produk mencukupi.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@else
<!-- User Dashboard -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Pesanan Saya -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-receipt fs-1 text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Pesanan Saya</h5>
                        <p class="text-muted small">Lihat status pesanan Anda</p>
                        <p class="fs-4 fw-bold text-primary mb-3">
                            {{ auth()->user()->transaksi()->count() }} pesanan
                        </p>
                        <a href="{{ route('pesanan.index') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>Lihat Pesanan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Keranjang -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="p-3 bg-success bg-opacity-10 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-cart fs-1 text-success"></i>
                        </div>
                        <h5 class="fw-bold">Keranjang</h5>
                        <p class="text-muted small">Produk di keranjang belanja</p>
                        <p class="fs-4 fw-bold text-success mb-3">
                            {{ auth()->user()->keranjang()->sum('jumlah') }} item
                        </p>
                        <a href="{{ route('keranjang.index') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-cart me-1"></i>Buka Keranjang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mulai Belanja -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-grid fs-1 text-warning"></i>
                        </div>
                        <h5 class="fw-bold">Belanja</h5>
                        <p class="text-muted small">Jelajahi produk kami</p>
                        <p class="fs-4 fw-bold text-warning mb-3">
                            {{ \App\Models\Produk::where('stok', '>', 0)->count() }} produk
                        </p>
                        <a href="{{ route('katalog') }}" class="btn btn-warning btn-sm text-white">
                            <i class="bi bi-grid me-1"></i>Mulai Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesanan Terbaru -->
        @php
            $pesananTerbaru = auth()->user()->transaksi()->with('detailTransaksi')->latest()->limit(3)->get();
        @endphp
        @if($pesananTerbaru->isNotEmpty())
        <div class="row mt-5" data-aos="fade-up">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-clock-history me-2"></i>Pesanan Terbaru
                        </h5>
                        @foreach($pesananTerbaru as $order)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div>
                                <p class="mb-0 fw-bold">#TRX{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                                <p class="text-muted small mb-0">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ match($order->status) { 'pending' => 'warning', 'diproses' => 'info', 'dikirim' => 'primary', 'selesai' => 'success', 'dibatalkan' => 'danger', default => 'secondary' } }}">
                                    {{ match($order->status) { 'pending' => 'Menunggu', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan', default => $order->status } }}
                                </span>
                                <p class="mb-0 fw-bold">Rp {{ number_format((float) ($order->total_bayar ?? $order->total ?? 0), 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endif
@endsection

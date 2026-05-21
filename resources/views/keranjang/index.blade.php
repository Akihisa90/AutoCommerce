@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<!-- Breadcrumb -->
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item active">Keranjang</li>
    </ol>
</nav>

<!-- Cart Section -->
<section class="py-5">
    <div class="container">
        <h1 class="text-center mb-2" data-aos="fade-up">
            <i class="bi bi-cart3 me-2"></i>Keranjang Belanja
        </h1>
        <p class="text-muted text-center mb-5" data-aos="fade-up" data-aos-delay="50">
            @if(!$items->isEmpty())
            {{ $items->sum('jumlah') }} item di keranjang Anda
            @else
            Keranjang Anda masih kosong
            @endif
        </p>

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if ($items->isEmpty())
        <!-- Empty Cart -->
        <div class="text-center py-5" data-aos="fade-up">
            <div class="mb-4">
                <i class="bi bi-cart-x display-1 text-muted"></i>
            </div>
            <h3 class="fw-bold mb-2">Keranjang Belanja Kosong</h3>
            <p class="text-muted mb-4">Yuk, mulai belanja produk otomotif berkualitas!</p>
            <a href="{{ route('katalog') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-grid me-2"></i>Mulai Belanja
            </a>
        </div>
        @else
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4" data-aos="fade-right">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 35%;">Produk</th>
                                        <th class="text-center" style="width: 18%;">Harga</th>
                                        <th class="text-center" style="width: 20%;">Jumlah</th>
                                        <th class="text-center" style="width: 17%;">Subtotal</th>
                                        <th class="text-center" style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                    <tr class="cart-item">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->produk->gambar)
                                                <img src="{{ Storage::disk(config('filesystems.cloud', 'public'))->url($item->produk->gambar) }}"
                                                    alt="{{ $item->produk->nama }}"
                                                    class="rounded"
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                                @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                    style="width: 80px; height: 80px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                                @endif
                                                <div class="ms-3">
                                                    <h6 class="mb-0 fw-bold">{{ $item->produk->nama }}</h6>
                                                    <small class="text-muted">{{ $item->produk->kategori->nama ?? 'Umum' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-semibold">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('keranjang.update', $item) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm" style="width: 120px; margin: 0 auto;">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        onclick="updateQty(this, -1, {{ $item->produk->stok }})">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" name="jumlah"
                                                        class="form-control text-center qty-input"
                                                        value="{{ $item->jumlah }}" min="1"
                                                        max="{{ $item->produk->stok }}"
                                                        data-item-id="{{ $item->id }}">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        onclick="updateQty(this, 1, {{ $item->produk->stok }})">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-center fw-bold text-primary">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('keranjang.hapus', $item) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Hapus {{ $item->produk->nama }} dari keranjang?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Continue Shopping -->
                <div class="mt-3">
                    <a href="{{ route('katalog') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i>Lanjut Belanja
                    </a>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4" data-aos="fade-left">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px; z-index: 1;">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 fw-bold">
                            <i class="bi bi-receipt me-2"></i>Ringkasan Belanja
                        </h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Produk</span>
                            <span class="fw-bold">{{ $items->sum('jumlah') }} item</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span class="text-success fw-bold">Gratis</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold">Total</span>
                            <span class="fs-4 fw-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout.create') }}" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="bi bi-credit-card me-2"></i>Checkout
                        </a>

                        <div class="text-center">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1"></i>Pembayaran aman & terenkripsi
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<link href="{{ asset('css/keranjang.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true, offset: 100 });

    function updateQty(btn, delta, max) {
        const input = btn.parentElement.querySelector('.qty-input');
        let qty = parseInt(input.value) + delta;
        if (qty < 1) qty = 1;
        if (qty > max) qty = max;
        input.value = qty;

        // Auto-submit form to update cart
        input.closest('form').submit();
    }
</script>
@endpush

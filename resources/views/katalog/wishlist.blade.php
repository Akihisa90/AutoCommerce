@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item active">Wishlist</li>
    </ol>
</nav>

<section class="py-5">
    <div class="container">
        <h1 class="fw-bold mb-4 text-center" data-aos="fade-up">
            <i class="bi bi-heart me-2 text-danger"></i>Wishlist Saya
        </h1>

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($wishlists->count() > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($wishlists as $item)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="product-card rounded-3 overflow-hidden border-0 shadow-sm bg-white d-flex flex-column h-100">
                    <figure class="product-figure mb-0 position-relative overflow-hidden">
                        <a href="{{ route('produk.show', $item->produk) }}">
                            @if ($item->produk->gambar)
                            <img src="{{ $item->produk->imageUrl() }}" alt="{{ $item->produk->nama }}" class="img-fluid product-image w-100">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center product-placeholder">
                                <i class="bi bi-image display-1 text-muted"></i>
                            </div>
                            @endif
                        </a>
                        <form action="{{ route('wishlist.remove', $item->produk) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Hapus dari Wishlist">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </figure>
                    <div class="product-info p-3 d-flex flex-column flex-grow-1">
                        <a href="{{ route('katalog', ['kategori' => $item->produk->kategori_id]) }}" class="text-decoration-none mb-1">
                            <small class="text-primary text-uppercase fw-semibold" style="font-size:0.7rem;">{{ $item->produk->kategori->nama ?? 'Umum' }}</small>
                        </a>
                        <a href="{{ route('produk.show', $item->produk) }}" class="text-decoration-none mb-2">
                            <h6 class="fw-bold mb-0 text-dark product-title">{{ $item->produk->nama }}</h6>
                        </a>
                        <div class="mb-2">
                            @if($item->produk->harga_diskon && $item->produk->harga_diskon < $item->produk->harga)
                            <span class="text-decoration-line-through text-muted small">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</span>
                            <div class="fs-5 fw-bold text-danger">Rp {{ number_format($item->produk->harga_diskon, 0, ',', '.') }}</div>
                            @else
                            <span class="fs-5 fw-bold text-primary">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="mt-auto">
                            @if($item->produk->stok > 0)
                            <form action="{{ route('keranjang.tambah', $item->produk) }}" method="POST">
                                @csrf
                                <input type="hidden" name="jumlah" value="1">
                                <button type="submit" class="btn btn-cart w-100 btn-sm">
                                    <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
                                </button>
                            </form>
                            @else
                            <button class="btn btn-secondary w-100 btn-sm" disabled>Stok Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if ($wishlists->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $wishlists->links('vendor.pagination.bootstrap-5') }}
        </div>
        @endif
        @else
        <div class="text-center py-5" data-aos="fade-up">
            <i class="bi bi-heart display-1 text-muted mb-3 d-block"></i>
            <h4>Wishlist Kosong</h4>
            <p class="text-muted">Anda belum menambahkan produk ke wishlist.</p>
            <a href="{{ route('katalog') }}" class="btn btn-primary mt-2">
                <i class="bi bi-bag me-1"></i>Mulai Belanja
            </a>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<link href="{{ asset('css/wishlist.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
</script>
@endpush

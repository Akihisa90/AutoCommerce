@extends('layouts.app')

@section('title', $produk->nama)

@section('content')
<!-- Breadcrumb -->
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Katalog</a></li>
        @if($produk->kategori)
        <li class="breadcrumb-item">
            <a href="{{ route('katalog', ['kategori' => $produk->kategori_id]) }}" class="text-decoration-none">
                {{ $produk->kategori->nama }}
            </a>
        </li>
        @endif
        <li class="breadcrumb-item active">{{ Str::limit($produk->nama, 30) }}</li>
    </ol>
</nav>

<!-- Product Detail Section -->
<section class="py-5 section-product-detail">
    <div class="container">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <!-- Product Image Gallery -->
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="product-large-slider swiper rounded-3 overflow-hidden shadow-sm product-detail-slider">
                    <div class="swiper-wrapper">
                        @if($produk->gambar)
                        <div class="swiper-slide">
                            <img src="{{ $produk->imageUrl() }}" alt="{{ $produk->nama }}"
                                class="img-fluid w-100" style="max-height: 500px; object-fit: cover;">
                        </div>
                        @else
                        <div class="swiper-slide">
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="height: 500px;">
                                <i class="bi bi-image display-1 text-muted"></i>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="mb-3">
                    @if($produk->kategori)
                    <a href="{{ route('katalog', ['kategori' => $produk->kategori_id]) }}"
                        class="badge detail-badge px-3 py-2 text-decoration-none">
                        <i class="bi bi-tag me-1"></i>{{ $produk->kategori->nama }}
                    </a>
                    @endif
                    @if($produk->stok <= 10 && $produk->stok > 0)
                    <span class="badge bg-warning text-dark ms-2 px-3 py-2">
                        <i class="bi bi-lightning-fill me-1"></i>Stok Terbatas
                    </span>
                    @elseif($produk->stok <= 0)
                    <span class="badge bg-danger ms-2 px-3 py-2">
                        <i class="bi bi-x-circle me-1"></i>Stok Habis
                    </span>
                    @else
                    <span class="badge bg-success ms-2 px-3 py-2">
                        <i class="bi bi-check-circle me-1"></i>Tersedia
                    </span>
                    @endif
                </div>

                <h1 class="display-5 fw-bold mb-3 product-detail-title">{{ $produk->nama }}</h1>

                <div class="price-box rounded-3 p-4 mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="product-price me-3">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="shipping-note mb-0">
                        <i class="bi bi-truck me-2 text-accent"></i>Gratis ongkir untuk semua pembelian
                    </p>
                </div>

                <!-- Quantity & Add to Cart -->
                @auth
                <form action="{{ route('keranjang.tambah', $produk) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3 align-items-stretch">
                        <div class="col-auto">
                            <label class="form-label fw-semibold d-block">Jumlah</label>
                            <div class="input-group quantity-group">
                                <button class="btn btn-outline-secondary quantity-left-minus" type="button">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" name="jumlah" id="quantity" class="form-control text-center qty-detail"
                                    value="1" min="1" max="{{ $produk->stok }}" style="width: 80px;">
                                <button class="btn btn-outline-secondary quantity-right-plus" type="button">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col">
                            <label class="form-label fw-semibold d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-cart-detail btn-lg w-100"
                                {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                                <i class="bi bi-cart-plus me-2"></i>{{ $produk->stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                            </button>
                        </div>
                    </div>
                </form>
                @else
                <div class="mb-4">
                    <a href="{{ route('login') }}" class="btn btn-cart-detail btn-lg w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Membeli
                    </a>
                </div>
                @endauth

                <!-- Product Description -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-file-text me-2"></i>Deskripsi Produk
                    </h5>
                    <div class="text-muted">
                        {!! nl2br(e($produk->deskripsi ?? 'Deskripsi produk belum tersedia.')) !!}
                    </div>
                </div>

                <!-- Product Info Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 180px;">Kategori</th>
                                <td>{{ $produk->kategori->nama ?? 'Umum' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Stok Tersedia</th>
                                <td>
                                    <span class="badge {{ $produk->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $produk->stok }} unit
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Kondisi</th>
                                <td><span class="badge bg-success">Baru</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-4 section-features">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-4" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-icon">
                    <i class="bi bi-truck display-6 mb-2 d-block"></i>
                    <small class="fw-semibold">Gratis Ongkir</small>
                </div>
            </div>
            <div class="col-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="bi bi-shield-check display-6 mb-2 d-block"></i>
                    <small class="fw-semibold">Garansi Resmi</small>
                </div>
            </div>
            <div class="col-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="bi bi-headset display-6 mb-2 d-block"></i>
                    <small class="fw-semibold">Support 24/7</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="fw-bold mb-4" data-aos="fade-up">
            <i class="bi bi-star me-2 text-warning"></i>Ulasan Pembeli
        </h3>

        @if($produk->review_count > 0)
        <div class="row mb-4" data-aos="fade-up">
            <div class="col-md-4">
                <div class="bg-white rounded-3 p-4 shadow-sm text-center">
                    <div class="display-4 fw-bold text-warning">{{ number_format($produk->rating, 1) }}</div>
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($produk->rating))
                        <i class="bi bi-star-fill text-warning"></i>
                        @elseif($i - 0.5 <= $produk->rating)
                        <i class="bi bi-star-half text-warning"></i>
                        @else
                        <i class="bi bi-star text-muted"></i>
                        @endif
                        @endfor
                    </div>
                    <small class="text-muted">{{ $produk->review_count }} ulasan</small>
                </div>
            </div>
            <div class="col-md-8">
                <div class="bg-white rounded-3 p-4 shadow-sm">
                    @foreach([5, 4, 3, 2, 1] as $star)
                    @php
                        $count = $produk->reviews->where('rating', $star)->count();
                        $percent = $produk->review_count > 0 ? ($count / $produk->review_count) * 100 : 0;
                    @endphp
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="small fw-semibold" style="width: 20px;">{{ $star }}</span>
                        <i class="bi bi-star-fill text-warning" style="font-size: 0.75rem;"></i>
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                        <span class="small text-muted" style="width: 30px;">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3 p-4 shadow-sm" data-aos="fade-up">
            @foreach($produk->reviews->sortByDesc('created_at')->take(10) as $review)
            <div class="border-bottom pb-3 mb-3 {{ !$loop->last ? '' : 'border-0' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong>{{ $review->user->name }}</strong>
                        <div class="mb-1">
                            @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                            <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                            @else
                            <i class="bi bi-star text-muted" style="font-size: 0.8rem;"></i>
                            @endif
                            @endfor
                        </div>
                    </div>
                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                </div>
                @if($review->komentar)
                <p class="text-muted small mb-0">"{{ $review->komentar }}"</p>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5 bg-white rounded-3 shadow-sm" data-aos="fade-up">
            <i class="bi bi-chat-square-text display-1 text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Belum Ada Ulasan</h5>
            <p class="text-muted small">Jadilah yang pertama memberikan ulasan untuk produk ini.</p>
        </div>
        @endif
    </div>
</section>

<!-- Related Products -->
<section class="py-5 section-related">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title fw-bold">Produk Terkait</h2>
            <p class="section-subtitle">Produk lain yang mungkin Anda butuhkan</p>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @php
                $relatedProducts = \App\Models\Produk::where('kategori_id', $produk->kategori_id)
                    ->where('id', '!=', $produk->id)
                    ->limit(4)
                    ->get();
            @endphp
            @forelse($relatedProducts as $related)
            <div class="col d-flex" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="product-card rounded-3 overflow-hidden border-0 shadow-sm bg-white d-flex flex-column w-100 h-100">
                    <figure class="product-figure mb-0 position-relative overflow-hidden">
                        <a href="{{ route('produk.show', $related) }}">
                            @if($related->gambar)
                            <img src="{{ $related->imageUrl() }}" alt="{{ $related->nama }}"
                                class="img-fluid product-image w-100">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center product-placeholder">
                                <i class="bi bi-image display-1 text-muted"></i>
                            </div>
                            @endif
                        </a>
                    </figure>
                    <div class="product-info p-3 d-flex flex-column flex-grow-1">
                        <small class="text-primary text-uppercase fw-semibold mb-1">{{ $related->kategori->nama ?? 'Umum' }}</small>
                        <a href="{{ route('produk.show', $related) }}" class="text-decoration-none mb-2">
                            <h6 class="fw-bold mb-0 text-dark product-title">{{ $related->nama }}</h6>
                        </a>
                        <span class="fs-5 fw-bold text-primary mb-3">Rp {{ number_format($related->harga, 0, ',', '.') }}</span>
                        <div class="mt-auto product-actions">
                            <a href="{{ route('produk.show', $related) }}" class="btn btn-detail w-100">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Tidak ada produk terkait dalam kategori ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('styles')
<link href="{{ asset('css/produk-detail.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true, offset: 100 });

    // Product image swiper
    new Swiper('.product-large-slider', {
        pagination: { el: '.swiper-pagination', clickable: true },
    });

    // Quantity spinner
    document.querySelector('.quantity-left-minus')?.addEventListener('click', function() {
        var qty = parseInt(document.getElementById('quantity').value);
        if(qty > 1) document.getElementById('quantity').value = qty - 1;
    });

    document.querySelector('.quantity-right-plus')?.addEventListener('click', function() {
        var qty = parseInt(document.getElementById('quantity').value);
        var max = parseInt(document.getElementById('quantity').max);
        if(qty < max) document.getElementById('quantity').value = qty + 1;
    });
</script>
@endpush

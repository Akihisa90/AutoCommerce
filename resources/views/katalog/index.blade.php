@extends('layouts.app')

@section('title', 'Katalog Produk Otomotif')

@section('content')
<!-- Alert Messages -->
@if (session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif
@if (session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

<!-- Flash Sale Banner -->
@if($flashSale->count() > 0)
<section class="py-3 bg-danger text-white" data-aos="fade-down">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-lightning-fill fs-4"></i>
                <h5 class="mb-0 fw-bold">FLASH SALE!</h5>
                <span class="badge bg-warning text-dark">Terbatas</span>
            </div>
            <div class="d-flex gap-2 overflow-auto">
                @foreach($flashSale as $item)
                <a href="{{ route('produk.show', $item) }}" class="text-white text-decoration-none d-flex align-items-center gap-2 bg-white bg-opacity-25 rounded-pill px-3 py-1 small">
                    <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : '' }}" alt="{{ $item->nama }}" class="rounded-circle" style="width:24px;height:24px;object-fit:cover;">
                    <span class="text-truncate" style="max-width:120px;">{{ $item->nama }}</span>
                    @if($item->harga_diskon)
                    <span class="badge bg-warning text-dark">-{{ $item->getHargaDiskonPercent() }}%</span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Breadcrumb -->
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Beranda</a></li>
        @if(request('kategori'))
        @php
        $kat = $kategori->firstWhere('id', request('kategori'));
        @endphp
        @if($kat)
        <li class="breadcrumb-item active">{{ $kat->nama }}</li>
        @endif
        @else
        <li class="breadcrumb-item active">Semua Produk</li>
        @endif
    </ol>
</nav>

<!-- Main Catalog Section -->
<section id="products" class="py-4 section-products">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <div class="filter-sidebar bg-white rounded-3 shadow-sm p-4 sticky-top" style="top:80px;z-index:100;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-funnel me-2"></i>Filter</h5>
                        <a href="{{ route('katalog') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </a>
                    </div>

                    <form action="{{ route('katalog') }}" method="GET" id="filterForm">
                        <!-- Search -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Pencarian</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari produk, brand..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Kategori</label>
                            <div class="list-group list-group-flush">
                                <a href="{{ route('katalog', request()->except('kategori')) }}" class="list-group-item list-group-item-action border-0 px-0 py-2 {{ !request('kategori') ? 'active bg-primary' : '' }}">
                                    <i class="bi bi-grid me-2"></i>Semua Kategori
                                </a>
                                @foreach($kategori as $k)
                                <a href="{{ route('katalog', array_merge(request()->except('kategori'), ['kategori' => $k->id])) }}" class="list-group-item list-group-item-action border-0 px-0 py-2 {{ request('kategori') == $k->id ? 'active bg-primary' : '' }}">
                                    {{ $k->nama }} <span class="badge bg-light text-dark float-end">{{ $k->produk->count() }}</span>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Rentang Harga -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Rentang Harga</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="harga_min" class="form-control form-control-sm" placeholder="Min" value="{{ request('harga_min') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="harga_max" class="form-control form-control-sm" placeholder="Max" value="{{ request('harga_max') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Brand -->
                        @if($brands->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Merek/Brand</label>
                            <select name="brand" class="form-select form-select-sm">
                                <option value="">Semua Brand</option>
                                @foreach($brands as $b)
                                <option value="{{ $b }}" {{ request('brand') == $b ? 'selected' : '' }}>{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Warna -->
                        @if($warnaOptions->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Warna</label>
                            <select name="warna" class="form-select form-select-sm">
                                <option value="">Semua Warna</option>
                                @foreach($warnaOptions as $w)
                                <option value="{{ $w }}" {{ request('warna') == $w ? 'selected' : '' }}>{{ $w }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Rating Minimum -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Rating Minimum</label>
                            <div class="d-flex flex-column gap-2">
                                @foreach([5, 4, 3, 2] as $r)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating_min" id="rating{{ $r }}" value="{{ $r }}" {{ request('rating_min') == $r ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="rating{{ $r }}">
                                        @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $r)
                                        <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                        <i class="bi bi-star text-muted"></i>
                                        @endif
                                        @endfor
                                        @if($r > 0)
                                        <span class="text-muted">& up</span>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating_min" id="rating0" value="" {{ !request('rating_min') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="rating0">Semua Rating</label>
                                </div>
                            </div>
                        </div>

                        <!-- Ketersediaan & Pengiriman -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Ketersediaan & Pengiriman</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="stok_tersedia" id="stok_tersedia" value="1" {{ request('stok_tersedia') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="stok_tersedia">Hanya Stok Tersedia</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="gratis_ongkir" id="gratis_ongkir" value="1" {{ request('gratis_ongkir') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="gratis_ongkir"><i class="bi bi-truck text-success me-1"></i>Gratis Ongkir</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="same_day" id="same_day" value="1" {{ request('same_day') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="same_day"><i class="bi bi-lightning text-warning me-1"></i>Same Day Delivery</label>
                            </div>
                        </div>

                        <!-- Label Khusus -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Label Khusus</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['best_seller' => 'Best Seller', 'diskon' => 'Diskon', 'baru' => 'Baru', 'stok_terbatas' => 'Stok Terbatas', 'flash_sale' => 'Flash Sale'] as $key => $label)
                                <a href="{{ route('katalog', array_merge(request()->except('label'), request('label') == $key ? [] : ['label' => $key])) }}" class="btn btn-sm {{ request('label') == $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    {{ $label }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-sm">
                            <i class="bi bi-funnel me-1"></i>Terapkan Filter
                        </button>
                    </form>
                </div>
            </div>

            <!-- Products Area -->
            <div class="col-lg-9">
                <!-- Top Bar: Results count, Sort, View toggle -->
                <div class="bg-white rounded-3 shadow-sm p-3 mb-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <span class="small text-muted">
                                Menampilkan <strong>{{ $produks->firstItem() ?? 0 }}-{{ $produks->lastItem() ?? 0 }}</strong> dari <strong>{{ $produks->total() }}</strong> produk
                            </span>
                        </div>
                        <div class="col-md-4">
                            <form action="{{ route('katalog') }}" method="GET" class="d-flex gap-2" id="sortForm">
                                @foreach(request()->except('sort') as $key => $value)
                                @if(is_array($value))
                                @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                                @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                                @endforeach
                                <select name="sort" class="form-select form-select-sm" onchange="document.getElementById('sortForm').submit()">
                                    <option value="relevansi" {{ request('sort') == 'relevansi' ? 'selected' : '' }}>Relevansi</option>
                                    <option value="harga_terendah" {{ request('sort') == 'harga_terendah' ? 'selected' : '' }}>Harga: Terendah</option>
                                    <option value="harga_tertinggi" {{ request('sort') == 'harga_tertinggi' ? 'selected' : '' }}>Harga: Tertinggi</option>
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
                                    <option value="review" {{ request('sort') == 'review' ? 'selected' : '' }}>Ulasan Terbanyak</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-4 d-flex justify-content-md-end gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary view-toggle {{ request('view') != 'list' ? 'active bg-primary text-white' : '' }}" data-view="grid">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary view-toggle {{ request('view') == 'list' ? 'active bg-primary text-white' : '' }}" data-view="list">
                                <i class="bi bi-list-ul"></i>
                            </button>
                            <select class="form-select form-select-sm" style="width:auto;" id="perPageSelect" onchange="updatePerPage(this.value)">
                                <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12/hal</option>
                                <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24/hal</option>
                                <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48/hal</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request()->hasAny(['search', 'kategori', 'brand', 'harga_min', 'harga_max', 'rating_min', 'stok_tersedia', 'gratis_ongkir', 'same_day', 'label']))
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="small fw-semibold text-muted">Filter aktif:</span>
                    @if(request('search'))
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Search: "{{ request('search') }}"
                        <a href="{{ route('katalog', request()->except('search')) }}" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    @endif
                    @if(request('kategori'))
                    @php $kat = $kategori->firstWhere('id', request('kategori')); @endphp
                    @if($kat)
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Kategori: {{ $kat->nama }}
                        <a href="{{ route('katalog', request()->except('kategori')) }}" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    @endif
                    @endif
                    @if(request('brand'))
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Brand: {{ request('brand') }}
                        <a href="{{ route('katalog', request()->except('brand')) }}" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    @endif
                    @if(request('harga_min') || request('harga_max'))
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Harga: {{ request('harga_min') ? 'Rp ' . number_format(request('harga_min')) : '0' }} - {{ request('harga_max') ? 'Rp ' . number_format(request('harga_max')) : '∞' }}
                        <a href="{{ route('katalog', request()->except(['harga_min', 'harga_max'])) }}" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    @endif
                    @if(request('rating_min'))
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Rating: {{ request('rating_min') }}+ <i class="bi bi-star-fill text-warning"></i>
                        <a href="{{ route('katalog', request()->except('rating_min')) }}" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    @endif
                    @if(request('stok_tersedia'))
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Stok Tersedia
                        <a href="{{ route('katalog', request()->except('stok_tersedia')) }}" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    @endif
                    @if(request('gratis_ongkir'))
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        <i class="bi bi-truck text-success"></i> Gratis Ongkir
                        <a href="{{ route('katalog', request()->except('gratis_ongkir')) }}" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    @endif
                    @if(request('label'))
                    @php
                    $labels = ['best_seller' => 'Best Seller', 'diskon' => 'Diskon', 'baru' => 'Baru', 'stok_terbatas' => 'Stok Terbatas', 'flash_sale' => 'Flash Sale'];
                    @endphp
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        {{ $labels[request('label')] ?? request('label') }}
                        <a href="{{ route('katalog', request()->except('label')) }}" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    @endif
                </div>
                @endif

                <!-- Products Grid/List -->
                <div id="productContainer" class="{{ request('view') == 'list' ? 'list-view' : 'grid-view' }}">
                    <!-- Grid View -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 grid-container">
                        @forelse($produks as $produk)
                        <div class="col product-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 30 }}">
                            <div class="product-card rounded-3 overflow-hidden border-0 shadow-sm bg-white d-flex flex-column h-100">
                                <!-- Product Image -->
                                <figure class="product-figure mb-0 position-relative overflow-hidden">
                                    <a href="{{ route('produk.show', $produk) }}">
                                        @if ($produk->gambar)
                                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}" class="img-fluid product-image w-100" loading="lazy">
                                        @else
                                        <div class="bg-light d-flex align-items-center justify-content-center product-placeholder">
                                            <i class="bi bi-image display-1 text-muted"></i>
                                        </div>
                                        @endif
                                    </a>

                                    <!-- Labels -->
                                    <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-1">
                                        @if($produk->label == 'best_seller' || $produk->terjual >= 50)
                                        <span class="badge bg-danger"><i class="bi bi-fire me-1"></i>Best Seller</span>
                                        @endif
                                        @if($produk->harga_diskon && $produk->harga_diskon < $produk->harga)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-tag me-1"></i>-{{ $produk->getHargaDiskonPercent() }}%</span>
                                        @endif
                                        @if($produk->label == 'baru' || $produk->created_at->diffInDays(now()) <= 30)
                                        <span class="badge bg-info"><i class="bi bi-stars me-1"></i>Baru</span>
                                        @endif
                                        @if($produk->stok <= 10 && $produk->stok > 0)
                                        <span class="badge bg-orange text-white"><i class="bi bi-lightning me-1"></i>Stok Terbatas</span>
                                        @endif
                                        @if($produk->label == 'flash_sale')
                                        <span class="badge bg-danger"><i class="bi bi-lightning-fill me-1"></i>Flash Sale</span>
                                        @endif
                                    </div>

                                    <!-- Wishlist Button -->
                                    <button type="button" class="btn btn-sm btn-wishlist position-absolute top-0 end-0 m-2 rounded-circle {{ auth()->check() && $produk->isInWishlist(auth()->id()) ? 'active' : '' }}" data-produk="{{ $produk->id }}" title="Tambah ke Wishlist">
                                        <i class="bi bi-heart{{ auth()->check() && $produk->isInWishlist(auth()->id()) ? '-fill' : '' }}"></i>
                                    </button>

                                    <!-- Stok Badge -->
                                    <span class="badge stock-badge position-absolute bottom-0 end-0 m-2 {{ $produk->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $produk->stok > 0 ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </figure>

                                <!-- Product Info -->
                                <div class="product-info p-3 d-flex flex-column flex-grow-1">
                                    <a href="{{ route('katalog', ['kategori' => $produk->kategori_id]) }}" class="text-decoration-none mb-1">
                                        <small class="text-primary text-uppercase fw-semibold" style="font-size:0.7rem;">{{ $produk->kategori->nama ?? 'Umum' }}</small>
                                    </a>
                                    <a href="{{ route('produk.show', $produk) }}" class="text-decoration-none mb-2">
                                        <h6 class="fw-bold mb-0 text-dark product-title" style="font-size:0.9rem;">{{ $produk->nama }}</h6>
                                    </a>

                                    @if($produk->brand)
                                    <small class="text-muted mb-1"><i class="bi bi-award me-1"></i>{{ $produk->brand }}</small>
                                    @endif

                                    <!-- Rating & Reviews -->
                                    <div class="d-flex align-items-center gap-1 mb-2">
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($produk->rating))
                                            <i class="bi bi-star-fill text-warning" style="font-size:0.75rem;"></i>
                                            @elseif($i - 0.5 <= $produk->rating)
                                            <i class="bi bi-star-half text-warning" style="font-size:0.75rem;"></i>
                                            @else
                                            <i class="bi bi-star text-muted" style="font-size:0.75rem;"></i>
                                            @endif
                                            @endfor
                                        </div>
                                        <span class="small text-muted">({{ $produk->review_count }})</span>
                                    </div>

                                    <!-- Terjual -->
                                    @if($produk->terjual > 0)
                                    <small class="text-muted mb-1"><i class="bi bi-bag-check me-1"></i>{{ $produk->terjual }} terjual</small>
                                    @endif

                                    <!-- Price -->
                                    <div class="mb-2">
                                        @if($produk->harga_diskon && $produk->harga_diskon < $produk->harga)
                                        <span class="text-decoration-line-through text-muted small">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                        <div class="fs-6 fw-bold text-danger">Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</div>
                                        @else
                                        <span class="fs-6 fw-bold text-primary">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                        @endif
                                    </div>

                                    <!-- Shipping badges -->
                                    <div class="d-flex gap-1 mb-2 flex-wrap">
                                        @if($produk->gratis_ongkir)
                                        <span class="badge bg-success bg-opacity-10 text-success" style="font-size:0.65rem;"><i class="bi bi-truck me-1"></i>Gratis Ongkir</span>
                                        @endif
                                        @if($produk->same_day_delivery)
                                        <span class="badge bg-warning bg-opacity-10 text-dark" style="font-size:0.65rem;"><i class="bi bi-lightning me-1"></i>Same Day</span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-auto product-actions">
                                        @auth
                                        <form action="{{ route('keranjang.tambah', $produk) }}" method="POST" class="mb-2">
                                            @csrf
                                            <input type="hidden" name="jumlah" value="1">
                                            <button type="submit" class="btn btn-cart w-100 btn-sm" {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                                                <i class="bi bi-cart-plus me-1"></i>{{ $produk->stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                                            </button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-cart w-100 btn-sm mb-2">
                                            <i class="bi bi-box-arrow-in-right me-1"></i>Login untuk Beli
                                        </a>
                                        @endauth
                                        <a href="{{ route('produk.show', $produk) }}" class="btn btn-detail w-100 btn-sm">
                                            <i class="bi bi-eye me-1"></i>Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5" data-aos="fade-up">
                                <i class="bi bi-search display-1 text-muted mb-3 d-block"></i>
                                <h4>Produk Tidak Ditemukan</h4>
                                <p class="text-muted">Coba ubah kata kunci atau filter pencarian Anda.</p>
                                <a href="{{ route('katalog') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Pencarian
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- List View (hidden by default) -->
                    <div class="list-container d-none">
                        @forelse($produks as $produk)
                        <div class="product-card-list bg-white rounded-3 shadow-sm border-0 p-3 mb-3 d-flex gap-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 30 }}">
                            <a href="{{ route('produk.show', $produk) }}" class="flex-shrink-0" style="width:180px;">
                                @if ($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}" class="img-fluid rounded-2" style="height:150px;object-fit:cover;" loading="lazy">
                                @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded-2" style="height:150px;">
                                    <i class="bi bi-image display-4 text-muted"></i>
                                </div>
                                @endif
                            </a>
                            <div class="flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <a href="{{ route('katalog', ['kategori' => $produk->kategori_id]) }}" class="text-decoration-none">
                                            <small class="text-primary text-uppercase fw-semibold" style="font-size:0.7rem;">{{ $produk->kategori->nama ?? 'Umum' }}</small>
                                        </a>
                                        <a href="{{ route('produk.show', $produk) }}" class="text-decoration-none">
                                            <h6 class="fw-bold mb-1 text-dark">{{ $produk->nama }}</h6>
                                        </a>
                                        @if($produk->brand)
                                        <small class="text-muted"><i class="bi bi-award me-1"></i>{{ $produk->brand }}</small>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-wishlist {{ auth()->check() && $produk->isInWishlist(auth()->id()) ? 'active' : '' }}" data-produk="{{ $produk->id }}">
                                        <i class="bi bi-heart{{ auth()->check() && $produk->isInWishlist(auth()->id()) ? '-fill' : '' }}"></i>
                                    </button>
                                </div>

                                <div class="d-flex align-items-center gap-1 my-1">
                                    @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($produk->rating))
                                    <i class="bi bi-star-fill text-warning" style="font-size:0.75rem;"></i>
                                    @elseif($i - 0.5 <= $produk->rating)
                                    <i class="bi bi-star-half text-warning" style="font-size:0.75rem;"></i>
                                    @else
                                    <i class="bi bi-star text-muted" style="font-size:0.75rem;"></i>
                                    @endif
                                    @endfor
                                    <span class="small text-muted">({{ $produk->review_count }} ulasan)</span>
                                    @if($produk->terjual > 0)
                                    <span class="small text-muted ms-2"><i class="bi bi-bag-check me-1"></i>{{ $produk->terjual }} terjual</span>
                                    @endif
                                </div>

                                <div class="d-flex gap-1 mb-2 flex-wrap">
                                    @if($produk->harga_diskon && $produk->harga_diskon < $produk->harga)
                                    <span class="badge bg-warning text-dark">-{{ $produk->getHargaDiskonPercent() }}%</span>
                                    @endif
                                    @if($produk->label == 'best_seller' || $produk->terjual >= 50)
                                    <span class="badge bg-danger"><i class="bi bi-fire me-1"></i>Best Seller</span>
                                    @endif
                                    @if($produk->label == 'baru' || $produk->created_at->diffInDays(now()) <= 30)
                                    <span class="badge bg-info"><i class="bi bi-stars me-1"></i>Baru</span>
                                    @endif
                                    @if($produk->gratis_ongkir)
                                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size:0.65rem;"><i class="bi bi-truck me-1"></i>Gratis Ongkir</span>
                                    @endif
                                    @if($produk->same_day_delivery)
                                    <span class="badge bg-warning bg-opacity-10 text-dark" style="font-size:0.65rem;"><i class="bi bi-lightning me-1"></i>Same Day</span>
                                    @endif
                                </div>

                                <div class="mt-auto d-flex align-items-center justify-content-between">
                                    <div>
                                        @if($produk->harga_diskon && $produk->harga_diskon < $produk->harga)
                                        <span class="text-decoration-line-through text-muted small">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                        <div class="fs-5 fw-bold text-danger">Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</div>
                                        @else
                                        <span class="fs-5 fw-bold text-primary">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        @auth
                                        <form action="{{ route('keranjang.tambah', $produk) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="jumlah" value="1">
                                            <button type="submit" class="btn btn-cart btn-sm" {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                                                <i class="bi bi-cart-plus me-1"></i>Keranjang
                                            </button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-cart btn-sm">
                                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                                        </a>
                                        @endauth
                                        <a href="{{ route('produk.show', $produk) }}" class="btn btn-detail btn-sm">
                                            <i class="bi bi-eye me-1"></i>Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="bi bi-search display-1 text-muted mb-3 d-block"></i>
                            <h4>Produk Tidak Ditemukan</h4>
                            <p class="text-muted">Coba ubah kata kunci atau filter pencarian Anda.</p>
                            <a href="{{ route('katalog') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Pencarian
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if ($produks->hasPages())
                <div class="mt-4 d-flex justify-content-center" data-aos="fade-up">
                    {{ $produks->links('vendor.pagination.bootstrap-5') }}
                </div>
                @endif

                <!-- Rekomendasi Section -->
                @if($rekomendasi->count() > 0 && $produks->count() > 0)
                <div class="mt-5 pt-4 border-top" data-aos="fade-up">
                    <h5 class="fw-bold mb-4"><i class="bi bi-lightbulb text-warning me-2"></i>Pelanggan Juga Membeli</h5>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
                        @foreach($rekomendasi as $rec)
                        <div class="col">
                            <a href="{{ route('produk.show', $rec) }}" class="text-decoration-none">
                                <div class="bg-white rounded-3 shadow-sm p-2 text-center h-100">
                                    @if($rec->gambar)
                                    <img src="{{ asset('storage/' . $rec->gambar) }}" alt="{{ $rec->nama }}" class="img-fluid rounded-2 mb-2" style="height:100px;object-fit:cover;" loading="lazy">
                                    @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded-2 mb-2" style="height:100px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                    @endif
                                    <small class="text-dark fw-semibold d-block text-truncate">{{ $rec->nama }}</small>
                                    <small class="text-primary fw-bold">Rp {{ number_format($rec->harga, 0, ',', '.') }}</small>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Mobile Filter Toggle -->
<div class="d-lg-none position-fixed bottom-0 start-0 end-0 p-3 bg-white border-top shadow" style="z-index:1050;">
    <button type="button" class="btn btn-primary w-100" data-bs-toggle="offcanvas" data-bs-target="#mobileFilter">
        <i class="bi bi-funnel me-2"></i>Filter & Sortir
    </button>
</div>

<!-- Mobile Filter Offcanvas -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileFilter" style="width:85%;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold"><i class="bi bi-funnel me-2"></i>Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('katalog') }}" method="GET">
            <!-- Search -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Pencarian</label>
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
            </div>

            <!-- Kategori -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Harga -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Rentang Harga</label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" name="harga_min" class="form-control" placeholder="Min" value="{{ request('harga_min') }}">
                    </div>
                    <div class="col-6">
                        <input type="number" name="harga_max" class="form-control" placeholder="Max" value="{{ request('harga_max') }}">
                    </div>
                </div>
            </div>

            <!-- Brand -->
            @if($brands->count() > 0)
            <div class="mb-3">
                <label class="form-label fw-semibold small">Brand</label>
                <select name="brand" class="form-select">
                    <option value="">Semua Brand</option>
                    @foreach($brands as $b)
                    <option value="{{ $b }}" {{ request('brand') == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Rating -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Rating Minimum</label>
                <select name="rating_min" class="form-select">
                    <option value="">Semua Rating</option>
                    <option value="5" {{ request('rating_min') == '5' ? 'selected' : '' }}>5 Bintang</option>
                    <option value="4" {{ request('rating_min') == '4' ? 'selected' : '' }}>4+ Bintang</option>
                    <option value="3" {{ request('rating_min') == '3' ? 'selected' : '' }}>3+ Bintang</option>
                </select>
            </div>

            <!-- Checkboxes -->
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="stok_tersedia" id="m_stok" value="1" {{ request('stok_tersedia') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="m_stok">Stok Tersedia</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="gratis_ongkir" id="m_ongkir" value="1" {{ request('gratis_ongkir') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="m_ongkir">Gratis Ongkir</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="same_day" id="m_sameday" value="1" {{ request('same_day') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="m_sameday">Same Day Delivery</label>
                </div>
            </div>

            <!-- Sort -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Urutkan</label>
                <select name="sort" class="form-select">
                    <option value="relevansi" {{ request('sort') == 'relevansi' ? 'selected' : '' }}>Relevansi</option>
                    <option value="harga_terendah" {{ request('sort') == 'harga_terendah' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="harga_tertinggi" {{ request('sort') == 'harga_tertinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
            <a href="{{ route('katalog') }}" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/katalog.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // View Toggle
    document.querySelectorAll('.view-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            const container = document.getElementById('productContainer');
            document.querySelectorAll('.view-toggle').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            if (view === 'list') {
                container.classList.add('list-view');
                container.classList.remove('grid-view');
            } else {
                container.classList.add('grid-view');
                container.classList.remove('list-view');
            }
        });
    });

    // Wishlist Toggle
    document.querySelectorAll('.btn-wishlist').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            @auth
            const produkId = this.dataset.produk;
            const icon = this.querySelector('i');

            fetch(`/wishlist/toggle/${produkId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(res => {
                    if (!res.ok) {
                        return res.text().then(text => {
                            throw new Error('HTTP ' + res.status + ': ' + text.substring(0, 200));
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        this.classList.toggle('active');
                        if (data.in_wishlist) {
                            icon.className = 'bi bi-heart-fill';
                        } else {
                            icon.className = 'bi bi-heart';
                        }
                    }
                })
                .catch(err => {
                    console.error('Wishlist error:', err);
                    alert('Gagal mengubah wishlist: ' + err.message);
                });
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        });
    });

    // Update per page
    function updatePerPage(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', val);
        window.location.href = url.toString();
    }

    // Lazy loading images
    document.addEventListener('DOMContentLoaded', function() {
        const lazyImages = document.querySelectorAll('.lazy-image');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    });
</script>
@endpush

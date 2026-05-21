<div class="product-card rounded-3 overflow-hidden border-0 shadow-sm bg-white d-flex flex-column h-100">
    <!-- Product Image -->
    <figure class="product-figure mb-0 position-relative overflow-hidden">
        <a href="{{ route('produk.show', $produk) }}">
            @if ($produk->gambar)
            <img src="{{ $produk->imageUrl() }}" alt="{{ $produk->nama }}" class="img-fluid product-image w-100" loading="lazy">
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

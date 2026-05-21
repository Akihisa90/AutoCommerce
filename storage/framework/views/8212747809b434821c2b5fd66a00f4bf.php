<?php $__env->startSection('title', 'Katalog Produk Otomotif'); ?>

<?php $__env->startSection('content'); ?>
<!-- Alert Messages -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- Flash Sale Banner -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashSale->count() > 0): ?>
<section class="py-3 bg-danger text-white" data-aos="fade-down">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-lightning-fill fs-4"></i>
                <h5 class="mb-0 fw-bold">FLASH SALE!</h5>
                <span class="badge bg-warning text-dark">Terbatas</span>
            </div>
            <div class="d-flex gap-2 overflow-auto">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $flashSale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('produk.show', $item)); ?>" class="text-white text-decoration-none d-flex align-items-center gap-2 bg-white bg-opacity-25 rounded-pill px-3 py-1 small">
                    <img src="<?php echo e($item->gambar ? asset('storage/' . $item->gambar) : ''); ?>" alt="<?php echo e($item->nama); ?>" class="rounded-circle" style="width:24px;height:24px;object-fit:cover;">
                    <span class="text-truncate" style="max-width:120px;"><?php echo e($item->nama); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->harga_diskon): ?>
                    <span class="badge bg-warning text-dark">-<?php echo e($item->getHargaDiskonPercent()); ?>%</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- Breadcrumb -->
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('katalog')); ?>" class="text-decoration-none">Beranda</a></li>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('kategori')): ?>
        <?php
        $kat = $kategori->firstWhere('id', request('kategori'));
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kat): ?>
        <li class="breadcrumb-item active"><?php echo e($kat->nama); ?></li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php else: ?>
        <li class="breadcrumb-item active">Semua Produk</li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                        <a href="<?php echo e(route('katalog')); ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </a>
                    </div>

                    <form action="<?php echo e(route('katalog')); ?>" method="GET" id="filterForm">
                        <!-- Search -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Pencarian</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari produk, brand..." value="<?php echo e(request('search')); ?>">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Kategori</label>
                            <div class="list-group list-group-flush">
                                <a href="<?php echo e(route('katalog', request()->except('kategori'))); ?>" class="list-group-item list-group-item-action border-0 px-0 py-2 <?php echo e(!request('kategori') ? 'active bg-primary' : ''); ?>">
                                    <i class="bi bi-grid me-2"></i>Semua Kategori
                                </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('katalog', array_merge(request()->except('kategori'), ['kategori' => $k->id]))); ?>" class="list-group-item list-group-item-action border-0 px-0 py-2 <?php echo e(request('kategori') == $k->id ? 'active bg-primary' : ''); ?>">
                                    <?php echo e($k->nama); ?> <span class="badge bg-light text-dark float-end"><?php echo e($k->produk->count()); ?></span>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Rentang Harga -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Rentang Harga</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="harga_min" class="form-control form-control-sm" placeholder="Min" value="<?php echo e(request('harga_min')); ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="harga_max" class="form-control form-control-sm" placeholder="Max" value="<?php echo e(request('harga_max')); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Brand -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($brands->count() > 0): ?>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Merek/Brand</label>
                            <select name="brand" class="form-select form-select-sm">
                                <option value="">Semua Brand</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b); ?>" <?php echo e(request('brand') == $b ? 'selected' : ''); ?>><?php echo e($b); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Warna -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($warnaOptions->count() > 0): ?>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Warna</label>
                            <select name="warna" class="form-select form-select-sm">
                                <option value="">Semua Warna</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $warnaOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($w); ?>" <?php echo e(request('warna') == $w ? 'selected' : ''); ?>><?php echo e($w); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Rating Minimum -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Rating Minimum</label>
                            <div class="d-flex flex-column gap-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [5, 4, 3, 2]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating_min" id="rating<?php echo e($r); ?>" value="<?php echo e($r); ?>" <?php echo e(request('rating_min') == $r ? 'checked' : ''); ?>>
                                    <label class="form-check-label small" for="rating<?php echo e($r); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i <= $r): ?>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <?php else: ?>
                                        <i class="bi bi-star text-muted"></i>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($r > 0): ?>
                                        <span class="text-muted">& up</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rating_min" id="rating0" value="" <?php echo e(!request('rating_min') ? 'checked' : ''); ?>>
                                    <label class="form-check-label small" for="rating0">Semua Rating</label>
                                </div>
                            </div>
                        </div>

                        <!-- Ketersediaan & Pengiriman -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Ketersediaan & Pengiriman</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="stok_tersedia" id="stok_tersedia" value="1" <?php echo e(request('stok_tersedia') ? 'checked' : ''); ?>>
                                <label class="form-check-label small" for="stok_tersedia">Hanya Stok Tersedia</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="gratis_ongkir" id="gratis_ongkir" value="1" <?php echo e(request('gratis_ongkir') ? 'checked' : ''); ?>>
                                <label class="form-check-label small" for="gratis_ongkir"><i class="bi bi-truck text-success me-1"></i>Gratis Ongkir</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="same_day" id="same_day" value="1" <?php echo e(request('same_day') ? 'checked' : ''); ?>>
                                <label class="form-check-label small" for="same_day"><i class="bi bi-lightning text-warning me-1"></i>Same Day Delivery</label>
                            </div>
                        </div>

                        <!-- Label Khusus -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">Label Khusus</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['best_seller' => 'Best Seller', 'diskon' => 'Diskon', 'baru' => 'Baru', 'stok_terbatas' => 'Stok Terbatas', 'flash_sale' => 'Flash Sale']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('katalog', array_merge(request()->except('label'), request('label') == $key ? [] : ['label' => $key]))); ?>" class="btn btn-sm <?php echo e(request('label') == $key ? 'btn-primary' : 'btn-outline-secondary'); ?>">
                                    <?php echo e($label); ?>

                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                Menampilkan <strong><?php echo e($produks->firstItem() ?? 0); ?>-<?php echo e($produks->lastItem() ?? 0); ?></strong> dari <strong><?php echo e($produks->total()); ?></strong> produk
                            </span>
                        </div>
                        <div class="col-md-4">
                            <form action="<?php echo e(route('katalog')); ?>" method="GET" class="d-flex gap-2" id="sortForm">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = request()->except('sort'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($value)): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="<?php echo e($key); ?>[]" value="<?php echo e($v); ?>">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <select name="sort" class="form-select form-select-sm" onchange="document.getElementById('sortForm').submit()">
                                    <option value="relevansi" <?php echo e(request('sort') == 'relevansi' ? 'selected' : ''); ?>>Relevansi</option>
                                    <option value="harga_terendah" <?php echo e(request('sort') == 'harga_terendah' ? 'selected' : ''); ?>>Harga: Terendah</option>
                                    <option value="harga_tertinggi" <?php echo e(request('sort') == 'harga_tertinggi' ? 'selected' : ''); ?>>Harga: Tertinggi</option>
                                    <option value="rating" <?php echo e(request('sort') == 'rating' ? 'selected' : ''); ?>>Rating Tertinggi</option>
                                    <option value="terbaru" <?php echo e(request('sort') == 'terbaru' ? 'selected' : ''); ?>>Terbaru</option>
                                    <option value="terlaris" <?php echo e(request('sort') == 'terlaris' ? 'selected' : ''); ?>>Terlaris</option>
                                    <option value="review" <?php echo e(request('sort') == 'review' ? 'selected' : ''); ?>>Ulasan Terbanyak</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-4 d-flex justify-content-md-end gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary view-toggle <?php echo e(request('view') != 'list' ? 'active bg-primary text-white' : ''); ?>" data-view="grid">
                                <i class="bi bi-grid-3x3-gap"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary view-toggle <?php echo e(request('view') == 'list' ? 'active bg-primary text-white' : ''); ?>" data-view="list">
                                <i class="bi bi-list-ul"></i>
                            </button>
                            <select class="form-select form-select-sm" style="width:auto;" id="perPageSelect" onchange="updatePerPage(this.value)">
                                <option value="12" <?php echo e(request('per_page') == 12 ? 'selected' : ''); ?>>12/hal</option>
                                <option value="24" <?php echo e(request('per_page') == 24 ? 'selected' : ''); ?>>24/hal</option>
                                <option value="48" <?php echo e(request('per_page') == 48 ? 'selected' : ''); ?>>48/hal</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Active Filters -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search', 'kategori', 'brand', 'harga_min', 'harga_max', 'rating_min', 'stok_tersedia', 'gratis_ongkir', 'same_day', 'label'])): ?>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="small fw-semibold text-muted">Filter aktif:</span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Search: "<?php echo e(request('search')); ?>"
                        <a href="<?php echo e(route('katalog', request()->except('search'))); ?>" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('kategori')): ?>
                    <?php $kat = $kategori->firstWhere('id', request('kategori')); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kat): ?>
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Kategori: <?php echo e($kat->nama); ?>

                        <a href="<?php echo e(route('katalog', request()->except('kategori'))); ?>" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('brand')): ?>
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Brand: <?php echo e(request('brand')); ?>

                        <a href="<?php echo e(route('katalog', request()->except('brand'))); ?>" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('harga_min') || request('harga_max')): ?>
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Harga: <?php echo e(request('harga_min') ? 'Rp ' . number_format(request('harga_min')) : '0'); ?> - <?php echo e(request('harga_max') ? 'Rp ' . number_format(request('harga_max')) : '∞'); ?>

                        <a href="<?php echo e(route('katalog', request()->except(['harga_min', 'harga_max']))); ?>" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('rating_min')): ?>
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Rating: <?php echo e(request('rating_min')); ?>+ <i class="bi bi-star-fill text-warning"></i>
                        <a href="<?php echo e(route('katalog', request()->except('rating_min'))); ?>" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('stok_tersedia')): ?>
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        Stok Tersedia
                        <a href="<?php echo e(route('katalog', request()->except('stok_tersedia'))); ?>" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('gratis_ongkir')): ?>
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        <i class="bi bi-truck text-success"></i> Gratis Ongkir
                        <a href="<?php echo e(route('katalog', request()->except('gratis_ongkir'))); ?>" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('label')): ?>
                    <?php
                    $labels = ['best_seller' => 'Best Seller', 'diskon' => 'Diskon', 'baru' => 'Baru', 'stok_terbatas' => 'Stok Terbatas', 'flash_sale' => 'Flash Sale'];
                    ?>
                    <span class="badge bg-light text-dark d-flex align-items-center gap-1">
                        <?php echo e($labels[request('label')] ?? request('label')); ?>

                        <a href="<?php echo e(route('katalog', request()->except('label'))); ?>" class="text-danger"><i class="bi bi-x"></i></a>
                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Products Grid/List -->
                <div id="productContainer" class="<?php echo e(request('view') == 'list' ? 'list-view' : 'grid-view'); ?>">
                    <!-- Grid View -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 grid-container">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $produks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col product-item" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 30); ?>">
                            <div class="product-card rounded-3 overflow-hidden border-0 shadow-sm bg-white d-flex flex-column h-100">
                                <!-- Product Image -->
                                <figure class="product-figure mb-0 position-relative overflow-hidden">
                                    <a href="<?php echo e(route('produk.show', $produk)); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->gambar): ?>
                                        <img src="<?php echo e(asset('storage/' . $produk->gambar)); ?>" alt="<?php echo e($produk->nama); ?>" class="img-fluid product-image w-100" loading="lazy">
                                        <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center product-placeholder">
                                            <i class="bi bi-image display-1 text-muted"></i>
                                        </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </a>

                                    <!-- Labels -->
                                    <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->label == 'best_seller' || $produk->terjual >= 50): ?>
                                        <span class="badge bg-danger"><i class="bi bi-fire me-1"></i>Best Seller</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->harga_diskon && $produk->harga_diskon < $produk->harga): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-tag me-1"></i>-<?php echo e($produk->getHargaDiskonPercent()); ?>%</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->label == 'baru' || $produk->created_at->diffInDays(now()) <= 30): ?>
                                        <span class="badge bg-info"><i class="bi bi-stars me-1"></i>Baru</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->stok <= 10 && $produk->stok > 0): ?>
                                        <span class="badge bg-orange text-white"><i class="bi bi-lightning me-1"></i>Stok Terbatas</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->label == 'flash_sale'): ?>
                                        <span class="badge bg-danger"><i class="bi bi-lightning-fill me-1"></i>Flash Sale</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <!-- Wishlist Button -->
                                    <button type="button" class="btn btn-sm btn-wishlist position-absolute top-0 end-0 m-2 rounded-circle <?php echo e(auth()->check() && $produk->isInWishlist(auth()->id()) ? 'active' : ''); ?>" data-produk="<?php echo e($produk->id); ?>" title="Tambah ke Wishlist">
                                        <i class="bi bi-heart<?php echo e(auth()->check() && $produk->isInWishlist(auth()->id()) ? '-fill' : ''); ?>"></i>
                                    </button>

                                    <!-- Stok Badge -->
                                    <span class="badge stock-badge position-absolute bottom-0 end-0 m-2 <?php echo e($produk->stok > 0 ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo e($produk->stok > 0 ? 'Tersedia' : 'Habis'); ?>

                                    </span>
                                </figure>

                                <!-- Product Info -->
                                <div class="product-info p-3 d-flex flex-column flex-grow-1">
                                    <a href="<?php echo e(route('katalog', ['kategori' => $produk->kategori_id])); ?>" class="text-decoration-none mb-1">
                                        <small class="text-primary text-uppercase fw-semibold" style="font-size:0.7rem;"><?php echo e($produk->kategori->nama ?? 'Umum'); ?></small>
                                    </a>
                                    <a href="<?php echo e(route('produk.show', $produk)); ?>" class="text-decoration-none mb-2">
                                        <h6 class="fw-bold mb-0 text-dark product-title" style="font-size:0.9rem;"><?php echo e($produk->nama); ?></h6>
                                    </a>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->brand): ?>
                                    <small class="text-muted mb-1"><i class="bi bi-award me-1"></i><?php echo e($produk->brand); ?></small>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <!-- Rating & Reviews -->
                                    <div class="d-flex align-items-center gap-1 mb-2">
                                        <div class="rating-stars">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i <= floor($produk->rating)): ?>
                                            <i class="bi bi-star-fill text-warning" style="font-size:0.75rem;"></i>
                                            <?php elseif($i - 0.5 <= $produk->rating): ?>
                                            <i class="bi bi-star-half text-warning" style="font-size:0.75rem;"></i>
                                            <?php else: ?>
                                            <i class="bi bi-star text-muted" style="font-size:0.75rem;"></i>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <span class="small text-muted">(<?php echo e($produk->review_count); ?>)</span>
                                    </div>

                                    <!-- Terjual -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->terjual > 0): ?>
                                    <small class="text-muted mb-1"><i class="bi bi-bag-check me-1"></i><?php echo e($produk->terjual); ?> terjual</small>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <!-- Price -->
                                    <div class="mb-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->harga_diskon && $produk->harga_diskon < $produk->harga): ?>
                                        <span class="text-decoration-line-through text-muted small">Rp <?php echo e(number_format($produk->harga, 0, ',', '.')); ?></span>
                                        <div class="fs-6 fw-bold text-danger">Rp <?php echo e(number_format($produk->harga_diskon, 0, ',', '.')); ?></div>
                                        <?php else: ?>
                                        <span class="fs-6 fw-bold text-primary">Rp <?php echo e(number_format($produk->harga, 0, ',', '.')); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <!-- Shipping badges -->
                                    <div class="d-flex gap-1 mb-2 flex-wrap">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->gratis_ongkir): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success" style="font-size:0.65rem;"><i class="bi bi-truck me-1"></i>Gratis Ongkir</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->same_day_delivery): ?>
                                        <span class="badge bg-warning bg-opacity-10 text-dark" style="font-size:0.65rem;"><i class="bi bi-lightning me-1"></i>Same Day</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-auto product-actions">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                        <form action="<?php echo e(route('keranjang.tambah', $produk)); ?>" method="POST" class="mb-2">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="jumlah" value="1">
                                            <button type="submit" class="btn btn-cart w-100 btn-sm" <?php echo e($produk->stok <= 0 ? 'disabled' : ''); ?>>
                                                <i class="bi bi-cart-plus me-1"></i><?php echo e($produk->stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis'); ?>

                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <a href="<?php echo e(route('login')); ?>" class="btn btn-cart w-100 btn-sm mb-2">
                                            <i class="bi bi-box-arrow-in-right me-1"></i>Login untuk Beli
                                        </a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <a href="<?php echo e(route('produk.show', $produk)); ?>" class="btn btn-detail w-100 btn-sm">
                                            <i class="bi bi-eye me-1"></i>Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="text-center py-5" data-aos="fade-up">
                                <i class="bi bi-search display-1 text-muted mb-3 d-block"></i>
                                <h4>Produk Tidak Ditemukan</h4>
                                <p class="text-muted">Coba ubah kata kunci atau filter pencarian Anda.</p>
                                <a href="<?php echo e(route('katalog')); ?>" class="btn btn-primary mt-2">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Pencarian
                                </a>
                            </div>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- List View (hidden by default) -->
                    <div class="list-container d-none">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $produks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="product-card-list bg-white rounded-3 shadow-sm border-0 p-3 mb-3 d-flex gap-3" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 30); ?>">
                            <a href="<?php echo e(route('produk.show', $produk)); ?>" class="flex-shrink-0" style="width:180px;">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->gambar): ?>
                                <img src="<?php echo e(asset('storage/' . $produk->gambar)); ?>" alt="<?php echo e($produk->nama); ?>" class="img-fluid rounded-2" style="height:150px;object-fit:cover;" loading="lazy">
                                <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center rounded-2" style="height:150px;">
                                    <i class="bi bi-image display-4 text-muted"></i>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </a>
                            <div class="flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <a href="<?php echo e(route('katalog', ['kategori' => $produk->kategori_id])); ?>" class="text-decoration-none">
                                            <small class="text-primary text-uppercase fw-semibold" style="font-size:0.7rem;"><?php echo e($produk->kategori->nama ?? 'Umum'); ?></small>
                                        </a>
                                        <a href="<?php echo e(route('produk.show', $produk)); ?>" class="text-decoration-none">
                                            <h6 class="fw-bold mb-1 text-dark"><?php echo e($produk->nama); ?></h6>
                                        </a>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->brand): ?>
                                        <small class="text-muted"><i class="bi bi-award me-1"></i><?php echo e($produk->brand); ?></small>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-wishlist <?php echo e(auth()->check() && $produk->isInWishlist(auth()->id()) ? 'active' : ''); ?>" data-produk="<?php echo e($produk->id); ?>">
                                        <i class="bi bi-heart<?php echo e(auth()->check() && $produk->isInWishlist(auth()->id()) ? '-fill' : ''); ?>"></i>
                                    </button>
                                </div>

                                <div class="d-flex align-items-center gap-1 my-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i <= floor($produk->rating)): ?>
                                    <i class="bi bi-star-fill text-warning" style="font-size:0.75rem;"></i>
                                    <?php elseif($i - 0.5 <= $produk->rating): ?>
                                    <i class="bi bi-star-half text-warning" style="font-size:0.75rem;"></i>
                                    <?php else: ?>
                                    <i class="bi bi-star text-muted" style="font-size:0.75rem;"></i>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="small text-muted">(<?php echo e($produk->review_count); ?> ulasan)</span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->terjual > 0): ?>
                                    <span class="small text-muted ms-2"><i class="bi bi-bag-check me-1"></i><?php echo e($produk->terjual); ?> terjual</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="d-flex gap-1 mb-2 flex-wrap">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->harga_diskon && $produk->harga_diskon < $produk->harga): ?>
                                    <span class="badge bg-warning text-dark">-<?php echo e($produk->getHargaDiskonPercent()); ?>%</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->label == 'best_seller' || $produk->terjual >= 50): ?>
                                    <span class="badge bg-danger"><i class="bi bi-fire me-1"></i>Best Seller</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->label == 'baru' || $produk->created_at->diffInDays(now()) <= 30): ?>
                                    <span class="badge bg-info"><i class="bi bi-stars me-1"></i>Baru</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->gratis_ongkir): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size:0.65rem;"><i class="bi bi-truck me-1"></i>Gratis Ongkir</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->same_day_delivery): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-dark" style="font-size:0.65rem;"><i class="bi bi-lightning me-1"></i>Same Day</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="mt-auto d-flex align-items-center justify-content-between">
                                    <div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->harga_diskon && $produk->harga_diskon < $produk->harga): ?>
                                        <span class="text-decoration-line-through text-muted small">Rp <?php echo e(number_format($produk->harga, 0, ',', '.')); ?></span>
                                        <div class="fs-5 fw-bold text-danger">Rp <?php echo e(number_format($produk->harga_diskon, 0, ',', '.')); ?></div>
                                        <?php else: ?>
                                        <span class="fs-5 fw-bold text-primary">Rp <?php echo e(number_format($produk->harga, 0, ',', '.')); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                        <form action="<?php echo e(route('keranjang.tambah', $produk)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="jumlah" value="1">
                                            <button type="submit" class="btn btn-cart btn-sm" <?php echo e($produk->stok <= 0 ? 'disabled' : ''); ?>>
                                                <i class="bi bi-cart-plus me-1"></i>Keranjang
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <a href="<?php echo e(route('login')); ?>" class="btn btn-cart btn-sm">
                                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                                        </a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <a href="<?php echo e(route('produk.show', $produk)); ?>" class="btn btn-detail btn-sm">
                                            <i class="bi bi-eye me-1"></i>Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-search display-1 text-muted mb-3 d-block"></i>
                            <h4>Produk Tidak Ditemukan</h4>
                            <p class="text-muted">Coba ubah kata kunci atau filter pencarian Anda.</p>
                            <a href="<?php echo e(route('katalog')); ?>" class="btn btn-primary mt-2">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Pencarian
                            </a>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produks->hasPages()): ?>
                <div class="mt-4 d-flex justify-content-center" data-aos="fade-up">
                    <?php echo e($produks->links('vendor.pagination.bootstrap-5')); ?>

                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Rekomendasi Section -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rekomendasi->count() > 0 && $produks->count() > 0): ?>
                <div class="mt-5 pt-4 border-top" data-aos="fade-up">
                    <h5 class="fw-bold mb-4"><i class="bi bi-lightbulb text-warning me-2"></i>Pelanggan Juga Membeli</h5>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rekomendasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col">
                            <a href="<?php echo e(route('produk.show', $rec)); ?>" class="text-decoration-none">
                                <div class="bg-white rounded-3 shadow-sm p-2 text-center h-100">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rec->gambar): ?>
                                    <img src="<?php echo e(asset('storage/' . $rec->gambar)); ?>" alt="<?php echo e($rec->nama); ?>" class="img-fluid rounded-2 mb-2" style="height:100px;object-fit:cover;" loading="lazy">
                                    <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded-2 mb-2" style="height:100px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <small class="text-dark fw-semibold d-block text-truncate"><?php echo e($rec->nama); ?></small>
                                    <small class="text-primary fw-bold">Rp <?php echo e(number_format($rec->harga, 0, ',', '.')); ?></small>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
        <form action="<?php echo e(route('katalog')); ?>" method="GET">
            <!-- Search -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Pencarian</label>
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?php echo e(request('search')); ?>">
            </div>

            <!-- Kategori -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k->id); ?>" <?php echo e(request('kategori') == $k->id ? 'selected' : ''); ?>><?php echo e($k->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>

            <!-- Harga -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Rentang Harga</label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" name="harga_min" class="form-control" placeholder="Min" value="<?php echo e(request('harga_min')); ?>">
                    </div>
                    <div class="col-6">
                        <input type="number" name="harga_max" class="form-control" placeholder="Max" value="<?php echo e(request('harga_max')); ?>">
                    </div>
                </div>
            </div>

            <!-- Brand -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($brands->count() > 0): ?>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Brand</label>
                <select name="brand" class="form-select">
                    <option value="">Semua Brand</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b); ?>" <?php echo e(request('brand') == $b ? 'selected' : ''); ?>><?php echo e($b); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Rating -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Rating Minimum</label>
                <select name="rating_min" class="form-select">
                    <option value="">Semua Rating</option>
                    <option value="5" <?php echo e(request('rating_min') == '5' ? 'selected' : ''); ?>>5 Bintang</option>
                    <option value="4" <?php echo e(request('rating_min') == '4' ? 'selected' : ''); ?>>4+ Bintang</option>
                    <option value="3" <?php echo e(request('rating_min') == '3' ? 'selected' : ''); ?>>3+ Bintang</option>
                </select>
            </div>

            <!-- Checkboxes -->
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="stok_tersedia" id="m_stok" value="1" <?php echo e(request('stok_tersedia') ? 'checked' : ''); ?>>
                    <label class="form-check-label small" for="m_stok">Stok Tersedia</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="gratis_ongkir" id="m_ongkir" value="1" <?php echo e(request('gratis_ongkir') ? 'checked' : ''); ?>>
                    <label class="form-check-label small" for="m_ongkir">Gratis Ongkir</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="same_day" id="m_sameday" value="1" <?php echo e(request('same_day') ? 'checked' : ''); ?>>
                    <label class="form-check-label small" for="m_sameday">Same Day Delivery</label>
                </div>
            </div>

            <!-- Sort -->
            <div class="mb-3">
                <label class="form-label fw-semibold small">Urutkan</label>
                <select name="sort" class="form-select">
                    <option value="relevansi" <?php echo e(request('sort') == 'relevansi' ? 'selected' : ''); ?>>Relevansi</option>
                    <option value="harga_terendah" <?php echo e(request('sort') == 'harga_terendah' ? 'selected' : ''); ?>>Harga Terendah</option>
                    <option value="harga_tertinggi" <?php echo e(request('sort') == 'harga_tertinggi' ? 'selected' : ''); ?>>Harga Tertinggi</option>
                    <option value="rating" <?php echo e(request('sort') == 'rating' ? 'selected' : ''); ?>>Rating Tertinggi</option>
                    <option value="terbaru" <?php echo e(request('sort') == 'terbaru' ? 'selected' : ''); ?>>Terbaru</option>
                    <option value="terlaris" <?php echo e(request('sort') == 'terlaris' ? 'selected' : ''); ?>>Terlaris</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
            <a href="<?php echo e(route('katalog')); ?>" class="btn btn-outline-secondary w-100 mt-2">Reset</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/katalog.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
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
            <?php if(auth()->guard()->check()): ?>
            const produkId = this.dataset.produk;
            const icon = this.querySelector('i');

            fetch(`/wishlist/toggle/${produkId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
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
            <?php else: ?>
            window.location.href = '<?php echo e(route("login")); ?>';
            <?php endif; ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Ahmad\XAMPP\php\www\otomotif-shop-bak1\otomotif-shop\resources\views/katalog/index.blade.php ENDPATH**/ ?>
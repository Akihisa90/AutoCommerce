<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<!-- Welcome Banner -->
<section class="jarallax hero-section py-5" style="background-image: url('<?php echo e(asset('frontend/images/banner-1.jpg')); ?>'); min-height: 400px;">
    <div class="hero-overlay"></div>
    <div class="container py-5 position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <h1 class="hero-title fw-bold mb-3 text-white">
                    Selamat Datang, <?php echo e(auth()->user()->name); ?>!
                </h1>
                <p class="hero-lead mb-4">
                    Temukan sparepart dan aksesoris terbaik untuk kendaraan Anda. Belanja mudah, harga terjangkau, pengiriman cepat.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?php echo e(route('katalog')); ?>" class="btn btn-accent btn-lg hero-btn">
                        <i class="bi bi-bag me-2"></i>Mulai Belanja
                    </a>
                    <a href="<?php echo e(route('pesanan.index')); ?>" class="btn btn-outline-light btn-lg hero-btn">
                        <i class="bi bi-receipt me-2"></i>Pesanan Saya
                    </a>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block" data-aos="fade-left" data-aos-delay="200">
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <div class="hero-feature">
                        <i class="bi bi-truck fs-1 text-accent mb-2 d-block"></i>
                        <p class="text-white mb-0 small fw-semibold">Gratis Ongkir</p>
                    </div>
                    <div class="hero-feature">
                        <i class="bi bi-shield-check fs-1 text-accent mb-2 d-block"></i>
                        <p class="text-white mb-0 small fw-semibold">Garansi Resmi</p>
                    </div>
                    <div class="hero-feature">
                        <i class="bi bi-headset fs-1 text-accent mb-2 d-block"></i>
                        <p class="text-white mb-0 small fw-semibold">Support 24/7</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-card text-center p-3 rounded-3 bg-light">
                    <i class="bi bi-box-seam fs-2 text-primary mb-2 d-block"></i>
                    <h4 class="fw-bold mb-0"><?php echo e($produkTerbaru->count()); ?>+</h4>
                    <small class="text-muted">Produk Baru</small>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="50">
                <div class="stat-card text-center p-3 rounded-3 bg-light">
                    <i class="bi bi-fire fs-2 text-danger mb-2 d-block"></i>
                    <h4 class="fw-bold mb-0"><?php echo e($bestSeller->count()); ?></h4>
                    <small class="text-muted">Best Seller</small>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card text-center p-3 rounded-3 bg-light">
                    <i class="bi bi-tag fs-2 text-warning mb-2 d-block"></i>
                    <h4 class="fw-bold mb-0"><?php echo e($produkDiskon->count()); ?></h4>
                    <small class="text-muted">Sedang Diskon</small>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="150">
                <div class="stat-card text-center p-3 rounded-3 bg-light">
                    <i class="bi bi-grid fs-2 text-success mb-2 d-block"></i>
                    <h4 class="fw-bold mb-0"><?php echo e($kategori->count()); ?></h4>
                    <small class="text-muted">Kategori</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pesanan Terbaru (jika ada) -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pesananTerbaru && $pesananTerbaru->count() > 0): ?>
<section class="py-4 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</h5>
            <a href="<?php echo e(route('pesanan.index')); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row g-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pesananTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold small">Order #<?php echo e($pesanan->id); ?></span>
                            <?php
                            $statusColors = [
                                'pending' => 'bg-warning text-dark',
                                'dibayar' => 'bg-info',
                                'diproses' => 'bg-primary',
                                'dikirim' => 'bg-success',
                                'selesai' => 'bg-success',
                                'dibatalkan' => 'bg-danger'
                            ];
                            $statusLabel = ucfirst($pesanan->status ?? 'pending');
                            ?>
                            <span class="badge <?php echo e($statusColors[$pesanan->status ?? 'pending'] ?? 'bg-secondary'); ?>"><?php echo e($statusLabel); ?></span>
                        </div>
                        <p class="text-muted small mb-1"><i class="bi bi-calendar me-1"></i><?php echo e($pesanan->created_at->format('d M Y')); ?></p>
                        <p class="fw-bold text-primary mb-2">Rp <?php echo e(number_format($pesanan->total, 0, ',', '.')); ?></p>
                        <a href="<?php echo e(route('pesanan.show', $pesanan)); ?>" class="btn btn-sm btn-outline-primary w-100">Detail</a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- Flash Sale -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashSale->count() > 0): ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1"><i class="bi bi-lightning-fill text-danger me-2"></i>Flash Sale</h3>
                <p class="text-muted mb-0">Penawaran terbatas, jangan sampai kehabisan!</p>
            </div>
            <a href="<?php echo e(route('katalog', ['label' => 'flash_sale'])); ?>" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
        </div>
        <div class="row row-cols-2 row-cols-md-4 g-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $flashSale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                <div class="flash-sale-card bg-white rounded-3 shadow-sm overflow-hidden border-0 h-100">
                    <a href="<?php echo e(route('produk.show', $produk)); ?>" class="text-decoration-none">
                        <div class="position-relative">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produk->gambar): ?>
                            <img src="<?php echo e(asset('storage/' . $produk->gambar)); ?>" alt="<?php echo e($produk->nama); ?>" class="img-fluid w-100" style="height:150px;object-fit:cover;">
                            <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height:150px;">
                                <i class="bi bi-image display-4 text-muted"></i>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                -<?php echo e($produk->getHargaDiskonPercent()); ?>%
                            </span>
                        </div>
                        <div class="p-3">
                            <h6 class="fw-bold mb-1 text-dark text-truncate"><?php echo e($produk->nama); ?></h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-danger fw-bold">Rp <?php echo e(number_format($produk->harga_diskon, 0, ',', '.')); ?></span>
                                <small class="text-decoration-line-through text-muted">Rp <?php echo e(number_format($produk->harga, 0, ',', '.')); ?></small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- Kategori -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1">Kategori Produk</h3>
            <p class="text-muted">Temukan sparepart sesuai jenis kendaraan Anda</p>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                <a href="<?php echo e(route('katalog', ['kategori' => $kat->id])); ?>" class="text-decoration-none">
                    <div class="category-box bg-white rounded-3 shadow-sm overflow-hidden h-100">
                        <?php
                            $produkGambar = $kat->produk->first();
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produkGambar && $produkGambar->gambar): ?>
                        <div class="category-image-wrapper position-relative">
                            <img src="<?php echo e(asset('storage/' . $produkGambar->gambar)); ?>" alt="<?php echo e($kat->nama); ?>" class="img-fluid w-100 category-image" style="height:150px;object-fit:cover;">
                            <div class="category-overlay"></div>
                        </div>
                        <?php else: ?>
                        <div class="category-icon-placeholder bg-light d-flex align-items-center justify-content-center" style="height:150px;">
                            <i class="bi bi-tools fs-1 text-primary"></i>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="p-3 text-center">
                            <h6 class="fw-bold mb-1 text-dark"><?php echo e($kat->nama); ?></h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary"><?php echo e($kat->produk_count); ?> produk</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<!-- Produk Terbaru -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1"><i class="bi bi-stars text-warning me-2"></i>Produk Terbaru</h3>
                <p class="text-muted mb-0">Koleksi terbaru untuk kendaraan Anda</p>
            </div>
            <a href="<?php echo e(route('katalog', ['sort' => 'terbaru'])); ?>" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $produkTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                <?php echo $__env->make('katalog.partials.product-card', ['produk' => $produk], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<!-- Best Seller -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1"><i class="bi bi-fire text-danger me-2"></i>Best Seller</h3>
                <p class="text-muted mb-0">Produk paling laris dibeli pelanggan</p>
            </div>
            <a href="<?php echo e(route('katalog', ['sort' => 'terlaris'])); ?>" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bestSeller; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                <?php echo $__env->make('katalog.partials.product-card', ['produk' => $produk], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>

<!-- Promo Banner -->
<section class="py-5" style="background: linear-gradient(135deg, #1B4D3E, #2D6B54);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white mb-4 mb-lg-0" data-aos="fade-right">
                <h2 class="fw-bold mb-3">Dapatkan Diskon Hingga 50%!</h2>
                <p class="lead mb-4 text-white-50">Gunakan kode promo <strong class="text-warning">OTOMOTIF50</strong> untuk pembelian pertama Anda.</p>
                <a href="<?php echo e(route('katalog')); ?>" class="btn btn-accent btn-lg">
                    <i class="bi bi-bag me-2"></i>Belanja Sekarang
                </a>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="promo-card bg-white rounded-3 p-4 text-center">
                            <i class="bi bi-truck fs-1 text-success mb-2 d-block"></i>
                            <h6 class="fw-bold">Gratis Ongkir</h6>
                            <small class="text-muted">Untuk pembelian semua pembelian</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="promo-card bg-white rounded-3 p-4 text-center">
                            <i class="bi bi-shield-check fs-1 text-primary mb-2 d-block"></i>
                            <h6 class="fw-bold">Garansi Resmi</h6>
                            <small class="text-muted">Semua produk bergaransi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
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
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                })
                .then(res => res.json())
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
                .catch(err => console.error('Wishlist error:', err));
            <?php else: ?>
            window.location.href = '<?php echo e(route("login")); ?>';
            <?php endif; ?>
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Ahmad\XAMPP\php\www\otomotif-shop-bak1\otomotif-shop\resources\views/dashboard/index.blade.php ENDPATH**/ ?>
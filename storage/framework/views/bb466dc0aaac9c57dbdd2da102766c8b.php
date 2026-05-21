<?php $__env->startSection('title', 'Riwayat Pesanan'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('katalog')); ?>" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item active">Pesanan Saya</li>
    </ol>
</nav>

<!-- Orders Section -->
<section class="py-5">
    <div class="container">
        <h1 class="text-center mb-2" data-aos="fade-up">
            <i class="bi bi-receipt me-2"></i>Riwayat Pesanan
        </h1>
        <p class="text-muted text-center mb-5" data-aos="fade-up" data-aos-delay="50">
            Pantau status pesanan Anda di sini
        </p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
            <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pesanan->isEmpty()): ?>
        <!-- No Orders -->
        <div class="text-center py-5" data-aos="fade-up">
            <div class="mb-4">
                <i class="bi bi-receipt display-1 text-muted"></i>
            </div>
            <h3 class="fw-bold mb-2">Belum Ada Pesanan</h3>
            <p class="text-muted mb-4">Anda belum memiliki pesanan. Yuk, mulai belanja!</p>
            <a href="<?php echo e(route('katalog')); ?>" class="btn btn-primary btn-lg">
                <i class="bi bi-grid me-2"></i>Mulai Belanja
            </a>
        </div>
        <?php else: ?>
        <!-- Orders Timeline -->
        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="fade-up">
                <div class="accordion" id="ordersAccordion">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="accordion-item mb-3 border-0 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header" id="heading<?php echo e($order->id); ?>">
                            <div class="d-flex">
                                <button class="accordion-button <?php echo e($loop->first ? '' : 'collapsed'); ?>" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($order->id); ?>"
                                    aria-expanded="<?php echo e($loop->first ? 'true' : 'false'); ?>"
                                    aria-controls="collapse<?php echo e($order->id); ?>">
                                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                        <div>
                                            <a href="<?php echo e(route('pesanan.show', $order)); ?>" class="fw-bold text-decoration-none">
                                                #TRX<?php echo e(str_pad($order->id, 6, '0', STR_PAD_LEFT)); ?>

                                            </a>
                                            <span class="text-muted ms-3 small">
                                                <i class="bi bi-calendar me-1"></i><?php echo e($order->created_at->format('d M Y')); ?>

                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-<?php echo e(match($order->status) {
                                            'pending' => 'warning',
                                            'diproses' => 'info',
                                            'dikirim' => 'primary',
                                            'selesai' => 'success',
                                            'dibatalkan' => 'danger',
                                            default => 'secondary'
                                        }); ?> px-3 py-2">
                                            <i class="bi bi-<?php echo e(match($order->status) {
                                                'pending' => 'clock',
                                                'diproses' => 'gear',
                                                'dikirim' => 'truck',
                                                'selesai' => 'check-circle',
                                                'dibatalkan' => 'x-circle',
                                                default => 'question-circle'
                                            }); ?> me-1"></i>
                                            <?php echo e(match($order->status) {
                                                'pending' => 'Menunggu Pembayaran',
                                                'diproses' => 'Sedang Diproses',
                                                'dikirim' => 'Dikirim',
                                                'selesai' => 'Selesai',
                                                'dibatalkan' => 'Dibatalkan',
                                                default => $order->status
                                            }); ?>

                                        </span>
                                        <span class="fw-bold text-primary">Rp <?php echo e(number_format($order->total_bayar ?? $order->total, 0, ',', '.')); ?></span>
                                    </div>
                                </div>
                                </button>
                                <a href="<?php echo e(route('pesanan.show', $order)); ?>" class="btn btn-sm btn-outline-primary me-2" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'pending'): ?>
                                <form action="<?php echo e(route('pesanan.cancel', $order)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Pesanan">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </h2>
                        <div id="collapse<?php echo e($order->id); ?>"
                            class="accordion-collapse collapse <?php echo e($loop->first ? 'show' : ''); ?>"
                            aria-labelledby="heading<?php echo e($order->id); ?>"
                            data-bs-parent="#ordersAccordion">
                            <div class="accordion-body p-4">
                                <!-- Shipping Info -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <h6 class="fw-bold mb-2">
                                                    <i class="bi bi-wallet2 me-1 text-primary"></i>Pembayaran
                                                </h6>
                                                <p class="mb-0 small fw-bold">
                                                    <?php
                                                        $paymentLabels = [
                                                            'cod' => 'COD',
                                                            'qris' => 'QRIS',
                                                            'dana' => 'DANA',
                                                            'transfer_bank' => 'Transfer Bank',
                                                        ];
                                                    ?>
                                                    <?php echo e($paymentLabels[$order->metode_pembayaran] ?? ($order->metode_pembayaran ?? '-')); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <h6 class="fw-bold mb-2">
                                                    <i class="bi bi-geo-alt me-1 text-primary"></i>Alamat
                                                </h6>
                                                <p class="mb-0 small"><?php echo e($order->alamat); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <h6 class="fw-bold mb-2">
                                                    <i class="bi bi-person me-1 text-primary"></i>Penerima
                                                </h6>
                                                <p class="mb-0 small fw-bold"><?php echo e($order->nama_pembeli); ?></p>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->no_telepon): ?>
                                                <p class="mb-0 small text-muted"><i class="bi bi-telephone me-1"></i><?php echo e($order->no_telepon); ?></p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <p class="mb-0 small text-muted"><?php echo e($order->user->email); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Items -->
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-box-seam me-1"></i>Detail Produk
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 40%;">Produk</th>
                                                <th class="text-center" style="width: 15%;">Harga</th>
                                                <th class="text-center" style="width: 10%;">Jumlah</th>
                                                <th class="text-end" style="width: 15%;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->detailTransaksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo e($detail->nama_produk); ?></strong>
                                                </td>
                                                <td class="text-center">Rp <?php echo e(number_format($detail->harga_satuan, 0, ',', '.')); ?></td>
                                                <td class="text-center"><?php echo e($detail->jumlah); ?></td>
                                                <td class="text-end fw-bold">Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="border-top">
                                                <td colspan="3" class="text-end text-muted">Subtotal:</td>
                                                <td class="text-end fw-bold">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></td>
                                            </tr>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->diskon_voucher > 0): ?>
                                            <tr>
                                                <td colspan="3" class="text-end text-success">
                                                    <i class="bi bi-ticket-perforated me-1"></i>Diskon:
                                                </td>
                                                <td class="text-end text-success fw-bold">- Rp <?php echo e(number_format($order->diskon_voucher, 0, ',', '.')); ?></td>
                                            </tr>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <tr class="border-top">
                                                <td colspan="3" class="text-end fw-bold fs-6">Total Bayar:</td>
                                                <td class="text-end fw-bold fs-5 text-primary">
                                                    Rp <?php echo e(number_format($order->total_bayar ?? $order->total, 0, ',', '.')); ?>

                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/pesanan.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    AOS.init({ duration: 800, once: true, offset: 100 });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Ahmad\XAMPP\php\www\otomotif-shop-bak1\otomotif-shop\resources\views/pesanan/index.blade.php ENDPATH**/ ?>
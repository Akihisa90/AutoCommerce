@extends('layouts.app')

@section('title', 'Detail Pesanan #TRX' . str_pad($pesanan->id, 6, '0', STR_PAD_LEFT))

@section('content')
<!-- Breadcrumb -->
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pesanan.index') }}" class="text-decoration-none">Pesanan Saya</a></li>
        <li class="breadcrumb-item active">Detail #TRX{{ str_pad($pesanan->id, 6, '0', STR_PAD_LEFT) }}</li>
    </ol>
</nav>

<!-- Detail Pesanan Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="text-center mb-2" data-aos="fade-up">
                    <i class="bi bi-receipt me-2"></i>Detail Pesanan
                </h1>
                <p class="text-muted text-center mb-5" data-aos="fade-up" data-aos-delay="50">
                    Informasi lengkap pesanan Anda
                </p>

                <!-- Order Header Card -->
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h5 class="fw-bold mb-1">#TRX{{ str_pad($pesanan->id, 6, '0', STR_PAD_LEFT) }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar me-1"></i>{{ $pesanan->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ match($pesanan->status) {
                                    'pending' => 'warning',
                                    'diproses' => 'info',
                                    'dikirim' => 'primary',
                                    'selesai' => 'success',
                                    'dibatalkan' => 'danger',
                                    default => 'secondary'
                                } }} px-3 py-2 fs-6">
                                    <i class="bi bi-{{ match($pesanan->status) {
                                        'pending' => 'clock',
                                        'diproses' => 'gear',
                                        'dikirim' => 'truck',
                                        'selesai' => 'check-circle',
                                        'dibatalkan' => 'x-circle',
                                        default => 'question-circle'
                                    } }} me-1"></i>
                                    {{ match($pesanan->status) {
                                        'pending' => 'Menunggu Pembayaran',
                                        'diproses' => 'Sedang Diproses',
                                        'dikirim' => 'Dikirim',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                        default => $pesanan->status
                                    } }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-wallet2 me-1 text-primary"></i>Metode Pembayaran
                                </h6>
                                <p class="mb-0 small fw-bold">
                                    @php
                                        $paymentLabels = [
                                            'cod' => 'Bayar di Tempat (COD)',
                                            'qris' => 'QRIS',
                                            'dana' => 'DANA',
                                            'transfer_bank' => 'Transfer Bank',
                                        ];
                                    @endphp
                                    {{ $paymentLabels[$pesanan->metode_pembayaran] ?? ($pesanan->metode_pembayaran ?? 'Belum dipilih') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Info -->
                <div class="row mb-4" data-aos="fade-up">
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-geo-alt me-1 text-primary"></i>Alamat Pengiriman
                                </h6>
                                <p class="mb-0 small">{{ $pesanan->alamat }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-person me-1 text-primary"></i>Penerima
                                </h6>
                                <p class="mb-0 small fw-bold">{{ $pesanan->nama_pembeli }}</p>
                                @if($pesanan->no_telepon)
                                <p class="mb-0 small text-muted"><i class="bi bi-telephone me-1"></i>{{ $pesanan->no_telepon }}</p>
                                @endif
                                <p class="mb-0 small text-muted">{{ $pesanan->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-box-seam me-1"></i>Detail Produk
                        </h5>
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
                                    @foreach($pesanan->detailTransaksi as $detail)
                                    <tr>
                                        <td>
                                            <strong>{{ $detail->nama_produk }}</strong>
                                        </td>
                                        <td class="text-center">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $detail->jumlah }}</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-top">
                                        <td colspan="3" class="text-end text-muted">Subtotal:</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($pesanan->diskon_voucher > 0)
                                    <tr>
                                        <td colspan="3" class="text-end text-success">
                                            <i class="bi bi-ticket-perforated me-1"></i>Diskon Voucher ({{ $pesanan->voucher->kode ?? '' }}):
                                        </td>
                                        <td class="text-end text-success fw-bold">- Rp {{ number_format($pesanan->diskon_voucher, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="text-end text-muted">Ongkir:</td>
                                        <td class="text-end text-success fw-bold">Gratis</td>
                                    </tr>
                                    <tr class="border-top">
                                        <td colspan="3" class="text-end fw-bold fs-6">Total Bayar:</td>
                                        <td class="text-end fw-bold fs-4 text-primary">
                                            Rp {{ number_format($pesanan->total_bayar ?? $pesanan->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                @if($pesanan->status === 'pending')
                <div class="row g-4 mb-4" data-aos="fade-up">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">
                                    <i class="bi bi-info-circle me-1 text-primary"></i>Instruksi Pembayaran
                                </h5>

                        @if($pesanan->metode_pembayaran === 'cod')
                            <div class="alert alert-success mb-0">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-hand-thumbs-up me-1"></i>COD (Bayar di Tempat)
                                </h6>
                                <p class="mb-2 small">Pesanan Anda akan diproses setelah admin mengkonfirmasi. Siapkan uang tunai saat kurir tiba.</p>
                                <ul class="mb-0 small">
                                    <li>Pastikan Anda berada di lokasi saat kurir tiba</li>
                                    <li>Siapkan uang pas untuk pembayaran</li>
                                    <li>Jika tidak ada/tolak barang, biaya pengiriman tetap dibebankan</li>
                                </ul>
                            </div>

                        @elseif($pesanan->metode_pembayaran === 'qris')
                            <div class="alert alert-primary mb-0">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-qr-code me-1"></i>QRIS
                                </h6>
                                <p class="mb-2 small">Scan QR Code QRIS menggunakan aplikasi e-wallet atau mobile banking pilihan Anda (GoPay, OVO, ShopeePay, DANA, LinkAja, m-Banking, dll.)</p>
                                <ul class="mb-0 small">
                                    <li>Pembayaran akan dikonfirmasi otomatis</li>
                                    <li>Batas waktu pembayaran: 15 menit</li>
                                    <li>Jika QR Code expired, hubungi admin untuk QR baru</li>
                                </ul>
                            </div>

                        @elseif($pesanan->metode_pembayaran === 'dana')
                            @if($pesanan->sub_tipe_pembayaran === 'qr_code')
                                <div class="alert alert-info mb-0">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-qr-code me-1"></i>DANA via QR Code
                                    </h6>
                                    <p class="mb-2 small">Buka aplikasi DANA, pilih <strong>"Scan QR"</strong> dan arahkan kamera ke QR Code yang ditampilkan di halaman checkout.</p>
                                    <ul class="mb-0 small">
                                        <li>Pembayaran dikonfirmasi otomatis setelah scan berhasil</li>
                                        <li>Pastikan saldo DANA Anda mencukupi</li>
                                    </ul>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-phone me-1"></i>DANA via Nomor HP
                                    </h6>
                                    <p class="mb-2 small">Buka aplikasi DANA, pilih <strong>"Kirim"</strong> dan masukkan nomor DANA toko yang tertera di halaman checkout.</p>
                                    <ul class="mb-0 small">
                                        <li>Transfer nominal TEPAT sesuai total pesanan</li>
                                        <li>Setelah transfer, upload bukti pembayaran di bawah</li>
                                        <li>Batas waktu pembayaran: 24 jam</li>
                                    </ul>
                                </div>
                            @endif

                        @elseif($pesanan->metode_pembayaran === 'transfer_bank')
                            <div class="alert alert-warning mb-0">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-bank me-1"></i>Transfer Bank
                                </h6>
                                <p class="mb-2 small">Transfer ke rekening bank yang tertera di halaman checkout. Pastikan nominal transfer TEPAT sama dengan total pesanan.</p>
                                <ul class="mb-0 small">
                                    <li>Nominal transfer harus TEPAT sama dengan tagihan</li>
                                    <li>Setelah transfer, upload bukti pembayaran di bawah</li>
                                    <li>Batas waktu pembayaran: 24 jam</li>
                                    <li>Admin akan memverifikasi bukti pembayaran dalam 1x24 jam</li>
                                </ul>
                            </div>
                        @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3 text-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Batalkan Pesanan
                                </h5>
                                <p class="text-muted small mb-3">
                                    Jika Anda ingin membatalkan pesanan ini, stok produk akan dikembalikan dan voucher (jika digunakan) akan dikembalikan ke akun Anda.
                                </p>
                                <form action="{{ route('pesanan.cancel', $pesanan) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-x-circle me-1"></i>Batalkan Pesanan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Proof Upload / Display -->
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-camera me-1 text-primary"></i>Bukti Pembayaran
                        </h5>

                        @if($pesanan->bukti_pembayaran)
                            <div class="text-center">
                                <img src="{{ Storage::url($pesanan->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                                     class="img-fluid rounded shadow-sm" style="max-width: 400px;">
                                <p class="text-success small mt-2">
                                    <i class="bi bi-check-circle me-1"></i>Bukti pembayaran sudah diupload. Menunggu verifikasi admin.
                                </p>
                            </div>
                        @else
                            @if(in_array($pesanan->metode_pembayaran, ['transfer_bank', 'dana']))
                                @if($pesanan->metode_pembayaran === 'dana' && $pesanan->sub_tipe_pembayaran === 'qr_code')
                                    <p class="text-muted small">
                                        Pembayaran via QR Code dikonfirmasi otomatis. Tidak perlu upload bukti.
                                    </p>
                                @else
                                    <form action="{{ route('pesanan.upload.bukti', $pesanan) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <p class="text-muted small mb-2">Upload screenshot bukti transfer Anda di sini:</p>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" name="bukti_pembayaran" accept="image/*" required>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-upload me-1"></i>Upload
                                            </button>
                                        </div>
                                        <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                                    </form>
                                @endif
                            @else
                                <p class="text-muted small">
                                    @if($pesanan->metode_pembayaran === 'cod')
                                        Pembayaran dilakukan saat barang diterima. Tidak perlu upload bukti.
                                    @elseif($pesanan->metode_pembayaran === 'qris')
                                        Pembayaran QRIS dikonfirmasi otomatis. Tidak perlu upload bukti.
                                    @endif
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
                @endif

                <!-- Status Timeline -->
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-clock-history me-1"></i>Riwayat Status
                        </h5>
                        <div class="timeline">
                            <div class="d-flex gap-3 mb-3">
                                <div class="text-center" style="min-width: 40px;">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <i class="bi bi-check text-white small"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold small">Pesanan Dibuat</p>
                                    <p class="mb-0 text-muted small">{{ $pesanan->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @if(in_array($pesanan->status, ['diproses', 'dikirim', 'selesai']))
                            <div class="d-flex gap-3 mb-3">
                                <div class="text-center" style="min-width: 40px;">
                                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <i class="bi bi-gear text-white small"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold small">Sedang Diproses</p>
                                    <p class="mb-0 text-muted small">{{ $pesanan->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @endif
                            @if(in_array($pesanan->status, ['dikirim', 'selesai']))
                            <div class="d-flex gap-3 mb-3">
                                <div class="text-center" style="min-width: 40px;">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <i class="bi bi-truck text-white small"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold small">Dikirim</p>
                                    <p class="mb-0 text-muted small">{{ $pesanan->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @endif
                            @if($pesanan->status === 'selesai')
                            <div class="d-flex gap-3 mb-3">
                                <div class="text-center" style="min-width: 40px;">
                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <i class="bi bi-check-lg text-white small"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold small">Selesai</p>
                                    <p class="mb-0 text-muted small">{{ $pesanan->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @endif
                            @if($pesanan->status === 'dibatalkan')
                            <div class="d-flex gap-3 mb-3">
                                <div class="text-center" style="min-width: 40px;">
                                    <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <i class="bi bi-x-lg text-white small"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold small">Pesanan Dibatalkan</p>
                                    <p class="mb-0 text-muted small">{{ $pesanan->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Rating Section (only for completed orders) -->
                @if($pesanan->status === 'selesai')
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-star me-2 text-warning"></i>Beri Rating & Ulasan
                        </h5>
                        <p class="text-muted small mb-4">Berikan rating untuk produk yang Anda beli. Ulasan Anda sangat membantu pelanggan lain!</p>

                        @foreach($pesanan->detailTransaksi as $detail)
                        @php
                            $existingReview = $pesanan->reviews->where('produk_id', $detail->produk_id)->first();
                        @endphp
                        <div class="border rounded-3 p-3 mb-3 {{ $existingReview ? 'bg-light' : '' }}">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                @if($detail->produk->gambar)
                                <img src="{{ asset('storage/' . $detail->produk->gambar) }}" alt="{{ $detail->produk->nama }}" class="rounded-2" style="width:60px;height:60px;object-fit:cover;">
                                @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded-2" style="width:60px;height:60px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $detail->produk->nama }}</h6>
                                    <small class="text-muted">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }} x {{ $detail->jumlah }}</small>
                                </div>
                            </div>

                            @if($existingReview)
                            <div class="bg-white rounded-3 p-3">
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill text-warning" style="font-size:1rem;"></i>
                                    @endfor
                                </div>
                                @if($existingReview->komentar)
                                <p class="text-muted small mb-0">"{{ $existingReview->komentar }}"</p>
                                @endif
                                <small class="text-muted">{{ $existingReview->created_at->format('d M Y') }}</small>
                            </div>
                            @else
                            <form action="{{ route('pesanan.review', $pesanan) }}" method="POST">
                                @csrf
                                <input type="hidden" name="detail_transaksi_id" value="{{ $detail->id }}">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Rating</label>
                                    <div class="rating-input d-flex gap-1" data-produk="{{ $detail->id }}">
                                        @for($i = 1; $i <= 5; $i++)
                                        <button type="button" class="btn btn-outline-warning btn-sm rating-star p-2" data-rating="{{ $i }}" data-produk="{{ $detail->id }}">
                                            <i class="bi bi-star"></i>
                                        </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="rating-{{ $detail->id }}" value="0" required>
                                </div>
                                <div class="mb-2">
                                    <label for="komentar-{{ $detail->id }}" class="form-label fw-semibold small">Ulasan (opsional)</label>
                                    <textarea class="form-control form-control-sm" id="komentar-{{ $detail->id }}" name="komentar" rows="2" placeholder="Ceritakan pengalaman Anda..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-send me-1"></i>Kirim Rating
                                </button>
                            </form>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Back Button -->
                <a href="{{ route('pesanan.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Pesanan
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true, offset: 100 });

    // Star rating interaction
    document.querySelectorAll('.rating-input').forEach(container => {
        const produkId = container.dataset.produk;
        const stars = container.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('rating-' + produkId);

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                ratingInput.value = rating;

                stars.forEach((s, index) => {
                    const icon = s.querySelector('i');
                    if (index < rating) {
                        icon.className = 'bi bi-star-fill';
                        s.classList.add('active');
                    } else {
                        icon.className = 'bi bi-star';
                        s.classList.remove('active');
                    }
                });
            });

            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                stars.forEach((s, index) => {
                    const icon = s.querySelector('i');
                    if (index < rating) {
                        icon.className = 'bi bi-star-fill';
                    } else {
                        icon.className = 'bi bi-star';
                    }
                });
            });
        });

        container.addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingInput.value);
            stars.forEach((s, index) => {
                const icon = s.querySelector('i');
                if (index < currentRating) {
                    icon.className = 'bi bi-star-fill';
                } else {
                    icon.className = 'bi bi-star';
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<link href="{{ asset('css/pesanan.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true, offset: 100 });
</script>
@endpush

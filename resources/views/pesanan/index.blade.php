@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<!-- Breadcrumb -->
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Beranda</a></li>
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

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($pesanan->isEmpty())
        <!-- No Orders -->
        <div class="text-center py-5" data-aos="fade-up">
            <div class="mb-4">
                <i class="bi bi-receipt display-1 text-muted"></i>
            </div>
            <h3 class="fw-bold mb-2">Belum Ada Pesanan</h3>
            <p class="text-muted mb-4">Anda belum memiliki pesanan. Yuk, mulai belanja!</p>
            <a href="{{ route('katalog') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-grid me-2"></i>Mulai Belanja
            </a>
        </div>
        @else
        <!-- Orders Timeline -->
        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="fade-up">
                <div class="accordion" id="ordersAccordion">
                    @foreach($pesanan as $order)
                    <div class="accordion-item mb-3 border-0 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header" id="heading{{ $order->id }}">
                            <div class="d-flex">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-controls="collapse{{ $order->id }}">
                                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                        <div>
                                            <a href="{{ route('pesanan.show', $order) }}" class="fw-bold text-decoration-none">
                                                #TRX{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                            </a>
                                            <span class="text-muted ms-3 small">
                                                <i class="bi bi-calendar me-1"></i>{{ $order->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-{{ match($order->status) {
                                            'pending' => 'warning',
                                            'diproses' => 'info',
                                            'dikirim' => 'primary',
                                            'selesai' => 'success',
                                            'dibatalkan' => 'danger',
                                            default => 'secondary'
                                        } }} px-3 py-2">
                                            <i class="bi bi-{{ match($order->status) {
                                                'pending' => 'clock',
                                                'diproses' => 'gear',
                                                'dikirim' => 'truck',
                                                'selesai' => 'check-circle',
                                                'dibatalkan' => 'x-circle',
                                                default => 'question-circle'
                                            } }} me-1"></i>
                                            {{ match($order->status) {
                                                'pending' => 'Menunggu Pembayaran',
                                                'diproses' => 'Sedang Diproses',
                                                'dikirim' => 'Dikirim',
                                                'selesai' => 'Selesai',
                                                'dibatalkan' => 'Dibatalkan',
                                                default => $order->status
                                            } }}
                                        </span>
                                        <span class="fw-bold text-primary">Rp {{ number_format($order->total_bayar ?? $order->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                </button>
                                <a href="{{ route('pesanan.show', $order) }}" class="btn btn-sm btn-outline-primary me-2" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($order->status === 'pending')
                                <form action="{{ route('pesanan.cancel', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Pesanan">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </h2>
                        <div id="collapse{{ $order->id }}"
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                            aria-labelledby="heading{{ $order->id }}"
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
                                                    @php
                                                        $paymentLabels = [
                                                            'cod' => 'COD',
                                                            'qris' => 'QRIS',
                                                            'dana' => 'DANA',
                                                            'transfer_bank' => 'Transfer Bank',
                                                        ];
                                                    @endphp
                                                    {{ $paymentLabels[$order->metode_pembayaran] ?? ($order->metode_pembayaran ?? '-') }}
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
                                                <p class="mb-0 small">{{ $order->alamat }}</p>
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
                                                <p class="mb-0 small fw-bold">{{ $order->nama_pembeli }}</p>
                                                @if($order->no_telepon)
                                                <p class="mb-0 small text-muted"><i class="bi bi-telephone me-1"></i>{{ $order->no_telepon }}</p>
                                                @endif
                                                <p class="mb-0 small text-muted">{{ $order->user->email }}</p>
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
                                            @foreach($order->detailTransaksi as $detail)
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
                                                <td class="text-end fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            </tr>
                                            @if($order->diskon_voucher > 0)
                                            <tr>
                                                <td colspan="3" class="text-end text-success">
                                                    <i class="bi bi-ticket-perforated me-1"></i>Diskon:
                                                </td>
                                                <td class="text-end text-success fw-bold">- Rp {{ number_format($order->diskon_voucher, 0, ',', '.') }}</td>
                                            </tr>
                                            @endif
                                            <tr class="border-top">
                                                <td colspan="3" class="text-end fw-bold fs-6">Total Bayar:</td>
                                                <td class="text-end fw-bold fs-5 text-primary">
                                                    Rp {{ number_format($order->total_bayar ?? $order->total, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<link href="{{ asset('css/pesanan.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true, offset: 100 });
</script>
@endpush

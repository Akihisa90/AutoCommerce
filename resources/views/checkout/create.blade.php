@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<!-- Breadcrumb -->
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('keranjang.index') }}" class="text-decoration-none">Keranjang</a></li>
        <li class="breadcrumb-item active">Checkout</li>
    </ol>
</nav>

<!-- Checkout Section -->
<section class="py-5">
    <div class="container">
        <h1 class="text-center mb-2" data-aos="fade-up">
            <i class="bi bi-credit-card me-2"></i>Checkout
        </h1>
        <p class="text-muted text-center mb-5" data-aos="fade-up" data-aos-delay="50">
            Lengkapinya data pengiriman untuk memproses pesanan
        </p>

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-up">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Billing Details -->
                <div class="col-lg-7 mb-4" data-aos="fade-right">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 fw-bold">
                                <i class="bi bi-person-vcard me-2 text-primary"></i>Informasi Pengiriman
                            </h5>

                            <div class="mb-3">
                                <label for="nama_pembeli" class="form-label fw-semibold">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nama_pembeli') is-invalid @enderror"
                                    id="nama_pembeli" name="nama_pembeli"
                                    value="{{ old('nama_pembeli', auth()->user()->nama) }}" required>
                                @error('nama_pembeli')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_telepon" class="form-label fw-semibold">
                                    Nomor Telepon <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control @error('no_telepon') is-invalid @enderror"
                                    id="no_telepon" name="no_telepon"
                                    value="{{ old('no_telepon', auth()->user()->no_telepon) }}"
                                    placeholder="08xxxxxxxxxx" required>
                                <small class="text-muted">Nomor ini akan dihubungi oleh kurir untuk pengiriman paket.</small>
                                @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-semibold">
                                    Alamat Lengkap <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror"
                                    id="alamat" name="alamat" rows="4" required
                                    placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info small mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Catatan:</strong> Pastikan alamat lengkap dan benar agar pesanan dapat dikirim dengan tepat.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method & Order Summary -->
                <div class="col-lg-5" data-aos="fade-left">
                    <!-- Payment Method -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 fw-bold">
                                <i class="bi bi-wallet2 me-2 text-primary"></i>Metode Pembayaran
                            </h5>

                            <div class="d-grid gap-2">
                                <div class="form-check payment-option p-3 border rounded-3 @error('metode_pembayaran') is-invalid @enderror">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran"
                                        id="payment_cod" value="cod" {{ old('metode_pembayaran') == 'cod' ? 'checked' : '' }} required>
                                    <label class="form-check-label w-100" for="payment_cod">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-hand-thumbs-up me-2 text-success"></i>
                                                <strong>COD (Bayar di Tempat)</strong>
                                                <small class="d-block text-muted">Bayar saat paket tiba</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check payment-option p-3 border rounded-3">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran"
                                        id="payment_qris" value="qris" {{ old('metode_pembayaran') == 'qris' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="payment_qris">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-qr-code me-2 text-primary"></i>
                                                <strong>QRIS</strong>
                                                <small class="d-block text-muted">Scan QR untuk pembayaran instan</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check payment-option p-3 border rounded-3">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran"
                                        id="payment_dana" value="dana" {{ old('metode_pembayaran') == 'dana' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="payment_dana">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-phone me-2 text-info"></i>
                                                <strong>DANA</strong>
                                                <small class="d-block text-muted">Bayar melalui e-wallet DANA</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check payment-option p-3 border rounded-3">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran"
                                        id="payment_transfer" value="transfer_bank" {{ old('metode_pembayaran') == 'transfer_bank' ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="payment_transfer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-bank me-2 text-warning"></i>
                                                <strong>Transfer Bank</strong>
                                                <small class="d-block text-muted">Transfer ke rekening bank kami</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            @error('metode_pembayaran')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror

                            <!-- DANA Sub-type Selection -->
                            <div id="dana-subtype-section" class="mt-3" style="display: none;">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-phone me-1 text-info"></i>Pilih Cara Pembayaran DANA
                                </h6>
                                <div class="d-grid gap-2">
                                    <div class="form-check payment-subtype-option p-3 border rounded-3">
                                        <input class="form-check-input" type="radio" name="sub_tipe_pembayaran"
                                            id="dana_nomor_hp" value="nomor_hp" {{ old('sub_tipe_pembayaran') == 'nomor_hp' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="dana_nomor_hp">
                                            <div>
                                                <i class="bi bi-person me-2 text-info"></i>
                                                <strong>Via Nomor HP</strong>
                                                <small class="d-block text-muted">Transfer ke nomor DANA toko</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-check payment-subtype-option p-3 border rounded-3">
                                        <input class="form-check-input" type="radio" name="sub_tipe_pembayaran"
                                            id="dana_qr_code" value="qr_code" {{ old('sub_tipe_pembayaran') == 'qr_code' ? 'checked' : '' }}>
                                        <label class="form-check-label w-100" for="dana_qr_code">
                                            <div>
                                                <i class="bi bi-qr-code me-2 text-info"></i>
                                                <strong>Via QR Code</strong>
                                                <small class="d-block text-muted">Scan QR Code untuk pembayaran otomatis</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @error('sub_tipe_pembayaran')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Detail Sections -->
                    <!-- QRIS Detail -->
                    <div id="payment-detail-qris" class="card border-0 shadow-sm mb-4" style="display: none;">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3 fw-bold">
                                <i class="bi bi-qr-code me-2 text-primary"></i>QR Code Pembayaran
                            </h5>
                            <p class="text-muted small mb-3">{{ $qrisDescription }}</p>
                            @if(!empty($qrisQrImage))
                            <div class="text-center bg-white p-4 rounded-3">
                                <img src="{{ Storage::disk('public')->url($qrisQrImage) }}" alt="QR Code QRIS"
                                    class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                            </div>
                            @else
                            <div class="text-center py-4 bg-light rounded-3">
                                <i class="bi bi-qr-code text-muted" style="font-size: 5rem;"></i>
                                <p class="text-muted mt-2 mb-0 small">QR Code belum diatur. Hubungi admin untuk info pembayaran.</p>
                            </div>
                            @endif
                            <div class="alert alert-info small mt-3 mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                Buka aplikasi e-wallet atau mobile banking Anda, lalu scan QR Code di atas untuk melakukan pembayaran.
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Bank Detail -->
                    <div id="payment-detail-transfer_bank" class="card border-0 shadow-sm mb-4" style="display: none;">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3 fw-bold">
                                <i class="bi bi-bank me-2 text-warning"></i>Rekening Bank
                            </h5>
                            <p class="text-muted small mb-3">Transfer ke salah satu rekening berikut:</p>
                            @foreach($bankAccounts as $bank)
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold mb-1">
                                                <i class="bi bi-building me-1"></i>{{ $bank['bank_name'] }}
                                            </h6>
                                            <p class="mb-0 fw-bold fs-5 font-monospace" id="rek-{{ $loop->index }}">
                                                {{ $bank['account_number'] }}
                                            </p>
                                            <p class="mb-0 text-muted small">a.n. {{ $bank['account_holder'] }}</p>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="copyToClipboard('{{ $bank['account_number'] }}')">
                                            <i class="bi bi-clipboard me-1"></i>Salin
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="alert alert-warning small mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                Setelah transfer, simpan bukti transfer dan pesanan akan diproses otomatis setelah pembayaran dikonfirmasi.
                            </div>
                        </div>
                    </div>

                    <!-- DANA Detail -->
                    <div id="payment-detail-dana" class="card border-0 shadow-sm mb-4" style="display: none;">
                        <!-- DANA via Nomor HP -->
                        <div id="dana-nomor-hp-section" style="display: none;">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3 fw-bold">
                                    <i class="bi bi-phone me-2 text-info"></i>Pembayaran DANA via Nomor HP
                                </h5>
                                <p class="text-muted small mb-3">Transfer ke nomor DANA berikut:</p>
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body p-3 text-center">
                                        <h6 class="fw-bold mb-1">
                                            <i class="bi bi-phone me-1"></i>Nomor DANA
                                        </h6>
                                        <p class="mb-1 fw-bold fs-4 font-monospace" id="dana-number">{{ $danaPhone }}</p>
                                        <p class="mb-0 text-muted small">a.n. {{ $danaName }}</p>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                                onclick="copyToClipboard('{{ $danaPhone }}')">
                                            <i class="bi bi-clipboard me-1"></i>Salin Nomor
                                        </button>
                                    </div>
                                </div>
                                <div class="alert alert-info small mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Buka aplikasi DANA, pilih <strong>"Kirim"</strong> dan masukkan nomor di atas, lalu konfirmasi pembayaran.
                                </div>
                            </div>
                        </div>

                        <!-- DANA via QR Code -->
                        <div id="dana-qr-code-section" style="display: none;">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3 fw-bold">
                                    <i class="bi bi-qr-code me-2 text-info"></i>Pembayaran DANA via QR Code
                                </h5>
                                <p class="text-muted small mb-3">Scan QR Code di bawah untuk pembayaran otomatis</p>
                                @if(!empty($danaQrImage))
                                <div class="text-center bg-white p-4 rounded-3">
                                    <img src="{{ Storage::disk('public')->url($danaQrImage) }}" alt="QR Code DANA"
                                        class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                                </div>
                                @else
                                <div class="text-center py-4 bg-light rounded-3">
                                    <i class="bi bi-qr-code text-muted" style="font-size: 5rem;"></i>
                                    <p class="text-muted mt-2 mb-0 small">QR Code DANA belum diatur. Gunakan pembayaran via Nomor HP.</p>
                                </div>
                                @endif
                                <div class="alert alert-info small mt-3 mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Buka aplikasi DANA, pilih <strong>"Scan QR"</strong> dan arahkan kamera ke QR Code di atas.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4 fw-bold">
                                <i class="bi bi-receipt me-2 text-primary"></i>Ringkasan Pesanan
                            </h5>

                            <!-- Order Items -->
                            <div class="table-responsive mb-3">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Jml</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                <small class="d-block fw-bold">{{ $item->produk->nama }}</small>
                                                <small class="text-muted">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</small>
                                            </td>
                                            <td class="text-center">{{ $item->jumlah }}</td>
                                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Voucher Section -->
                            <div class="mb-3" id="voucher-section">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-ticket-perforated me-1 text-warning"></i>Kode Voucher Promo
                                </h6>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="voucher-input" placeholder="Masukkan kode voucher"
                                        value="{{ old('kode_voucher') }}" style="text-transform: uppercase;">
                                    <button type="button" class="btn btn-outline-primary" id="voucher-apply-btn">
                                        Terapkan
                                    </button>
                                </div>
                                <div id="voucher-message" class="mt-2 small" style="display: none;"></div>
                                <div id="voucher-applied" class="mt-2" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center bg-success bg-opacity-10 rounded-3 p-2 px-3">
                                        <div>
                                            <i class="bi bi-check-circle text-success me-1"></i>
                                            <strong id="voucher-applied-code"></strong>
                                            <span class="text-muted small ms-1" id="voucher-applied-name"></span>
                                        </div>
                                        <button type="button" class="btn btn-sm text-danger" id="voucher-remove-btn">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="voucher_id" id="voucher-id-input" value="">
                            </div>

                            <!-- Summary Totals -->
                            <div class="table-responsive mb-3">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-end text-muted">Subtotal:</td>
                                            <td class="text-end fw-bold" id="summary-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end text-muted">Ongkir:</td>
                                            <td class="text-end text-success fw-bold">Gratis</td>
                                        </tr>
                                        <tr id="voucher-discount-row" style="display: none;">
                                            <td class="text-end text-success">
                                                <i class="bi bi-ticket-perforated me-1"></i>Diskon Voucher:
                                            </td>
                                            <td class="text-end text-success fw-bold" id="voucher-discount-amount">- Rp 0</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="text-end fw-bold fs-6">Total:</td>
                                            <td class="text-end fw-bold fs-5 text-primary" id="summary-total">
                                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Payment Proof Upload (for manual payment methods) -->
                            <div id="payment-proof-section" class="mt-4" style="display: none;">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-camera me-1 text-primary"></i>Upload Bukti Pembayaran
                                </h6>
                                <p class="text-muted small mb-2">
                                    Setelah melakukan pembayaran, upload screenshot bukti transfer untuk verifikasi.
                                </p>
                                <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran"
                                       accept="image/*">
                                @error('bukti_pembayaran')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="bi bi-check-circle me-2"></i>Buat Pesanan
                            </button>

                            <a href="{{ route('keranjang.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true, offset: 100 });

    const subtotal = {{ $subtotal }};
    let appliedDiscount = 0;

    // Voucher handling
    const voucherInput = document.getElementById('voucher-input');
    const voucherApplyBtn = document.getElementById('voucher-apply-btn');
    const voucherMessage = document.getElementById('voucher-message');
    const voucherApplied = document.getElementById('voucher-applied');
    const voucherAppliedCode = document.getElementById('voucher-applied-code');
    const voucherAppliedName = document.getElementById('voucher-applied-name');
    const voucherRemoveBtn = document.getElementById('voucher-remove-btn');
    const voucherIdInput = document.getElementById('voucher-id-input');
    const voucherDiscountRow = document.getElementById('voucher-discount-row');
    const voucherDiscountAmount = document.getElementById('voucher-discount-amount');
    const summaryTotal = document.getElementById('summary-total');

    voucherApplyBtn.addEventListener('click', applyVoucher);
    voucherInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyVoucher();
        }
    });

    voucherRemoveBtn.addEventListener('click', function() {
        appliedDiscount = 0;
        voucherIdInput.value = '';
        voucherInput.value = '';
        voucherInput.disabled = false;
        voucherApplyBtn.disabled = false;
        voucherApplied.style.display = 'none';
        voucherMessage.style.display = 'none';
        voucherDiscountRow.style.display = 'none';
        updateTotal();
    });

    function applyVoucher() {
        const kode = voucherInput.value.trim().toUpperCase();
        if (!kode) {
            showVoucherMessage('Masukkan kode voucher.', 'danger');
            return;
        }

        voucherApplyBtn.disabled = true;
        voucherApplyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memeriksa...';

        fetch('{{ route('checkout.validate-voucher') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                kode_voucher: kode,
                subtotal: subtotal
            })
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
            voucherApplyBtn.disabled = false;
            voucherApplyBtn.innerHTML = 'Terapkan';

            if (data.success) {
                appliedDiscount = data.diskon;
                voucherIdInput.value = data.voucher_id;
                voucherAppliedCode.textContent = data.kode;
                let nameText = '(' + data.nama + ')';
                if (data.remaining_user_quota > 0) {
                    nameText += ' <small class="text-muted">(' + data.remaining_user_quota + 'x lagi)</small>';
                } else if (data.remaining_user_quota === 0) {
                    nameText += ' <small class="text-danger">(Terakhir kali!)</small>';
                }
                voucherAppliedName.innerHTML = nameText;
                voucherInput.disabled = true;
                voucherApplyBtn.disabled = true;
                voucherApplied.style.display = 'block';
                voucherMessage.style.display = 'none';
                voucherDiscountRow.style.display = '';
                voucherDiscountAmount.textContent = '- Rp ' + formatNumber(data.diskon);
                updateTotal();
            } else {
                showVoucherMessage(data.message, 'danger');
            }
        })
        .catch(err => {
            voucherApplyBtn.disabled = false;
            voucherApplyBtn.innerHTML = 'Terapkan';
            showVoucherMessage('Error: ' + err.message, 'danger');
            console.error('Voucher error:', err);
        });
    }

    function updateTotal() {
        const total = Math.max(0, subtotal - appliedDiscount);
        summaryTotal.textContent = 'Rp ' + formatNumber(total);
    }

    function showVoucherMessage(message, type) {
        voucherMessage.textContent = message;
        voucherMessage.className = 'mt-2 small text-' + type;
        voucherMessage.style.display = 'block';
    }

    function formatNumber(num) {
        return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Highlight selected payment method and show corresponding detail
    document.querySelectorAll('.payment-option').forEach(function(option) {
        const radio = option.querySelector('input[type="radio"]');
        if (radio && radio.checked) {
            option.classList.add('border-primary', 'bg-light');
            showPaymentDetail(radio.value);
        }
        option.addEventListener('click', function() {
            document.querySelectorAll('.payment-option').forEach(function(opt) {
                opt.classList.remove('border-primary', 'bg-light');
            });
            option.classList.add('border-primary', 'bg-light');
            const radioInput = option.querySelector('input[type="radio"]');
            if (radioInput) {
                radioInput.checked = true;
                showPaymentDetail(radioInput.value);
            }
        });
    });

    // DANA sub-type selection
    document.querySelectorAll('.payment-subtype-option').forEach(function(option) {
        option.addEventListener('click', function() {
            document.querySelectorAll('.payment-subtype-option').forEach(function(opt) {
                opt.classList.remove('border-primary', 'bg-light');
            });
            option.classList.add('border-primary', 'bg-light');
            const radioInput = option.querySelector('input[type="radio"]');
            if (radioInput) {
                radioInput.checked = true;
                showDanaSubtype(radioInput.value);
            }
        });
    });

    // Initialize DANA sub-type if already checked
    const checkedDanaSubtype = document.querySelector('input[name="sub_tipe_pembayaran"]:checked');
    if (checkedDanaSubtype) {
        const parentOption = checkedDanaSubtype.closest('.payment-subtype-option');
        if (parentOption) {
            parentOption.classList.add('border-primary', 'bg-light');
        }
    }

    function showPaymentDetail(method) {
        // Hide all payment details
        document.querySelectorAll('[id^="payment-detail-"]').forEach(function(el) {
            el.style.display = 'none';
        });

        // Hide DANA sub-type section
        const danaSubtypeSection = document.getElementById('dana-subtype-section');
        danaSubtypeSection.style.display = 'none';

        // Hide payment proof section
        document.getElementById('payment-proof-section').style.display = 'none';

        // Show specific payment detail
        const detailEl = document.getElementById('payment-detail-' + method);
        if (detailEl) {
            detailEl.style.display = 'block';

            // For DANA, show sub-type selection
            if (method === 'dana') {
                danaSubtypeSection.style.display = 'block';
                // Show the currently selected DANA sub-type
                const checkedSubtype = document.querySelector('input[name="sub_tipe_pembayaran"]:checked');
                if (checkedSubtype) {
                    showDanaSubtype(checkedSubtype.value);
                } else {
                    // Default to nomor_hp if nothing selected
                    showDanaSubtype('nomor_hp');
                    document.getElementById('dana_nomor_hp').checked = true;
                    document.getElementById('dana_nomor_hp').closest('.payment-subtype-option')
                            .classList.add('border-primary', 'bg-light');
                }
            }
        }

        // Show payment proof upload for manual payment methods
        if (method === 'transfer_bank') {
            document.getElementById('payment-proof-section').style.display = 'block';
        }
    }

    function showDanaSubtype(subtype) {
        const hpSection = document.getElementById('dana-nomor-hp-section');
        const qrSection = document.getElementById('dana-qr-code-section');
        const proofSection = document.getElementById('payment-proof-section');

        hpSection.style.display = 'none';
        qrSection.style.display = 'none';
        proofSection.style.display = 'none';

        if (subtype === 'nomor_hp') {
            hpSection.style.display = 'block';
            proofSection.style.display = 'block';
        } else if (subtype === 'qr_code') {
            qrSection.style.display = 'block';
            // QR code payments don't need manual proof upload
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 start-50 translate-middle-x mt-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = '<div class="toast show" role="alert">' +
                '<div class="toast-body bg-success text-white">' +
                '<i class="bi bi-check-circle me-2"></i>Berhasil disalin ke clipboard!' +
                '</div></div>';
            document.body.appendChild(toast);
            setTimeout(function() { toast.remove(); }, 2000);
        }).catch(function() {
            const tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('Nomor berhasil disalin: ' + text);
        });
    }
</script>
@endpush

@push('styles')
<link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
@endpush

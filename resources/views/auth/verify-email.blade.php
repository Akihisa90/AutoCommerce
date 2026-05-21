@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <i class="bi bi-envelope-check text-primary" style="font-size: 4rem;"></i>
                    </div>

                    <h4 class="fw-bold mb-3">Verifikasi Email Anda</h4>

                    <p class="text-muted mb-4">
                        Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan ke email Anda.
                        Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan email verifikasi baru.
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="bi bi-send me-2"></i>Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted text-decoration-none">
                            <i class="bi bi-box-arrow-left me-1"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

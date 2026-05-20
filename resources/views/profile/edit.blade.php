@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<!-- Breadcrumb -->
<nav class="container py-3 bg-light">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item active">Edit Profil</li>
    </ol>
</nav>

<!-- Profile Edit Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="text-center mb-2" data-aos="fade-up">
                    <i class="bi bi-gear me-2"></i>Edit Profil
                </h1>
                <p class="text-muted text-center mb-5" data-aos="fade-up" data-aos-delay="50">
                    Perbarui informasi akun Anda
                </p>

                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
                    <i class="bi bi-check-circle me-2"></i>Profil berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
                    <i class="bi bi-check-circle me-2"></i>Password berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-up">
                    <i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan:
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Profile Information -->
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-person-vcard me-2 text-primary"></i>Informasi Profil
                        </h5>
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="no_telepon" class="form-label fw-semibold">Nomor Telepon</label>
                                    <input type="tel" class="form-control @error('no_telepon') is-invalid @enderror"
                                        id="no_telepon" name="no_telepon"
                                        value="{{ old('no_telepon', auth()->user()->no_telepon) }}"
                                        placeholder="08xxxxxxxxxx">
                                    @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Opsional, namun diperlukan saat checkout.</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status Email</label>
                                    <div class="form-control-plaintext mt-1">
                                        @if(auth()->user()->hasVerifiedEmail())
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Terverifikasi</span>
                                        @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Belum Terverifikasi</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if (!auth()->user()->hasVerifiedEmail())
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-envelope me-2 text-warning"></i>Verifikasi Email
                        </h5>
                        <p class="text-muted small">Email Anda belum diverifikasi. Klik tombol di bawah untuk mengirim ulang email verifikasi.</p>
                        <form action="{{ route('verification.send') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning text-dark">
                                <i class="bi bi-send me-1"></i>Kirim Ulang Email Verifikasi
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Change Password -->
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-shield-lock me-2 text-primary"></i>Ubah Password
                        </h5>
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                                    <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                        id="current_password" name="current_password" required autocomplete="current-password">
                                    @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold">Password Baru</label>
                                    <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                        id="password" name="password" required autocomplete="new-password">
                                    @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimal 8 karakter.</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                    <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                        id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                                    @error('password_confirmation', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-warning text-dark">
                                    <i class="bi bi-key me-1"></i>Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-2 text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>Hapus Akun
                        </h5>
                        <p class="text-muted small mb-3">
                            Setelah akun dihapus, semua data Anda akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                        </p>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash me-1"></i>Hapus Akun Saya
                        </button>
                    </div>
                </div>

                <!-- Back Button -->
                <a href="{{ route('katalog') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="mb-3">Masukkan password Anda untuk konfirmasi:</p>
                    <div>
                        <label for="delete_password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            id="delete_password" name="password" required>
                        @error('password', 'userDeletion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    AOS.init({ duration: 800, once: true, offset: 100 });
</script>
@endpush

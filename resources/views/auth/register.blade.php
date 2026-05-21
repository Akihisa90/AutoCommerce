@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('auth-subtitle').textContent = 'Daftar akun baru';
    });
</script>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div class="mb-3">
        <label for="name" class="form-label">
            <i class="bi bi-person me-1"></i>Nama Lengkap
        </label>
        <input id="name" type="text" name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name') }}" required autofocus autocomplete="name"
            placeholder="John Doe">
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="bi bi-envelope me-1"></i>Email
        </label>
        <input id="email" type="email" name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}" required autocomplete="username"
            placeholder="nama@email.com">
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">
            <i class="bi bi-lock me-1"></i>Password
        </label>
        <input id="password" type="password" name="password"
            class="form-control @error('password') is-invalid @enderror"
            required autocomplete="new-password"
            placeholder="Min. 8 karakter">
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">
            <i class="bi bi-lock-fill me-1"></i>Konfirmasi Password
        </label>
        <input id="password_confirmation" type="password" name="password_confirmation"
            class="form-control @error('password_confirmation') is-invalid @enderror"
            required autocomplete="new-password"
            placeholder="Ulangi password">
        @error('password_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary-custom mb-3">
        <i class="bi bi-person-plus me-1"></i>Register
    </button>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- Login Link -->
    <p class="text-center mb-0" style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">
        Sudah punya akun?
        <a href="{{ route('login') }}">
            <i class="bi bi-box-arrow-in-right me-1"></i>Login di sini
        </a>
    </p>
</form>
@endsection

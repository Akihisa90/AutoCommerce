@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('auth-subtitle').textContent = 'Login ke Otomotif Shop';
    });
</script>

<form method="POST" action="{{ route('login') }}">
    @csrf

    @if (session('status'))
        <div class="alert alert-success mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        </div>
    @endif

    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="bi bi-envelope me-1"></i>Email
        </label>
        <input id="email" type="email" name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}" required autofocus autocomplete="username"
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
            required autocomplete="current-password"
            placeholder="Masukkan password">
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="mb-3 form-check">
        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
        <label for="remember_me" class="form-check-label">
            Ingat saya
        </label>
    </div>

    @if (Route::has('password.request'))
    <div class="text-end mb-3">
        <a href="{{ route('password.request') }}" class="text-sm">
            Lupa password?
        </a>
    </div>
    @endif

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary-custom mb-3">
        <i class="bi bi-box-arrow-in-right me-1"></i>Login
    </button>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- Register Link -->
    <p class="text-center mb-0" style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">
        Belum punya akun?
        <a href="{{ route('register') }}">
            <i class="bi bi-person-plus me-1"></i>Daftar sekarang
        </a>
    </p>
</form>
@endsection

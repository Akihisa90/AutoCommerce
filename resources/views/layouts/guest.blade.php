<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Otomotif Shop') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Template CSS -->
    <link href="{{ asset('frontend/css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/vendor.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">

    <style>
        .auth-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, #0F2E25 0%, #1B4D3E 50%, #111827 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow: hidden;
        }
        .auth-wrapper::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.06) 0%, transparent 50%);
            animation: float 15s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2.5rem;
            max-width: 440px;
            width: 100%;
            position: relative;
            z-index: 1;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        }
        .auth-card .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-card .auth-logo i {
            font-size: 3rem;
            color: #F59E0B;
        }
        .auth-card .auth-logo h3 {
            color: #fff;
            font-weight: 700;
            margin-top: 0.5rem;
            margin-bottom: 0.25rem;
        }
        .auth-card .auth-logo p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.875rem;
        }
        .auth-card .form-control {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            color: #fff;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }
        .auth-card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }
        .auth-card .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #F59E0B;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
            color: #fff;
        }
        .auth-card .form-label {
            color: rgba(255, 255, 255, 0.75);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .auth-card .btn-primary-custom {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }
        .auth-card .btn-primary-custom:hover {
            background: linear-gradient(135deg, #D97706, #B45309);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.25);
        }
        .auth-card a {
            color: #FBBF24;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .auth-card a:hover {
            color: #F59E0B;
        }
        .auth-card .text-muted-custom {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        .auth-card .divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 1.5rem 0;
        }
        .auth-card .form-check-label {
            color: rgba(255, 255, 255, 0.6);
        }
        .auth-card .form-check-input {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }
        .auth-card .form-check-input:checked {
            background-color: #F59E0B;
            border-color: #F59E0B;
        }
        .input-group-text {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.5);
        }
        .is-invalid {
            border-color: #EF4444 !important;
        }
        .invalid-feedback {
            color: #FCA5A5;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="bi bi-car-front-fill"></i>
                <h3>Otomotif Shop</h3>
                <p id="auth-subtitle"></p>
            </div>

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('frontend/js/jquery-1.11.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend/js/modernizr.js') }}"></script>
    <script src="{{ asset('frontend/js/plugins.js') }}"></script>
    <script src="{{ asset('frontend/js/script.js') }}"></script>

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Otomotif Shop') - Toko Sparepart Mobil</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Template CSS -->
    <link href="{{ asset('frontend/css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/vendor.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    <div class="preloader-wrapper">
        <div class="preloader"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ auth()->check() ? route('dashboard') : route('katalog') }}">
                <img src="{{ asset('logo.png') }}" alt="AutoCommerce" width="80" height="50">
                AutoCommerce
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="bi bi-house-fill me-1"></i>Dashboard
                        </a>
                    </li>
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('katalog') ? 'active' : '' }}"
                            href="{{ route('katalog') }}">
                            <i class="bi bi-grid-fill me-1"></i>Katalog
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('keranjang.*') ? 'active' : '' }}"
                                href="{{ route('keranjang.index') }}">
                                <i class="bi bi-cart-fill me-1"></i>Keranjang
                                @php
                                    $cartCount = auth()->user()->keranjang()->sum('jumlah');
                                @endphp
                                @if ($cartCount > 0)
                                    <span class="badge bg-danger rounded-pill ms-1">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('wishlist.*') ? 'active' : '' }}"
                                href="{{ route('wishlist.index') }}">
                                <i class="bi bi-heart-fill me-1"></i>Wishlist
                                @php
                                    $wishlistCount = auth()->user()->wishlists()->count();
                                @endphp
                                @if ($wishlistCount > 0)
                                    <span class="badge bg-danger rounded-pill ms-1">{{ $wishlistCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}"
                                href="{{ route('pesanan.index') }}">
                                <i class="bi bi-receipt me-1"></i>Pesanan
                            </a>
                        </li>
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus-fill me-1"></i>Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <span>{{ Auth::user()->nama }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="bi bi-house me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-gear me-2"></i>Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('wishlist.index') }}">
                                        <i class="bi bi-heart me-2"></i>Wishlist
                                    </a>
                                </li>
                                @if (Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/admin') }}" target="_blank">
                                            <i class="bi bi-shield-lock me-2"></i>Admin Panel
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
                <button type="button" class="theme-toggle ms-2" id="themeToggle" title="Toggle Theme">
                    <i class="bi bi-moon-fill"></i>
                    <i class="bi bi-sun-fill"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-white mb-3">
                        <a href="{{ auth()->check() ? route('dashboard') : route('katalog') }}" class="text-white text-decoration-none">
                            <i class="bi bi-car-front-fill me-2"></i>AutoCommerce
                        </a>
                    </h5>
                    <p>Toko online terpercaya untuk sparepart, oli, dan aksesoris mobil berkualitas.</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="text-white mb-3">Layanan Kami</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-truck me-2"></i>Gratis Ongkir</li>
                        <li><i class="bi bi-shield-check me-2"></i>Garansi Produk</li>
                        <li><i class="bi bi-headset me-2"></i>Support 24/7</li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="text-white mb-3">Hubungi Kami</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt me-2"></i>Jl. Otomotif No. 123, Jakarta</li>
                        <li><i class="bi bi-telephone me-2"></i>021-12345678</li>
                        <li><i class="bi bi-envelope me-2"></i>info@otomotifshop.com</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Otomotif Shop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('frontend/js/jquery-1.11.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('frontend/js/modernizr.js') }}"></script>
    <script src="{{ asset('frontend/js/plugins.js') }}"></script>
    <script src="{{ asset('frontend/js/script.js') }}"></script>

    <script>
        (function() {
            const html = document.documentElement;
            const toggle = document.getElementById('themeToggle');
            const saved = localStorage.getItem('theme');

            if (saved) {
                html.setAttribute('data-theme', saved);
            } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                html.setAttribute('data-theme', 'dark');
            }

            if (toggle) {
                toggle.addEventListener('click', function() {
                    const current = html.getAttribute('data-theme');
                    const next = current === 'dark' ? 'light' : 'dark';
                    html.setAttribute('data-theme', next);
                    localStorage.setItem('theme', next);
                });
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>

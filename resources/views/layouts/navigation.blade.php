<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <!-- Logo / Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('katalog') }}">
            <i class="bi bi-car-front-fill me-2"></i>Otomotif Shop
        </a>

        <!-- Toggle Button Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
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
                        <a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}"
                            href="{{ route('pesanan.index') }}">
                            <i class="bi bi-receipt me-1"></i>Pesanan
                        </a>
                    </li>
                @endauth
            </ul>

            <!-- Auth Links -->
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
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-gear me-2"></i>Profil
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
        </div>
    </div>
</nav>

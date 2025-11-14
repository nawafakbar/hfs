<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bgd Hydrofarm - E-Commerce')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Panggil CSS dari folder public --}}
    <link rel="stylesheet" href="{{ asset('user-assets/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container mt-3">

        {{-- BAGIAN LOGO (BRAND) --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('user-assets/images/logo.png') }}" alt="Logo" class="me-2" style="width: 30px; height: auto;">
            Bgd Hydrofarm
        </a>

        {{-- IKON LOGIN & CART (SELALU DI KANAN, DI LUAR HAMBURGER) --}}
        <div class="d-flex align-items-center order-lg-2">
            @guest
                <a class="nav-link text-white me-3" href="{{ route('login') }}">
                    <i class="bi bi-person"></i>
                </a>
            @else
                <div class="nav-item dropdown me-3">
                    <a class="nav-link text-white dropdown-toggle p-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-fill"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil Saya</a></li>
                        <li><a class="dropdown-item" href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest

            <a class="nav-link text-white" href="{{ route('cart.index') }}">
                <i class="bi bi-cart"></i>
            </a>
        </div>

        {{-- HAMBURGER MENU --}}
        <button class="navbar-toggler order-lg-3 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU UTAMA --}}
        <div class="collapse navbar-collapse order-lg-1 mt-2 mt-lg-0" id="navbarNav">
            <ul class="navbar-nav mx-auto text-center">
                <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#produk">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
        </div>

    </div>
</nav>


    <main>
        {{-- Bagian ini akan diisi oleh konten dari halaman lain --}}
        @yield('content')
    </main>

    <footer id="kontak" class="footer-section text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold">Bgd_Hydrofarm</h5>
                    <p class="text-white-50">Providing fresh, healthy, and locally grown hydroponic vegetables for your
                        family's needs.</p>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-4 mb-lg-0">
                    <h5 class="fw-bold">Page</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('home') }}#produk">Shop</a></li>
                        <li><a href="{{ route('home')}}#about">About</a></li>
                        <li><a href="{{ route('home') }}#kontak">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-4 mb-lg-0">
                    <h5 class="fw-bold">Socials</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Tiktok</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold">Contact</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">info@bgdhyrofarm.com</a></li>
                        <li><a href="#">+62 123 4567 890</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center text-white-50 pt-4 mt-4 border-top border-secondary">
                <p>&copy; 2025 bgd_hydrofarm. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
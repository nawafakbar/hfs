@extends('layouts.user')

@section('content')
    <section class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                
                {{-- 
                    PERUBAHAN DI SINI:
                    1. Tambah 'col-12' agar full-width di HP.
                    2. Tambah 'text-center' dan 'text-lg-start' agar teks rata tengah di HP, tapi rata kiri di desktop.
                --}}
                <div class="col-12 col-lg-6 text-white text-center text-lg-start">
                    <h1 class="display-4">Bring The Product Close To You</h1>
                    <p class="my-4">We deliver your everyday hydroponics needs straight to your door. No more
                        hassles, just fresh and healthy greens for your family.</p>
                    <a href="{{ route('home') }}#produk" class="btn btn-brand">Shop Now</a>
                </div>
                
                {{-- 
                    PERUBAHAN DI SINI:
                    1. Hapus 'd-none' dan 'd-lg-block' agar gambar MUNCUL di HP.
                    2. Tambah 'col-12' agar full-width di HP.
                    3. Tambah 'mt-5' dan 'mt-lg-0' agar ada jarak di HP, tapi tidak di desktop.
                    4. Hapus 'ms-5' dari <img>, kita atur di CSS.
                --}}
                <div class="col-12 col-lg-6 mt-5 mt-lg-0">
                    <img src="{{ asset('user-assets/Images/image1.png') }}" alt="Bok Choy" class="img-fluid">
                </div>

            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <div class="row text-white text-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="icon-box"><i class="bi bi-truck"></i></div>
                    <h5 class="mt-2">Fast Delivery</h5>
                    <p>Same day delivery for all orders.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="icon-box"><i class="bi bi-credit-card"></i></div>
                    <h5 class="mt-2">Easy Payment</h5>
                    <p>Secure and easy online payment.</p>
                </div>
                <div class="col-md-4">
                    <div class="icon-box"><i class="bi bi-patch-check"></i></div>
                    <h5 class="mt-2">Florian Guarantee</h5>
                    <p>100% fresh and quality products.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="produk" class="py-5 px-5">
        <div class="container px-5">
            <h2 class="text-center mb-4 fw-bold">Our Products</h2>

            {{-- BAGIAN KATEGORI DINAMIS --}}
            <div class="mb-4 text-center">
                {{-- Tombol "All Products" --}}
                {{-- PERHATIKAN PENAMBAHAN #produk DI AKHIR URL --}}
                <a href="{{ route('home') }}#produk" 
                   class="btn rounded-pill me-2 {{ !$activeCategory ? 'btn-success' : 'btn-outline-success' }} mb-2">
                   All Products
                </a>
                
                {{-- Loop kategori --}}
                @foreach ($categories as $category)
                    {{-- PERHATIKAN PENAMBAHAN #produk DI AKHIR URL --}}
                    <a href="{{ route('home.category', $category->slug) }}#produk" 
                       class="btn rounded-pill me-2 {{ $activeCategory && $activeCategory->id == $category->id ? 'btn-success' : 'btn-outline-success' }} mb-2">
                       {{ $category->name }}
                    </a>
                @endforeach
            </div>

            {{-- BAGIAN PRODUK DINAMIS --}}
            <div class="row g-0 px-4">
                @forelse ($products as $product)
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('product.detail', $product->slug) }}" class="product-link">
                            <div class="card product-card h-100">
                                @if($product->discount_price)
                                    <span class="badge bg-danger discount-badge">-{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%</span>
                                @endif
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <div class="mt-auto">
                                        @if($product->discount_price)
                                            <p class="card-text fw-bold text-success mb-0">
                                                Rp {{ number_format($product->discount_price, 0, ',', '.') }} /ikat
                                                <small class="text-muted text-decoration-line-through d-block">Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                                            </p>
                                        @else
                                            <p class="card-text fw-bold text-success mb-0">Rp {{ number_format($product->price, 0, ',', '.') }} /ikat</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5">Produk dalam kategori ini belum tersedia.</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION LINKS --}}
            <div class="mt-5 d-flex justify-content-center">
                {{-- Tambahkan #produk ke link paginasi juga --}}
                {{ $products->fragment('produk')->links() }}
            </div>
        </div>
    </section>

    <!-- Catalog -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left -->
                <div class="col-lg-4 text-white">
                    <h2 class="fw-bold mb-3">Visit To Our Catalog For Free!</h2>
                </div>

                <!-- Middle (Images in circles) -->
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <div class="circle-images">
                        <img src="{{ asset('user-assets/Images/Rectangle 236.png') }}" alt="Cucumber" class="circle circle-lg">
                        <img src="{{ asset('user-assets/Images/images2.jpg') }}" alt="Lettuce" class="circle circle-sm">
                    </div>
                </div>

                <!-- Right -->
                <div class="col-lg-4 text-white">
                    <p class="mb-3">
                        Get the full list of our fresh products, updated daily with new arrivals and special offers.
                    </p>
                    <a href="#" class="btn btn-orange">
                        See The Catalog <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>


    <section id="about" class="py-5 steps-section">
        <div class="container text-center">
            <h2 class="mb-3 fw-bold">Steps to start growing hydroponics</h2>
            <p class="mb-5 text-muted">
                Follow our three essential steps to start your own productive hydroponic farm at home.
            </p>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="step-icon-wrapper mx-auto">
                        <i class="bi bi-flower2"></i>
                    </div>
                    <h5 class="mt-3 fw-semibold">Choose System & Plants</h5>
                    <p class="text-muted">Choose the wick system and leafy green vegetables like water spinach or
                        lettuce.</p>
                </div>
                <div class="col-md-4">
                    <div class="step-icon-wrapper mx-auto">
                        <i class="bi bi-eyedropper"></i>
                    </div>
                    <h5 class="mt-3 fw-semibold">Prepare Nutrients & Media</h5>
                    <p class="text-muted">Use AB Mix nutrients as fertilizer and rockwool as the growing medium.</p>
                </div>
                <div class="col-md-4">
                    <div class="step-icon-wrapper mx-auto">
                        <i class="bi bi-brightness-high"></i>
                    </div>
                    <h5 class="mt-3 fw-semibold">Sunlight & Regular Checks</h5>
                    <p class="text-muted">Ensure the plants get 5–6 hours of sunlight daily and check the nutrient water
                        level.</p>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row card-grow">
                <!-- Left image -->
                <img src="{{ asset('user-assets/Images/step.jpg') }}" alt="Hydroponics" class="img-fluid">
                <!-- Right content -->
                <div class="col-md-7 p-4 rounded-end">
                    <h4 class="fw-bold mb-3">Come With Us How to Grow your Hydroponics to be better</h4>
                    <p class="text-muted">
                        Start your hydroponic journey with us! From choosing the right system, preparing nutrients,
                        to maintaining healthy plants — we provide the guidance you need to grow fresh, sustainable,
                        and high-quality crops at home.
                    </p>
                    <a href="https://wa.me/6285759873301?text=Halo%2C%20saya%20tertarik%20untuk%20mulai%20menanam%20hidroponik" target="_blank" class="btn btn-danger btn-sm rounded-pill px-3">
                        Lets Start <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
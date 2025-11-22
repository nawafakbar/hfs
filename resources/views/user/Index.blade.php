@extends('layouts.user')

@section('content')
<style>
    /* Variable warna dan font utama */
:root {
    --primary-green: #0d2c23;
    --dark-green: #0a211a;
    --brand-orange: #E58444;
    --light-green: #8BD078;
    --font-family: 'Poppins', sans-serif;
}

.text-justify {
    text-align: justify;
}

/* Styling untuk Floating WhatsApp */
.float-wa {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #8BD078;
    color: #FFF;
    width: 60px;
    height: 60px;
    border-radius: 50px;
    text-align: center;
    font-size: 30px;
    box-shadow: 2px 2px 3px #999;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.float-wa:hover {
    background-color: #8BD078;
    transform: scale(1.1);
    color: #FFF;
}

    /* Styling khusus About Me */
.bg-brand-dark {
    background-color: #0d2c23; /* Hijau tua elegan */
    color: white;
}
    
.about-image {
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

    /* Styling Partner */
.partner-logo {
    filter: grayscale(0%);
    transition: all 0.3s;
    max-height: 80px; /* Batasi tinggi logo */
    width: auto;
}
    
.partner-logo:hover {
    filter: grayscale(20%);
    opacity: 1;
    transform: scale(1.05);
}

/* Styling Social Media Icons (UPDATE KEREN) */
.social-icon-btn {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1); /* Transparan putih */
    border: 1px solid rgba(255, 255, 255, 0.4);
    color: white;
    border-radius: 50%;
    font-size: 1.4rem;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Efek mantul */
    text-decoration: none;
    margin-right: 15px;
}

.social-icon-btn:hover {
    background: #fff;
    color: #145e3f; /* Warna ikon jadi hijau saat hover */
    transform: translateY(-5px); /* Naik sedikit */
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    border-color: #fff;
}

/* artikel */
.article-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
    .article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}
.article-card:hover img {
    transform: scale(1.05);
}
.title-hover {
    background-image: linear-gradient(to right, #145e3f, #145e3f);
    background-size: 0% 2px;
    background-repeat: no-repeat;
    background-position: left bottom;
    transition: background-size 0.3s ease;
}
.article-card:hover .title-hover {
    background-size: 100% 2px;
    color: #145e3f !important;
}
</style>
    {{-- TOMBOL WHATSAPP MENGAMBANG --}}
    <a href="https://wa.me/6285759873301?text=Halo%20BGD%20Hydrofarm%2C%20saya%20tertarik%20dengan%20produk%20hidroponiknya" class="float-wa" target="_blank">
        <i class="bi bi-whatsapp"></i>
    </a>

    {{-- HERO SECTION --}}
    <section class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 text-white text-center text-lg-start">
                    <h1 class="display-4">Bring The Product Close To You</h1>
                    <p class="my-4">We deliver your everyday hydroponics needs straight to your door. No more
                        hassles, just fresh and healthy greens for your family.</p>
                    <a href="{{ route('home') }}#produk" class="btn btn-brand">Shop Now</a>
                </div>
                
                <div class="col-12 col-lg-6 mt-5 mt-lg-0">
                    <img src="{{ asset('user-assets/Images/image1.png') }}" alt="Bok Choy" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
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

    {{-- PRODUCTS SECTION --}}
    <section id="produk" class="py-5 px-5">
        <div class="container px-5">
            <h2 class="text-center mb-4 fw-bold">Our Products</h2>

            {{-- KATEGORI --}}
            <div class="mb-4 text-center">
                <a href="{{ route('home') }}#produk" 
                class="btn rounded-pill me-2 {{ !$activeCategory ? 'btn-success' : 'btn-outline-success' }} mb-2">
                All Products
                </a>
                
                @foreach ($categories as $category)
                    <a href="{{ route('home.category', $category->slug) }}#produk" 
                    class="btn rounded-pill me-2 {{ $activeCategory && $activeCategory->id == $category->id ? 'btn-success' : 'btn-outline-success' }} mb-2">
                    {{ $category->name }}
                    </a>
                @endforeach
            </div>

            {{-- DAFTAR PRODUK --}}
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

            {{-- PAGINATION --}}
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->fragment('produk')->links() }}
            </div>
        </div>
    </section>

    {{-- CATALOG CTA --}}
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 text-white">
                    <h2 class="fw-bold mb-3">Visit To Our Catalog For Free!</h2>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <div class="circle-images">
                        <img src="{{ asset('user-assets/Images/Rectangle 236.png') }}" alt="Cucumber" class="circle circle-lg">
                        <img src="{{ asset('user-assets/Images/images2.jpg') }}" alt="Lettuce" class="circle circle-sm">
                    </div>
                </div>
                <div class="col-lg-4 text-white">
                    <p class="mb-3">
                        Get the full list of our fresh products, updated daily with new arrivals and special offers.
                    </p>
                    <a href="https://www.instagram.com/bgd_hydrofarm/" target="_blank" class="btn btn-orange">
                        See The Catalog <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- STEPS SECTION (Existing) --}}
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

    {{-- about us --}}
    <section id="about-me" class="py-5 bg-brand-dark">
    <div class="container">
        <div class="row align-items-center">
            <!-- Kolom Gambar Owner/Farm -->
            <div class="col-lg-5 mb-4 mb-lg-0 text-center">
                <img src="{{ asset('user-assets/Images/about-me.png') }}" 
                     alt="Owner BGD Hydrofarm" 
                     class="img-fluid about-image w-75">
            </div>
            
            <!-- Kolom Teks -->
            <div class="col-lg-7 text-white text-center text-lg-start">
                <h5 class="text-warning text-uppercase mb-2 px-2">About Us</h5>
                <h2 class="display-6 mb-4 px-2">Mengenal BGD Hydrofarm</h2>
                
                <p class="lead text-justify px-2" style="font-weight: 300;">
                    "Hidup sehat dimulai dari apa yang kita makan. Kami hadir untuk memastikan kesegaran itu sampai di meja makan Anda."
                </p>
                
                <p class="mb-4 text-white-50 text-justify px-2">
                    BGD Hydrofarm didirikan dengan satu visi sederhana: menyediakan sayuran hidroponik berkualitas tinggi yang bebas pestisida dan kaya nutrisi bagi masyarakat. Berawal dari hobi kecil di halaman rumah, kini kami tumbuh menjadi supplier sayuran segar terpercaya.
                </p>
                <div class="mt-4 px-2">
                                <p class="text-white-50 mb-3">Ikuti Keseruan Kami di Media Sosial:</p>
                                
                                <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                                    {{-- Instagram --}}
                                    <a href="[https://www.instagram.com/bgd_hydrofarm/](https://www.instagram.com/bgd_hydrofarm/)" target="_blank" class="social-icon-btn" title="Instagram">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                    
                                    {{-- Facebook --}}
                                    <a href="#" class="social-icon-btn" title="Facebook">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    
                                    {{-- TikTok --}}
                                    <a href="#" class="social-icon-btn" title="TikTok">
                                        <i class="bi bi-tiktok"></i>
                                    </a>

                                    {{-- WhatsApp --}}
                                    <a href="[https://wa.me/6285759873301](https://wa.me/6285759873301)" target="_blank" class="social-icon-btn" title="WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
            </div>
        </div>
    </div>
    </section>

    {{-- patner --}}
    <section class="py-5 bg-white">
        <div class="container">
            {{-- Judul kecil (opsional) --}}
            <div class="text-center mb-4">
                <small class="text-muted fw-bold ls-2">Partner</small>
            </div>

            {{-- Baris Logo Partner --}}
            {{-- Perbaikan: g-4 di mobile, g-lg-5 di desktop agar tidak overflow --}}
            <div class="row justify-content-center align-items-center g-4 g-lg-5 text-center">
                
                {{-- Partner 1 --}}
                <div class="col-6 col-md-3">
                    <img src="{{ asset('user-assets/Images/patner-1.png') }}" alt="Partner 1" class="img-fluid partner-logo">
                </div>

                {{-- Partner 2 --}}
                <div class="col-6 col-md-3">
                    <img src="{{ asset('user-assets/Images/patner-2.png') }}" alt="Partner 2" class="img-fluid partner-logo">
                </div>

                {{-- Partner 3 --}}
                <div class="col-6 col-md-3">
                    <img src="{{ asset('user-assets/Images/patner-3.png') }}" alt="Partner 3" class="img-fluid partner-logo">
                </div>

                {{-- Partner 4 --}}
                <div class="col-6 col-md-3">
                    <img src="{{ asset('user-assets/Images/patner-4.png') }}" alt="Partner 4" class="img-fluid partner-logo">
                </div>

            </div>
        </div>
    </section>

    {{-- artikel --}}
    <section class="py-5 bg-white border-top" id="artikel">
    <div class="container">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <small class="text-success fw-bold text-uppercase ls-2">Blog & Article</small>
                <h2 class="fw-bold mb-0 mt-2">Story From Garden</h2>
            </div>
            {{-- Tombol lihat semua (opsional, bisa diarahkan ke halaman arsip nanti) --}}
            <a href="#" class="btn btn-outline-dark rounded-pill px-4 d-none d-md-block">View All</a>
        </div>

        <div class="row g-4">
            @forelse($articles as $article)
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm article-card">
                        {{-- Gambar Artikel --}}
                        <div class="overflow-hidden rounded-top-4 position-relative">
                            {{-- Gambar Placeholder jika tidak ada gambar --}}
                            <img src="{{ $article->image ? asset('storage/' . $article->image) : 'https://placehold.co/600x400?text=Kegiatan+BGD' }}" 
                                 class="card-img-top object-fit-cover" 
                                 alt="{{ $article->title }}"
                                 style="height: 240px; transition: transform 0.5s ease;">
                            
                            {{-- Badge Tanggal --}}
                            <div class="position-absolute top-0 start-0 bg-white px-3 py-2 m-3 rounded-3 shadow-sm text-center">
                                <span class="d-block fw-bold text-dark fs-5">{{ $article->created_at->format('d') }}</span>
                                <small class="text-uppercase text-muted" style="font-size: 10px;">{{ $article->created_at->format('M') }}</small>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            {{-- Penulis & Waktu --}}
                            <div class="d-flex align-items-center text-muted mb-3" style="font-size: 0.85rem;">
                                <i class="bi bi-person-circle me-2 text-success"></i> {{ $article->author }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-clock me-2"></i> {{ $article->created_at->diffForHumans() }}
                            </div>

                            {{-- Judul --}}
                            <h5 class="card-title fw-bold mb-3">
                                <a href="{{ route('article.show', $article->slug) }}" class="text-dark text-decoration-none stretched-link title-hover">
                                    {{ $article->title }}
                                </a>
                            </h5>

                            {{-- Excerpt / Ringkasan --}}
                            <p class="card-text text-muted" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $article->excerpt }}
                            </p>
                        </div>
                        
                        <div class="card-footer bg-white border-0 p-4 pt-0">
                            <small class="text-success fw-bold">Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i></small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="bg-light rounded-3 p-5">
                        <i class="bi bi-journal-album fs-1 text-muted mb-3"></i>
                        <p class="text-muted">There are no recent activity articles.</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        {{-- Tombol lihat semua (versi mobile) --}}
        <div class="text-center mt-4 d-md-none">
            <a href="#" class="btn btn-outline-dark rounded-pill px-4 w-100">View All</a>
        </div>
    </div>
</section>

    
@endsection
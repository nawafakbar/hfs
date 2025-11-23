@extends('layouts.user')

@section('content')

{{-- STYLE KHUSUS --}}
<style>
    .article-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%; /* Pastikan kartu sama tinggi */
    }
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }
    .bg-header {
        background: linear-gradient(135deg, #145e3f 0%, #1d8c5e 100%);
        color: white;
    }
</style>

{{-- HEADER SECTION --}}
<section class="bg-header py-5 mt-5">
    <div class="container text-center py-4">
        <h1 class="display-6 fw-bold mb-3">Blog & Kegiatan</h1>
        <p class="mb-4" style="opacity: 0.9;">Berita terbaru, tips hidroponik, dan kegiatan seru dari kebun kami.</p>
        
        {{-- SEARCH FORM --}}
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('articles.index') }}" method="GET">
                    <div class="input-group input-group-sm shadow-sm">
                        <input type="text" name="search" class="form-control border-0" placeholder="Cari artikel..." value="{{ request('search') }}">
                        <button class="btn btn-warning px-4 fw-bold" type="submit">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ARTIKEL LIST SECTION --}}
<section class="py-5 bg-light">
    <div class="container">
        
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-5">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Blog</li>
            </ol>
        </nav>

        @if(request('search'))
            <div class="mb-4">
                <h5>Hasil pencarian untuk: <span class="fw-bold text-success">"{{ request('search') }}"</span></h5>
            </div>
        @endif

        <div class="row g-4">
            @forelse($articles as $article)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm article-card rounded-4 overflow-hidden">
                        {{-- Gambar Artikel --}}
                        <div class="position-relative overflow-hidden">
                            <img src="{{ $article->image ? asset('storage/' . $article->image) : 'https://placehold.co/600x400?text=BGD+News' }}" 
                                 class="card-img-top object-fit-cover" 
                                 alt="{{ $article->title }}"
                                 style="height: 220px;">
                            
                            {{-- Badge Tanggal --}}
                            <div class="position-absolute top-0 start-0 bg-white px-3 py-1 m-3 rounded-3 shadow-sm text-center border-start border-4 border-success">
                                <small class="fw-bold text-dark d-block">{{ $article->created_at->format('d M') }}</small>
                                <small class="text-muted" style="font-size: 10px;">{{ $article->created_at->format('Y') }}</small>
                            </div>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            {{-- Penulis --}}
                            <div class="d-flex align-items-center text-muted mb-2" style="font-size: 0.8rem;">
                                <i class="bi bi-person-circle me-1 text-success"></i> {{ $article->author }}
                            </div>

                            {{-- Judul --}}
                            <h5 class="card-title fw-bold mb-3">
                                <a href="{{ route('article.show', $article->slug) }}" class="text-dark text-decoration-none stretched-link">
                                    {{ $article->title }}
                                </a>
                            </h5>

                            {{-- Excerpt --}}
                            <p class="card-text text-muted mb-4 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.95rem;">
                                {{ $article->excerpt }}
                            </p>
                            
                            {{-- Link Baca --}}
                            <div class="mt-auto text-end">
                                <small class="text-success fw-bold">Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i></small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h4 class="text-muted">Belum ada artikel ditemukan.</h4>
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-success mt-3 rounded-pill px-4">Reset Pencarian</a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $articles->links() }}
        </div>

    </div>
</section>

@endsection
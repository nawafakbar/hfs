@extends('layouts.user')
@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Blog</li>
                </ol>
            </nav>

            {{-- Judul Utama --}}
            <h1 class="display-5 mb-4">{{ $article->title }}</h1>

            {{-- Meta Info --}}
            <div class="d-flex align-items-center mb-4 text-muted border-bottom pb-4">
                <div class="d-flex align-items-center me-4">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <small class="d-block lh-1">Penulis</small>
                        <span class="text-dark">{{ $article->author }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar3 me-2"></i>
                    <span>{{ $article->created_at->isoFormat('D MMMM Y') }}</span>
                </div>
            </div>

            {{-- Gambar Utama --}}
            <div class="mb-5 rounded-4 overflow-hidden shadow-sm">
                <img src="{{ $article->image ? asset('storage/' . $article->image) : 'https://placehold.co/1200x600?text=Kegiatan+BGD' }}" 
                     alt="{{ $article->title }}" 
                     class="img-fluid w-100">
            </div>

            {{-- Konten Artikel --}}
            <div class="article-content text-secondary mb-5 px-3" style="text-align: justify;">
                {!! nl2br(e($article->content)) !!}
            </div>

            {{-- Tombol Share (Opsional/Dummy) --}}
            <div class="d-flex gap-2 mb-5">
                <button class="btn btn-outline-success rounded-pill"><i class="bi bi-whatsapp"></i> Share</button>
                <button class="btn btn-outline-primary rounded-pill"><i class="bi bi-facebook"></i> Share</button>
            </div>

        </div>
    </div>

    {{-- Artikel Terkait --}}
    @if($relatedArticles->count() > 0)
    <div class="row justify-content-center mt-5 pt-5 border-top">
        <div class="col-12 text-center mb-4">
            <h3 class="fw-bold">Baca Juga</h3>
        </div>
        @foreach($relatedArticles as $related)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ $related->image ? asset('storage/' . $related->image) : 'https://placehold.co/600x400' }}" class="card-img-top" alt="{{ $related->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="fw-bold"><a href="{{ route('article.show', $related->slug) }}" class="text-decoration-none text-dark stretched-link">{{ $related->title }}</a></h6>
                        <small class="text-muted">{{ $related->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
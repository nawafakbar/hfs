@extends('layouts.user')

@section('title', $product->name . ' - Bgd Hydrofarm')

@section('content')
<main class="product-detail-page py-5">
    <div class="container">
        <div class="row">
            {{-- GAMBAR PRODUK --}}
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="product-image-container shadow-sm rounded-4 overflow-hidden">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid w-100">
                </div>
            </div>

            <div class="col-lg-6 ps-lg-5">
                {{-- BREADCRUMB --}}
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-success">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                    </ol>
                </nav>

                {{-- NAMA & HARGA PRODUK --}}
                <h1 class="product-title fw-bold mb-2">{{ $product->name }}</h1>
                
                @if($product->discount_price)
                    <p class="product-price fs-3 fw-bold text-success mb-3">
                        Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                        <small class="text-muted text-decoration-line-through fs-6 ms-2">Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                    </p>
                @else
                    <p class="product-price fs-3 fw-bold text-success mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                @endif

                {{-- DESKRIPSI SINGKAT --}}
                <p class="text-muted mb-4 lh-lg">{{ Str::limit($product->description, 200) }}</p>

                {{-- FORM ADD TO CART (YANG SUDAH DIPERBAIKI) --}}
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    {{-- INPUT HIDDEN: Mengirim ID Produk yang benar --}}
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="d-flex align-items-end gap-3 mb-4">
                        <div style="max-width: 100px;">
                            <label class="form-label fw-bold small text-muted">Jumlah</label>
                            {{-- INPUT QUANTITY: Mengirim jumlah barang --}}
                            <input type="number" 
                                   name="quantity" 
                                   class="form-control text-center" 
                                   value="1" 
                                   min="1" 
                                   max="{{ $product->stock }}" 
                                   oninput="if(parseInt(this.value) > {{ $product->stock }}) this.value = {{ $product->stock }};">
                        </div>
                        
                        <button type="submit" class="btn btn-success flex-grow-1 py-2">
                            <i class="bi bi-cart-plus me-2"></i> Masukkan Keranjang
                        </button>
                    </div>
                </form>

                {{-- META PRODUK --}}
                <div class="product-meta border-top pt-4">
                    <p class="mb-2">
                        <strong>Kategori:</strong> 
                        <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Stok Tersedia:</strong> 
                        @if($product->stock > 0)
                            <span class="badge bg-success">{{ $product->stock }} Ikat</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </p>
                    <p class="text-muted mt-3"><i class="bi bi-instagram me-2"></i> Follow kami di <strong>@bgd_hydrofarm</strong></p>
                </div>
            </div>
        </div>

        {{-- TAB DESKRIPSI & REVIEW --}}
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <ul class="nav nav-pills" id="productTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill px-4" id="description-tab" data-bs-toggle="tab" data-bs-target="#description-pane" type="button">Deskripsi</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button">Ulasan ({{ $product->testimonials->count() }})</button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="tab-content" id="productTabContent">
                            {{-- Tab Deskripsi --}}
                            <div class="tab-pane fade show active" id="description-pane" role="tabpanel">
                                <p class="text-secondary lh-lg">{{ $product->description }}</p>
                            </div>
                            
                            {{-- Tab Ulasan --}}
                            <div class="tab-pane fade" id="reviews-pane" role="tabpanel">
                                @forelse($product->testimonials as $testimonial)
                                    <div class="review-item mb-4 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person-fill text-secondary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $testimonial->user->name }}</h6>
                                                    <small class="text-muted" style="font-size: 12px;">{{ $testimonial->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <div class="text-warning">
                                                @for ($i = 0; $i < 5; $i++)
                                                    @if($i < $testimonial->rating)
                                                        <i class="bi bi-star-fill"></i>
                                                    @else
                                                        <i class="bi bi-star text-muted opacity-25"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-secondary ps-5 ms-2 mb-0">"{{ $testimonial->comment }}"</p>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        <i class="bi bi-chat-square-text fs-1 d-block mb-2 opacity-50"></i>
                                        <p>Belum ada ulasan untuk produk ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
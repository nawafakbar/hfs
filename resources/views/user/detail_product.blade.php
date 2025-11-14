{{-- resources/views/user/detail_product.blade.php --}}
@extends('layouts.user')

@section('title', $product->name . ' - Bgd Hydrofarm')

@section('content')
<main class="product-detail-page">
    <div class="container">
        <div class="row">
            {{-- GAMBAR PRODUK --}}
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="product-image-container">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded-4">
                </div>
            </div>

            <div class="col-lg-6">
                {{-- BREADCRUMB --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Shop</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                    </ol>
                </nav>

                {{-- NAMA & HARGA PRODUK --}}
                <h1 class="product-title">{{ $product->name }}</h1>
                @if($product->discount_price)
                    <p class="product-price">
                        Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                        <small class="text-muted text-decoration-line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                    </p>
                @else
                    <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                @endif

                {{-- DESKRIPSI SINGKAT --}}
                <p class="text-muted">{{ Str::limit($product->description, 150) }}</p>

                {{-- FORM ADD TO CART --}}
                <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="d-flex align-items-center my-4">
                    <div class="input-group quantity-selector me-3">
                        <button class="btn btn-outline-secondary" type="button" id="button-minus">-</button>
                        <input type="text" name="quantity" class="form-control text-center" value="1" id="quantity-input">
                        <button class="btn btn-outline-secondary" type="button" id="button-plus">+</button>
                    </div>
                    <button type="submit" class="btn btn-brand flex-grow-1">Add to Cart</button>
                </div>
            </form>

                {{-- META PRODUK --}}
                <div class="product-meta">
                    <p><strong>Categories:</strong> 
                        <a href="#" class="badge bg-light text-dark">{{ $product->category->name }}</a>
                        <strong class="ms-3">Stok: </strong> 
                        @if($product->stock > 0)
                            <span class="badge bg-light text-dark">{{ $product->stock }}</span>
                        @else
                            <span class="badge bg-light text-dark">Stok Habis</span>
                        @endif
                    </p>
                    <p><i class="bi bi-instagram me-2"></i> BGD_hydrofarm</p>
                </div>
            </div>
        </div>

        {{-- TAB DESKRIPSI & REVIEW --}}
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs product-tabs" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description-pane" type="button">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button">Reviews ({{ $product->testimonials->count() }})</button>
                    </li>
                </ul>
                <div class="tab-content pt-4" id="productTabContent">
                    <div class="tab-pane fade show active" id="description-pane" role="tabpanel">
                        <p>{{ $product->description }}</p>
                    </div>
                    <div class="tab-pane fade" id="reviews-pane" role="tabpanel">
                        @forelse($product->testimonials as $testimonial)
                            <div class="review mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="fw-bold me-2">{{ $testimonial->user->name }}</div>
                                    <div class="text-warning">
                                        @for ($i = 0; $i < $testimonial->rating; $i++) ★ @endfor
                                    </div>
                                </div>
                                <p class="text-muted">{{ $testimonial->comment }}</p>
                                <small>{{ $testimonial->created_at->diffForHumans() }}</small>
                            </div>
                            <hr>
                        @empty
                            <p>Belum ada review untuk produk ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
{{-- JAVASCRIPT UNTUK TOMBOL KUANTITAS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const minusBtn = document.getElementById('button-minus');
        const plusBtn = document.getElementById('button-plus');
        const quantityInput = document.getElementById('quantity-input');

        minusBtn.addEventListener('click', function () {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        plusBtn.addEventListener('click', function () {
            let currentValue = parseInt(quantityInput.value);
            // Nanti kita bisa batasi dengan stok produk
            quantityInput.value = currentValue + 1;
        });
    });
</script>
@endpush
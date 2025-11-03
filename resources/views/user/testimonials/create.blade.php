@extends('layouts.profile')

@section('title', 'Beri Ulasan')

{{-- Tambahkan CSS untuk Bintang Rating --}}
<style>
.rating { display: inline-block; }
.rating input { display: none; }
.rating label {
    float: right;
    cursor: pointer;
    color: #ccc;
    font-size: 2rem;
    transition: color 0.2s;
}
.rating label:before { content: '★'; }
.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label { color: #ffc107; }
</style>

@section('content')
    <h2 class="mb-1">Beri Ulasan</h2>
    <p class="text-muted">Bagikan pendapat Anda tentang produk ini.</p>
    <hr class="my-4">

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;" class="me-3">
                <div>
                    <h4 class="mb-0">{{ $product->name }}</h4>
                    <p class="text-muted mb-0">Beri rating untuk produk ini</p>
                </div>
            </div>
            
            <hr>

            <form action="{{ route('testimonial.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="mb-0">
                    <label class="form-label">Rating Anda</label>
                </div>
                <div class="rating">
                        <input type="radio" id="star5" name="rating" value="5" /><label for="star5"></label>
                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4"></label>
                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3"></label>
                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2"></label>
                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1"></label>
                    </div>
                    @error('rating') <div class="text-danger">{{ $message }}</div> @enderror

                <div class="mb-3 mt-2">
                    <label for="comment" class="form-label">Ulasan Anda</label>
                    <textarea name="comment" id="comment" class="form-control" rows="5" placeholder="Tulis pendapat Anda tentang produk ini..." required>{{ old('comment') }}</textarea>
                    @error('comment') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-success">Kirim Ulasan</button>
            </form>
        </div>
    </div>
@endsection
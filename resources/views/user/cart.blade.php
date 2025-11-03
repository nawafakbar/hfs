@extends('layouts.user')
@section('title', 'Keranjang Belanja - Bgd Hydrofarm')
@section('content')
<section class="page-header">
    <div class="container">
        <h1 class="page-title">Shopping Cart</h1>
        {{-- Breadcrumb bisa ditambahkan di sini --}}
    </div>
</section>

<section class="shopping-cart-section">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="cart-items-wrapper">
                    <table class="table align-middle cart-table">
                        <thead>
                            <tr>
                                <th scope="col" class="product-col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cartItems->sortBy('name') as $item)
                            <tr>
                                <td data-label="Product">
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-remove me-3">×</button>
                                        </form>
                                        <img src="{{ asset('storage/' . $item->attributes->image) }}" alt="{{ $item->name }}" class="cart-item-img">
                                        <div>
                                            <h6 class="cart-item-title ms-3">{{ $item->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Price" class="cart-item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td data-label="Quantity">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group quantity-selector">
                                            <input type="number" name="quantity" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" min="1">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                        </div>
                                    </form>
                                </td>
                                <td data-label="Subtotal" class="cart-item-subtotal">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Keranjang belanja Anda kosong.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="order-summary-card">
                    <h3 class="card-title mb-4">Order Summary</h3>
                    <div class="summary-item">
                        <span>Subtotal</span>
                        {{-- Gunakan variabel $subTotal dari controller --}}
                        <span>Rp {{ number_format($subTotal, 0, ',', '.') }}</span>
                    </div>
                    <hr class="my-3">
                    <div class="summary-item summary-total">
                        <span>Total</span>
                        {{-- Gunakan variabel $total dari controller --}}
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-grid mt-4">
                        {{-- Cek keranjang pakai $cartItems --}}
                        <a href="{{ route('checkout.index') }}" class="btn btn-checkout {{ $cartItems->isEmpty() ? 'disabled' : '' }}">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
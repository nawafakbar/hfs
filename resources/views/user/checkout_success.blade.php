@extends('layouts.user')
@section('title', 'Pesanan Berhasil')
@section('content')
<div class="container py-5" style="margin-top: 100px;">
    <div class="text-center mb-5">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
        <h1 class="mt-3">Terima Kasih!</h1>
        <p class="lead">Pesanan Anda (#{{ $order->invoice_number }}) telah berhasil dibuat.</p>        
        <div class="d-flex justify-content-center gap-2 mt-4">
            <a href="{{ route('orders.print', $order->invoice_number) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                Cetak Invoice
            </a>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">Lihat Riwayat Pesanan</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-light">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Beri Ulasan untuk Produk Anda</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($order->orderItems as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" class="me-3">
                            <span>{{ $item->product->name }}</span>
                        </div>
                        <a href="{{ route('testimonial.create', $item->product_id) }}" class="btn btn-sm btn-outline-success">
                            Beri Ulasan
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
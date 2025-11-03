@extends('layouts.profile')

@section('title', 'Detail Pesanan ' . $order->invoice_number)

@section('content')

    {{-- BAGIAN HEADER HALAMAN (Judul & Tombol Aksi) --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h2 class="mb-1">Detail Pesanan</h2>
            <p class="text-muted mb-0">Order #{{ $order->invoice_number }}</p>
        </div>
        
        {{-- KUMPULAN TOMBOL AKSI --}}
        <div class="d-flex gap-2">
            @if ($order->status == 'pending')
                <a href="{{ route('checkout.payment', $order->invoice_number) }}" class="btn btn-danger">
                    Bayar Sekarang
                </a>
            @elseif (in_array($order->status, ['paid', 'completed', 'packaging', 'shipping']))
                <a href="{{ route('orders.print', $order->invoice_number) }}" target="_blank" class="btn btn-outline-success">
                    Cetak Invoice
                </a>
            @endif
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <hr class="my-4">

    {{-- KARTU INFORMASI PENGIRIMAN & RINGKASAN --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Dikirim Ke:</h5>
                    <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                    <p class="mb-1">{{ $order->user->phone_number }}</p>
                    <p class="mb-0">{{ $order->shipping_address }}</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <h5>Ringkasan</h5>
                    <p class="mb-1"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</p>
                    <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    <p class="mb-1"><strong>Status:</strong> 
                        <span class="badge {{ 
                            match($order->status) {
                                'pending' => 'bg-warning text-dark',
                                'paid' => 'bg-success',
                                'packaging' => 'bg-info',
                                'shipping' => 'bg-primary',
                                'completed' => 'bg-secondary',
                                'cancelled' => 'bg-danger',
                                default => 'bg-light text-dark',
                            } 
                        }}">{{ ucfirst($order->status) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- KARTU ITEM PESANAN (TABEL SUDAH DIPERBAIKI) --}}
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Item Pesanan</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th scope="col">Produk</th>
                        <th scope="col" class="text-end">Harga</th>
                        <th scope="col" class="text-center">Kuantitas</th>
                        <th scope="col" class="text-end">Subtotal</th>
                        <th scope="col" class="text-center">Ulasan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                
                                {{-- ======================================================== --}}
                                {{-- INI DIA PERBAIKANNYA: STYLE DITAMBAHKAN LANGSUNG (INLINE) --}}
                                {{-- ======================================================== --}}
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                
                                <span class="ms-3">{{ $item->product->name }}</span>
                            </div>
                        </td>
                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>

                        {{-- LOGIKA TOMBOL ULASAN --}}
                        <td class="text-center">
                            @if(in_array($item->product_id, $reviewedProductIds))
                                <span class="badge bg-success">Sudah Direview</span>
                            @elseif(in_array($order->status, ['paid', 'completed']))
                                <a href="{{ route('testimonial.create', $item->product_id) }}" class="btn btn-sm btn-outline-primary">
                                    Beri Ulasan
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
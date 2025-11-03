@extends('layouts.user')
@section('title', 'Konfirmasi Checkout')
@section('content')
<div class="container py-5" style="margin-top: 100px;">
    <h2 class="mb-4">Konfirmasi Pesanan</h2>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Alamat Pengiriman</h4>
                    <hr>
                    <p><strong>{{ auth()->user()->name }}</strong></p>
                    <p>{{ auth()->user()->phone_number }}</p>
                    <p>{{ auth()->user()->address }}</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">Ubah Alamat</a>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Ringkasan Pesanan</h4>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>Rp {{ number_format(Cart::getTotal(), 0, ',', '.') }}</span>
                    </div>
                    
                    {{-- Form ini akan membuat order dan mengalihkan ke halaman pembayaran --}}
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-brand w-100 mt-4">Lanjutkan ke Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.user')
@section('title', 'Konfirmasi Checkout')
@section('content')

@php
    $user = auth()->user();
    $kecamatan = $user->kecamatan;
    $ongkir = config('ongkir.zona_padang')[$kecamatan] ?? 0;
    $subtotal = Cart::getTotal();
@endphp

<div class="container py-5" style="margin-top: 100px;">
    <h2 class="mb-4">Konfirmasi Pesanan</h2>

    <div class="row">

        <!-- ========================== -->
        <!-- ALAMAT PENGIRIMAN -->
        <!-- ========================== -->
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Alamat Pengiriman</h4>
                    <hr>
                    <p><strong>{{ $user->name }}</strong></p>
                    <p>{{ $user->phone_number }}</p>
                    <p>{{ $user->address }}</p>

                    <p><strong>Kecamatan:</strong> {{ $user->kecamatan }}</p>

                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">
                        Ubah Alamat
                    </a>
                </div>
            </div>
        </div>

        <!-- ========================== -->
        <!-- RINGKASAN PESANAN -->
        <!-- ========================== -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Ringkasan Pesanan</h4>
                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Ongkir ({{ $kecamatan }})</span>
                        <span id="ongkir_display">
                            Rp {{ number_format($ongkir, 0, ',', '.') }}
                        </span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span id="total_display">
                            Rp {{ number_format($subtotal + $ongkir, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- ======================== --}}
                    {{-- METODE PENGIRIMAN --}}
                    {{-- ======================== --}}
                    <form action="{{ route('checkout.process') }}" method="POST" class="mt-3">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Metode Pengiriman</label>
                            <select class="form-select" name="shipping_method" id="shipping_method">
                                <option value="delivery">Diantar (Delivery)</option>
                                <option value="pickup">Ambil di Tempat (Gratis)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 mt-3">
                            Lanjutkan ke Pembayaran
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- ======================== --}}
{{-- SCRIPT UPDATE ONGKIR --}}
{{-- ======================== --}}
<script>
document.getElementById('shipping_method').addEventListener('change', function () {
    const ongkirNormal = {{ $ongkir }};
    const subtotal = {{ $subtotal }};
    
    if (this.value === 'pickup') {
        document.getElementById('ongkir_display').innerText = "Rp 0 (Pickup)";
        document.getElementById('total_display').innerText =
            "Rp " + subtotal.toLocaleString('id-ID');
    } else {
        document.getElementById('ongkir_display').innerText =
            "Rp " + ongkirNormal.toLocaleString('id-ID');
        document.getElementById('total_display').innerText =
            "Rp " + (subtotal + ongkirNormal).toLocaleString('id-ID');
    }
});
</script>

@endsection

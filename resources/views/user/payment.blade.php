@extends('layouts.user')

@section('title', 'Pembayaran Pesanan')

{{-- Push CSS khusus ke layout --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('user-assets/css/payment-card.css') }}">
@endpush

@section('content')
<div class="payment-overlay mt-5">
    <div class="payment-card my-5">
        
        {{-- HEADER KARTU --}}
        <div class="payment-card-header">
            <h4>BGD Hydrofarm</h4>
            <span class="invoice-id">Order ID: #{{ $order->invoice_number }}</span>
        </div>

        {{-- BODY KARTU --}}
        <div class="payment-card-body">

            {{-- GANTI DENGAN PATH GAMBAR QRIS-mu --}}
            <img src="{{ asset('user-assets/images/qris.jpg') }}" alt="QRIS BGD Hydrofarm" class="payment-qris-image">
            <div>
            <a href="{{ asset('user-assets/images/qris.jpg') }}" download="QRIS_BGD_Hydrofarm.png" class="btn btn-sm btn-outline-success mb-3">
                <i class="bi bi-download me-1"></i> Unduh QRIS
            </a>
            </div>

            <div class="payment-summary mt-3 mb-4">
            <strong>Rincian Pembayaran:</strong>
            <ul class="list-unstyled mt-2">
                <li class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <strong>Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</strong>
                </li>

                <li class="d-flex justify-content-between">
                    <span>Ongkir</span>
                    <strong>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</strong>
                </li>
            </ul>
        </div>

            <div class="payment-total">
                <p class="payment-total-label">Total Pembayaran</p>
                <h2 class="payment-total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h2>
            </div>
            
            <div id="payment-timer">Batas Waktu Pembayaran: 09:59</div>

            <div class="payment-instructions">
                <strong>Cara Pembayaran:</strong>
                <ul>
                    <li>Buka aplikasi e-wallet (GoPay, OVO, DANA, dll) atau M-Banking Anda.</li>
                    <li>Scan QR Code di atas.</li>
                    <li>Pastikan nominal pembayaran sudah sesuai.</li>
                    <li>Selesaikan pembayaran.</li>
                </ul>
            </div>
        </div>

        {{-- FOOTER KARTU --}}
        <div class="payment-card-footer">
            <p>Setelah membayar, silakan upload bukti transfer di halaman "Pesanan Saya".</p>
            <a href="{{ route('orders.index') }}" class="btn btn-brand" style="font-size: 15px">Ke Halaman Pesanan Saya</a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Script untuk timer countdown 10 menit --}}
<script>
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        var interval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = "Batas Waktu Pembayaran: " + minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(interval);
                display.textContent = "Waktu pembayaran habis";
                // Kamu bisa tambahkan logic untuk mengalihkan user jika waktu habis
            }
        }, 1000);
    }

    window.onload = function () {
        var tenMinutes = 60 * 10, // 10 menit
            display = document.querySelector('#payment-timer');
        startTimer(tenMinutes, display);
    };
</script>
@endpush
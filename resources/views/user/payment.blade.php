@extends('layouts.user')
@section('title', 'Pembayaran')
@section('content')
<div class="container py-5 text-center" style="margin-top: 100px;">
    <h2 class="mb-4">Selesaikan Pembayaran Anda</h2>
    <p>Silakan klik tombol di bawah untuk melanjutkan proses pembayaran.</p>
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h5 class="card-title">Order #{{ $order->invoice_number }}</h5>
            <p class="card-text">Total Tagihan:</p>
            <h3 class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
            <button id="pay-button" class="btn btn-brand w-100 mt-3">Bayar Sekarang</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    function payNow() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                // PERUBAHAN DI SINI: kirim invoice number (order_id) ke URL
                window.location.href = '{{ route("checkout.success") }}?invoice=' + result.order_id;
            },
            onPending: function(result){
                alert("Menunggu pembayaran Anda!");
                window.location.href = '{{ route("orders.index") }}'; // Ubah ke riwayat pesanan
            },
            onError: function(result){
                alert("Pembayaran gagal!");
                window.location.href = '{{ route("orders.index") }}'; // Ubah ke riwayat pesanan
            },
            
            // PERUBAHAN UTAMA DI SINI
            onClose: function(){
                alert('Pesanan Anda telah dibuat. Selesaikan pembayaran nanti di halaman "Pesanan Saya".');
                // Alihkan ke halaman riwayat pesanan
                window.location.href = '{{ route("orders.index") }}';
            }
        });
    }

    // Panggil pembayaran secara otomatis saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        payNow();
    });

    // Juga tambahkan listener ke tombol sebagai cadangan
    document.getElementById('pay-button').onclick = function(){
        payNow();
    };
</script>
@endpush
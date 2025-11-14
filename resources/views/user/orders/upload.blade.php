@extends('layouts.profile')
@section('title', 'Upload Bukti Pembayaran')
@section('content')
<h2 class="mb-1">Upload Bukti Pembayaran</h2>
<p class="text-muted">Untuk Order #{{ $order->invoice_number }}</p>
<hr class="my-4">

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Total Tagihan: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5>
        <p>Silakan upload bukti transfer Anda di sini. Admin akan segera memverifikasi pembayaran Anda.</p>

        <form action="{{ route('orders.storeUpload', $order->invoice_number) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="payment_proof" class="form-label">Upload Gambar (JPG, PNG maks 2MB)</label>
                <input class="form-control" type="file" id="payment_proof" name="payment_proof" required>
                @error('payment_proof')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Upload dan Konfirmasi</button>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
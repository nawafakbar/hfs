@extends('layouts.admin')
@section('page-title', 'Detail Pesanan')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Pesanan: {{ $order->invoice_number }}</h3>
        <div class="card-actions">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Kembali ke Daftar</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <h4>Informasi Pelanggan</h4>
                <p><strong>Nama:</strong> {{ $order->user->name ?? 'User Dihapus' }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
                <p><strong>Alamat Pengiriman:</strong><br>{{ $order->shipping_address }}</p>
            </div>
            <div class="col-md-6">
                <h4>Informasi Pesanan</h4>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Total Bayar:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <hr>
        <h4>Rincian Produk</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <h4>Update Status Pesanan</h4>
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-9">
                    <select name="status" class="form-select">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="packaging" {{ $order->status == 'packaging' ? 'selected' : '' }}>Packaging</option>
                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Shipping</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
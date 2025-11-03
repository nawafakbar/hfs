@extends('layouts.admin')
@section('page-title', 'Manajemen Pesanan')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Daftar Pesanan Masuk</h3></div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->invoice_number }}</td>
                            <td>{{ $order->user->name ?? 'User Dihapus' }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'completed') <span class="badge bg-success me-1"></span>
                                @elseif($order->status == 'shipping') <span class="badge bg-info me-1"></span>
                                @elseif($order->status == 'packaging') <span class="badge bg-primary me-1"></span>
                                @elseif($order->status == 'paid') <span class="badge bg-secondary me-1"></span>
                                @else <span class="badge bg-warning me-1"></span>
                                @endif
                                {{ ucfirst($order->status) }}
                            </td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-info me-2">Detail</a>
                                    {{-- TAMBAHKAN FORM HAPUS INI --}}
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
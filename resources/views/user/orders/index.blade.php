@extends('layouts.profile')

@section('title', 'Pesanan Saya')

@section('content')
    <div>
        <h2 class="mb-1">Pesanan Saya</h2>
        <p class="text-muted">Lihat riwayat dan status semua pesanan Anda.</p>
    </div>
    <hr class="my-4">

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', $order->invoice_number) }}">
                                {{ $order->invoice_number }}
                            </a>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        
                        {{-- PERUBAHAN UTAMA DI SINI --}}
                        <td>
                            @if ($order->status == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                            @elseif ($order->status == 'paid')
                                <span class="badge bg-success">Sudah Dibayar</span>
                            @elseif ($order->status == 'packaging')
                                <span class="badge bg-info">Sedang Dikemas</span>
                            @elseif ($order->status == 'shipping')
                                <span class="badge bg-primary">Sedang Dikirim</span>
                            @elseif ($order->status == 'completed')
                                <span class="badge bg-secondary">Selesai</span>
                            @elseif ($order->status == 'cancelled')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @endif
                        </td>
                        <td>
                            @if ($order->status == 'pending')
                                {{-- TOMBOL BAYAR LAGI --}}
                                <a href="{{ route('checkout.payment', $order->invoice_number) }}" class="btn btn-outline-danger btn-sm">
                                    Bayar Sekarang
                                </a>
                            @else
                                <a href="{{ route('orders.show', $order->invoice_number) }}" class="btn btn-outline-info btn-sm">
                                    Lihat Detail
                                </a>
                            @endif
                        </td>
                        {{-- AKHIR PERUBAHAN --}}

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Anda belum memiliki riwayat pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{ $orders->links() }}
        </div>
    </div>
@endsection

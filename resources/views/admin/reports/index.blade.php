@extends('layouts.admin')
@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="text-muted mt-1">Rekap pendapatan BGD Hydrofarm</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        
        <!-- CARD FILTER -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('admin.reports.index') }}" method="GET">
                    <input type="hidden" name="filter" value="true">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Pilih Periode</label>
                            <select name="periode" class="form-select" id="periode-select">
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="weekly" {{ request('periode') == 'weekly' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="monthly" {{ request('periode') == 'monthly' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="yearly" {{ request('periode') == 'yearly' ? 'selected' : '' }}>Tahun Ini</option>
                                <option value="custom" {{ request('start_date') ? 'selected' : '' }}>Custom Tanggal</option>
                            </select>
                        </div>
                        
                        <!-- Input Tanggal Custom (Opsional, pakai JS buat show/hide kalau mau canggih) -->
                        <div class="col-md-3">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                Tampilkan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(request('filter'))
            <!-- HASIL LAPORAN -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Laporan</h3>
                    <div class="card-actions btn-list">
                        <!-- Tombol Export Excel -->
                        <a href="{{ route('admin.reports.excel', request()->all()) }}" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-spreadsheet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                            Export Excel
                        </a>
                    </div>
                </div>
                
                <div class="card-body border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <span class="text-muted">Total Pendapatan Periode Ini:</span>
                        <h2 class="ms-auto text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->invoice_number }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                    <td>
                                        <span class="badge bg-green-lt">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Tidak ada data transaksi pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
@extends('layouts.admin')
@section('page-title', 'Manajemen Testimoni')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Daftar Testimoni</h3></div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Pelanggan</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $testimonial)
                        <tr>
                            <td>{{ $testimonial->product->name ?? 'Produk Dihapus' }}</td>
                            <td>{{ $testimonial->user->name ?? 'User Dihapus' }}</td>
                            <td>{{ $testimonial->rating }} ★</td>
                            <td>{{ Str::limit($testimonial->comment, 50) }}</td>
                            <td>
                                @if($testimonial->status == 'approved') <span class="badge bg-success">Approved</span>
                                @else <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($testimonial->status == 'pending')
                                <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                </form>
                                @endif
                                <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada testimoni.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $testimonials->links() }}</div>
    </div>
</div>
@endsection
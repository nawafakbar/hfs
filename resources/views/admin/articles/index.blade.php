@extends('layouts.admin')
@section('page-title', 'Manajemen Artikel')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Artikel</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-table me-1"></i> Daftar Artikel & Kegiatan</span>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Artikel Baru
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($article->image)
                                    <img src="{{ asset('storage/' . $article->image) }}" width="100" class="rounded">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->author }}</td>
                            <td>{{ $article->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
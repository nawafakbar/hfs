@extends('layouts.admin')
@section('page-title', 'Edit Artikel')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Artikel</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- WAJIB UNTUK UPDATE --}}
                
                <div class="mb-3">
                    <label class="form-label">Judul Artikel</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar Utama (Biarkan kosong jika tidak ingin mengganti)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if($article->image)
                        <div class="mt-2">
                            <small>Gambar Saat Ini:</small><br>
                            <img src="{{ asset('storage/' . $article->image) }}" width="150" class="rounded border">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Artikel</label>
                    <textarea name="content" rows="10" class="form-control">{{ old('content', $article->content) }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Update Artikel</button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')
@section('page-title', 'Create Artikel')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Artikel Baru</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Judul Artikel</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Panen Raya Hidroponik">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar Utama (Cover)</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    <div class="form-text">Format: JPG, PNG, WEBP. Maks: 2MB.</div>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Artikel</label>
                    {{-- Kalau pakai text editor (Summernote/CKEditor), class-nya sesuaikan --}}
                    <textarea name="content" rows="10" class="form-control @error('content') is-invalid @enderror" placeholder="Tulis cerita kegiatan di sini...">{{ old('content') }}</textarea>
                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Artikel</button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
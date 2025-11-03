@extends('layouts.admin')
@section('page-title', 'Tambah Kategori')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Form Tambah Kategori</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label required">Nama Kategori</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Sayuran Daun" value="{{ old('name') }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-link">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
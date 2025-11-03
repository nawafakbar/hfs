@extends('layouts.admin')
@section('page-title', 'Tambah Produk')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Form Tambah Produk</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label required">Nama Produk</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label class="form-label required">Kategori</label>
                <select name="category_id" class="form-select">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label required">Deskripsi</label>
                <textarea name="description" rows="5" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Harga</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Harga Diskon (Opsional)</label>
                        <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price') }}">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label required">Stok</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock') }}">
            </div>
            <div class="mb-3">
                <label class="form-label required">Gambar Produk</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-link">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')
@section('page-title', 'Edit Produk')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Form Edit Produk: {{ $product->name }}</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label required">Nama Produk</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
            </div>
            <div class="mb-3">
                <label class="form-label required">Kategori</label>
                <select name="category_id" class="form-select">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label required">Deskripsi</label>
                <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Harga</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Harga Diskon (Opsional)</label>
                        <input type="number" name="discount_price" class="form-control" value="{{ old('discount_price', $product->discount_price) }}">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label required">Stok</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Ganti Gambar (Kosongkan jika tidak diubah)</label>
                <input type="file" name="image" class="form-control">
                <small class="form-hint">Gambar saat ini: <br><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="150" class="mt-2"></small>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-link">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')
@section('page-title', 'Edit Customer')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Form Edit Customer</h3></div>
    <div class="card-body">
        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">No. Telepon</label>
                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $customer->phone_number) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="address" class="form-control">{{ old('address', $customer->address) }}</textarea>
            </div>
            <hr>
            <div class="mb-3">
                <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-link">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.profile')

@section('title', 'Profil Saya')

@section('content')
    <div>
        <h2 class="mb-1">Profil Saya</h2>
        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif
        <p class="text-muted">Kelola informasi profil anda unutuk mengontrol, melindungi, dan mengamankan akun.</p>
    </div>
    <hr class="my-4">

    {{-- Notifikasi Sukses --}}
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success">Profil berhasil diperbarui.</div>
    @endif
    
    {{-- FORM PROFIL --}}
    <div class="row">
        {{-- Form utama ada di kolom kiri --}}
        <div class="col-md-8">
            {{-- PENTING: Pastikan ada enctype="multipart/form-data" --}}
            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch') {{-- PENTING: Pastikan ada @method('patch') --}}

                {{-- Nama Lengkap --}}
                <div class="mb-3 row">
                    <label for="name" class="col-sm-3 col-form-label">Nama Lengkap</label>
                    <div class="col-sm-9">
                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-3 row">
                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                </div>

                {{-- Nomor Telepon --}}
                <div class="mb-3 row">
                    <label for="phone_number" class="col-sm-3 col-form-label">Nomor Telepon</label>
                    <div class="col-sm-9">
                        <input id="phone_number" name="phone_number" type="text" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
                        <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                    </div>
                </div>
                
                {{-- Alamat Lengkap --}}
                <div class="mb-3 row">
                    <label for="address" class="col-sm-3 col-form-label">Alamat Lengkap</label>
                    <div class="col-sm-9">
                        <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>
                </div>

                {{-- PENTING: Input file untuk foto, terhubung dengan form ini --}}
                <input type="file" id="photo-upload" name="profile_photo" class="d-none">

                <div class="row mt-4">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Kolom kanan untuk menampilkan dan memilih foto --}}
        <div class="col-md-4">
            <div class="text-center mt-3">
                <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" alt="Avatar" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                <label for="photo-upload" class="btn btn-outline-dark btn-sm mt-3">Pilih Gambar</label>
                 <small class="d-block text-muted mt-1">Klik untuk mengganti foto profil</small>
            </div>
        </div>
    </div>
    
    <hr class="my-5">

    {{-- FORM UPDATE PASSWORD --}}
    <div>
        <h3 class="mb-1">Update Password</h3>
        <p class="text-muted">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
        
        @if (session('status') === 'password-updated')
            <div class="alert alert-success mt-3">Password berhasil diperbarui.</div>
        @endif

        <form method="post" action="{{ route('password.update') }}" class="mt-4">
            @csrf
            @method('put')

            <div class="mb-3 row">
                <label for="current_password" class="col-sm-3 col-form-label">Password Saat Ini</label>
                <div class="col-sm-9">
                    <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password" class="col-sm-3 col-form-label">Password Baru</label>
                <div class="col-sm-9">
                    <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password_confirmation" class="col-sm-3 col-form-label">Konfirmasi Password</label>
                <div class="col-sm-9">
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-dark">Simpan Password</button>
                </div>
            </div>
        </form>
    </div>
@endsection
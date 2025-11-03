@extends('layouts.auth')
@section('title', 'Lupa Password')
@section('content')
<div class="auth-form-container">
    <h2 class="auth-title mb-3">Lupa Password?</h2>
    <p class="auth-subtitle mb-4">
        Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-auth-primary">
                Kirim Link Reset Password
            </button>
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="auth-link">Kembali ke Login</a>
        </div>
    </form>
</div>
@endsection
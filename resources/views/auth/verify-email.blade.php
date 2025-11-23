@extends('layouts.auth')
@section('title', 'Verifikasi Email')
@section('content')
<div class="auth-form-container text-center">
    <h2 class="auth-title mb-3">Verifikasi Alamat Email Anda</h2>
    
    <p class="auth-subtitle mb-4">
        Terima kasih telah mendaftar! Sebelum melanjutkan, bisakah Anda memverifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan? Jika Anda tidak menerima email, silahkan buka folder spam di email atau daftar menggunakan akun google.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4">
            Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
        </div>
    @endif

    <div class="mt-4 d-flex flex-column gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-auth-primary w-100">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-muted">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
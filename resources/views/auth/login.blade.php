{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-form-container">
    <h2 class="auth-title">Welcome Back</h2>
    <p class="auth-subtitle">Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>

    <div class="d-grid">
        <a href="{{ route('google.redirect') }}" class="btn btn-auth-secondary">
            <img src="{{ asset('user-assets/images/google.png') }}" alt="Google logo" class="me-2">
            Login with Google
        </a>
    </div>
    <div class="auth-separator"><span>or</span></div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Menampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="mt-4">
        @csrf
        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="youremail@example.com" value="{{ old('email') }}" required autofocus>
        </div>
        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
        </div>
        {{-- Remember Me & Forgot Password --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-auth-primary">Login</button>
        </div>
    </form>
</div>
@endsection
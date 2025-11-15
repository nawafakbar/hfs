{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-form-container">
    <h2 class="auth-title">Create Account</h2>
    <p class="auth-subtitle">Already have an account? <a href="{{ route('login') }}">Sign In</a></p>

    <div class="d-grid">
        <a href="{{ route('google.redirect') }}" class="btn btn-auth-secondary">
            <img src="{{ asset('user-assets/Images/google.png') }}" alt="Google logo" class="me-2">
            Sign Up with Google
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

    <form action="{{ route('register') }}" method="POST" class="mt-4">
        @csrf
        {{-- Name --}}
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" value="{{ old('name') }}" required autofocus>
        </div>
        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="youremail@example.com" value="{{ old('email') }}" required>
        </div>
        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
        </div>
        {{-- Confirm Password --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
        </div>
        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-auth-primary">Create Account</button>
        </div>
    </form>
</div>
@endsection
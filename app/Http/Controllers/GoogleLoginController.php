<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    // Method untuk redirect ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Method untuk menangani callback dari Google
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

        if ($user) {
            // Jika user sudah ada, langsung login
            Auth::login($user);
            return redirect()->intended('/');
        } else {
            // Jika user belum ada, buat user baru
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(uniqid()), // Buat password acak
                'email_verified_at' => now(), // Anggap email sudah terverifikasi
            ]);

            Auth::login($newUser);
            return redirect()->intended('/');
        }
    }
}
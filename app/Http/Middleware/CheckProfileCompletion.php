<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (is_null($user->address) || is_null($user->phone_number)) {
            // Jika profil belum lengkap, alihkan ke halaman edit profil
            return redirect()->route('profile.edit')
                             ->with('warning', 'Harap lengkapi alamat dan nomor telepon Anda sebelum melanjutkan ke checkout.');
        }

        return $next($request);
    }
}
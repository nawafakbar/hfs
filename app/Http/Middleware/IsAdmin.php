<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/IsAdmin.php
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role == 'admin') {
            return $next($request);
        }
        // Jika bukan admin, tendang ke halaman lain atau tampilkan error 403
        abort(403, 'ANDA TIDAK PUNYA AKSES KE HALAMAN INI.');
    }
}

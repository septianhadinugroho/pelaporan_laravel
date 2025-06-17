<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class OnlyGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Arahkan pengguna yang sudah login ke halaman sesuai perannya
            if (Auth::user()->role_id == 1) {
                return redirect('dashboard'); // Admin diarahkan ke dashboard
            } elseif (Auth::user()->role_id == 2) {
                return redirect('index'); // User diarahkan ke halaman user
            }
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class OnlyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id != 2) {
                // Jika admin mencoba mengakses halaman user, redirect ke dashboard
                return redirect('dashboard');
            }
        }
        else {
            return redirect('login'); // Jika belum login, arahkan ke login
        }
        return $next($request);
    }
}

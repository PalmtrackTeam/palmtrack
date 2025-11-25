<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KaryawanMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isKaryawan()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Akses ditolak. Hanya untuk Karyawan.');
    }
}
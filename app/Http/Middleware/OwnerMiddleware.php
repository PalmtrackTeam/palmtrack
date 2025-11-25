<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isOwner()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Akses ditolak. Hanya untuk Owner.');
    }
}
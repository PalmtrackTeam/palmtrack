<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DBDynamicConnection
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            switch ($role) {

                case 'owner':
                    Config::set('database.default', 'mysql_owner');
                    break;

                case 'admin':
                    Config::set('database.default', 'mysql_admin');
                    break;

                default:
                    // selain owner & mandor → pakai koneksi default
                    Config::set('database.default', 'mysql');
                    break;
            }

            // reset koneksi supaya perubahan berlaku
            DB::reconnect();
        }

        return $next($request);
    }
}

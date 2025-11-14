<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard sesuai role user
     */
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'super_admin':
                return view('dashboard.pimpinan', compact('user'));
            case 'mandor':
                return view('dashboard.mandor', compact('user'));
            default:
                return view('dashboard.karyawan', compact('user'));
        }
    }
}

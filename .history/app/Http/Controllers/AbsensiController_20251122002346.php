<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ROLE: Karyawan → melihat semua karyawan, tapi hanya bisa absen dirinya
        if ($user->role === 'karyawan') {
            $karyawan = User::where('role', 'karyawan')->get();
        }
        // ROLE: Mandor → semua mandor + karyawan
        elseif ($user->role === 'mandor') {
            $karyawan = User::whereIn('role', ['karyawan', 'mandor'])->get();
        }
        // ROLE: Owner → semua kecuali dirinya sendiri
        else {
            $karyawan = User::where('id', '!=', $user->id)->get();
        }

        return view('absensi.index', compact('karyawan', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

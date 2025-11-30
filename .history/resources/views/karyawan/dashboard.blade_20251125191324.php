@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="max-w-6xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Karyawan</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Panen Hari Ini</h3>
            <p class="text-3xl font-bold text-green-600">{{ $panen_hari_ini ?? 0 }} kg</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Status Absen</h3>
            <p class="text-3xl font-bold text-blue-600">
                {{ $status_absen_hari_ini ? '✅' : '❌' }}
            </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2">Total Bulan Ini</h3>
            <p class="text-3xl font-bold text-orange-600">{{ $total_panen_bulan_ini ?? 0 }} kg</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('karyawan.input-panen') }}" 
           class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow text-center transition">
            <h3 class="text-xl font-semibold mb-2">📥 Input Panen</h3>
            <p>Catat hasil panen hari ini</p>
        </a>
        
        <a href="{{ route('karyawan.absensi') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow text-center transition">
            <h3 class="text-xl font-semibold mb-2">⏰ Input Absensi</h3>
            <p>Catat kehadiran hari ini</p>
        </a>
        
        <a href="{{ route('karyawan.riwayat-panen') }}" 
           class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg shadow text-center transition">
            <h3 class="text-xl font-semibold mb-2">📊 Riwayat Panen</h3>
            <p>Lihat history panen</p>
        </a>
        
        <div class="bg-gray-100 p-6 rounded-lg shadow text-center">
            <h3 class="text-xl font-semibold mb-2">ℹ️ Profile</h3>
            <p>{{ auth()->user()->nama_lengkap }}</p>
            <p class="text-sm text-gray-600">{{ auth()->user()->username }}</p>
        </div>
    </div>
</div>
@endsection
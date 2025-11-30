@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="max-w-6xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Karyawan</h1>

    {{-- Grid untuk stats & actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Status Absen Card -->
        <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
            <h3 class="text-lg font-semibold mb-2">Status Absen Hari Ini</h3>
            <p class="text-4xl font-bold text-blue-600">
                {{ $status_absen_hari_ini ? '✅ Hadir' : '❌ Tidak Hadir' }}
            </p>
        </div>

        <!-- Quick Action: Input Absensi -->
        <a href="{{ route('karyawan.absensi') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow flex flex-col items-center justify-center transition">
            <h3 class="text-xl font-semibold mb-2">⏰ Input Absensi</h3>
            <p class="text-center">Catat kehadiran hari ini</p>
        </a>

        <!-- Profile Card -->
        <div class="bg-gray-100 p-6 rounded-lg shadow flex flex-col items-center justify-center">
            <h3 class="text-xl font-semibold mb-2">ℹ️ Profil</h3>
            <p class="text-lg font-medium">{{ auth()->user()->nama_lengkap }}</p>
            <p class="text-sm text-gray-600">{{ auth()->user()->username }}</p>
        </div>

    </div>
</div>
@endsection

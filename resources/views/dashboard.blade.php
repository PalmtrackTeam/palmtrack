@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-gray-100 to-gray-300 py-10">
    <div class="max-w-5xl mx-auto px-6">
        <div class="bg-white shadow-lg rounded-xl w-full p-8 border-t-4 border-black">
            {{-- Header Nama --}}
            <div class="flex items-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-black mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
                </svg>
                <h2 class="text-xl font-semibold text-black uppercase">
                    {{ Auth::user()->name }}
                </h2>
            </div>

            {{-- Garis Pembatas Hitam --}}
            <hr class="border-t-2 border-black mb-6">

            {{-- Informasi Pengguna --}}
            <div class="space-y-4 text-gray-800">
                <div class="flex justify-between items-center border-b border-gray-300 pb-3">
                    <span class="font-medium text-black">Identitas</span>
                    <span class="text-gray-700">{{ Auth::user()->email }}</span>
                </div>

                <div class="flex justify-between items-center border-b border-gray-300 pb-3">
                    <span class="font-medium text-black">Jabatan</span>
                    @if (Auth::user()->role === 'super_admin')
                        <span class="text-gray-700">Pimpinan</span>
                    @elseif (Auth::user()->role === 'admin')
                        <span class="text-gray-700">Mandor</span>
                    @else
                        <span class="text-gray-700">Karyawan</span>
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <span class="font-medium text-black">Status Akses</span>
                    <span class="text-gray-700 capitalize">{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

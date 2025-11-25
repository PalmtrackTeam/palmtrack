@extends('layouts.app')

@section('title', 'Laporan Masalah - Owner')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Masalah</h1>
        <p class="text-gray-600">Laporan masalah yang diteruskan oleh mandor</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-flag text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $laporan_masalah->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Perlu Tindakan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $laporan_masalah->where('status_masalah', '!=', 'selesai')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $laporan_masalah->where('status_masalah', 'selesai')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Masalah List -->
    <div class="bg-white rounded-2xl shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Laporan Masalah</h3>
        </div>
        <div class="p-6">
            @if($laporan_masalah->count() > 0)
            <div class="space-y-4">
                @foreach($laporan_masalah as $laporan)
                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($laporan->jenis_masalah == 'Serangan Hama') bg-red-100 text-red-800
                                @elseif($laporan->jenis_masalah == 'Kerusakan Alat') bg-yellow-100 text-yellow-800
                                @elseif($laporan->jenis_masalah == 'Cuaca Buruk') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $laporan->jenis_masalah }}
                            </span>
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($laporan->tingkat_keparahan == 'berat') bg-red-100 text-red-800
                                @elseif($laporan->tingkat_keparahan == 'sedang') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $laporan->tingkat_keparahan }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-500">{{ $laporan->tanggal->format('d M Y') }}</span>
                    </div>
                    
                    <h4 class="font-semibold text-gray-800 mb-2">{{ $laporan->deskripsi }}</h4>
                    
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 text-xs font-medium">
                                    {{ substr($laporan->pelapor->nama_lengkap ?? 'Unknown', 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-2">
                                <div class="text-sm font-medium text-gray-900">{{ $laporan->pelapor->nama_lengkap ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">Dilaporkan oleh</div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            @if($laporan->status_masalah != 'selesai')
                            <button class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-700 transition">
                                Tandai Selesai
                            </button>
                            @endif
                            <button class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-700 transition">
                                Detail
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-check-circle text-green-400 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada laporan masalah</h3>
                <p class="text-gray-500">Semua laporan sudah ditangani dengan baik</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
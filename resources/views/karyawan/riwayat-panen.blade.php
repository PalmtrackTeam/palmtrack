@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Riwayat Panen Harian</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($riwayatPanen->count() > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blok Ladang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Buah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah (kg)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Upah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($riwayatPanen as $panen)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $panen->tanggal->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $panen->blokLadang->nama_blok ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $panen->jenis_buah_text }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($panen->jumlah_kg, 2) }} kg
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            Rp {{ number_format($panen->total_upah, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($panen->status_panen == 'draft')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                                    {{ $panen->status_panen_text }}
                                </span>
                            @elseif($panen->status_panen == 'diverifikasi')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                    {{ $panen->status_panen_text }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                    {{ $panen->status_panen_text }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <p>Belum ada data panen. 
                <a href="{{ route('karyawan.input-panen') }}" class="font-medium underline text-blue-600">
                    Input panen pertama Anda
                </a>
            </p>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('karyawan.input-panen') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
            ➕ Input Panen Baru
        </a>
        <a href="{{ route('karyawan.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-200 ml-2">
            📊 Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
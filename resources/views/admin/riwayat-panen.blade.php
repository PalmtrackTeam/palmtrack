@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Riwayat Panen Harian</h1>

    {{-- Form Filter --}}
    <form method="GET" action="{{ route('admin.riwayat-panen') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Blok Ladang</label>
            <select name="id_blok" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">Semua Blok</option>
                @foreach($blokLadang as $blok)
                    <option value="{{ $blok->id_blok }}" {{ request('id_blok') == $blok->id_blok ? 'selected' : '' }}>
                        {{ $blok->nama_blok }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" 
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        </div>

        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                🔍 Filter
            </button>
        </div>
    </form>

    {{-- Tabel Riwayat --}}
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
                            <span class="px-2 py-1 text-xs rounded-full {{ $panen->status_panen_class }}">
                                {{ $panen->status_panen_text }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <p>Belum ada data panen. 
                <a href="{{ route('admin.input-panen') }}" class="font-medium underline text-blue-600">
                    Input panen pertama Anda
                </a>
            </p>
        </div>
    @endif

    <div class="mt-6 flex gap-2">
        <a href="{{ route('admin.input-panen') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
            ➕ Input Panen Baru
        </a>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-200">
            📊 Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection

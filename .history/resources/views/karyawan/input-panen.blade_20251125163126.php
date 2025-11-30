@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Input Panen Harian</h1>

    <form action="{{ route('karyawan.store-panen') }}" method="POST" class="max-w-2xl">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Blok Ladang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Blok Ladang</label>
                <select name="id_blok" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Pilih Blok Ladang</option>
                    @foreach($blokLadang as $blok)
                        <option value="{{ $blok->id_blok }}" {{ old('id_blok') == $blok->id_blok ? 'selected' : '' }}>
                            {{ $blok->nama_blok }} ({{ $blok->luas }} ha)
                        </option>
                    @endforeach
                </select>
                @error('id_blok')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Panen -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Panen</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                @error('tanggal')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Jumlah Panen -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Panen (kg)</label>
                <input type="number" name="jumlah_kg" step="0.01" min="0" 
                       value="{{ old('jumlah_kg') }}" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="0.00" required>
                @error('jumlah_kg')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Buah -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Buah</label>
                <select name="jenis_buah" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Pilih Jenis Buah</option>
                    <option value="buah_segar" {{ old('jenis_buah') == 'buah_segar' ? 'selected' : '' }}>Buah Segar</option>
                    <option value="buah_gugur" {{ old('jenis_buah') == 'buah_gugur' ? 'selected' : '' }}>Buah Gugur</option>
                </select>
                @error('jenis_buah')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Keterangan -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
            <textarea name="keterangan" rows="3" 
                      class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                      placeholder="Catatan tambahan tentang panen...">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition duration-200">
                💾 Simpan Data Panen
            </button>
            <a href="{{ route('karyawan.riwayat-panen') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition duration-200">
                📋 Lihat Riwayat
            </a>
        </div>
    </form>
</div>
@endsection
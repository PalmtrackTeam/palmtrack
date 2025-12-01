@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Input Panen Harian</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.store-panen') }}" method="POST" class="max-w-2xl">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Blok Ladang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Blok Ladang</label>
                <select name="id_blok" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
                    <option value="">Pilih Blok Ladang</option>
                    @foreach($blokLadang as $blok)
                        <option value="{{ $blok->id_blok }}">
                            {{ $blok->nama_blok }} ({{ $blok->kategori }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Panen -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Panen</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Jumlah Panen -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Panen (kg)</label>
                <input type="number" name="jumlah_kg" step="0.01" min="0"
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
            </div>

            <!-- Jenis Buah -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Buah</label>
                <select name="jenis_buah" id="jenis_buah" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required>
                    <option value="">Pilih Jenis Buah</option>
                    <option value="buah_segar">Buah Segar</option>
                    <option value="buah_gugur">Buah Gugur</option>
                </select>
            </div>
        </div>


        <!-- Harga untuk buah gugur -->
        <div class="mb-4" id="harga-gugur-wrapper" style="display: none;">
            <label class="block text-sm font-medium text-gray-700 mb-2">Harga (per kg)</label>
            <input type="number" name="harga_gugur" step="0.01" min="0"
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
        </div>

        <!-- Keterangan (opsional) -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
            <textarea name="keterangan" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                💾 Simpan Data Panen
            </button>

            <a href="{{ route('admin.riwayat-panen') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">
                📋 Lihat Riwayat
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisBuahSelect = document.getElementById('jenis_buah');
    const hargaGugurWrapper = document.getElementById('harga-gugur-wrapper');

    jenisBuahSelect.addEventListener('change', function () {
        if (this.value === 'buah_gugur') {
            hargaGugurWrapper.style.display = 'block';
        } else {
            hargaGugurWrapper.style.display = 'none';
        }
    });
});
</script>
@endsection

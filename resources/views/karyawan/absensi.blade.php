@extends('layouts.app')

@section('title', 'Input Absensi')

@section('content')
<div class="max-w-2xl mx-auto py-6 px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Input Absensi Harian</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(isset($absenHariIni) && $absenHariIni)
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                <strong>Anda sudah absen hari ini!</strong><br>
                Status: {{ $absenHariIni->status_kehadiran }}<br>
                @if($absenHariIni->jam_masuk)
                    Jam Masuk: {{ $absenHariIni->jam_masuk }}
                @endif
            </div>
        @else
            <form method="POST" action="{{ route('karyawan.store-absensi') }}">
                @csrf

                <!-- Status Kehadiran -->
                <div class="mb-6">
                    <label for="status_kehadiran" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Kehadiran *
                    </label>
                    <select id="status_kehadiran" name="status_kehadiran" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Status</option>
                        <option value="Hadir">Hadir</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Alpha">Alpha</option>
                        <option value="Libur_Agama">Libur Agama</option>
                    </select>
                </div>

                <!-- Jam Masuk (muncul hanya jika Hadir) -->
                <div id="jam-masuk-group" class="mb-6 hidden">
                    <label for="jam_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Masuk *
                    </label>
                    <input type="time" id="jam_masuk" name="jam_masuk"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Keterangan -->
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Alasan izin/sakit atau catatan lainnya..."></textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('karyawan.dashboard') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                        Kembali
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition font-semibold">
                        Simpan Absensi
                    </button>
                </div>
            </form>
        @endif
    </div>

    <!-- Quick Info -->
    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <h3 class="font-semibold text-yellow-800 mb-2">📝 Informasi Absensi:</h3>
        <ul class="text-sm text-yellow-700 list-disc list-inside space-y-1">
            <li><strong>Hadir:</strong> Wajib mengisi jam masuk</li>
            <li><strong>Izin/Sakit:</strong> Wajib mengisi keterangan</li>
            <li><strong>Alpha:</strong> Tidak hadir tanpa keterangan</li>
            <li>Absensi hanya bisa dilakukan 1x per hari</li>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status_kehadiran');
        const jamMasukGroup = document.getElementById('jam-masuk-group');
        const jamMasukInput = document.getElementById('jam_masuk');

        function toggleJamMasuk() {
            if (statusSelect.value === 'Hadir') {
                jamMasukGroup.classList.remove('hidden');
                jamMasukInput.required = true;
            } else {
                jamMasukGroup.classList.add('hidden');
                jamMasukInput.required = false;
                jamMasukInput.value = '';
            }
        }

        statusSelect.addEventListener('change', toggleJamMasuk);
        
        // Set jam masuk default ke waktu sekarang jika Hadir
        if (statusSelect.value === 'Hadir') {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            jamMasukInput.value = `${hours}:${minutes}`;
        }
    });
</script>
@endsection
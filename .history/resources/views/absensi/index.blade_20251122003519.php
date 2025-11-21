@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
<div class="max-w-6xl mx-auto mt-6 bg-white shadow-md rounded-2xl overflow-hidden border border-gray-200">

    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-600 to-indigo-600 text-white p-6 text-center">
        <h1 class="text-2xl font-semibold tracking-wide">📋 Data Absensi Karyawan</h1>
        <p class="text-sm mt-1 opacity-90">Isi absensi sesuai tanggal</p>
    </div>

    <div class="p-6">

        @if (session('status'))
            <div class="p-3 bg-green-100 text-green-700 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <!-- Form Absensi -->
        <form method="POST" action="{{ route('absensi.store') }}">
            @csrf

            <!-- Pilih Tanggal -->
            <div class="flex flex-col mb-5">
                <label class="font-semibold mb-1">Tanggal Absensi:</label>
                <input name="tanggal" type="date" required 
                    class="border rounded px-3 py-2 w-48 shadow-sm">
            </div>

            <!-- Tabel Absensi -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left border">No</th>
                            <th class="px-4 py-3 text-left border">Nama & Role</th>
                            <th class="px-4 py-3 text-center border">Hadir</th>
                            <th class="px-4 py-3 text-center border">Izin</th>
                            <th class="px-4 py-3 text-center border">Sakit</th>

                            {{-- Alpha hanya untuk mandor & owner --}}
                            @if(auth()->user()->role !== 'karyawan')
                            <th class="px-4 py-3 text-center border">Alpha</th>
                            @endif

                            <th class="px-4 py-3 border">Keterangan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($karyawan as $index => $k)

                            @php
                                $user = auth()->user();

                                // Karyawan hanya bisa absen dirinya sendiri
                                $disabled = ($user->role === 'karyawan' && $user->id != $k->id)
                                            ? 'disabled'
                                            : '';
                            @endphp

                            <tr class="hover:bg-gray-50 transition text-gray-800">

                                <!-- No -->
                                <td class="px-4 py-3 border text-center font-medium">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Nama -->
                                <td class="px-4 py-3 border">
                                    <div class="font-semibold">{{ $k->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst($k->role) }}</div>
                                </td>

                                <!-- Hadir -->
                                <td class="px-4 py-3 border text-center">
                                    <input {{ $disabled }} type="radio" 
                                        name="absensi[{{ $k->id }}]" value="Hadir"
                                        class="accent-green-500 w-5 h-5">
                                </td>

                                <!-- Izin -->
                                <td class="px-4 py-3 border text-center">
                                    <input {{ $disabled }} type="radio" 
                                        name="absensi[{{ $k->id }}]" value="Izin"
                                        class="accent-yellow-500 w-5 h-5 ketTrigger">
                                </td>

                                <!-- Sakit -->
                                <td class="px-4 py-3 border text-center">
                                    <input {{ $disabled }} type="radio" 
                                        name="absensi[{{ $k->id }}]" value="Sakit"
                                        class="accent-blue-500 w-5 h-5 ketTrigger">
                                </td>

                                <!-- Alpha (hanya mandor & owner) -->
                                @if($user->role !== 'karyawan')
                                <td class="px-4 py-3 border text-center">
                                    <input {{ $disabled }} type="radio" 
                                        name="absensi[{{ $k->id }}]" value="Alpha"
                                        class="accent-red-500 w-5 h-5 ketTrigger">
                                </td>
                                @endif

                                <!-- Keterangan -->
                                <td class="px-4 py-3 border">
                                    <input {{ $disabled }} type="text" 
                                        name="keterangan[{{ $k->id }}]"
                                        class="ketInput hidden w-full px-2 py-1 border rounded"
                                        placeholder="Isi keterangan...">
                                </td>

                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tombol Simpan -->
            <div class="text-center mt-6">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-sm transition">
                    💾 Simpan Absensi
                </button>
            </div>

        </form>
    </div>
</div>

<script>
// tampilkan input keterangan otomatis
document.querySelectorAll('.ketTrigger').forEach(el => {
    el.onclick = function() {
        const row = this.closest('tr');
        const ket = row.querySelector('.ketInput');
        ket.classList.remove('hidden');
    };
});

document.querySelectorAll('input[value="Hadir"]').forEach(el => {
    el.onclick = function() {
        const row = this.closest('tr');
        const ket = row.querySelector('.ketInput');
        ket.classList.add('hidden');
        ket.value = "";
    };
});
</script>

@endsection

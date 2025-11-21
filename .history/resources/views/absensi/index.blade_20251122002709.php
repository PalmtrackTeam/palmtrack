@extends('layouts.app')

@section('title', 'Absensi')

@section('content')
<div class="max-w-6xl mx-auto mt-6 bg-white shadow-md rounded-2xl overflow-hidden border border-gray-200">
    
    <div class="bg-gradient-to-r from-gray-600 to-indigo-600 text-white p-6 text-center">
        <h1 class="text-2xl font-semibold tracking-wide">📋 Absensi Karyawan</h1>
    </div>

    <div class="p-6">

        @if (session('status'))
            <div class="p-3 bg-green-100 text-green-700 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('absensi.store') }}">
            @csrf

            {{-- pilih tanggal --}}
            <div class="mb-5">
                <label class="font-semibold">Tanggal Absensi:</label>
                <input type="date" name="tanggal" required class="border px-3 py-2 rounded ml-2">
            </div>

            <div class="overflow-x-auto rounded border">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 border text-center">No</th>
                            <th class="px-4 py-3 border text-left">Nama</th>
                            <th class="px-4 py-3 border text-center">Hadir</th>
                            <th class="px-4 py-3 border text-center">Izin</th>
                            <th class="px-4 py-3 border text-center">Sakit</th>
                            <th class="px-4 py-3 border text-center">Alpha</th>
                            <th class="px-4 py-3 border text-left">Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($karyawan as $i => $k)

                            @php
                                // Karyawan login → hanya boleh centang absensinya sendiri
                                $disabled = (auth()->user()->role === 'karyawan' && auth()->user()->id !== $k->id) ? 'disabled' : '';
                            @endphp

                            <tr>
                                <td class="px-4 py-3 border text-center">{{ $i + 1 }}</td>

                                <td class="px-4 py-3 border font-semibold">
                                    {{ $k->nama }}
                                    <div class="text-xs text-gray-500">{{ ucfirst($k->role) }}</div>
                                </td>

                                {{-- Status Kehadiran --}}
                                <td class="px-4 py-3 border text-center">
                                    <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Hadir">
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Izin">
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Sakit">
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Alpha">
                                </td>

                                {{-- Keterangan --}}
                                <td class="px-4 py-3 border">
                                    <input {{ $disabled }} type="text" name="keterangan[{{ $k->id }}]"
                                        class="w-full border px-2 py-1 rounded">
                                </td>
                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="mt-6 text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    💾 Simpan Absensi
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

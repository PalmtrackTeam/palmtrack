@extends('layouts.app')

@section('title', 'Riwayat Laporan Masalah')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">

    <div class="flex items-center gap-2 mb-6">
        <!-- Icon formal -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-800" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 6h6m-7 4h8m-7 4h6m5 2V7a2 2 0 00-2-2h-3.343a1 1 0 01-.894-.553l-.447-.894A1 1 0 0011.343 3H8.657a1 1 0 00-.894.553l-.447.894A1 1 0 016.343 5H3a2 2 0 00-2 2v11a2 2 0 002 2h16a2 2 0 002-2z" />
        </svg>
        
        <h1 class="text-3xl font-bold text-gray-800">
            Riwayat Laporan Masalah
        </h1>
    </div>

    @if($laporan_masalah->count() > 0)
    <div class="overflow-x-auto bg-white shadow-md rounded-xl border border-gray-200">
        <table class="min-w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-100 text-gray-700 font-semibold">
                    <th class="px-5 py-3">Tanggal</th>
                    <th class="px-5 py-3">Jenis Masalah</th>
                    <th class="px-5 py-3">Tingkat Keparahan</th>
                    <th class="px-5 py-3">Deskripsi</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @foreach($laporan_masalah as $laporan)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">{{ $laporan->tanggal }}</td>
                    <td class="px-5 py-3">{{ $laporan->jenis_masalah }}</td>
                    <td class="px-5 py-3">{{ $laporan->tingkat_keparahan }}</td>
                    <td class="px-5 py-3">{{ $laporan->deskripsi }}</td>
                    <td class="px-5 py-3">
                        <span class="
                            px-3 py-1 rounded-full text-xs font-semibold
                            @if($laporan->status_masalah === 'Selesai')
                                bg-green-100 text-green-700
                            @elseif($laporan->status_masalah === 'Diproses')
                                bg-yellow-100 text-yellow-700
                            @else
                                bg-red-100 text-red-700
                            @endif
                        ">
                            {{ $laporan->status_masalah }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
    @else
    <div class="text-center text-gray-500 bg-white shadow-md border border-gray-200 py-8 rounded-xl mt-6">
        Belum ada laporan masalah.
    </div>
    @endif

</div>
@endsection

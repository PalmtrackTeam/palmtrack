@extends('layouts.app')

@section('title', 'Riwayat Laporan Masalah')

@section('content')
<div class="max-w-6xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold mb-6">Riwayat Laporan Masalah</h1>

    @if($laporan_masalah->count() > 0)
        <table class="min-w-full border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Jenis Masalah</th>
                    <th class="px-4 py-2">Tingkat Keparahan</th>
                    <th class="px-4 py-2">Deskripsi</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan_masalah as $laporan)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $laporan->tanggal }}</td>
                    <td class="px-4 py-2">{{ $laporan->jenis_masalah }}</td>
                    <td class="px-4 py-2">{{ $laporan->tingkat_keparahan }}</td>
                    <td class="px-4 py-2">{{ $laporan->deskripsi }}</td>
                    <td class="px-4 py-2">{{ $laporan->status_masalah }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-500">Belum ada laporan masalah.</p>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')

<div class="max-w-7xl mx-auto py-6 px-4">

    <!-- TITLE -->
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Karyawan</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KIRI (2 Kolom) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- CARD: INPUT ABSENSI -->
            <a href="{{ route('karyawan.absensi') }}"
            class="block bg-gradient-to-br from-blue-50 to-white rounded-2xl shadow-lg p-6 relative overflow-hidden group hover:shadow-xl transition-all duration-300 border border-blue-100">

                <!-- Bubble dekorasi -->
                <div class="absolute top-0 right-0 w-20 h-20 bg-blue-200 rounded-full -mr-6 -mt-6 opacity-20 
                            group-hover:opacity-30 transition-opacity"></div>

                <div class="flex items-center">
                    
                    <!-- Icon -->
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg 
                                group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>

                    <!-- Text -->
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600">Input Absensi</p>
                        <p class="text-xl font-bold text-gray-800 mt-1">Catat Kehadiran</p>

                        <div class="flex items-center mt-2">
                            <p class="text-xs text-gray-500">Catat Kehadiran hari ini</p>
                        </div>
                    </div>
                </div>

            </a>


            <!-- CARD: LAPOR MASALAH -->
            <a href="{{ route('karyawan.input-laporan-masalah') }}"
            class="block bg-gradient-to-br from-orange-50 to-white rounded-2xl shadow-lg p-6 relative overflow-hidden 
                    group hover:shadow-xl transition-all duration-300 border border-orange-100">

                <!-- Bubble dekorasi -->
                <div class="absolute top-0 right-0 w-20 h-20 bg-orange-200 rounded-full -mr-6 -mt-6 opacity-20 
                            group-hover:opacity-30 transition-opacity"></div>

                <div class="flex items-center">

                    <!-- Icon -->
                    <div class="p-3 bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl shadow-lg 
                                group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>

                    <!-- Text -->
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600">Lapor Masalah</p>
                        <p class="text-xl font-bold text-gray-800 mt-1">Laporkan Masalah</p>

                        <div class="flex items-center mt-2">
                            <p class="text-xs text-gray-500">Lapor Masalah di Lapangan</p>
                        </div>
                    </div>

                </div>

            </a>


            <!-- CARD: RIWAYAT LAPORAN -->
            <a href="{{ route('karyawan.riwayat-laporan-masalah') }}"
            class="block bg-gradient-to-br from-purple-50 to-white rounded-2xl shadow-lg p-6 
                    relative overflow-hidden group hover:shadow-xl transition-all duration-300
                    border border-purple-100">

                <!-- Bubble dekorasi -->
                <div class="absolute top-0 right-0 w-20 h-20 bg-purple-200 rounded-full -mr-6 -mt-6 opacity-20 
                            group-hover:opacity-30 transition-opacity"></div>

                <div class="flex items-center">

                    <!-- Icon -->
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg 
                                group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-history text-white text-xl"></i>
                    </div>

                    <!-- Text -->
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-600">Riwayat Laporan</p>
                        <p class="text-xl font-bold text-gray-800 mt-1">Lihat Laporan</p>

                        <div class="flex items-center mt-2">
                            <p class="text-xs text-gray-500">Lihat Laporan yang telah dikirim</p>
                        </div>
                    </div>
                </div>
            </a>

        </div>

        <!-- KANAN (Status + Profil) -->
        <div class="space-y-6">

            <!-- STATUS ABSENSI -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-300">
            <div class="flex items-center gap-3 mb-4">
                
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center shadow-sm">
                    <img src="{{ asset('images/absen.png') }}" alt="Icon Calendar" class="w-8 h-8">
                </div>

                <h3 class="text-lg font-semibold text-gray-900">Status Absensi</h3>
            </div>

            <p class="text-xl font-bold {{ $status_absen_hari_ini ? 'text-green-600' : 'text-red-600' }}">
                {{ $status_absen_hari_ini ? 'Hadir' : 'Belum Absen' }}
            </p>

            @if($status_absen_hari_ini)
                <p class="text-sm text-gray-500 mt-1">
                    Masuk: {{ $status_absen_hari_ini->jam_masuk }}
                </p>
            @endif
        </div>

            <!-- PROFIL -->
           <div class="bg-white p-6 rounded-xl shadow-md border border-gray-300 text-center">
                <div class="mx-auto flex items-center justify-center shadow-sm overflow-hidden mb-3">
                    <img src="{{ asset('images/user.png') }}" alt="Icon User" class="w-20 h-20 object-cover rounded-full">
                </div>

                <h3 class="text-lg font-semibold text-gray-900">Profil</h3>

                <!-- Nama & Role -->
                <p class="text-gray-700 font-medium mt-2">{{ auth()->user()->nama_lengkap }}</p>
                <p class="text-sm text-gray-500">Role: {{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>

    </div>

   <!-- Aktivitas Terbaru -->
<div class="bg-white p-6 rounded-xl shadow-md mt-8 border border-gray-300">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">Aktivitas Terbaru</h2>
        <a href="{{ route('karyawan.riwayat-laporan-masalah') }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            Lihat Semua →
        </a>
    </div>

    @if($laporan_terbaru->count() > 0)

        @php
            $colors = [
                'bg-red-100 text-red-600',
                'bg-blue-100 text-blue-600',
                'bg-green-100 text-green-600',
                'bg-yellow-100 text-yellow-600',
                'bg-purple-100 text-purple-600',
                'bg-pink-100 text-pink-600',
                'bg-orange-100 text-orange-600',
            ];
        @endphp

        <div class="space-y-4">
            @foreach($laporan_terbaru as $laporan)

                @php 
                    // Pilih warna berdasarkan ID, supaya konsisten
                    $color = $colors[$laporan->id_masalah % count($colors)];
                @endphp

                <div class="border border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition flex gap-4">

                    <!-- ICON BULAT OTOMATIS WARNA -->
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shadow {{ $color }}">
                        <i class="fas fa-exclamation-circle text-lg"></i>
                    </div>

                    <!-- INFO LAPORAN -->
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">
                            {{ $laporan->jenis_masalah }}
                        </h4>

                        <p class="text-sm text-gray-600 mt-1">
                            {{ Str::limit($laporan->deskripsi, 100) }}
                        </p>

                        <div class="flex items-center text-xs text-gray-500 mt-2">
                            <i class="far fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    
                </div>

            @endforeach
        </div>

    @else

        <div class="text-center py-8">
            <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 mb-4">Belum ada laporan masalah</p>
            <a href="{{ route('karyawan.input-laporan-masalah') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-white hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Buat Laporan Pertama
            </a>
        </div>

    @endif

</div>


<!-- MODAL DETAIL -->
<div id="laporanDetailModal"
     class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-xl border border-gray-300">

        <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-300 flex justify-between items-center">
            <h3 class="text-lg font-semibold">Detail Laporan</h3>
            <button onclick="closeLaporanDetailModal()" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-6" id="laporanDetailContent"></div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    function showLaporanDetail(lid) {
        fetch(`/karyawan/laporan-masalah/${lid}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const d = data.data;

                    const html = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500">Tanggal</p>
                                    <p class="text-sm font-medium">${new Date(d.tanggal).toLocaleDateString('id-ID')}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Jenis Masalah</p>
                                    <p class="text-sm font-medium">${d.jenis_masalah}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Deskripsi</p>
                                <p class="text-sm mt-1">${d.deskripsi}</p>
                            </div>
                        </div>
                    `;

                    document.getElementById('laporanDetailContent').innerHTML = html;
                    document.getElementById('laporanDetailModal').classList.remove('hidden');
                }
            });
    }

    function closeLaporanDetailModal() {
        document.getElementById('laporanDetailModal').classList.add('hidden');
    }
</script>
@endsection

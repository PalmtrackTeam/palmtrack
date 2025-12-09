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
               class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition border border-gray-300">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gray-100 rounded-xl">
                        <i class="fas fa-clock text-gray-700 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Input Absensi</h3>
                        <p class="text-sm text-gray-500">Catat kehadiran hari ini</p>
                    </div>
                </div>
            </a>

            <!-- CARD: LAPOR MASALAH -->
            <a href="{{ route('karyawan.input-laporan-masalah') }}"
               class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition border border-gray-300">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gray-100 rounded-xl">
                        <i class="fas fa-exclamation-triangle text-gray-700 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Lapor Masalah</h3>
                        <p class="text-sm text-gray-500">Laporkan masalah di lapangan</p>
                    </div>
                </div>
            </a>

            <!-- CARD: RIWAYAT LAPORAN -->
            <a href="{{ route('karyawan.riwayat-laporan-masalah') }}"
               class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition border border-gray-300">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gray-100 rounded-xl">
                        <i class="fas fa-history text-gray-700 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Laporan</h3>
                        <p class="text-sm text-gray-500">Lihat laporan yang telah dikirim</p>
                    </div>
                </div>
            </a>

        </div>

        <!-- KANAN (Status + Profil) -->
        <div class="space-y-6">

            <!-- STATUS ABSENSI -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-300">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-3 bg-gray-100 rounded-xl">
                        <i class="fas fa-calendar-check text-gray-700 text-2xl"></i>
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
                <div class="p-3 bg-gray-100 w-fit mx-auto rounded-xl mb-3">
                    <i class="fas fa-user text-gray-700 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Profil</h3>

                <p class="text-gray-700 font-medium mt-2">{{ auth()->user()->nama_lengkap }}</p>

                <p class="text-sm text-gray-500">
                    Role: {{ ucfirst(auth()->user()->role) }}
                </p>
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
            <div class="space-y-4">

                @foreach($laporan_terbaru as $laporan)
                    <div class="border border-gray-300 rounded-xl p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between">

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

                            @if($laporan->tindakan)
                                <button onclick="showLaporanDetail({{ $laporan->id_masalah }})"
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                    Lihat
                                </button>
                            @endif

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

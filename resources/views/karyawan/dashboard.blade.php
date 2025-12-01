@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')

<div class="max-w-6xl mx-auto py-6 px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Karyawan</h1>


{{-- Grid untuk stats & actions --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Status Absen Card -->
    <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
        <div class="bg-blue-100 p-3 rounded-full mb-4">
            <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold mb-2 text-center">Status Absen</h3>
        <p class="text-xl font-bold {{ $status_absen_hari_ini ? 'text-green-600' : 'text-red-600' }}">
            {{ $status_absen_hari_ini ? 'Hadir' : 'Belum Absen' }}
        </p>
        @if($status_absen_hari_ini)
            <p class="text-sm text-gray-500 mt-1">
                {{ $status_absen_hari_ini->jam_masuk ? 'Masuk: ' . $status_absen_hari_ini->jam_masuk : '' }}
            </p>
        @endif
    </div>

    <!-- Profile Card -->
    <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
        <div class="bg-purple-100 p-3 rounded-full mb-4">
            <i class="fas fa-user text-purple-600 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold mb-2 text-center">Profil</h3>
        <p class="text-lg font-medium text-gray-800">{{ auth()->user()->nama_lengkap }}</p>
        <p class="text-sm text-gray-500 mt-1">Role: {{ ucfirst(auth()->user()->role) }}</p>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('karyawan.absensi') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow flex flex-col items-center justify-center transition transform hover:-translate-y-1">
            <i class="fas fa-clock text-3xl mb-3"></i>
            <h3 class="text-lg font-semibold mb-2">Input Absensi</h3>
            <p class="text-center text-sm opacity-90">Catat kehadiran hari ini</p>
        </a>
        <a href="{{ route('karyawan.input-laporan-masalah') }}" class="bg-orange-600 hover:bg-orange-700 text-white p-6 rounded-lg shadow flex flex-col items-center justify-center transition transform hover:-translate-y-1">
            <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
            <h3 class="text-lg font-semibold mb-2">Lapor Masalah</h3>
            <p class="text-center text-sm opacity-90">Laporkan masalah di lapangan</p>
        </a>
        <a href="{{ route('karyawan.riwayat-laporan-masalah') }}" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow flex flex-col items-center justify-center transition transform hover:-translate-y-1">
            <i class="fas fa-history text-3xl mb-3"></i>
            <h3 class="text-lg font-semibold mb-2">Riwayat Laporan</h3>
            <p class="text-center text-sm opacity-90">Lihat laporan yang telah dikirim</p>
        </a>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Aktivitas Terbaru</h2>
        <a href="{{ route('karyawan.riwayat-laporan-masalah') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    @if($laporan_terbaru->count() > 0)
        <div class="space-y-4">
            @foreach($laporan_terbaru as $laporan)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <h4 class="font-medium text-gray-900">{{ $laporan->jenis_masalah }}</h4>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($laporan->tingkat_keparahan == 'ringan') bg-green-100 text-green-800
                                    @elseif($laporan->tingkat_keparahan == 'sedang') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($laporan->tingkat_keparahan) }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($laporan->status_masalah == 'dilaporkan') bg-yellow-100 text-yellow-800
                                    @elseif($laporan->status_masalah == 'dalam_penanganan') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    @if($laporan->status_masalah == 'dilaporkan') Menunggu
                                    @elseif($laporan->status_masalah == 'dalam_penanganan') Ditangani
                                    @else Selesai @endif
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($laporan->deskripsi, 100) }}</p>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="far fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        @if($laporan->tindakan)
                            <button onclick="showLaporanDetail({{ $laporan->id_masalah }})" class="ml-4 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>Lihat
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 mb-4">Belum ada laporan masalah</p>
            <a href="{{ route('karyawan.input-laporan-masalah') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-white hover:bg-orange-700">
                <i class="fas fa-plus mr-2"></i>Buat Laporan Pertama
            </a>
        </div>
    @endif
</div>
```

</div>

<!-- Detail Modal -->

<div id="laporanDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Detail Laporan</h3>
            <button onclick="closeLaporanDetailModal()" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6" id="laporanDetailContent">
            <!-- Detail akan dimuat di sini -->
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    function showLaporanDetail(laporanId) {
        fetch(`/karyawan/laporan-masalah/${laporanId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const laporan = data.data;
                    const detailHTML = `
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Laporan</label>
                                    <p class="mt-1 text-sm text-gray-900">${new Date(laporan.tanggal).toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric'})}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Masalah</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">${laporan.jenis_masalah}</p>
                                </div>
                            </div>
                            <!-- Detail lainnya sama seperti sebelumnya -->
                        </div>
                    `;
                    document.getElementById('laporanDetailContent').innerHTML = detailHTML;
                    document.getElementById('laporanDetailModal').classList.remove('hidden');
                }
            }).catch(err => {
                console.error(err);
                alert('Gagal memuat detail laporan');
            });
    }
    function closeLaporanDetailModal() {
        document.getElementById('laporanDetailModal').classList.add('hidden');
    }
    document.getElementById('laporanDetailModal').addEventListener('click', function(e) {
        if (e.target === this) closeLaporanDetailModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLaporanDetailModal();
    });
</script>

@endsection

@section('styles')

<style>
    .transform { transition: transform 0.2s ease-in-out; }
    .hover\:-translate-y-1:hover { transform: translateY(-4px); }
</style>

@endsection

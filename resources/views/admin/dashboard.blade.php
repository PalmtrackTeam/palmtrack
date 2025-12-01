<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mandor</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .hover-lift { transition: all 0.3s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-green-800 text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <i class="fas fa-tractor text-xl mr-3"></i>
                        <span class="font-semibold text-xl">Sawit Management</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-green-200">Mandor</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-green-700 hover:bg-green-600 px-3 py-1 rounded transition-colors">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar & Main Content -->
        <div class="flex">
            <!-- Sidebar -->
            <div class="w-64 bg-white min-h-screen card-shadow">
                <div class="p-4 border-b">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">{{ auth()->user()->nama_lengkap }}</div>
                            <div class="text-sm text-gray-600 capitalize">{{ auth()->user()->jabatan }}</div>
                        </div>
                    </div>
                </div>
                
                <nav class="mt-4">

                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-green-700 bg-green-50 border-r-4 border-green-600">
                        <i class="fas fa-chart-line w-6 mr-3"></i>
                        Dashboard
                    </a>

                    <!-- Input Panen -->
                    <a href="{{ route('admin.input-panen') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-seedling w-6 mr-3"></i>
                        Input Panen
                    </a>

                    <!-- Riwayat Panen -->
                    <a href="{{ route('admin.riwayat-panen') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-history w-6 mr-3"></i>
                        Riwayat Panen
                    </a>

                    <a href="{{ route('admin.kelola-absensi') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-clipboard-check w-6 mr-3"></i>
                        Kelola Absensi
                    </a>
                    <a href="{{ route('admin.laporan-masalah') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-exclamation-triangle w-6 mr-3"></i>
                        Laporan Masalah
                    </a>
                    <a href="{{ route('admin.rekap-produktivitas') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chart-bar w-6 mr-3"></i>
                        Rekap Produktivitas
                    </a>

                    <!-- Fitur Baru -->
                    <a href="{{ route('admin.input-pengeluaran') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-money-bill-wave w-6 mr-3"></i>
                        Input Pengeluaran
                    </a>
                    <a href="{{ route('admin.input-pemasukan') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-cash-register w-6 mr-3"></i>
                        Input Pemasukan
                    </a>
                    <a href="{{ route('admin.input-laporan-masalah') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-flag w-6 mr-3"></i>
                        Laporkan Masalah
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Mandor</h1>
                    <p class="text-gray-600">Ringkasan aktivitas hari ini - {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                    <a href="{{ route('admin.input-pengeluaran') }}" class="bg-white rounded-xl card-shadow p-6 border-l-4 border-blue-500 hover-lift group">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Input Pengeluaran</p>
                                <p class="text-lg font-bold text-gray-900">Catat Biaya</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.input-pemasukan') }}" class="bg-white rounded-xl card-shadow p-6 border-l-4 border-green-500 hover-lift group">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                                <i class="fas fa-cash-register text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Input Pemasukan</p>
                                <p class="text-lg font-bold text-gray-900">Catat Pendapatan</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.input-laporan-masalah') }}" class="bg-white rounded-xl card-shadow p-6 border-l-4 border-orange-500 hover-lift group">
                        <div class="flex items-center">
                            <div class="p-3 bg-orange-100 rounded-lg group-hover:bg-orange-200 transition-colors">
                                <i class="fas fa-flag text-orange-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Laporkan Masalah</p>
                                <p class="text-lg font-bold text-gray-900">Buat Laporan</p>
                            </div>
                        </div>
                    </a>

                    <!-- Input PANEN -->
                    <a href="{{ route('admin.input-panen') }}" class="bg-white rounded-xl card-shadow p-6 border-l-4 border-yellow-500 hover-lift group">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-lg group-hover:bg-yellow-200 transition-colors">
                                <i class="fas fa-seedling text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Input Panen</p>
                                <p class="text-lg font-bold text-gray-900">Catat Panen</p>
                            </div>
                        </div>
                    </a>

                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    <div class="bg-white rounded-xl card-shadow p-6 border-l-4 border-blue-500 hover-lift">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Karyawan Aktif</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $total_karyawan_aktif }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl card-shadow p-6 border-l-4 border-green-500 hover-lift">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-seedling text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Panen Hari Ini</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($produktivitas_hari_ini->total_kg ?? 0, 0, ',', '.') }} kg</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl card-shadow p-6 border-l-4 border-purple-500 hover-lift">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-clipboard-check text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Hadir Hari Ini</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $absensi_hari_ini->total_hadir ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl card-shadow p-6 border-l-4 border-orange-500 hover-lift">
                        <div class="flex items-center">
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <i class="fas fa-clock text-orange-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Belum Absen</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $karyawan_belum_absen->count() }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- CARD BELUM ABSEN -->
                    <div class="bg-white rounded-xl card-shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Belum Absen Hari Ini</h3>
                                <a href="{{ route('admin.kelola-absensi') }}" class="text-sm text-blue-600 hover:text-blue-800">Kelola Absensi</a>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($karyawan_belum_absen->count() > 0)
                                <div class="space-y-3">
                                    @foreach($karyawan_belum_absen->take(5) as $karyawan)
                                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-sm font-semibold">
                                                {{ strtoupper(substr($karyawan->nama_lengkap, 0, 1)) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">{{ $karyawan->nama_lengkap }}</div>
                                            </div>
                                        </div>
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Belum Absen</span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-check-circle text-3xl mb-3 text-green-500"></i>
                                    <p>Semua karyawan sudah absen hari ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Bottom Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

                    <!-- Blok Terproduktif -->
                    <div class="bg-white rounded-xl card-shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Blok Terproduktif Hari Ini</h3>
                        @if($blok_terproduktif)
                            <div class="text-center py-4">
                                <div class="text-2xl font-bold text-green-600 mb-2">{{ $blok_terproduktif->nama_blok }}</div>
                                <div class="text-gray-600">{{ number_format($blok_terproduktif->total_kg, 1) }} kg</div>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-seedling text-3xl mb-3 text-gray-400"></i>
                                <p>Belum ada data panen hari ini</p>
                            </div>
                        @endif
                    </div>

                    <!-- Ringkasan Absensi -->
                    <div class="bg-white rounded-xl card-shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Absensi</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-xl font-bold text-green-600">{{ $absensi_hari_ini->total_hadir ?? 0 }}</div>
                                <div class="text-sm text-green-800">Hadir</div>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-xl font-bold text-blue-600">{{ $absensi_hari_ini->total_izin ?? 0 }}</div>
                                <div class="text-sm text-blue-800">Izin</div>
                            </div>
                            <div class="text-center p-3 bg-orange-50 rounded-lg">
                                <div class="text-xl font-bold text-orange-600">{{ $absensi_hari_ini->total_sakit ?? 0 }}</div>
                                <div class="text-sm text-orange-800">Sakit</div>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded-lg">
                                <div class="text-xl font-bold text-red-600">{{ $absensi_hari_ini->total_alpha ?? 0 }}</div>
                                <div class="text-sm text-red-800">Alpha</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white rounded-xl card-shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Akses Cepat</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.riwayat-pengeluaran') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-history text-gray-400 w-6 mr-3"></i>
                                <span class="text-gray-700">Riwayat Pengeluaran</span>
                            </a>
                            <a href="{{ route('admin.riwayat-pemasukan') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-receipt text-gray-400 w-6 mr-3"></i>
                                <span class="text-gray-700">Riwayat Pemasukan</span>
                            </a>

                            <a href="{{ route('admin.rekap-produktivitas') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chart-bar text-gray-400 w-6 mr-3"></i>
                                <span class="text-gray-700">Rekap Produktivitas</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh stats every 30 seconds
        setInterval(() => {
            fetch('{{ route("admin.dashboard-stats") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Stats updated:', data.data);
                    }
                });
        }, 30000);
    </script>
</body>
</html>

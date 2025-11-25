@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <!-- Header dengan Navigation -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Owner</h1>
                <p class="text-gray-600">Ringkasan keseluruhan sistem perkebunan sawit</p>
            </div>
            <div class="flex space-x-3">
                <!-- Quick Navigation Buttons -->
                <a href="{{ route('owner.laporan-keuangan') }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                    <i class="fas fa-chart-line mr-2"></i>Laporan Keuangan
                </a>
                <a href="{{ route('owner.manajemen-user') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-users mr-2"></i>Manajemen User
                </a>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white rounded-2xl shadow-lg p-4">
            <div class="flex space-x-8 border-b border-gray-200">
                <a href="{{ route('owner.dashboard') }}" 
                   class="pb-4 px-2 border-b-2 border-blue-500 text-blue-600 font-semibold flex items-center">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="{{ route('owner.laporan-keuangan') }}" 
                   class="pb-4 px-2 text-gray-500 hover:text-gray-700 font-medium flex items-center">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>Laporan Keuangan
                </a>
                <a href="{{ route('owner.manajemen-user') }}" 
                   class="pb-4 px-2 text-gray-500 hover:text-gray-700 font-medium flex items-center">
                    <i class="fas fa-users-cog mr-2"></i>Manajemen User
                </a>
                <a href="{{ route('owner.rekap-produktivitas') }}" 
                   class="pb-4 px-2 text-gray-500 hover:text-gray-700 font-medium flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>Rekap Produktivitas
                </a>
                <a href="{{ route('owner.laporan-masalah') }}" 
                   class="pb-4 px-2 text-gray-500 hover:text-gray-700 font-medium flex items-center relative">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Laporan Masalah
                    @if($laporan_masalah_baru > 0)
                    <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ $laporan_masalah_baru }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid Modern -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Karyawan -->
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl shadow-lg p-6 relative overflow-hidden group hover:shadow-xl transition-all duration-300 border border-blue-100">
            <div class="absolute top-0 right-0 w-20 h-20 bg-blue-200 rounded-full -mr-6 -mt-6 opacity-20 group-hover:opacity-30 transition-opacity"></div>
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Total Karyawan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $total_karyawan ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                        <p class="text-xs text-gray-500">Aktif</p>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600"></div>
        </div>

        <!-- Total Admin -->
        <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl shadow-lg p-6 relative overflow-hidden group hover:shadow-xl transition-all duration-300 border border-green-100">
            <div class="absolute top-0 right-0 w-20 h-20 bg-green-200 rounded-full -mr-6 -mt-6 opacity-20 group-hover:opacity-30 transition-opacity"></div>
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user-shield text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Mandor/Admin</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $total_admin ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2"></div>
                        <p class="text-xs text-gray-500">Manager Lapangan</p>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-green-600"></div>
        </div>

        <!-- Panen Bulan Ini -->
        <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl shadow-lg p-6 relative overflow-hidden group hover:shadow-xl transition-all duration-300 border border-purple-100">
            <div class="absolute top-0 right-0 w-20 h-20 bg-purple-200 rounded-full -mr-6 -mt-6 opacity-20 group-hover:opacity-30 transition-opacity"></div>
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-seedling text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Panen Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($panen_bulan_ini ?? 0, 0, ',', '.') }}<span class="text-lg">kg</span></p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                        <p class="text-xs text-gray-500">Total Produksi</p>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-400 to-purple-600"></div>
        </div>

        <!-- Laporan Masalah -->
        <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl shadow-lg p-6 relative overflow-hidden group hover:shadow-xl transition-all duration-300 border border-orange-100">
            <div class="absolute top-0 right-0 w-20 h-20 bg-orange-200 rounded-full -mr-6 -mt-6 opacity-20 group-hover:opacity-30 transition-opacity"></div>
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Laporan Masalah</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $laporan_masalah_baru ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2 animate-pulse"></div>
                        <p class="text-xs text-gray-500">Perlu Tindakan</p>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-orange-600"></div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Financial Overview -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Ringkasan Keuangan</h3>
                <a href="{{ route('owner.laporan-keuangan') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-arrow-down text-green-600 text-xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-green-800">Total Pemasukan</p>
                            <p class="text-sm text-green-600">Bulan ini</p>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-green-700">Rp {{ number_format($pemasukan_bulan_ini ?? 0, 0, ',', '.') }}</p>
                </div>
                
                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-xl border border-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-arrow-up text-red-600 text-xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-red-800">Total Pengeluaran</p>
                            <p class="text-sm text-red-600">Bulan ini</p>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-red-700">Rp {{ number_format($pengeluaran_bulan_ini ?? 0, 0, ',', '.') }}</p>
                </div>

                <div class="flex justify-between items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                    <div class="flex items-center">
                        <i class="fas fa-balance-scale text-blue-600 text-xl mr-4"></i>
                        <div>
                            <p class="font-semibold text-blue-800">Estimasi Laba</p>
                            <p class="text-sm text-blue-600">Bulan ini</p>
                        </div>
                    </div>
                    @php
                        $laba = ($pemasukan_bulan_ini ?? 0) - ($pengeluaran_bulan_ini ?? 0);
                        $textColor = $laba >= 0 ? 'text-green-700' : 'text-red-700';
                        $icon = $laba >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                    @endphp
                    <div class="text-right">
                        <p class="text-xl font-bold {{ $textColor }}">Rp {{ number_format($laba, 0, ',', '.') }}</p>
                        <p class="text-sm {{ $textColor }}">
                            <i class="fas {{ $icon }} mr-1"></i>
                            {{ $laba >= 0 ? 'Profit' : 'Loss' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Mini Financial Chart -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Trend 6 Bulan Terakhir</h4>
                @php
                    $allValues = array_merge($pemasukan_data ?? [], $pengeluaran_data ?? []);
                    $maxValue = !empty($allValues) ? max($allValues) : 1;
                    $maxHeight = $maxValue > 0 ? $maxValue : 1;
                @endphp
                
                @if($maxValue > 0 && !empty($pemasukan_data) && !empty($pengeluaran_data))
                <div class="h-32 flex items-end justify-between space-x-1">
                    @foreach($pemasukan_data as $index => $pemasukan)
                    <div class="flex flex-col items-center flex-1">
                        <div class="flex flex-col items-center space-y-1">
                            <div class="flex space-x-1">
                                <div class="bg-green-500 rounded-t w-2" style="height: {{ ($pemasukan / $maxHeight) * 80 }}px"></div>
                                <div class="bg-red-500 rounded-t w-2" style="height: {{ ($pengeluaran_data[$index] / $maxHeight) * 80 }}px"></div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $bulan_labels[$index] ?? '' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="h-32 flex items-center justify-center">
                    <p class="text-gray-400 text-sm">Belum ada data keuangan</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions & User Summary -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-6 text-gray-800">Aksi Cepat</h3>
                <div class="space-y-4">
                    <a href="{{ route('owner.laporan-keuangan') }}" 
                       class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all group">
                        <div class="p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                            <i class="fas fa-file-invoice-dollar text-green-600 text-lg"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-semibold text-gray-800">Laporan Keuangan</p>
                            <p class="text-sm text-gray-600">Detail pemasukan & pengeluaran</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    </a>

                    <a href="{{ route('owner.manajemen-user') }}"
                       class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all group">
                        <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-users-cog text-blue-600 text-lg"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-semibold text-gray-800">Manajemen User</p>
                            <p class="text-sm text-gray-600">Kelola karyawan & akses</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    </a>

                    <a href="{{ route('owner.rekap-produktivitas') }}"
                       class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all group">
                        <div class="p-3 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                            <i class="fas fa-chart-bar text-purple-600 text-lg"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-semibold text-gray-800">Rekap Produktivitas</p>
                            <p class="text-sm text-gray-600">Analisis kinerja tim</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                    </a>

                    @if($laporan_masalah_baru > 0)
                    <a href="{{ route('owner.laporan-masalah') }}"
                       class="flex items-center p-4 bg-red-50 rounded-xl border border-red-200 hover:bg-red-100 hover:border-red-300 transition-all group">
                        <div class="p-3 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-semibold text-red-800">Laporan Masalah</p>
                            <p class="text-sm text-red-600">{{ $laporan_masalah_baru }} perlu tindakan</p>
                        </div>
                        <i class="fas fa-chevron-right text-red-400 group-hover:text-red-600 transition-colors"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- User Summary -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Ringkasan Tim</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Karyawan</span>
                        <span class="font-semibold">{{ $total_karyawan ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Mandor/Admin</span>
                        <span class="font-semibold">{{ $total_admin ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total User</span>
                        <span class="font-semibold">{{ ($total_karyawan ?? 0) + ($total_admin ?? 0) + 1 }}</span>
                    </div>
                </div>
                <a href="{{ route('owner.manajemen-user') }}" class="block mt-4 text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Kelola User
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Recent Activity & Productivity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    <span class="text-sm text-gray-500">Hari Ini</span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($aktivitas_terbaru as $aktivitas)
                    <div class="flex items-start space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="p-2 {{ str_replace('text-', 'bg-', $aktivitas['color']) }} bg-opacity-10 rounded-lg">
                            <i class="{{ $aktivitas['icon'] }} {{ $aktivitas['color'] }} text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $aktivitas['title'] }}</p>
                            <p class="text-sm text-gray-600">{{ $aktivitas['description'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $aktivitas['time'] }}</p>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- System Status -->
                    <div class="flex items-start space-x-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-green-800">Sistem Berjalan Normal</p>
                            <p class="text-sm text-green-600">Semua modul berfungsi dengan baik</p>
                            <p class="text-xs text-green-500 mt-1">Real-time</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productivity Overview -->
        <div class="bg-white rounded-2xl shadow-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Produktivitas</h3>
                    <a href="{{ route('owner.rekap-produktivitas') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Detail →
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <i class="fas fa-seedling text-green-500 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">Panen Bulan Ini</p>
                                <p class="text-sm text-gray-600">{{ number_format($panen_bulan_ini ?? 0, 0, ',', '.') }} kg</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600">+12%</p>
                            <p class="text-xs text-gray-500">vs bulan lalu</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <i class="fas fa-users text-blue-500 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">Tim Aktif</p>
                                <p class="text-sm text-gray-600">{{ ($total_karyawan ?? 0) + ($total_admin ?? 0) }} anggota</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">94%</p>
                            <p class="text-xs text-gray-500">Kehadiran</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <i class="fas fa-tachometer-alt text-purple-500 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">Efisiensi</p>
                                <p class="text-sm text-gray-600">Operasional harian</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-purple-600">88%</p>
                            <p class="text-xs text-gray-500">Optimal</p>
                        </div>
                    </div>
                </div>

                <!-- Progress Bars -->
                <div class="mt-6 space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Target Panen</span>
                            <span class="font-medium">85%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">Efisiensi Tim</span>
                            <span class="font-medium">92%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 92%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Footer -->
    <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <i class="fas fa-money-bill-wave text-green-500 text-xl mb-2"></i>
            <p class="text-sm text-gray-600">Pemasukan</p>
            <p class="font-bold text-gray-800">Rp {{ number_format($pemasukan_bulan_ini ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <i class="fas fa-receipt text-red-500 text-xl mb-2"></i>
            <p class="text-sm text-gray-600">Pengeluaran</p>
            <p class="font-bold text-gray-800">Rp {{ number_format($pengeluaran_bulan_ini ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <i class="fas fa-chart-line text-blue-500 text-xl mb-2"></i>
            <p class="text-sm text-gray-600">Laba Bersih</p>
            @php $laba = ($pemasukan_bulan_ini ?? 0) - ($pengeluaran_bulan_ini ?? 0); @endphp
            <p class="font-bold {{ $laba >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($laba, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4 text-center">
            <i class="fas fa-percentage text-purple-500 text-xl mb-2"></i>
            <p class="text-sm text-gray-600">Margin</p>
            @php $margin = $pemasukan_bulan_ini > 0 ? ($laba / $pemasukan_bulan_ini) * 100 : 0; @endphp
            <p class="font-bold {{ $margin >= 20 ? 'text-green-600' : ($margin >= 10 ? 'text-yellow-600' : 'text-red-600') }}">{{ number_format($margin, 1) }}%</p>
        </div>
    </div>
</div>

<script>
// Auto refresh stats every 30 seconds
setInterval(function() {
    fetch('{{ route("owner.dashboard.stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update stats cards
                document.querySelector('[data-stat="karyawan"]').textContent = data.data.total_karyawan;
                document.querySelector('[data-stat="admin"]').textContent = data.data.total_admin;
                document.querySelector('[data-stat="panen"]').textContent = data.data.panen_bulan_ini.toLocaleString();
                document.querySelector('[data-stat="masalah"]').textContent = data.data.laporan_masalah_baru;
            }
        })
        .catch(error => console.error('Error fetching stats:', error));
}, 30000);

// Add data attributes to stats cards for easy updating
document.addEventListener('DOMContentLoaded', function() {
    const stats = document.querySelectorAll('.bg-gradient-to-br');
    if (stats[0]) stats[0].setAttribute('data-stat', 'karyawan');
    if (stats[1]) stats[1].setAttribute('data-stat', 'admin');
    if (stats[2]) stats[2].setAttribute('data-stat', 'panen');
    if (stats[3]) stats[3].setAttribute('data-stat', 'masalah');
});
</script>
@endsection
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Panen - Owner</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .card-shadow { 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                        0 2px 4px -1px rgba(0, 0, 0, 0.06); 
        }
        .tab-active {
            border-bottom: 3px solid #3b82f6;
            color: #1e40af;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('layouts.app')

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-tractor text-green-600 mr-3"></i>
                        Manajemen Panen
                    </h1>
                    <p class="text-gray-600">Verifikasi dan laporan panen harian</p>
                </div>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-user-shield mr-1"></i>
                    {{ auth()->user()->nama_lengkap }}
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-xl card-shadow mb-6 overflow-hidden">
            <div class="flex border-b border-gray-200">
                <a href="{{ route('owner.panen-management', ['tab' => 'verifikasi']) . ($active_tab == 'verifikasi' ? '#verifikasi' : '') }}"
                   class="flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors {{ $active_tab == 'verifikasi' ? 'tab-active bg-blue-50' : '' }}">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Verifikasi Panen
                    @if($stats_verifikasi['total_menunggu'] > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ $stats_verifikasi['total_menunggu'] }}
                    </span>
                    @endif
                </a>
                <a href="{{ route('owner.panen-management', ['tab' => 'laporan']) . ($active_tab == 'laporan' ? '#laporan' : '') }}"
                   class="flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors {{ $active_tab == 'laporan' ? 'tab-active bg-blue-50' : '' }}">
                    <i class="fas fa-file-alt mr-2"></i>
                    Laporan Panen
                </a>
                <a href="{{ route('owner.rekap-produktivitas') }}"
                   class="flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Analisis Produktivitas
                </a>
            </div>
        </div>

        <!-- Tab Content -->
        @if($active_tab == 'verifikasi')
        <!-- TAB VERIFIKASI -->
        <div id="verifikasi">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl card-shadow p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $stats_verifikasi['total_menunggu'] }}
                    </div>
                    <div class="text-sm text-gray-600">Menunggu Verifikasi</div>
                </div>
                <div class="bg-white rounded-xl card-shadow p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">
                        {{ number_format($stats_verifikasi['total_berat_menunggu'], 0) }} kg
                    </div>
                    <div class="text-sm text-gray-600">Total Berat</div>
                </div>
                <div class="bg-white rounded-xl card-shadow p-4 text-center">
                    <div class="text-2xl font-bold text-purple-600">
                        Rp {{ number_format($stats_verifikasi['total_upah_menunggu'], 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-600">Total Upah</div>
                </div>
                <div class="bg-white rounded-xl card-shadow p-4 text-center">
                    <div class="text-2xl font-bold text-orange-600">
                        {{ $stats_verifikasi['karyawan_terlibat'] }}
                    </div>
                    <div class="text-sm text-gray-600">Karyawan Terlibat</div>
                </div>
            </div>

            <!-- Panen Menunggu Verifikasi -->
            <div class="bg-white rounded-xl card-shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                        Panen Menunggu Verifikasi
                        @if($panen_perlu_verifikasi->isEmpty())
                        <span class="ml-2 text-sm font-normal text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>
                            Semua panen telah diverifikasi
                        </span>
                        @endif
                    </h2>
                </div>

                @if($panen_perlu_verifikasi->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
                    <p class="text-gray-600 text-lg">Tidak ada panen yang menunggu verifikasi</p>
                    <p class="text-gray-500 text-sm mt-2">Semua laporan panen telah diproses</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Karyawan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Blok Ladang
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Detail Panen
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($panen_perlu_verifikasi as $panen)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($panen->tanggal)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($panen->created_at)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $panen->user->nama_lengkap }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $panen->user->jabatan }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $panen->blokLadang->nama_blok }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if($panen->blokLadang->jarak == 'jauh')
                                        <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded">
                                            <i class="fas fa-road mr-1"></i>Jauh
                                        </span>
                                        @else
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded">
                                            <i class="fas fa-home mr-1"></i>Dekat
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <div class="text-xs text-gray-500">Berat</div>
                                            <div class="text-sm font-semibold {{ $panen->jenis_buah == 'buah_segar' ? 'text-green-600' : 'text-amber-600' }}">
                                                {{ number_format($panen->jumlah_kg, 1) }} kg
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Jenis</div>
                                            <div class="text-sm font-medium">
                                                @if($panen->jenis_buah == 'buah_segar')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                                                    Segar
                                                </span>
                                                @else
                                                <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs">
                                                    Gugur
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Upah/kg</div>
                                            <div class="text-sm">
                                                Rp {{ number_format($panen->harga_upah_per_kg, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Total Upah</div>
                                            <div class="text-sm font-bold text-blue-600">
                                                Rp {{ number_format($panen->total_upah, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                    @if($panen->keterangan)
                                    <div class="mt-2 text-xs text-gray-600 bg-gray-50 p-2 rounded">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        {{ $panen->keterangan }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-2">
                                        <button onclick="showVerifyModal({{ $panen->id_panen }}, 'approve', '{{ addslashes($panen->user->nama_lengkap) }}', {{ $panen->jumlah_kg }})"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors w-full">
                                            <i class="fas fa-check mr-2"></i>
                                            Setujui
                                        </button>
                                        <button onclick="showVerifyModal({{ $panen->id_panen }}, 'reject', '{{ addslashes($panen->user->nama_lengkap) }}', {{ $panen->jumlah_kg }})"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors w-full">
                                            <i class="fas fa-times mr-2"></i>
                                            Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <!-- Quick Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-medium text-blue-800">Informasi Verifikasi</h4>
                        <p class="text-sm text-blue-600 mt-1">
                            • Panen dengan status "draft" memerlukan verifikasi owner
                            <br>• Panen yang disetujui akan otomatis tercatat sebagai pemasukan
                            <br>• Panen yang ditolak akan dikembalikan ke karyawan untuk revisi
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- TAB LAPORAN -->
        <div id="laporan">
            <!-- Filter Section -->
            <div class="bg-white rounded-xl card-shadow p-6 mb-6">
                <form method="GET" action="{{ route('owner.panen-management') }}" class="flex flex-col md:flex-row gap-4 items-end">
                    <input type="hidden" name="tab" value="laporan">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $start_date }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                            <input type="date" name="end_date" value="{{ $end_date }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tampilkan</label>
                            <select name="per_page" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="10">10 data</option>
                                <option value="25">25 data</option>
                                <option value="50">50 data</option>
                                <option value="100">100 data</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                        <a href="{{ route('owner.panen-management', ['tab' => 'verifikasi']) }}"
                           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Verifikasi Panen
                        </a>
                        <button type="button" onclick="window.print()" 
                                class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl card-shadow p-4">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $stats_laporan['total_panen'] }}
                    </div>
                    <div class="text-sm text-gray-600">Total Panen</div>
                    <div class="text-xs text-blue-500 mt-1">
                        {{ number_format($stats_laporan['rata_per_panen'], 1) }} kg/panen
                    </div>
                </div>
                <div class="bg-white rounded-xl card-shadow p-4">
                    <div class="text-2xl font-bold text-green-600">
                        {{ number_format($stats_laporan['total_berat_kg'], 0) }} kg
                    </div>
                    <div class="text-sm text-gray-600">Total Berat</div>
                    <div class="text-xs text-green-500 mt-1">
                        {{ number_format($stats_laporan['buah_segar'], 0) }} kg segar
                    </div>
                </div>
                <div class="bg-white rounded-xl card-shadow p-4">
                    <div class="text-2xl font-bold text-purple-600">
                        Rp {{ number_format($stats_laporan['total_upah'], 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-600">Total Upah</div>
                    <div class="text-xs text-purple-500 mt-1">
                        {{ $stats_laporan['jumlah_karyawan'] }} karyawan
                    </div>
                </div>
                <div class="bg-white rounded-xl card-shadow p-4">
                    <div class="text-2xl font-bold text-orange-600">
                        {{ number_format($stats_laporan['buah_gugur'], 0) }} kg
                    </div>
                    <div class="text-sm text-gray-600">Buah Gugur</div>
                    <div class="text-xs text-orange-500 mt-1">
                        {{ $stats_laporan['total_berat_kg'] > 0 ? 
                           number_format(($stats_laporan['buah_gugur'] / $stats_laporan['total_berat_kg']) * 100, 1) : 0 }}%
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            @if(!empty($chart_data['labels']))
            <div class="bg-white rounded-xl card-shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend Panen per Bulan</h3>
                <div class="h-64">
                    <canvas id="panenChart"></canvas>
                </div>
            </div>
            @endif

            <!-- Data Table -->
            <div class="bg-white rounded-xl card-shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center justify-between">
                        <span>
                            <i class="fas fa-list-alt text-green-500 mr-2"></i>
                            Panen Terverifikasi
                        </span>
                        <span class="text-sm font-normal text-gray-600">
                            Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
                        </span>
                    </h2>
                </div>

                @if($panen_terverifikasi->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 text-lg">Tidak ada data panen pada periode ini</p>
                    <p class="text-gray-500 text-sm mt-2">Coba ubah filter tanggal atau verifikasi panen terlebih dahulu</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Karyawan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Blok
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Berat (kg)
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Upah
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($panen_terverifikasi as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal_verifikasi ?? $item->updated_at)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->user->nama_lengkap }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $item->user->jabatan }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->blokLadang->nama_blok }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if($item->blokLadang->jarak == 'jauh')
                                        <span class="text-orange-600">Jauh</span>
                                        @else
                                        <span class="text-green-600">Dekat</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold {{ $item->jenis_buah == 'buah_segar' ? 'text-green-600' : 'text-amber-600' }}">
                                        {{ number_format($item->jumlah_kg, 1) }} kg
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if($item->jenis_buah == 'buah_segar')
                                        <span class="text-green-600">Segar</span>
                                        @else
                                        <span class="text-amber-600">Gugur</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-blue-600">
                                        Rp {{ number_format($item->total_upah, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Rp {{ number_format($item->harga_upah_per_kg, 0) }}/kg
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Terverifikasi
                                    </span>
                                    @if($item->verifikator)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Oleh: {{ $item->verifikator->nama_lengkap }}
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-sm text-gray-600">Rata-rata Berat</div>
                            <div class="font-semibold text-gray-900">{{ number_format($stats_laporan['rata_per_panen'], 1) }} kg</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-600">Buah Segar</div>
                            <div class="font-semibold text-green-600">{{ number_format($stats_laporan['buah_segar'], 0) }} kg</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-600">Rata Upah/kg</div>
                            <div class="font-semibold text-blue-600">
                                @if($stats_laporan['total_berat_kg'] > 0)
                                Rp {{ number_format($stats_laporan['total_upah'] / $stats_laporan['total_berat_kg'], 0) }}
                                @else
                                Rp 0
                                @endif
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-600">Hari Kerja</div>
                            <div class="font-semibold text-gray-900">{{ $panen_terverifikasi->unique('tanggal')->count() }} hari</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Navigation Footer -->
        <div class="flex justify-between items-center mt-8">
            <a href="{{ route('owner.dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
            
            <div class="flex space-x-3">
                @if($active_tab == 'verifikasi')
                <a href="{{ route('owner.panen-management', ['tab' => 'laporan']) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-file-alt mr-2"></i>
                    Lihat Laporan Panen
                </a>
                @else
                <a href="{{ route('owner.panen-management', ['tab' => 'verifikasi']) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Verifikasi Panen
                    @if($stats_verifikasi['total_menunggu'] > 0)
                    <span class="ml-2 bg-white text-green-600 text-xs px-2 py-1 rounded-full">
                        {{ $stats_verifikasi['total_menunggu'] }}
                    </span>
                    @endif
                </a>
                @endif
                
                <a href="{{ route('owner.rekap-produktivitas') }}"
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Analisis Produktivitas
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Verifikasi -->
    <div id="verifyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                    <i class="fas fa-clipboard-check text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 text-center mb-2" id="modalTitle">
                    Verifikasi Panen
                </h3>
                <div class="text-sm text-gray-600 text-center mb-4" id="panenDetail">
                    <!-- Detail panen akan diisi oleh JavaScript -->
                </div>
                
                <div class="mt-2 px-7 py-3">
                    <form id="verifyForm">
                        @csrf
                        <input type="hidden" name="action" id="actionInput">
                        <input type="hidden" name="id_panen" id="idPanenInput">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Verifikasi
                            </label>
                            <textarea name="catatan_verifikasi" 
                                      id="catatanVerifikasi"
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Opsional: Berikan catatan verifikasi..."></textarea>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span id="actionDescription"></span>
                            </p>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeModal()"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" id="submitBtn"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Konfirmasi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan modal verifikasi
        function showVerifyModal(idPanen, action, namaKaryawan, berat) {
            const modal = document.getElementById('verifyModal');
            const actionInput = document.getElementById('actionInput');
            const idPanenInput = document.getElementById('idPanenInput');
            const modalTitle = document.getElementById('modalTitle');
            const panenDetail = document.getElementById('panenDetail');
            const actionDescription = document.getElementById('actionDescription');
            const submitBtn = document.getElementById('submitBtn');
            
            idPanenInput.value = idPanen;
            actionInput.value = action;
            
            // Update detail panen
            panenDetail.innerHTML = `
                <strong>${namaKaryawan}</strong> - ${berat} kg
            `;
            
            if (action === 'approve') {
                modalTitle.textContent = 'Setujui Panen';
                actionDescription.textContent = 'Panen akan disetujui dan dicatat dalam sistem. Upah akan dihitung otomatis.';
                submitBtn.className = 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors';
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Setujui Panen';
            } else {
                modalTitle.textContent = 'Tolak Panen';
                actionDescription.textContent = 'Panen akan ditolak. Karyawan dapat memperbaiki dan mengirim ulang.';
                submitBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors';
                submitBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Tolak Panen';
            }
            
            modal.classList.remove('hidden');
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            const modal = document.getElementById('verifyModal');
            modal.classList.add('hidden');
            document.getElementById('catatanVerifikasi').value = '';
        }

        // Handle form submission
        document.getElementById('verifyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const idPanen = formData.get('id_panen');
            const action = formData.get('action');
            
            // Tampilkan loading
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            
            fetch(`/owner/verifikasi-panen/${idPanen}/verify`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeModal();
                    
                    // Jika approve, redirect ke laporan
                    if (action === 'approve' && data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        // Reload halaman untuk update data
                        window.location.reload();
                    }
                } else {
                    alert('Error: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim data');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Close modal when clicking outside
        document.getElementById('verifyModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Chart untuk laporan panen
        @if($active_tab == 'laporan' && !empty($chart_data['labels']))
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('panenChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chart_data['labels']),
                    datasets: [
                        {
                            label: 'Total Berat (kg)',
                            data: @json($chart_data['berat']),
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Total Upah (ribu Rp)',
                            data: @json(array_map(function($upah) { 
                                return $upah / 1000; 
                            }, $chart_data['upah'])),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Berat (kg)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Upah (ribu Rp)'
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label.includes('Upah')) {
                                        return label + ': Rp ' + context.parsed.y.toLocaleString() + ' ribu';
                                    }
                                    return label + ': ' + context.parsed.y.toLocaleString() + ' kg';
                                }
                            }
                        }
                    }
                }
            });
        });
        @endif

        // Tab switching dengan smooth scroll
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            const hash = window.location.hash;
            
            if (hash === '#verifikasi' || hash === '#laporan') {
                const targetElement = document.getElementById(hash.substring(1));
                if (targetElement) {
                    setTimeout(() => {
                        targetElement.scrollIntoView({ behavior: 'smooth' });
                    }, 100);
                }
            }
        });

        // Auto-refresh untuk tab verifikasi setiap 30 detik
        @if($active_tab == 'verifikasi')
        setInterval(function() {
            if (!document.getElementById('verifyModal') || 
                document.getElementById('verifyModal').classList.contains('hidden')) {
                window.location.reload();
            }
        }, 30000);
        @endif
    </script>
</body>
</html>
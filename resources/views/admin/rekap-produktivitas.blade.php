<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Produktivitas - {{ ucfirst(auth()->user()->role) }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .bg-role-primary { 
            background-color: {{ auth()->user()->role == 'owner' ? '#1e40af' : '#059669' }}; 
        }
        .bg-role-secondary { 
            background-color: {{ auth()->user()->role == 'owner' ? '#3b82f6' : '#10b981' }}; 
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-role-primary text-white shadow-lg no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ auth()->user()->role == 'owner' ? route('owner.dashboard') : route('admin.dashboard') }}" 
                       class="text-gray-200 hover:text-white mr-4">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <i class="fas fa-tractor text-xl mr-3"></i>
                    <span class="font-semibold text-xl">Rekap Produktivitas</span>
                    <span class="ml-2 text-sm bg-white bg-opacity-20 px-2 py-1 rounded">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-200">{{ auth()->user()->nama_lengkap }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-role-secondary hover:bg-role-secondary/80 px-3 py-1 rounded transition-colors">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Rekap Produktivitas Lengkap</h1>
            <p class="text-gray-600">Analisis semua data panen tanpa filter status</p>
        </div>

        <!-- Date Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 no-print">
            <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
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
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-role-primary text-white px-4 py-2 rounded-lg hover:bg-role-primary/90 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <button type="button" onclick="resetFilter()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </button>
                    <button type="button" onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors no-print">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">
                    @if($total_berat_kg >= 1000)
                        {{ number_format($total_berat_kg / 1000, 2) }} ton
                    @else
                        {{ number_format($total_berat_kg, 0) }} kg
                    @endif
                </div>
                <div class="text-sm text-gray-600">Total Panen (Semua Status)</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">
                    Rp {{ number_format($total_upah_keseluruhan, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-600">Total Upah Dihitung</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">
                    {{ number_format($rata_per_panen_keseluruhan, 1) }} kg
                </div>
                <div class="text-sm text-gray-600">Rata-rata per Panen</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-orange-600">
                    {{ $jumlah_karyawan_aktif }}
                </div>
                <div class="text-sm text-gray-600">Karyawan Aktif</div>
            </div>
        </div>

        <!-- Dua Kolom Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Produktivitas per Blok -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                    Produktivitas per Blok
                </h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($produktivitas_per_blok as $blok)
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <div>
                            <div class="font-medium text-gray-900">{{ $blok->nama_blok }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2">
                                    {{ $blok->total_panen }} panen
                                </span>
                                @if($blok->jenis_buah_dominan)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">
                                    {{ $blok->jenis_buah_dominan == 'buah_segar' ? 'Segar' : 'Gugur' }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-blue-600">
                                @if($blok->total_berat >= 1000)
                                    {{ number_format($blok->total_berat / 1000, 2) }} ton
                                @else
                                    {{ number_format($blok->total_berat, 0) }} kg
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                Rp {{ number_format($blok->rata_upah_per_kg, 0) }}/kg
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($produktivitas_per_blok->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-seedling text-3xl mb-3"></i>
                        <p>Belum ada data panen untuk periode ini</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Top Performers -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-trophy text-yellow-500 mr-2"></i>
        Top 5 Performers (Berdasarkan Kehadiran)
    </h3>

    <div class="space-y-3">
        @php
            $rankColors = [
                'bg-yellow-500',  // Rank 1
                'bg-gray-400',    // Rank 2
                'bg-orange-500',  // Rank 3
                'bg-blue-400',    // Rank 4
                'bg-green-400'    // Rank 5
            ];
        @endphp

        @foreach($top_performers as $index => $performer)
        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-yellow-50 transition-colors">
            
            <!-- Rank + Name -->
            <div class="flex items-center flex-1">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm {{ $rankColors[$index] ?? 'bg-gray-300' }}">
                    {{ $index + 1 }}
                </div>

                <div class="ml-3">
                    <div class="font-semibold text-gray-900">{{ $performer->nama_lengkap }}</div>

                    <div class="text-xs text-gray-500 flex items-center gap-3 mt-1">
                        <span class="text-green-600 flex items-center gap-1">
                            <i class="fas fa-user-check"></i> 
                            {{ $performer->total_hadir }} hadir
                        </span>

                        <span class="text-red-600 flex items-center gap-1">
                            <i class="fas fa-user-times"></i> 
                            {{ $performer->total_alpha }} alpha
                        </span>
                    </div>
                </div>
            </div>

        </div>
        @endforeach

        @if($top_performers->isEmpty())
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-user-check text-3xl mb-3"></i>
            <p>Belum ada data performers</p>
        </div>
        @endif
    </div>
</div>

        <!-- Tabel Detail Karyawan -->
        
        <!-- Grafik untuk Owner -->
        @if(auth()->user()->role == 'owner' && !empty($chart_data['labels']))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Grafik Produktivitas Blok -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Produktivitas per Blok</h3>
                <div class="h-64">
                    <canvas id="blokChart"></canvas>
                </div>
            </div>
            
            <!-- Grafik Jenis Buah -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Jenis Buah</h3>
                <div class="h-64">
                    <canvas id="jenisBuahChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Summary -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Data</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-medium text-blue-800 mb-2">Statistik Keseluruhan</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Data Panen:</span>
                            <span class="font-semibold">{{ $produktivitas_per_blok->sum('total_panen') }} record</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Blok yang Berproduksi:</span>
                            <span class="font-semibold">{{ $produktivitas_per_blok->count() }} blok</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Karyawan Beraktivitas:</span>
                            <span class="font-semibold">{{ $produktivitas_karyawan->count() }} orang</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h4 class="font-medium text-green-800 mb-2">Jenis Buah</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Buah Segar:</span>
                            <span class="font-semibold">
                                {{ number_format($jenis_buah_stats->total_buah_segar ?? 0, 0) }} kg
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Buah Gugur:</span>
                            <span class="font-semibold">
                                {{ number_format($jenis_buah_stats->total_buah_gugur ?? 0, 0) }} kg
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rasio:</span>
                            <span class="font-semibold">
                                @php
                                    $total = ($jenis_buah_stats->total_buah_segar ?? 0) + ($jenis_buah_stats->total_buah_gugur ?? 0);
                                    $ratio = $total > 0 ? ($jenis_buah_stats->total_buah_segar ?? 0) / $total * 100 : 0;
                                @endphp
                                {{ number_format($ratio, 1) }}% segar
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500 mt-6">
            <div class="flex items-center justify-center space-x-4">
                <span>
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
                </span>
                <span>•</span>
                <span>
                    <i class="fas fa-database mr-1"></i>
                    Total Data: {{ $produktivitas_per_blok->sum('total_panen') }} panen
                </span>
                <span>•</span>
                <span>
                    <i class="fas fa-clock mr-1"></i>
                    Dicetak: {{ now()->format('d M Y H:i') }}
                </span>
            </div>
        </div>
    </div>

    <script>
        // Reset filter
        function resetFilter() {
            window.location.href = '{{ route(auth()->user()->role . ".rekap-produktivitas") }}';
        }

        // Set default dates
        @if(!request()->has('start_date'))
        document.querySelector('input[name="start_date"]').value = new Date().toISOString().split('T')[0].substring(0, 8) + '01';
        @endif

        @if(!request()->has('end_date'))
        document.querySelector('input[name="end_date"]').value = new Date().toISOString().split('T')[0];
        @endif

        // Grafik untuk Owner
        @if(auth()->user()->role == 'owner')
        
        // Grafik Blok
        const blokCtx = document.getElementById('blokChart')?.getContext('2d');
        if (blokCtx) {
            new Chart(blokCtx, {
                type: 'bar',
                data: {
                    labels: @json($chart_data['labels']),
                    datasets: [{
                        label: 'Total Berat (kg)',
                        data: @json($chart_data['berat']),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Berat (kg)'
                            }
                        }
                    }
                }
            });
        }

        // Grafik Jenis Buah
        const buahCtx = document.getElementById('jenisBuahChart')?.getContext('2d');
        if (buahCtx) {
            new Chart(buahCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Buah Segar', 'Buah Gugur'],
                    datasets: [{
                        data: [
                            {{ $chart_data['jenis_buah']['segar'] ?? 0 }},
                            {{ $chart_data['jenis_buah']['gugur'] ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.7)',
                            'rgba(251, 146, 60, 0.7)'
                        ],
                        borderColor: [
                            'rgb(34, 197, 94)',
                            'rgb(251, 146, 60)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        @endif
    </script>
</body>
</html>
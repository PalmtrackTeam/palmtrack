<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Produktivitas - {{ auth()->user()->role == 'owner' ? 'Owner' : 'Admin' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .card-shadow { box-shadow: none; border: 1px solid #e5e7eb; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation - Dinamis berdasarkan role -->
    <nav class="{{ auth()->user()->role == 'owner' ? 'bg-blue-800' : 'bg-green-800' }} text-white shadow-lg no-print">
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
                        {{ auth()->user()->role == 'owner' ? 'Owner' : 'Admin' }}
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-200">{{ auth()->user()->nama_lengkap }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="{{ auth()->user()->role == 'owner' ? 'bg-blue-700 hover:bg-blue-600' : 'bg-green-700 hover:bg-green-600' }} px-3 py-1 rounded transition-colors">
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
            <h1 class="text-2xl font-bold text-gray-900">Rekap Produktivitas Kebun</h1>
            <p class="text-gray-600">Analisis performa per blok dan produktivitas</p>
        </div>

        <!-- Date Filter -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-6 no-print">
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
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <button type="button" onclick="resetFilter()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </button>
                    @if(auth()->user()->role == 'owner')
                    <button type="button" onclick="exportToPDF()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Productivity Stats - DIKOREKSI TANPA KARYAWAN_AKTIF -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">
                    {{ number_format($produktivitas_per_blok->sum('total_berat'), 0, ',', '.') }} kg
                </div>
                <div class="text-sm text-gray-600">Total Panen</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">
                    Rp {{ number_format($produktivitas_per_blok->sum('total_upah'), 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-600">Total Upah</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">
                    {{ number_format($produktivitas_per_blok->avg('rata_per_panen') ?? 0, 1) }} kg
                </div>
                <div class="text-sm text-gray-600">Rata-rata per Panen</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Productivity by Block -->
            <div class="bg-white rounded-xl card-shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Produktivitas per Blok</h3>
                <div class="space-y-3">
                    @foreach($produktivitas_per_blok as $blok)
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                        <div class="font-medium text-gray-900">{{ $blok->nama_blok }}</div>
                        <div class="text-right">
                            <div class="font-semibold text-blue-600">{{ number_format($blok->total_berat, 0, ',', '.') }} kg</div>
                            <div class="text-sm text-gray-500">{{ $blok->total_panen }} kali panen</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Performers (Absensi) -->
<div class="bg-white rounded-xl card-shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performers</h3>

    <div class="space-y-3">

        @php
            $rankColor = [
                1 => 'bg-yellow-500 text-white',
                2 => 'bg-gray-400 text-white',
                3 => 'bg-orange-500 text-white'
            ];
        @endphp

        @if(isset($top_absensi) && $top_absensi->count() > 0)
            @foreach($top_absensi as $index => $karyawan)

            <div class="p-3 border border-gray-200 rounded-lg flex items-center gap-3">

                <!-- Ranking -->
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                    {{ $rankColor[$index+1] ?? 'bg-green-600 text-white' }}">
                    {{ $index + 1 }}
                </div>

                <!-- Avatar + Nama -->
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center 
                        text-green-600 font-semibold text-sm uppercase">
                        {{ substr($karyawan->nama_lengkap, 0, 1) }}
                    </div>

                    <div>
                        <div class="font-medium text-gray-900 text-sm leading-tight">
                            {{ $karyawan->nama_lengkap }}
                        </div>
                        <div class="text-xs text-gray-600 capitalize leading-tight">
                            {{ $karyawan->role }}
                        </div>
                    </div>
                </div>

                <!-- Statistik -->
                <div class="text-right leading-tight">
                    <div class="text-xs text-green-600 font-semibold">
                        Hadir: {{ $karyawan->total_hadir }} hari
                    </div>
                    <div class="text-xs text-red-500 font-semibold">
                        Alpha: {{ $karyawan->total_alpha }}
                    </div>
                </div>
            </div>

            @endforeach
        @else
            <div class="text-center py-4 text-gray-500">
                <i class="fas fa-chart-line text-2xl mb-2"></i>
                <p>Belum ada data absensi</p>
            </div>
        @endif
    </div>
</div>


        <!-- Detailed Productivity Table -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Detail Produktivitas per Blok</h3>
                @if(auth()->user()->role == 'owner')
                <div class="flex gap-2 no-print">
                    <button onclick="printTable()" class="bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600">
                        <i class="fas fa-print mr-1"></i>Print
                    </button>
                </div>
                @endif
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full" id="productivityTable">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Blok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Panen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Berat (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rata-rata per Panen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Upah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($produktivitas_per_blok as $index => $blok)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900">{{ $blok->nama_blok }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $blok->total_panen }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($blok->total_berat, 0, ',', '.') }} kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($blok->rata_per_panen ?? 0, 1) }} kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($blok->total_upah, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($produktivitas_per_blok->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-chart-bar text-3xl mb-3 text-gray-400"></i>
                    <p>Tidak ada data produktivitas untuk periode ini</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Tabel Produktivitas Karyawan (jika ada data) -->
        @if(isset($produktivitas_karyawan) && $produktivitas_karyawan->count() > 0)
        <div class="bg-white rounded-xl card-shadow overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Produktivitas Karyawan</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full" id="employeeProductivityTable">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Karyawan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari Kerja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Panen (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rata-rata/hari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Upah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($produktivitas_karyawan as $index => $karyawan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900">{{ $karyawan->nama_lengkap }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                    {{ $karyawan->role }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $karyawan->hari_kerja }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($karyawan->total_kg, 0, ',', '.') }} kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($karyawan->rata_kg_per_hari, 1) }} kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($karyawan->total_upah, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Periode Info -->
        <div class="mt-4 text-center text-sm text-gray-500">
            <i class="fas fa-calendar-alt mr-1"></i>
            Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
        </div>
    </div>

    <script>
        function resetFilter() {
            // Redirect ke route yang sesuai berdasarkan role
            @if(auth()->user()->role == 'owner')
            window.location.href = '{{ route("owner.rekap-produktivitas") }}';
            @else
            window.location.href = '{{ route("admin.rekap-produktivitas") }}';
            @endif
        }

        function printTable() {
            window.print();
        }

        function exportToPDF() {
            alert('Fitur export PDF akan segera tersedia!');
            // Implementasi export PDF bisa ditambahkan di sini
        }

        // Set default dates if not set
        @if(!request()->has('start_date'))
        document.querySelector('input[name="start_date"]').value = new Date().toISOString().split('T')[0].substring(0, 8) + '01';
        @endif

        @if(!request()->has('end_date'))
        document.querySelector('input[name="end_date"]').value = new Date().toISOString().split('T')[0];
        @endif
    </script>
</body>
</html>
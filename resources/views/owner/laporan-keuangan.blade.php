@extends('layouts.app')

@section('title', 'Laporan Keuangan - Owner')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
                <p class="text-gray-600">Monitoring pemasukan dan pengeluaran perusahaan</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportToPDF()" class="bg-white border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-download mr-2"></i>Export PDF
                </button>
                <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('owner.laporan-keuangan') }}" class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                    <select name="periode" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 w-full md:w-48">
                        <option value="bulan_ini" {{ request('periode', 'bulan_ini') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="bulan_lalu" {{ request('periode') == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="3_bulan" {{ request('periode') == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                        <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}" 
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full md:w-40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')) }}" 
                           class="border border-gray-300 rounded-lg px-3 py-2 w-full md:w-40">
                </div>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('owner.laporan-keuangan') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Pemasukan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pemasukan</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($pemasukan_bulan_ini, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                @if($growth_pemasukan >= 0)
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span class="text-green-500 text-sm font-medium">+{{ $growth_pemasukan }}%</span>
                @else
                    <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                    <span class="text-red-500 text-sm font-medium">{{ $growth_pemasukan }}%</span>
                @endif
                <span class="text-gray-500 text-sm ml-2">vs bulan lalu</span>
            </div>
        </div>

        <!-- Total Pengeluaran -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($pengeluaran_bulan_ini, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                @if($growth_pengeluaran >= 0)
                    <i class="fas fa-arrow-up text-red-500 mr-1"></i>
                    <span class="text-red-500 text-sm font-medium">+{{ $growth_pengeluaran }}%</span>
                @else
                    <i class="fas fa-arrow-down text-green-500 mr-1"></i>
                    <span class="text-green-500 text-sm font-medium">{{ $growth_pengeluaran }}%</span>
                @endif
                <span class="text-gray-500 text-sm ml-2">vs bulan lalu</span>
            </div>
        </div>

        <!-- Laba Bersih -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Laba Bersih</p>
                    @php
                        $laba_bersih = $pemasukan_bulan_ini - $pengeluaran_bulan_ini;
                        $text_color = $laba_bersih >= 0 ? 'text-blue-600' : 'text-red-600';
                    @endphp
                    <p class="text-2xl font-bold {{ $text_color }}">Rp {{ number_format($laba_bersih, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-balance-scale text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">Setelah semua biaya</p>
        </div>

        <!-- Margin Keuntungan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Margin Keuntungan</p>
                    @php
                        $margin = $pemasukan_bulan_ini > 0 ? ($laba_bersih / $pemasukan_bulan_ini) * 100 : 0;
                        $margin_color = $margin >= 20 ? 'text-purple-600' : ($margin >= 10 ? 'text-yellow-600' : 'text-red-600');
                    @endphp
                    <p class="text-2xl font-bold {{ $margin_color }}">{{ number_format($margin, 1) }}%</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">Profitability</p>
        </div>
    </div>

    <!-- Charts and Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Pemasukan Detail -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Detail Pemasukan</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($penjualan_bulan_ini as $penjualan)
                    <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div>
                            <p class="font-medium text-green-800">{{ $penjualan->pembeli }}</p>
                            <p class="text-sm text-green-600">
                                {{ $penjualan->tujuan_jual }} - 
                                {{ number_format($penjualan->total_berat_kg, 0, ',', '.') }} kg
                            </p>
                            <p class="text-xs text-green-500 mt-1">{{ $penjualan->tanggal->format('d M Y') }}</p>
                        </div>
                        <p class="font-bold text-green-700">Rp {{ number_format($penjualan->total_pemasukan, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                    
                    @if($penjualan_bulan_ini->isEmpty())
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Tidak ada data penjualan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pengeluaran per Kategori -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Pengeluaran per Kategori</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($pengeluaran_per_kategori as $pengeluaran)
                    @php
                        $color_class = [
                            'pupuk' => 'bg-blue-50 border-blue-200 text-blue-800',
                            'transportasi' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
                            'perawatan' => 'bg-purple-50 border-purple-200 text-purple-800',
                            'gaji' => 'bg-green-50 border-green-200 text-green-800',
                            'lainnya' => 'bg-gray-50 border-gray-200 text-gray-800'
                        ][$pengeluaran->jenis_pengeluaran] ?? 'bg-gray-50 border-gray-200 text-gray-800';
                    @endphp
                    <div class="flex justify-between items-center p-3 {{ $color_class }} rounded-lg border">
                        <span class="font-medium capitalize">{{ $pengeluaran->jenis_pengeluaran }}</span>
                        <span class="font-bold">Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                    
                    @if($pengeluaran_per_kategori->isEmpty())
                    <div class="text-center py-8">
                        <i class="fas fa-receipt text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Tidak ada data pengeluaran</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Financial Report -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Laporan Keuangan Detail</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemasukan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengeluaran</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Pemasukan Rows -->
                        @foreach($penjualan_bulan_ini as $penjualan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $penjualan->tanggal->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                Penjualan ke {{ $penjualan->pembeli }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 capitalize">
                                    {{ $penjualan->tujuan_jual }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                Rp {{ number_format($penjualan->total_pemasukan, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-</td>
                        </tr>
                        @endforeach

                        <!-- Pengeluaran Rows -->
                        @php
                            $pengeluaran_list = \App\Models\Pengeluaran::whereMonth('tanggal', \Carbon\Carbon::now()->month)
                                ->whereYear('tanggal', \Carbon\Carbon::now()->year)
                                ->orderBy('tanggal', 'desc')
                                ->limit(10)
                                ->get();
                        @endphp
                        
                        @foreach($pengeluaran_list as $pengeluaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengeluaran->tanggal->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $pengeluaran->keterangan ?: 'Pengeluaran ' . $pengeluaran->jenis_pengeluaran }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 capitalize">
                                    {{ $pengeluaran->jenis_pengeluaran }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                Rp {{ number_format($pengeluaran->total_biaya, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    // Implement PDF export functionality here
    alert('Fitur export PDF akan diimplementasikan');
}
</script>
@endsection
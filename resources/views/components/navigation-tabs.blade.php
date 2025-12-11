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
@extends('layouts.app')

@section('title', 'Laporan Masalah - Owner')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Masalah</h1>
        <p class="text-gray-600">Laporan masalah dari karyawan yang diteruskan oleh mandor dan laporan langsung dari mandor</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-flag text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $laporan_masalah->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Perlu Tindakan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $laporan_masalah->where('status_masalah', '!=', 'selesai')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-user-shield text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Dari Mandor</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $laporan_masalah->where('pelapor.role', 'admin')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $laporan_masalah->where('status_masalah', 'selesai')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Laporan Masalah</h3>
            <div class="flex flex-wrap gap-2">
                <select id="filterStatus" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="semua">Semua Status</option>
                    <option value="dilaporkan">Dilaporkan</option>
                    <option value="dalam_penanganan">Dalam Penanganan</option>
                    <option value="selesai">Selesai</option>
                </select>
                <select id="filterSumber" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="semua">Semua Sumber</option>
                    <option value="mandor">Dari Mandor</option>
                    <option value="karyawan">Dari Karyawan</option>
                </select>
                <button id="resetFilter" class="bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                    <i class="fas fa-refresh mr-1"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Laporan Masalah List -->
    <div class="bg-white rounded-2xl shadow-lg">
        <div class="p-6">
            @if($laporan_masalah->count() > 0)
            <div class="space-y-4" id="laporanContainer">
                @foreach($laporan_masalah as $laporan)
                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow laporan-item" 
                     data-status="{{ $laporan->status_masalah }}"
                     data-sumber="{{ $laporan->pelapor->role == 'admin' ? 'mandor' : 'karyawan' }}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($laporan->jenis_masalah == 'Serangan Hama') bg-red-100 text-red-800
                                @elseif($laporan->jenis_masalah == 'Kerusakan Alat') bg-yellow-100 text-yellow-800
                                @elseif($laporan->jenis_masalah == 'Cuaca Buruk') bg-blue-100 text-blue-800
                                @elseif($laporan->jenis_masalah == 'Kemalingan') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                <i class="fas 
                                    @if($laporan->jenis_masalah == 'Serangan Hama') fa-bug
                                    @elseif($laporan->jenis_masalah == 'Kerusakan Alat') fa-tools
                                    @elseif($laporan->jenis_masalah == 'Cuaca Buruk') fa-cloud-rain
                                    @elseif($laporan->jenis_masalah == 'Kemalingan') fa-shield-alt
                                    @else fa-exclamation-circle @endif mr-1">
                                </i>
                                {{ $laporan->jenis_masalah }}
                            </span>
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($laporan->tingkat_keparahan == 'berat') bg-red-100 text-red-800
                                @elseif($laporan->tingkat_keparahan == 'sedang') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                <i class="fas 
                                    @if($laporan->tingkat_keparahan == 'berat') fa-exclamation-triangle
                                    @elseif($laporan->tingkat_keparahan == 'sedang') fa-exclamation-circle
                                    @else fa-info-circle @endif mr-1">
                                </i>
                                {{ ucfirst($laporan->tingkat_keparahan) }}
                            </span>
                            
                            @if($laporan->pelapor->role == 'admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-user-shield mr-1"></i> Laporan Mandor
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-share mr-1"></i> Diteruskan
                            </span>
                            @endif

                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($laporan->status_masalah == 'selesai') bg-green-100 text-green-800
                                @elseif($laporan->status_masalah == 'dalam_penanganan') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                <i class="fas 
                                    @if($laporan->status_masalah == 'selesai') fa-check-circle
                                    @elseif($laporan->status_masalah == 'dalam_penanganan') fa-clock
                                    @else fa-flag @endif mr-1">
                                </i>
                                {{ ucfirst(str_replace('_', ' ', $laporan->status_masalah)) }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-500">{{ $laporan->tanggal->format('d M Y') }}</span>
                    </div>
                    
                    <h4 class="font-semibold text-gray-800 mb-3 text-lg">{{ $laporan->deskripsi }}</h4>
                    
                    <!-- Informasi Pelapor dan Penerus -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 text-sm font-medium">
                                    {{ substr($laporan->pelapor->nama_lengkap ?? 'Unknown', 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $laporan->pelapor->nama_lengkap ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($laporan->pelapor->role == 'admin')
                                    <i class="fas fa-user-shield mr-1"></i>Mandor
                                    @else
                                    <i class="fas fa-user mr-1"></i>Karyawan
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($laporan->penanda && $laporan->pelapor->role != 'admin')
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 text-sm font-medium">
                                    {{ substr($laporan->penanda->nama_lengkap ?? 'M', 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $laporan->penanda->nama_lengkap ?? 'Mandor' }}</div>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-share mr-1"></i>Diteruskan oleh
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Tindakan yang sudah dilakukan (jika ada) -->
                    @if($laporan->tindakan)
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clipboard-check mr-1"></i>Tindakan yang dilakukan:
                        </p>
                        <p class="text-sm text-gray-600">{{ $laporan->tindakan }}</p>
                        @if($laporan->ditangani_oleh)
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-user-check mr-1"></i>
                            Ditangani oleh: {{ $laporan->penangan->nama_lengkap ?? 'Owner' }}
                            @if($laporan->tanggal_selesai)
                            pada {{ $laporan->tanggal_selesai->format('d M Y') }}
                            @endif
                        </p>
                        @endif
                    </div>
                    @endif

                    <!-- Status dan Tindakan -->
                    <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-100">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            @if($laporan->created_at)
                                Dilaporkan {{ $laporan->created_at->diffForHumans() }}
                            @else
                                Dilaporkan baru saja
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            @if($laporan->status_masalah != 'selesai')
                            <button onclick="showTindakanModal({{ $laporan->id_masalah }})" 
                                class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition flex items-center">
                                <i class="fas fa-check mr-2"></i>
                                Tandai Selesai
                            </button>
                            @endif
                            <button onclick="showDetailModal({{ $laporan->id_masalah }})" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition flex items-center">
                                <i class="fas fa-eye mr-2"></i>
                                Detail
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Empty State untuk Filter -->
            <div id="emptyFilter" class="hidden text-center py-12">
                <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada laporan yang sesuai</h3>
                <p class="text-gray-500">Coba ubah filter pencarian Anda</p>
            </div>

            @else
            <div class="text-center py-12">
                <i class="fas fa-check-circle text-green-400 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada laporan masalah</h3>
                <p class="text-gray-500">Semua laporan sudah ditangani dengan baik</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal untuk Tindakan -->
<div id="tindakanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tandai Laporan sebagai Selesai</h3>
                <button onclick="closeTindakanModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="tindakanForm">
                @csrf
                <input type="hidden" name="id_masalah" id="modal_id_masalah">
                <div class="mb-4">
                    <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clipboard-list mr-1"></i>Tindakan yang Dilakukan
                    </label>
                    <textarea name="tindakan" id="tindakan" rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Jelaskan tindakan yang telah dilakukan untuk menyelesaikan masalah ini..."
                        required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTindakanModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition flex items-center">
                        <i class="fas fa-check mr-2"></i>
                        Simpan Tindakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Detail -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Laporan Masalah</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="detailContent" class="space-y-4">
                <!-- Detail akan diisi via JavaScript -->
            </div>
            <div class="flex justify-end mt-6">
                <button onclick="closeDetailModal()" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification -->
<div id="notification" class="hidden fixed top-4 right-4 p-4 rounded-lg shadow-lg z-[100]"></div>

@push('scripts')
<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterStatus = document.getElementById('filterStatus');
    const filterSumber = document.getElementById('filterSumber');
    const resetFilter = document.getElementById('resetFilter');
    const laporanItems = document.querySelectorAll('.laporan-item');
    const laporanContainer = document.getElementById('laporanContainer');
    const emptyFilter = document.getElementById('emptyFilter');

    function applyFilters() {
        const statusValue = filterStatus.value;
        const sumberValue = filterSumber.value;
        let visibleCount = 0;

        laporanItems.forEach(item => {
            const itemStatus = item.getAttribute('data-status');
            const itemSumber = item.getAttribute('data-sumber');
            
            const statusMatch = statusValue === 'semua' || itemStatus === statusValue;
            const sumberMatch = sumberValue === 'semua' || itemSumber === sumberValue;
            
            if (statusMatch && sumberMatch) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (visibleCount === 0 && laporanItems.length > 0) {
            emptyFilter.classList.remove('hidden');
            laporanContainer.classList.add('hidden');
        } else {
            emptyFilter.classList.add('hidden');
            laporanContainer.classList.remove('hidden');
        }
    }

    if (filterStatus && filterSumber) {
        filterStatus.addEventListener('change', applyFilters);
        filterSumber.addEventListener('change', applyFilters);
        
        resetFilter.addEventListener('click', function() {
            filterStatus.value = 'semua';
            filterSumber.value = 'semua';
            applyFilters();
        });

        applyFilters();
    }

    // Setup form submit handler
    const tindakanForm = document.getElementById('tindakanForm');
    if (tindakanForm) {
        tindakanForm.addEventListener('submit', handleTindakanSubmit);
    }
});

// Modal functions - PENTING: Fungsi harus di global scope
window.showTindakanModal = function(id) {
    const modal = document.getElementById('tindakanModal');
    const modalIdInput = document.getElementById('modal_id_masalah');
    
    if (modal && modalIdInput) {
        modalIdInput.value = id;
        modal.classList.remove('hidden');
    } else {
        console.error('Modal elements not found');
    }
}

window.closeTindakanModal = function() {
    const modal = document.getElementById('tindakanModal');
    const form = document.getElementById('tindakanForm');
    
    if (modal) {
        modal.classList.add('hidden');
    }
    if (form) {
        form.reset();
    }
}

window.showDetailModal = function(id) {
    const modal = document.getElementById('detailModal');
    const detailContent = document.getElementById('detailContent');
    
    if (!modal || !detailContent) {
        console.error('Detail modal elements not found');
        return;
    }

    // Tampilkan loading
    detailContent.innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-blue-500 text-3xl mb-3"></i>
            <p class="text-gray-600">Memuat detail laporan...</p>
        </div>
    `;
    modal.classList.remove('hidden');
    
    // Fetch detail
    fetch(`/owner/laporan-masalah/${id}/detail`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderDetailContent(data.laporan);
            } else {
                showErrorDetail();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorDetail();
        });
}

function renderDetailContent(laporan) {
    const detailContent = document.getElementById('detailContent');
    
    detailContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Utama -->
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Jenis Masalah</h4>
                    <p class="mt-1 text-sm text-gray-900">${laporan.jenis_masalah}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Tingkat Keparahan</h4>
                    <p class="mt-1 text-sm text-gray-900 capitalize">${laporan.tingkat_keparahan}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Status</h4>
                    <div class="mt-1">${getStatusBadge(laporan.status_masalah)}</div>
                </div>
            </div>
            
            <!-- Informasi Pelapor -->
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Pelapor</h4>
                    <p class="mt-1 text-sm text-gray-900">${laporan.pelapor.nama_lengkap}</p>
                    <p class="text-xs text-gray-500">${laporan.pelapor.role === 'admin' ? 'Mandor' : 'Karyawan'}</p>
                </div>
                ${laporan.penanda ? `
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Diteruskan Oleh</h4>
                    <p class="mt-1 text-sm text-gray-900">${laporan.penanda.nama_lengkap}</p>
                    <p class="text-xs text-gray-500">Mandor</p>
                </div>
                ` : ''}
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Tanggal Laporan</h4>
                    <p class="mt-1 text-sm text-gray-900">${new Date(laporan.tanggal).toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    })}</p>
                </div>
            </div>
        </div>
        
        <!-- Deskripsi Masalah -->
        <div class="border-t pt-4">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Deskripsi Masalah</h4>
            <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">${laporan.deskripsi}</p>
        </div>
        
        <!-- Tindakan (jika ada) -->
        ${laporan.tindakan ? `
        <div class="border-t pt-4">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Tindakan yang Dilakukan</h4>
            <p class="text-sm text-gray-900 bg-green-50 p-3 rounded-lg">${laporan.tindakan}</p>
            ${laporan.penangan ? `
            <p class="text-xs text-gray-500 mt-2">
                Ditangani oleh: ${laporan.penangan.nama_lengkap}
                ${laporan.tanggal_selesai ? `pada ${new Date(laporan.tanggal_selesai).toLocaleDateString('id-ID')}` : ''}
            </p>
            ` : ''}
        </div>
        ` : ''}
    `;
}

function showErrorDetail() {
    document.getElementById('detailContent').innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-3"></i>
            <h4 class="text-lg font-medium text-gray-900 mb-2">Gagal Memuat Detail</h4>
            <p class="text-gray-600">Terjadi kesalahan saat memuat data laporan.</p>
            <button onclick="closeDetailModal()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                Tutup
            </button>
        </div>
    `;
}

window.closeDetailModal = function() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function getStatusBadge(status) {
    const statusConfig = {
        'dilaporkan': { color: 'yellow', text: 'Dilaporkan', icon: 'fa-flag' },
        'dalam_penanganan': { color: 'blue', text: 'Dalam Penanganan', icon: 'fa-clock' },
        'selesai': { color: 'green', text: 'Selesai', icon: 'fa-check-circle' }
    };
    
    const config = statusConfig[status] || statusConfig['dilaporkan'];
    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${config.color}-100 text-${config.color}-800">
        <i class="fas ${config.icon} mr-1"></i>${config.text}
    </span>`;
}

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    if (!notification) return;
    
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-[100] ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    notification.classList.remove('hidden');
    
    setTimeout(() => {
        notification.classList.add('hidden');
    }, 5000);
}

// Handle form submission
// Handle form submission
async function handleTindakanSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        
        const idMasalah = document.getElementById('modal_id_masalah').value;
        const tindakan = document.getElementById('tindakan').value;
        
        // Validasi
        if (!tindakan.trim()) {
            throw new Error('Tindakan harus diisi');
        }
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                        || document.querySelector('input[name="_token"]')?.value;
        
        if (!csrfToken) {
            throw new Error('CSRF token tidak ditemukan');
        }
        
        const response = await fetch('/owner/laporan-masalah/mark-solved', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id_masalah: idMasalah,
                tindakan: tindakan
            })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            // Handle HTTP error status
            throw new Error(data.message || `HTTP error! status: ${response.status}`);
        }
        
        if (data.success) {
            showNotification(data.message, 'success');
            closeTindakanModal();
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
        
    } catch (error) {
        console.error('Error:', error);
        
        let errorMessage = error.message;
        
        // Handle specific error cases
        if (error.message.includes('419')) {
            errorMessage = 'Session expired. Silakan refresh halaman dan coba lagi.';
        } else if (error.message.includes('404')) {
            errorMessage = 'Endpoint tidak ditemukan. Periksa URL.';
        } else if (error.message.includes('500')) {
            errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.';
        }
        
        showNotification(errorMessage, 'error');
        
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}
document.addEventListener('click', function(e) {
    if (e.target.id === 'tindakanModal') {
        closeTindakanModal();
    }
    if (e.target.id === 'detailModal') {
        closeDetailModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTindakanModal();
        closeDetailModal();
    }
});
</script>
@endpush

@endsection
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Masalah - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .status-badge { 
            display: inline-flex; 
            align-items: center; 
            padding: 0.25rem 0.75rem; 
            border-radius: 9999px; 
            font-size: 0.75rem; 
            font-weight: 500; 
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-green-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-green-200 hover:text-white mr-4">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <i class="fas fa-tractor text-xl mr-3"></i>
                    <span class="font-semibold text-xl">Laporan Masalah Karyawan</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-green-200">Admin</span>
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

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Kelola Laporan Masalah Karyawan</h1>
            <p class="text-gray-600">Tinjau dan tangani laporan masalah dari karyawan</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl card-shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Filter:</span>
                    <select id="filterStatus" onchange="filterLaporan()" 
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="semua">Semua Status</option>
                        <option value="dilaporkan">Menunggu</option>
                        <option value="dalam_penanganan">Ditangani</option>
                        <option value="selesai">Selesai</option>
                    </select>
                    <select id="filterKeparahan" onchange="filterLaporan()"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="semua">Semua Tingkat</option>
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="berat">Berat</option>
                    </select>
                </div>
                <div class="text-sm text-gray-600">
                    <i class="fas fa-users mr-1"></i>
                    Menampilkan laporan dari karyawan saja
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">
                    {{ $laporan_masalah->count() }}
                </div>
                <div class="text-sm text-gray-600">Total Laporan Karyawan</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-orange-600">
                    {{ $laporan_masalah->where('status_masalah', 'dilaporkan')->count() }}
                </div>
                <div class="text-sm text-gray-600">Menunggu</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">
                    {{ $laporan_masalah->where('status_masalah', 'dalam_penanganan')->count() }}
                </div>
                <div class="text-sm text-gray-600">Ditangani</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ $laporan_masalah->where('status_masalah', 'selesai')->count() }}
                </div>
                <div class="text-sm text-gray-600">Selesai</div>
            </div>
        </div>

        <!-- Problems List -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Laporan Masalah Karyawan</h3>
                    <span class="text-sm text-gray-500">
                        {{ $laporan_masalah->count() }} laporan
                    </span>
                </div>
            </div>
            <div class="p-6">
                @if($laporan_masalah->count() > 0)
                    <div class="space-y-4" id="laporanList">
                        @foreach($laporan_masalah as $laporan)
                        <div class="laporan-item border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors" 
                             data-status="{{ $laporan->status_masalah }}"
                             data-keparahan="{{ $laporan->tingkat_keparahan }}">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="font-semibold text-gray-900">{{ $laporan->pelapor->nama_lengkap }}</span>
                                        <span class="bg-green-100 text-green-800 status-badge">
                                            <i class="fas fa-user-tie mr-1"></i>Karyawan
                                        </span>
                                        <span class="status-badge 
                                            @if($laporan->status_masalah == 'dilaporkan') bg-yellow-100 text-yellow-800
                                            @elseif($laporan->status_masalah == 'dalam_penanganan') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @if($laporan->status_masalah == 'dilaporkan') Menunggu
                                            @elseif($laporan->status_masalah == 'dalam_penanganan') Ditangani
                                            @else Selesai @endif
                                        </span>
                                        @if($laporan->tingkat_keparahan == 'berat')
                                        <span class="status-badge bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Berat
                                        </span>
                                        @elseif($laporan->tingkat_keparahan == 'sedang')
                                        <span class="status-badge bg-orange-100 text-orange-800">
                                            <i class="fas fa-exclamation-circle mr-1"></i>Sedang
                                        </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <i class="far fa-calendar mr-1"></i>
                                        {{ $laporan->tanggal->translatedFormat('d F Y') }}
                                        • {{ $laporan->jenis_masalah }}
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    @if($laporan->status_masalah == 'dilaporkan')
                                    <button onclick="teruskanKeOwner({{ $laporan->id_masalah }})" 
                                            class="bg-orange-500 text-white px-3 py-1 rounded text-sm hover:bg-orange-600 transition-colors">
                                        <i class="fas fa-share mr-1"></i>Teruskan
                                    </button>
                                    <button onclick="showHandleModal({{ $laporan->id_masalah }})"
                                            class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-tools mr-1"></i>Tangani
                                    </button>
                                    @elseif($laporan->status_masalah == 'dalam_penanganan')
                                    <button onclick="showHandleModal({{ $laporan->id_masalah }})"
                                            class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition-colors">
                                        <i class="fas fa-check mr-1"></i>Selesaikan
                                    </button>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-gray-700">{{ $laporan->deskripsi }}</p>
                            </div>

                            @if($laporan->tindakan)
                            <div class="bg-blue-50 border border-blue-200 rounded p-3">
                                <div class="font-medium text-blue-800 mb-1">
                                    <i class="fas fa-tools mr-1"></i>Tindakan yang diambil:
                                </div>
                                <p class="text-blue-700 text-sm">{{ $laporan->tindakan }}</p>
                                @if($laporan->penangan)
                                <div class="text-xs text-blue-600 mt-1">
                                    Ditangani oleh: {{ $laporan->penangan->nama_lengkap }}
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-users text-3xl mb-3 text-green-500"></i>
                        <p class="text-lg font-medium mb-2">Tidak ada laporan masalah dari karyawan</p>
                        <p class="text-sm">Semua laporan masalah dari karyawan akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Handle Problem Modal -->
    <div id="handleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl w-full max-w-2xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tangani Laporan Masalah Karyawan</h3>
            </div>
            <form id="handleForm" class="p-6">
                @csrf
                <input type="hidden" id="handle_masalah_id" name="masalah_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tindakan yang Diambil</label>
                    <textarea name="tindakan" id="tindakan" rows="4" required 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Jelaskan tindakan yang akan diambil..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Penanganan</label>
                    <select name="status_masalah" id="status_masalah" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="dalam_penanganan">Dalam Penanganan</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeHandleModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan Tindakan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function filterLaporan() {
            const statusFilter = document.getElementById('filterStatus').value;
            const keparahanFilter = document.getElementById('filterKeparahan').value;
            const items = document.querySelectorAll('.laporan-item');
            
            let visibleCount = 0;
            
            items.forEach(item => {
                const status = item.getAttribute('data-status');
                const keparahan = item.getAttribute('data-keparahan');
                
                const statusMatch = statusFilter === 'semua' || status === statusFilter;
                const keparahanMatch = keparahanFilter === 'semua' || keparahan === keparahanFilter;
                
                if (statusMatch && keparahanMatch) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Update counter
            const counter = document.querySelector('.text-sm.text-gray-500');
            if (counter) {
                counter.textContent = `${visibleCount} laporan`;
            }
        }

        function teruskanKeOwner(masalahId) {
            if (confirm('Teruskan laporan karyawan ini ke Owner?')) {
                fetch(`/admin/laporan-masalah/${masalahId}/teruskan-owner`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan: ' + error);
                });
            }
        }

        function showHandleModal(masalahId) {
            document.getElementById('handle_masalah_id').value = masalahId;
            document.getElementById('handleModal').classList.remove('hidden');
        }

        function closeHandleModal() {
            document.getElementById('handleModal').classList.add('hidden');
            document.getElementById('handleForm').reset();
        }

        // Handle form submission
        document.getElementById('handleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const masalahId = document.getElementById('handle_masalah_id').value;
            const formData = new FormData(this);
            
            fetch(`/admin/laporan-masalah/${masalahId}/tangani`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error);
            });
        });

        // Close modal when clicking outside
        document.getElementById('handleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeHandleModal();
            }
        });
    </script>
</body>
</html>
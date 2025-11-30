<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengeluaran - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .tab-active { border-bottom: 2px solid #3b82f6; color: #3b82f6; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <nav class="bg-green-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-green-200 hover:text-white mr-4">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <i class="fas fa-history text-xl mr-3"></i>
                    <span class="font-semibold text-xl">Riwayat Pengeluaran</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-green-200">Admin</span>
                    <a href="{{ route('admin.input-pengeluaran') }}" class="bg-green-600 hover:bg-green-500 px-3 py-1 rounded transition-colors">
                        <i class="fas fa-plus mr-1"></i>Tambah
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Filter Section -->
        <div class="bg-white rounded-xl card-shadow mb-6 p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" id="startDate" value="{{ $start_date }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" id="endDate" value="{{ $end_date }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pengeluaran</label>
                        <select id="jenisFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="semua" {{ $jenis_filter == 'semua' ? 'selected' : '' }}>Semua Jenis</option>
                            <option value="pupuk" {{ $jenis_filter == 'pupuk' ? 'selected' : '' }}>Pupuk</option>
                            <option value="transportasi" {{ $jenis_filter == 'transportasi' ? 'selected' : '' }}>Transportasi</option>
                            <option value="perawatan" {{ $jenis_filter == 'perawatan' ? 'selected' : '' }}>Perawatan</option>
                            <option value="gaji" {{ $jenis_filter == 'gaji' ? 'selected' : '' }}>Gaji</option>
                            <option value="lainnya" {{ $jenis_filter == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button id="filterBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <button id="resetBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl card-shadow p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary['total'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Periode terpilih</p>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Pupuk</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary['pupuk'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-seedling text-green-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">{{ $summary['total'] > 0 ? number_format(($summary['pupuk'] / $summary['total']) * 100, 1) : 0 }}% dari total</p>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Transportasi</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary['transportasi'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-truck text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">{{ $summary['total'] > 0 ? number_format(($summary['transportasi'] / $summary['total']) * 100, 1) : 0 }}% dari total</p>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Perawatan & Lainnya</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary['perawatan'] + $summary['lainnya'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-tools text-purple-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">{{ $summary['total'] > 0 ? number_format((($summary['perawatan'] + $summary['lainnya']) / $summary['total']) * 100, 1) : 0 }}% dari total</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl card-shadow mb-6">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Biaya</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pencatat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tbody-pengeluaran">
                            @foreach($pengeluaran as $item)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $item->jenis_pengeluaran == 'pupuk' ? 'bg-green-100 text-green-800' : 
                                           ($item->jenis_pengeluaran == 'transportasi' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($item->jenis_pengeluaran == 'perawatan' ? 'bg-blue-100 text-blue-800' : 
                                           ($item->jenis_pengeluaran == 'gaji' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))) }}">
                                        {{ ucfirst($item->jenis_pengeluaran) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($item->total_biaya, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->pencatat->nama_lengkap ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <button onclick="showDetail({{ $item->id_pengeluaran }}, '{{ $item->jenis_pengeluaran }}')" class="text-blue-600 hover:text-blue-900 mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="deleteItem({{ $item->id_pengeluaran }})" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($pengeluaran->count() == 0)
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data pengeluaran
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-700">
                        Menampilkan {{ $pengeluaran->firstItem() ?? 0 }} - {{ $pengeluaran->lastItem() ?? 0 }} dari {{ $pengeluaran->total() }} data
                    </div>
                    <div>
                        {{ $pengeluaran->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Detail Pengeluaran</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="mt-4" id="modalContent">
                    <!-- Detail akan diisi oleh JavaScript -->
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="hidden fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50"></div>

    <script>
        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        // Format date
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID');
        }

        // Show detail modal dengan error handling yang lebih baik
        async function showDetail(id, jenis) {
            try {
                console.log('Loading detail for:', { id, jenis });
                
                // Show loading state
                document.getElementById('modalContent').innerHTML = `
                    <div class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2">Memuat detail...</span>
                    </div>
                `;
                document.getElementById('detailModal').classList.remove('hidden');

                const response = await fetch(`/admin/api/pengeluaran/detail/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    let errorMessage = `HTTP error! status: ${response.status}`;
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        // Jika response bukan JSON
                    }
                    throw new Error(errorMessage);
                }

                const data = await response.json();
                console.log('Response data:', data);
                
                if (!data.success) {
                    throw new Error(data.message || 'Gagal memuat data');
                }

                const item = data.data;
                renderModalContent(item, jenis);
                
            } catch (error) {
                console.error('Error loading detail:', error);
                document.getElementById('modalContent').innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                        <p class="font-semibold">Gagal memuat detail</p>
                        <p class="text-sm mt-2">${error.message}</p>
                        <button onclick="showDetail(${id}, '${jenis}')" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-redo mr-2"></i>Coba Lagi
                        </button>
                    </div>
                `;
            }
        }

        // Function terpisah untuk render content
        function renderModalContent(item, jenis) {
            let content = '';
            
            // Validasi data exists
            if (!item) {
                content = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-exclamation-circle text-4xl mb-4"></i>
                        <p>Data tidak ditemukan</p>
                    </div>
                `;
            } else if (jenis === 'pupuk' && item.pupuk) {
                content = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal</p>
                            <p class="font-medium">${formatDate(item.tanggal)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Pupuk</p>
                            <p class="font-medium">${item.pupuk.jenis_pupuk || '-'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jumlah</p>
                            <p class="font-medium">${item.pupuk.jumlah ? item.pupuk.jumlah + ' kg' : '-'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Harga Satuan</p>
                            <p class="font-medium">${item.pupuk.harga_satuan ? formatCurrency(item.pupuk.harga_satuan) : '-'}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Total Harga</p>
                            <p class="font-medium text-lg">${formatCurrency(item.total_biaya)}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Keterangan</p>
                            <p class="font-medium">${item.keterangan || '-'}</p>
                        </div>
                    </div>
                `;
            } else if (jenis === 'transportasi' && item.transportasi) {
                content = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal</p>
                            <p class="font-medium">${formatDate(item.tanggal)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tujuan</p>
                            <p class="font-medium">${item.transportasi.tujuan || '-'}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Biaya Transportasi</p>
                            <p class="font-medium text-lg">${formatCurrency(item.total_biaya)}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Keterangan</p>
                            <p class="font-medium">${item.keterangan || '-'}</p>
                        </div>
                    </div>
                `;
            } else if (jenis === 'perawatan' && item.perawatan) {
                content = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal</p>
                            <p class="font-medium">${formatDate(item.tanggal)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Perawatan</p>
                            <p class="font-medium">${item.perawatan.jenis_perawatan || '-'}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Biaya Perawatan</p>
                            <p class="font-medium text-lg">${formatCurrency(item.total_biaya)}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Keterangan</p>
                            <p class="font-medium">${item.keterangan || '-'}</p>
                        </div>
                    </div>
                `;
            } else if (jenis === 'gaji' && item.gaji) {
                content = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal</p>
                            <p class="font-medium">${formatDate(item.tanggal)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Periode</p>
                            <p class="font-medium">${item.gaji.periode || '-'}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Total Gaji</p>
                            <p class="font-medium text-lg">${formatCurrency(item.total_biaya)}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Keterangan</p>
                            <p class="font-medium">${item.keterangan || '-'}</p>
                        </div>
                    </div>
                `;
            } else {
                // Fallback untuk data umum
                content = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal</p>
                            <p class="font-medium">${formatDate(item.tanggal)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Pengeluaran</p>
                            <p class="font-medium">${item.jenis_pengeluaran ? item.jenis_pengeluaran.charAt(0).toUpperCase() + item.jenis_pengeluaran.slice(1) : '-'}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Total Biaya</p>
                            <p class="font-medium text-lg">${formatCurrency(item.total_biaya)}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Keterangan</p>
                            <p class="font-medium">${item.keterangan || '-'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pencatat</p>
                            <p class="font-medium">${item.pencatat ? (item.pencatat.nama_lengkap || '-') : '-'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Input</p>
                            <p class="font-medium">${formatDate(item.created_at)}</p>
                        </div>
                    </div>
                `;
            }
            
            document.getElementById('modalContent').innerHTML = content;
        }

        // Close modal
        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Delete item
        async function deleteItem(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                try {
                    const response = await fetch(`/admin/api/pengeluaran/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (!response.ok) throw new Error('Gagal menghapus data');

                    const result = await response.json();
                    
                    showNotification('Data berhasil dihapus', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                    
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Gagal menghapus data: ' + error.message, 'error');
                }
            }
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            notification.classList.remove('hidden');
            
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 5000);
        }

        // Filter functionality
        document.getElementById('filterBtn').addEventListener('click', function() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const jenis = document.getElementById('jenisFilter').value;
            
            let url = '/admin/riwayat-pengeluaran?';
            const params = [];
            
            if (startDate) params.push(`start_date=${startDate}`);
            if (endDate) params.push(`end_date=${endDate}`);
            if (jenis !== 'semua') params.push(`jenis=${jenis}`);
            
            window.location.href = url + params.join('&');
        });

        document.getElementById('resetBtn').addEventListener('click', function() {
            window.location.href = '/admin/riwayat-pengeluaran';
        });

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target.id === 'detailModal') {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>
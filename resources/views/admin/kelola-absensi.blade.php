<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Absensi - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
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
                    <span class="font-semibold text-xl">Kelola Absensi</span>
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
            <h1 class="text-2xl font-bold text-gray-900">Kelola Absensi Karyawan</h1>
            <p class="text-gray-600">Kelola data kehadiran karyawan</p>
        </div>

        <!-- Date Filter -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-6">
            <form id="filterForm" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
                    <input type="date" id="filterDate" value="{{ $selected_tanggal }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <button type="button" onclick="resetFilter()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </button>
            </form>
        </div>

        <!-- Attendance Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $absensi->where('status_kehadiran', 'Hadir')->count() }}</div>
                <div class="text-sm text-gray-600">Hadir</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $absensi->where('status_kehadiran', 'Izin')->count() }}</div>
                <div class="text-sm text-gray-600">Izin</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $absensi->where('status_kehadiran', 'Sakit')->count() }}</div>
                <div class="text-sm text-gray-600">Sakit</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $karyawan_aktif->count() - $absensi->count() }}</div>
                <div class="text-sm text-gray-600">Belum Absen</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance List -->
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Absensi - {{ \Carbon\Carbon::parse($selected_tanggal)->translatedFormat('d F Y') }}</h3>
                </div>
                <div class="p-6">
                    @if($absensi->count() > 0)
                        <div class="space-y-3">
                            @foreach($absensi as $absen)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold">
                                        {{ strtoupper(substr($absen->user->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-semibold text-gray-900">{{ $absen->user->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-600 capitalize">{{ $absen->user->jabatan }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="status-badge 
                                        @if($absen->status_kehadiran == 'Hadir') bg-green-100 text-green-800
                                        @elseif($absen->status_kehadiran == 'Izin') bg-blue-100 text-blue-800
                                        @elseif($absen->status_kehadiran == 'Sakit') bg-orange-100 text-orange-800
                                        @elseif($absen->status_kehadiran == 'Libur_Agama') bg-purple-100 text-purple-800
                                        @else bg-red-100 text-red-800 @endif 
                                        px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $absen->status_kehadiran }}
                                    </span>
                                    @if($absen->jam_masuk)
                                    <div class="text-xs text-gray-500 mt-1">{{ $absen->jam_masuk }}</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-clipboard-check text-3xl mb-3 text-gray-400"></i>
                            <p>Belum ada absensi untuk tanggal ini</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Input Absensi -->
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Input Absensi</h3>
                </div>
                <div class="p-6">
                    <form id="absensiForm">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $selected_tanggal }}">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                            <select name="id_user" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Karyawan</option>
                                @foreach($karyawan_aktif as $karyawan)
                                <option value="{{ $karyawan->id_user }}">{{ $karyawan->nama_lengkap }} - {{ $karyawan->jabatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                            <select name="status_kehadiran" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpha">Alpha</option>
                                <option value="Libur_Agama">Libur Agama</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Masuk</label>
                                <input type="time" name="jam_masuk" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pulang</label>
                                <input type="time" name="jam_pulang" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea name="keterangan" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Keterangan tambahan..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <i class="fas fa-save mr-2"></i>Simpan Absensi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter form submission
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const date = document.getElementById('filterDate').value;
            window.location.href = `{{ route('admin.kelola-absensi') }}?tanggal=${date}`;
        });

        function resetFilter() {
            window.location.href = '{{ route("admin.kelola-absensi") }}';
        }

        // Absensi form submission
        document.getElementById('absensiForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("admin.input-absensi") }}', {
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

        // Set today's date as default if no date selected
        @if(!request()->has('tanggal'))
        document.getElementById('filterDate').value = new Date().toISOString().split('T')[0];
        @endif
    </script>
</body>
</html>
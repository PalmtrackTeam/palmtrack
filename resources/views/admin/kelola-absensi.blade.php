<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Absensi - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .card-shadow { 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 
                        0 2px 4px -1px rgba(0, 0, 0, 0.06); 
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .table-row:hover {
            background-color: #f9fafb;
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
                    <span class="font-semibold text-xl">Kelola Absensi</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-green-200">{{ auth()->user()->nama_lengkap }}</span>
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
            <p class="text-gray-600">Kelola data kehadiran karyawan - {{ \Carbon\Carbon::parse($selected_tanggal)->translatedFormat('l, d F Y') }}</p>
        </div>

        <!-- Date Filter -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-6">
            <form id="filterForm" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
                    <input type="date" id="filterDate" value="{{ $selected_tanggal }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <button type="button" onclick="resetFilter()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </button>
            </form>
        </div>

        <!-- Attendance Summary -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ $statistik->hadir ?? 0 }}
                </div>
                <div class="text-sm text-gray-600">Hadir</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">
                    {{ $statistik->izin ?? 0 }}
                </div>
                <div class="text-sm text-gray-600">Izin</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-orange-600">
                    {{ $statistik->sakit ?? 0 }}
                </div>
                <div class="text-sm text-gray-600">Sakit</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-red-600">
                    {{ $statistik->alpha ?? 0 }}
                </div>
                <div class="text-sm text-gray-600">Alpha</div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">
                    {{ $statistik->belum_absen ?? 0 }}
                </div>
                <div class="text-sm text-gray-600">Belum Absen</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Attendance List -->
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list-check mr-2"></i>
                        Daftar Absensi
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Total: {{ $statistik->total_absen_hari_ini ?? 0 }} dari {{ $statistik->total_karyawan ?? 0 }} karyawan
                    </p>
                </div>
                <div class="p-6">
                    @if($absensi->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 border-b">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jam Masuk</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status Telat</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absensi as $absen)
                                    <tr class="table-row border-b hover:bg-gray-50 transition-colors" id="row-{{ $absen->id_absensi }}">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">{{ $absen->nama_lengkap }}</div>
                                            <div class="text-xs text-gray-500">{{ $absen->role }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $statusColors = [
                                                    'Hadir' => 'bg-green-100 text-green-800',
                                                    'Izin' => 'bg-blue-100 text-blue-800',
                                                    'Sakit' => 'bg-orange-100 text-orange-800',
                                                    'Alpha' => 'bg-red-100 text-red-800',
                                                    'Libur_Agama' => 'bg-purple-100 text-purple-800'
                                                ];
                                            @endphp
                                            <span class="status-badge {{ $statusColors[$absen->status_kehadiran] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $absen->status_kehadiran }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $absen->jam_masuk ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                // Hitung status telat manual
                                                $status_telat = '-';
                                                $menit_telat = 0;
                                                
                                                if ($absen->jam_masuk && $absen->status_kehadiran == 'Hadir') {
                                                    $jam_standar = '07:00:00';
                                                    $menit_telat = max(0, 
                                                        (strtotime($absen->jam_masuk) - strtotime($jam_standar)) / 60
                                                    );
                                                    $status_telat = $menit_telat > 0 ? 'Telat' : 'Tepat Waktu';
                                                }
                                            @endphp
                                            
                                            @if($status_telat == 'Telat')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Telat {{ round($menit_telat) }}m
                                                </span>
                                            @elseif($status_telat == 'Tepat Waktu')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Tepat Waktu
                                                </span>
                                            @else
                                                <span class="text-gray-500 text-sm">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex space-x-2">
                                                <!-- Tombol Edit -->
                                                <button onclick="editAbsensi({{ $absen->id_absensi }})" 
                                                        class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded hover:bg-blue-50"
                                                        title="Edit Absensi">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                
                                                <!-- Tombol Hapus -->
                                                <button onclick="deleteAbsensi({{ $absen->id_absensi }})" 
                                                        class="text-red-600 hover:text-red-800 transition-colors p-1 rounded hover:bg-red-50"
                                                        title="Hapus Absensi">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-clipboard-check text-4xl mb-4 text-gray-400"></i>
                            <p class="text-lg font-medium">Belum ada absensi untuk tanggal ini</p>
                            <p class="text-sm mt-2">Silakan input absensi di form sebelah</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Input Absensi -->
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Input Absensi Baru
                    </h3>
                </div>
                <div class="p-6">
                    <form id="absensiForm" class="space-y-4">
                        @csrf
                        <input type="hidden" name="tanggal" value="{{ $selected_tanggal }}">
                        
                        <!-- Karyawan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-1"></i> Karyawan *
                            </label>
                            <select name="id_user" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih Karyawan</option>
                                @foreach($karyawan_aktif as $karyawan)
                                <option value="{{ $karyawan->id_user }}">
                                    {{ $karyawan->nama_lengkap }} 
                                    @if($karyawan->id_blok)
                                        @php
                                            $blok = DB::table('blok_ladang')->where('id_blok', $karyawan->id_blok)->first();
                                        @endphp
                                        @if($blok) - Blok {{ $blok->nama_blok }} @endif
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Kehadiran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-clipboard-check mr-1"></i> Status Kehadiran *
                            </label>
                            <select name="status_kehadiran" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpha">Alpha</option>
                                <option value="Libur_Agama">Libur Agama</option>
                            </select>
                        </div>

                        <!-- Jam Masuk -->
                        <div id="jamMasukGroup">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-clock mr-1"></i> Jam Masuk
                                <span class="text-xs text-gray-500">(wajib jika Hadir)</span>
                            </label>
                            <input type="time" name="jam_masuk" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="HH:MM">
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-sticky-note mr-1"></i> Keterangan
                                <span class="text-xs text-gray-500">(opsional)</span>
                            </label>
                            <textarea name="keterangan" rows="3" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="Alasan izin/sakit atau catatan lainnya..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" id="submitBtn" 
                                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-lg transition-all duration-300 font-semibold flex items-center justify-center shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i>
                                <span id="btnText">Simpan Absensi</span>
                                <i class="fas fa-spinner fa-spin ml-2 hidden" id="loadingIcon"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Absensi -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Absensi
                    </h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="editAbsensiForm" class="space-y-4 mt-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id_absensi" id="edit_id_absensi">
                    
                    <!-- Info Karyawan (readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Karyawan
                        </label>
                        <input type="text" id="edit_nama_lengkap" readonly
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-gray-50 text-gray-700">
                    </div>
                    
                    <!-- Status Kehadiran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status Kehadiran *
                        </label>
                        <select name="status_kehadiran" id="edit_status_kehadiran" required 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="Hadir">Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alpha">Alpha</option>
                            <option value="Libur_Agama">Libur Agama</option>
                        </select>
                    </div>
                    
                    <!-- Jam Masuk -->
                    <div id="editJamMasukGroup">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Masuk
                            <span class="text-xs text-gray-500">(wajib jika Hadir)</span>
                        </label>
                        <input type="time" name="jam_masuk" id="edit_jam_masuk"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    
                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan
                            <span class="text-xs text-gray-500">(opsional)</span>
                        </label>
                        <textarea name="keterangan" id="edit_keterangan" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Alasan izin/sakit atau catatan lainnya..."></textarea>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="editSubmitBtn"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            <span id="editBtnText">Simpan Perubahan</span>
                            <i class="fas fa-spinner fa-spin ml-2 hidden" id="editLoadingIcon"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Filter form submission
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const date = document.getElementById('filterDate').value;
            if (date) {
                window.location.href = `{{ route('admin.kelola-absensi') }}?tanggal=${date}`;
            }
        });

        function resetFilter() {
            window.location.href = '{{ route("admin.kelola-absensi") }}';
        }

        // Set default date to today if not set
        @if(!request()->has('tanggal'))
        document.getElementById('filterDate').value = new Date().toISOString().split('T')[0];
        @endif

        // Toggle jam masuk based on status
        const statusSelect = document.querySelector('select[name="status_kehadiran"]');
        const jamMasukGroup = document.getElementById('jamMasukGroup');
        
        function toggleJamMasuk() {
            if (statusSelect.value === 'Hadir') {
                jamMasukGroup.style.display = 'block';
            } else {
                jamMasukGroup.style.display = 'none';
            }
        }
        
        statusSelect.addEventListener('change', toggleJamMasuk);
        toggleJamMasuk(); // Initial call

        // Absensi form submission dengan loading state
        document.getElementById('absensiForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const loadingIcon = document.getElementById('loadingIcon');
            
            // Validasi jika Hadir harus isi jam masuk
            if (statusSelect.value === 'Hadir' && !formData.get('jam_masuk')) {
                alert('Harap isi jam masuk untuk status Hadir');
                return;
            }
            
            // Set loading state
            submitBtn.disabled = true;
            btnText.textContent = 'Menyimpan...';
            loadingIcon.classList.remove('hidden');
            
            try {
                const response = await fetch('{{ route("admin.input-absensi") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const contentType = response.headers.get("content-type");
                
                if (contentType && contentType.includes("application/json")) {
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('✅ ' + data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        alert('❌ ' + data.message);
                    }
                } else {
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 200));
                    alert('Terjadi kesalahan di server. Silakan coba lagi.');
                }
                
            } catch (error) {
                console.error('Fetch error:', error);
                alert('Gagal mengirim data. Periksa koneksi internet Anda.');
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                btnText.textContent = 'Simpan Absensi';
                loadingIcon.classList.add('hidden');
            }
        });

        // Auto set jam sekarang jika Hadir
        statusSelect.addEventListener('change', function() {
            if (this.value === 'Hadir') {
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                document.querySelector('input[name="jam_masuk"]').value = `${hours}:${minutes}`;
            }
        });

        // ================================
        // FUNGSI EDIT DAN HAPUS
        // ================================
        
      
            // Fungsi Edit Absensi
async function editAbsensi(id) {
    console.log('Edit absensi ID:', id);
    
    try {
        const response = await fetch(`/admin/kelola-absensi/${id}/edit`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            console.error('Response not OK:', response.status, response.statusText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            // Isi form edit dengan data yang ada
            document.getElementById('edit_id_absensi').value = data.data.id_absensi;
            document.getElementById('edit_nama_lengkap').value = data.data.nama_lengkap;
            document.getElementById('edit_status_kehadiran').value = data.data.status_kehadiran;
            document.getElementById('edit_jam_masuk').value = data.data.jam_masuk || '';
            document.getElementById('edit_keterangan').value = data.data.keterangan || '';
            
            // Toggle jam masuk
            const editJamMasukGroup = document.getElementById('editJamMasukGroup');
            if (data.data.status_kehadiran === 'Hadir') {
                editJamMasukGroup.style.display = 'block';
            } else {
                editJamMasukGroup.style.display = 'none';
            }
            
            // Tampilkan modal
            document.getElementById('editModal').classList.remove('hidden');
        } else {
            console.error('Server error:', data.message);
            alert('Gagal mengambil data absensi: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data: ' + error.message);
    }
}
        
        // Fungsi Delete Absensi
        function deleteAbsensi(id) {
            if (confirm('Apakah Anda yakin ingin menghapus absensi ini?')) {
                fetch(`/admin/kelola-absensi/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ ' + data.message);
                        // Hapus baris dari tabel
                        const row = document.getElementById(`row-${id}`);
                        if (row) {
                            row.style.opacity = '0.5';
                            setTimeout(() => {
                                row.remove();
                                // Refresh statistik
                                location.reload();
                            }, 500);
                        } else {
                            location.reload();
                        }
                    } else {
                        alert('❌ ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus');
                });
            }
        }
        
        // Tutup modal edit
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        
        // Submit form edit
        document.getElementById('editAbsensiForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const id = formData.get('id_absensi');
            const editSubmitBtn = document.getElementById('editSubmitBtn');
            const editBtnText = document.getElementById('editBtnText');
            const editLoadingIcon = document.getElementById('editLoadingIcon');
            
            // Validasi jika Hadir harus isi jam masuk
            const editStatus = document.getElementById('edit_status_kehadiran').value;
            const editJamMasuk = document.getElementById('edit_jam_masuk').value;
            
            if (editStatus === 'Hadir' && !editJamMasuk) {
                alert('Harap isi jam masuk untuk status Hadir');
                return;
            }
            
            // Set loading state
            editSubmitBtn.disabled = true;
            editBtnText.textContent = 'Menyimpan...';
            editLoadingIcon.classList.remove('hidden');
            
            try {
                const response = await fetch(`/admin/kelola-absensi/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: new URLSearchParams(formData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ ' + data.message);
                    closeEditModal();
                    setTimeout(() => location.reload(), 500);
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate');
            } finally {
                // Reset button state
                editSubmitBtn.disabled = false;
                editBtnText.textContent = 'Simpan Perubahan';
                editLoadingIcon.classList.add('hidden');
            }
        });
        
        // Toggle jam masuk di modal edit
        document.getElementById('edit_status_kehadiran').addEventListener('change', function() {
            const editJamMasukGroup = document.getElementById('editJamMasukGroup');
            if (this.value === 'Hadir') {
                editJamMasukGroup.style.display = 'block';
            } else {
                editJamMasukGroup.style.display = 'none';
            }
        });
        
        // Notification if any
        @if(session('success'))
        alert('✅ {{ session('success') }}');
        @endif
        
        @if(session('error'))
        alert('❌ {{ session('error') }}');
        @endif
    </script>
</body>
</html>
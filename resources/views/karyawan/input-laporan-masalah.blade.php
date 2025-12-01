<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Masalah - Karyawan</title>
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
                    <a href="{{ route('karyawan.dashboard') }}" class="text-green-200 hover:text-white mr-4">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <i class="fas fa-tractor text-xl mr-3"></i>
                    <span class="font-semibold text-xl">Lapor Masalah</span>
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

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Lapor Masalah</h1>
            <p class="text-gray-600">Sampaikan masalah yang Anda temui selama bekerja. Laporan akan dikirim ke Admin untuk ditindaklanjuti.</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Error Message -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>Terjadi kesalahan. Silakan periksa kembali form.</span>
            </div>
            <ul class="mt-2 text-sm">
                @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form Laporan -->
        <div class="bg-white rounded-xl card-shadow p-6">
            <form id="laporanForm" method="POST" action="{{ route('karyawan.laporan-masalah.store') }}">
                @csrf
                
                <!-- Jenis Masalah -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1"></i>Jenis Masalah
                    </label>
                    <select name="jenis_masalah" id="jenis_masalah" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Pilih jenis masalah</option>
                        <option value="Cuaca Buruk">Cuaca Buruk</option>
                        <option value="Serangan Hama">Serangan Hama</option>
                        <option value="Kerusakan Alat">Kerusakan Alat</option>
                        <option value="Kemalingan">Kemalingan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Tingkat Keparahan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exclamation-circle mr-1"></i>Tingkat Keparahan
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="tingkat_keparahan" value="ringan" 
                                   class="peer sr-only" required>
                            <div class="w-full p-4 border-2 border-gray-200 rounded-lg hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-500 mr-3 transition-colors"></div>
                                    <div>
                                        <div class="font-medium text-green-600">Ringan</div>
                                        <div class="text-sm text-gray-500">Tidak mengganggu pekerjaan</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="tingkat_keparahan" value="sedang" 
                                   class="peer sr-only" required>
                            <div class="w-full p-4 border-2 border-gray-200 rounded-lg hover:border-yellow-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition-all">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 mr-3 transition-colors"></div>
                                    <div>
                                        <div class="font-medium text-yellow-600">Sedang</div>
                                        <div class="text-sm text-gray-500">Mengganggu sebagian pekerjaan</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="tingkat_keparahan" value="berat" 
                                   class="peer sr-only" required>
                            <div class="w-full p-4 border-2 border-gray-200 rounded-lg hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-red-500 peer-checked:bg-red-500 mr-3 transition-colors"></div>
                                    <div>
                                        <div class="font-medium text-red-600">Berat</div>
                                        <div class="text-sm text-gray-500">Menghentikan pekerjaan</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="far fa-calendar mr-1"></i>Tanggal Kejadian
                    </label>
                    <input type="date" name="tanggal" id="tanggal" required 
                           value="{{ date('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Deskripsi Masalah -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-1"></i>Deskripsi Masalah
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="6" required 
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Jelaskan masalah secara detail, termasuk lokasi, waktu kejadian, dan dampaknya terhadap pekerjaan..."></textarea>
                    <div class="text-sm text-gray-500 mt-1">Minimal 10 karakter. Jelaskan sejelas mungkin agar dapat dipahami.</div>
                    <div id="charCount" class="text-sm text-gray-400 text-right mt-1">0/1000 karakter</div>
                </div>

                <!-- Lokasi (Opsional) -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i>Lokasi Kejadian (Opsional)
                    </label>
                    <input type="text" name="lokasi" id="lokasi"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Contoh: Gudang Utama, Kebun Blok A, Kantor, dll.">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="button" onclick="window.history.back()" 
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors mr-4 font-medium">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Reports -->
        @if($laporan_terbaru && $laporan_terbaru->count() > 0)
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Laporan Terbaru Anda</h2>
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($laporan_terbaru as $laporan)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="font-semibold text-gray-900">{{ $laporan->jenis_masalah }}</span>
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            @if($laporan->status_masalah == 'dilaporkan') bg-yellow-100 text-yellow-800
                                            @elseif($laporan->status_masalah == 'dalam_penanganan') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @if($laporan->status_masalah == 'dilaporkan') Menunggu
                                            @elseif($laporan->status_masalah == 'dalam_penanganan') Ditangani
                                            @else Selesai @endif
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <i class="far fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($laporan->tingkat_keparahan == 'ringan') bg-green-100 text-green-800
                                    @elseif($laporan->tingkat_keparahan == 'sedang') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($laporan->tingkat_keparahan) }}
                                </span>
                            </div>
                            <p class="text-gray-700 text-sm mb-2">{{ Str::limit($laporan->deskripsi, 150) }}</p>
                            @if($laporan->tindakan)
                            <div class="bg-blue-50 border border-blue-100 rounded p-2 mt-2">
                                <div class="text-xs text-blue-800 font-medium mb-1">
                                    <i class="fas fa-tools mr-1"></i>Tindakan Admin:
                                </div>
                                <p class="text-xs text-blue-700">{{ Str::limit($laporan->tindakan, 100) }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-sm mx-4">
            <div class="flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mb-4"></div>
                <p class="text-gray-700 font-medium">Mengirim laporan...</p>
                <p class="text-gray-500 text-sm mt-2">Harap tunggu sebentar</p>
            </div>
        </div>
    </div>

    <script>
        // Character counter for textarea
        const textarea = document.getElementById('deskripsi');
        const charCount = document.getElementById('charCount');
        
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/1000 karakter`;
            
            if (length < 10) {
                charCount.classList.remove('text-gray-400');
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
                charCount.classList.add('text-gray-400');
            }
            
            // Auto-resize
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Form submission
        document.getElementById('laporanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const jenis = document.getElementById('jenis_masalah').value;
            const tingkat = document.querySelector('input[name="tingkat_keparahan"]:checked');
            const tanggal = document.getElementById('tanggal').value;
            const deskripsi = document.getElementById('deskripsi').value.trim();
            
            let errors = [];
            
            if (!jenis) errors.push('Jenis masalah harus dipilih');
            if (!tingkat) errors.push('Tingkat keparahan harus dipilih');
            if (!tanggal) errors.push('Tanggal harus diisi');
            if (!deskripsi) errors.push('Deskripsi masalah harus diisi');
            if (deskripsi.length < 10) errors.push('Deskripsi minimal 10 karakter');
            
            if (errors.length > 0) {
                alert('Silakan perbaiki kesalahan berikut:\n\n' + errors.join('\n'));
                return;
            }
            
            // Show loading modal
            document.getElementById('loadingModal').classList.remove('hidden');
            document.getElementById('submitBtn').disabled = true;
            
            // Submit form via AJAX
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loadingModal').classList.add('hidden');
                document.getElementById('submitBtn').disabled = false;
                
                if (data.success) {
                    alert(data.message);
                    window.location.href = '{{ route("karyawan.riwayat-laporan-masalah") }}';
                } else {
                    alert('Gagal mengirim laporan: ' + data.message);
                }
            })
            .catch(error => {
                document.getElementById('loadingModal').classList.add('hidden');
                document.getElementById('submitBtn').disabled = false;
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                console.error('Error:', error);
            });
        });

        // Set max date to today
        document.getElementById('tanggal').max = new Date().toISOString().split("T")[0];
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pengeluaran - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .tab-active { border-bottom: 2px solid #3b82f6; color: #3b82f6; }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-green-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-green-200 hover:text-white mr-4">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <i class="fas fa-money-bill-wave text-xl mr-3"></i>
                    <span class="font-semibold text-xl">Input Pengeluaran</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-green-200">Admin</span>
                    <a href="{{ route('admin.riwayat-pengeluaran') }}" class="bg-green-600 hover:bg-green-500 px-3 py-1 rounded transition-colors">
                        <i class="fas fa-history mr-1"></i>Riwayat
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Tabs -->
        <div class="bg-white rounded-xl card-shadow mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="showTab('pupuk')" id="tab-pupuk" class="tab-active py-4 px-6 text-center border-transparent font-medium text-sm">
                        <i class="fas fa-seedling mr-2"></i>Pupuk
                    </button>
                    <button onclick="showTab('transportasi')" id="tab-transportasi" class="py-4 px-6 text-center border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-truck mr-2"></i>Transportasi
                    </button>
                    <button onclick="showTab('perawatan')" id="tab-perawatan" class="py-4 px-6 text-center border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-tools mr-2"></i>Perawatan
                    </button>
                    <button onclick="showTab('gaji')" id="tab-gaji" class="py-4 px-6 text-center border-transparent font-medium text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-money-bill-wave mr-2"></i>Gaji
                    </button>
                </nav>
            </div>

            <!-- Form Pupuk -->
            <div id="form-pupuk" class="p-6">
                <form id="formPupuk">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pupuk</label>
                            <input type="text" name="jenis_pupuk" placeholder="Contoh: Urea, NPK, dll" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (kg)</label>
                            <input type="number" name="jumlah" step="0.01" placeholder="0.00" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan</label>
                            <input type="number" name="harga_satuan" step="0.01" placeholder="0" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                            <input type="number" name="total_harga" step="0.01" placeholder="0" readonly
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan..." 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Pengeluaran Pupuk
                    </button>
                </form>
            </div>

            <!-- Form Transportasi -->
            <div id="form-transportasi" class="p-6 hidden">
                <form id="formTransportasi">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                            <input type="text" name="tujuan" placeholder="Contoh: Pengiriman ke pabrik, beli perlengkapan, dll" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Transportasi</label>
                        <input type="number" name="biaya" step="0.01" placeholder="0" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan..." 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Pengeluaran Transportasi
                    </button>
                </form>
            </div>

            <!-- Form Perawatan -->
            <div id="form-perawatan" class="p-6 hidden">
                <form id="formPerawatan">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Perawatan</label>
                            <input type="text" name="jenis_perawatan" placeholder="Contoh: Servis alat, perbaikan jalan, dll" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Perawatan</label>
                        <input type="number" name="biaya" step="0.01" placeholder="0" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan..." 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Pengeluaran Perawatan
                    </button>
                </form>
            </div>

            <!-- Form Gaji (Sesuai dengan struktur tabel pengeluaran_gaji) -->
       <!-- Form Gaji (Sesuai dengan struktur tabel pengeluaran_gaji) -->
<!-- Form Gaji (Sesuai dengan struktur tabel pengeluaran_gaji) -->
<!-- Form Gaji -->
<div id="form-gaji" class="p-6 hidden">
 <form id="formGaji" method="POST" action="{{ route('admin.pengeluaran.gaji.store') }}">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Generate</label>
            <input type="date" name="tanggal_generate" value="{{ date('Y-m-d') }}" 
                   class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
            <select name="periode" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                <option value="">Pilih Periode</option>
                <option value="Mingguan">Mingguan</option>
                <option value="Bulanan">Bulanan</option>
                <option value="Tahunan">Tahunan</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
            <select name="id_user" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                <option value="">Pilih Karyawan</option>
                @foreach($users as $user)
                    <option value="{{ $user->id_user }}">{{ $user->nama_lengkap }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Total Gaji</label>
            <input type="number" name="total_gaji" step="0.01" placeholder="0" 
                   class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
        </div>
    </div>

    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
        Simpan Pengeluaran Gaji
    </button>
</form>

</div>
        </div>

        <!-- Notification -->
        <div id="notification" class="hidden fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50"></div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all forms
            document.querySelectorAll('[id^="form-"]').forEach(form => {
                form.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                tab.classList.remove('tab-active', 'text-blue-600');
                tab.classList.add('text-gray-500');
            });
            
            // Show selected form and activate tab
            document.getElementById(`form-${tabName}`).classList.remove('hidden');
            document.getElementById(`tab-${tabName}`).classList.add('tab-active', 'text-blue-600');
            document.getElementById(`tab-${tabName}`).classList.remove('text-gray-500');
        }

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

        // Auto-calculate total harga for pupuk form
        document.querySelector('input[name="jumlah"]').addEventListener('input', calculateTotalHarga);
        document.querySelector('input[name="harga_satuan"]').addEventListener('input', calculateTotalHarga);

        function calculateTotalHarga() {
            const jumlah = parseFloat(document.querySelector('input[name="jumlah"]').value) || 0;
            const hargaSatuan = parseFloat(document.querySelector('input[name="harga_satuan"]').value) || 0;
            const totalHarga = jumlah * hargaSatuan;
            document.querySelector('input[name="total_harga"]').value = totalHarga.toFixed(2);
        }

        // Form submission handlers
        document.getElementById('formPupuk').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm(this, '{{ route("admin.pengeluaran.pupuk") }}');
        });

        document.getElementById('formTransportasi').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm(this, '{{ route("admin.pengeluaran.transportasi") }}');
        });

        document.getElementById('formPerawatan').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm(this, '{{ route("admin.pengeluaran.perawatan") }}');
        });

        document.getElementById('formGaji').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm(this, '{{ route("admin.pengeluaran.gaji.store") }}');
        });

        function submitForm(form, url) {
            const formData = new FormData(form);
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    form.reset();
                    // Reset tanggal to today
                    const dateField = form.querySelector('input[type="date"]');
                    if (dateField) {
                        dateField.value = new Date().toISOString().split('T')[0];
                    }
                    // Reset total harga calculation
                    if (form.id === 'formPupuk') {
                        document.querySelector('input[name="total_harga"]').value = '';
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Terjadi kesalahan saat menyimpan data', 'error');
                console.error('Error:', error);
            });

            
        }
    </script>
</body>
</html>
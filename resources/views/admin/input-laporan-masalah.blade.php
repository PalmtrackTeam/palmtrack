<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Laporan Masalah - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-green-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-green-200 hover:text-white mr-4">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                    <span class="font-semibold text-xl">Input Laporan Masalah</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h3 class="font-medium text-blue-800">Informasi Penting</h3>
                    <p class="text-blue-700 text-sm mt-1">
                        Laporan masalah yang Anda buat akan langsung diteruskan ke Owner dan muncul di halaman laporan masalah Owner.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form id="formLaporanMasalah">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Masalah *</label>
                        <select name="jenis_masalah" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Jenis Masalah</option>
                            <option value="Cuaca Buruk">Cuaca Buruk</option>
                            <option value="Kemalingan">Kemalingan</option>
                            <option value="Serangan Hama">Serangan Hama</option>
                            <option value="Kerusakan Alat">Kerusakan Alat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Keparahan *</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="tingkat_keparahan" value="ringan" class="text-blue-600 focus:ring-blue-500" required>
                            <span class="ml-2 text-gray-700">Ringan</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="tingkat_keparahan" value="sedang" class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-gray-700">Sedang</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="tingkat_keparahan" value="berat" class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-gray-700">Berat</span>
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Masalah *</label>
                    <textarea name="deskripsi" rows="4" placeholder="Jelaskan secara detail masalah yang terjadi, lokasi, dan dampaknya..." 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Laporan akan langsung dikirim ke Owner
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim ke Owner
                    </button>
                </div>
            </form>
        </div>

        <!-- Notification -->
        <div id="notification" class="hidden fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50"></div>
    </div>

    <script>
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
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

        // Form submission handler
        document.getElementById('formLaporanMasalah').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            
            submitForm(this, '{{ route("admin.laporan-masalah.store") }}')
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        function submitForm(form, url) {
            const formData = new FormData(form);
            
            return fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    form.reset();
                    // Reset tanggal to today
                    form.querySelector('input[name="tanggal"]').value = new Date().toISOString().split('T')[0];
                    
                    // Tetap di halaman yang sama setelah berhasil
                    // Tidak redirect ke riwayat
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Terjadi kesalahan saat mengirim laporan', 'error');
                console.error('Error:', error);
            });
        }

        // Auto-focus pada textarea
        document.querySelector('textarea[name="deskripsi"]').focus();
    </script>
</body>
</html>
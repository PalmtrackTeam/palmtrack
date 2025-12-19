@extends('layouts.app')

@section('title', 'Input Absensi')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4"> <!-- Lebar ditambah -->
    <!-- NOTIFIKASI BARU -->
   @if(($notifikasiAbsensi ?? collect())->count() > 0)
    <div class="mb-6 space-y-3">
        @foreach($notifikasiAbsensi as $notif)
        <div class="bg-{{ $notif->tipe == 'warning' ? 'yellow' : ($notif->tipe == 'danger' ? 'red' : 'blue') }}-100 
                    border border-{{ $notif->tipe == 'warning' ? 'yellow' : ($notif->tipe == 'danger' ? 'red' : 'blue') }}-400 
                    text-{{ $notif->tipe == 'warning' ? 'yellow' : ($notif->tipe == 'danger' ? 'red' : 'blue') }}-700 
                    px-4 py-3 rounded flex justify-between items-center">
            <div>
                <strong>{{ $notif->judul }}</strong><br>
                {{ $notif->pesan }}
            </div>
            <form action="{{ route('notifikasi.baca', $notif->id_notifikasi) }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">×</button>
            </form>
        </div>
        @endforeach
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Input Absensi Harian
        </h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(isset($absenHariIni) && $absenHariIni)
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <strong class="text-lg">✓ Anda sudah absen hari ini!</strong><br>
                        <div class="mt-2 space-y-1">
                            <div class="flex items-center">
                                <span class="font-semibold w-32">Status:</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $absenHariIni->status_kehadiran == 'Hadir' ? 'bg-green-100 text-green-800' : 
                                       ($absenHariIni->status_kehadiran == 'Izin' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ $absenHariIni->status_kehadiran }}
                                </span>
                            </div>
                            @if($absenHariIni->jam_masuk)
                            <div class="flex items-center">
                                <span class="font-semibold w-32">Jam Masuk:</span>
                                <span class="font-mono">{{ $absenHariIni->jam_masuk }}</span>
                                
                                <!-- FITUR BARU: Tampilkan status telat -->
                                @php
                                    // Panggil function database via AJAX atau pre-calculated
                                    $statusTelat = DB::select('SELECT fn_cek_telat(?) as status', [$absenHariIni->jam_masuk])[0]->status ?? '';
                                    $menitTelat = DB::select('SELECT fn_hitung_menit_telat(?) as menit', [$absenHariIni->jam_masuk])[0]->menit ?? 0;
                                @endphp
                                
                                @if($statusTelat == 'Telat')
                                    <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                        Telat {{ $menitTelat }} menit
                                    </span>
                                @elseif($statusTelat == 'Tepat Waktu')
                                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                        Tepat Waktu
                                    </span>
                                @endif
                            </div>
                            @endif
                            @if($absenHariIni->keterangan)
                            <div class="flex items-start">
                                <span class="font-semibold w-32">Keterangan:</span>
                                <span>{{ $absenHariIni->keterangan }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Absensi pada:</div>
                        <div class="font-semibold">{{ \Carbon\Carbon::parse($absenHariIni->created_at)->format('H:i') }}</div>
                    </div>
                </div>
            </div>

        @else
            <form method="POST" action="{{ route('karyawan.store-absensi') }}" id="absensiForm">
                @csrf

                <!-- Status Kehadiran -->
                <div class="mb-6">
                    <label for="status_kehadiran" class="block text-sm font-medium text-gray-700 mb-2">
                        Status Kehadiran *
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2" id="status-buttons">
                        <button type="button" data-value="Hadir" 
                                class="status-btn p-3 border rounded-lg text-center hover:bg-green-50 hover:border-green-300 transition
                                {{ old('status_kehadiran') == 'Hadir' ? 'bg-green-100 border-green-400 ring-2 ring-green-200' : 'border-gray-300' }}">
                            <div class="font-semibold text-green-700">Hadir</div>
                            <div class="text-xs text-gray-500">Wajib isi jam</div>
                        </button>
                        <button type="button" data-value="Izin" 
                                class="status-btn p-3 border rounded-lg text-center hover:bg-yellow-50 hover:border-yellow-300 transition
                                {{ old('status_kehadiran') == 'Izin' ? 'bg-yellow-100 border-yellow-400 ring-2 ring-yellow-200' : 'border-gray-300' }}">
                            <div class="font-semibold text-yellow-700">Izin</div>
                            <div class="text-xs text-gray-500">Wajib keterangan</div>
                        </button>
                        <button type="button" data-value="Sakit" 
                                class="status-btn p-3 border rounded-lg text-center hover:bg-orange-50 hover:border-orange-300 transition
                                {{ old('status_kehadiran') == 'Sakit' ? 'bg-orange-100 border-orange-400 ring-2 ring-orange-200' : 'border-gray-300' }}">
                            <div class="font-semibold text-orange-700">Sakit</div>
                            <div class="text-xs text-gray-500">Wajib keterangan</div>
                        </button>
                        <button type="button" data-value="Alpha" 
                                class="status-btn p-3 border rounded-lg text-center hover:bg-red-50 hover:border-red-300 transition
                                {{ old('status_kehadiran') == 'Alpha' ? 'bg-red-100 border-red-400 ring-2 ring-red-200' : 'border-gray-300' }}">
                            <div class="font-semibold text-red-700">Alpha</div>
                            <div class="text-xs text-gray-500">Tanpa keterangan</div>
                        </button>
                        <button type="button" data-value="Libur_Agama" 
                                class="status-btn p-3 border rounded-lg text-center hover:bg-purple-50 hover:border-purple-300 transition
                                {{ old('status_kehadiran') == 'Libur_Agama' ? 'bg-purple-100 border-purple-400 ring-2 ring-purple-200' : 'border-gray-300' }}">
                            <div class="font-semibold text-purple-700">Libur Agama</div>
                            <div class="text-xs text-gray-500">Cuti khusus</div>
                        </button>
                    </div>
                    <input type="hidden" id="status_kehadiran" name="status_kehadiran" 
                           value="{{ old('status_kehadiran') }}" required>
                    @error('status_kehadiran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Masuk -->
                <div id="jam-masuk-group" class="mb-6 hidden">
                    <label for="jam_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Masuk *
                        <span class="text-xs text-gray-500">(Batas: 07:00)</span>
                    </label>
                    <div class="flex items-center space-x-4">
                        <input type="time" id="jam_masuk" name="jam_masuk"
                               value="{{ old('jam_masuk') }}"
                               class="w-48 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" id="btn-now" 
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition">
                            ⏰ Sekarang
                        </button>
                        <div id="telat-info" class="text-sm hidden">
                            <span class="font-semibold">Estimasi:</span>
                            <span id="telat-text" class="ml-1"></span>
                        </div>
                    </div>
                    @error('jam_masuk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div id="keterangan-group" class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        <span id="keterangan-label">Keterangan (Opsional)</span>
                        <span id="keterangan-wajib" class="text-red-600 hidden">*</span>
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="">{{ old('keterangan') }}</textarea>
                    <div class="text-xs text-gray-500 mt-1" id="keterangan-hint">
                        Isikan alasan atau catatan jika diperlukan
                    </div>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview sebelum submit -->
                <div id="preview-absen" class="mb-6 p-4 border border-gray-200 rounded-lg hidden">
                    <h4 class="font-semibold text-gray-700 mb-2">Preview Absensi:</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium">Status:</span> <span id="preview-status"></span></div>
                        <div id="preview-jam" class="hidden"><span class="font-medium">Jam Masuk:</span> <span id="preview-jam-value"></span></div>
                        <div id="preview-keterangan" class="hidden"><span class="font-medium">Keterangan:</span> <span id="preview-keterangan-value"></span></div>
                        <div id="preview-telat" class="hidden"><span class="font-medium">Status:</span> <span id="preview-telat-value" class="font-semibold"></span></div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <a href="{{ route('karyawan.dashboard') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                    <div class="space-x-3">
                        <!-- <button type="button" id="btn-preview" 
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg transition">
                            Preview
                        </button> -->
                        <button type="submit" id="btn-submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Absensi
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <!-- Quick Info - DIPERBAHARUI -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <h3 class="font-semibold text-yellow-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Absensi
            </h3>
            <ul class="text-sm text-yellow-700 space-y-2">
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mt-1 mr-2 flex-shrink-0"></span>
                    <span><strong>Hadir:</strong> Wajib mengisi jam masuk (Batas 07:00)</span>
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mt-1 mr-2 flex-shrink-0"></span>
                    <span><strong>Izin/Sakit:</strong> Wajib mengisi keterangan yang jelas</span>
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mt-1 mr-2 flex-shrink-0"></span>
                    <span><strong>Alpha:</strong> Akan mempengaruhi penilaian kinerja</span>
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mt-1 mr-2 flex-shrink-0"></span>
                    <span>Absensi hanya bisa dilakukan <strong>1x per hari</strong></span>
                </li>
            </ul>
        </div>

        <!-- FITUR BARU: Jam kerja dan perhitungan -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Jadwal Kerja
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-700">Jam Masuk Standar:</span>
                    <span class="font-semibold text-blue-800" id="jam-standar">07:00</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-700">Jam Sekarang:</span>
                    <span class="font-semibold text-blue-800" id="jam-sekarang">--:--</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-700">Status:</span>
                    <span class="font-semibold" id="status-jam">-</span>
                </div>
                <div class="pt-3 border-t border-blue-200">
                    <div class="text-xs text-blue-600">
                        * Keterlambatan >30 menit akan mendapatkan notifikasi ke atasan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Element references
    const statusButtons = document.querySelectorAll('.status-btn');
    const statusInput = document.getElementById('status_kehadiran');
    const jamMasukGroup = document.getElementById('jam-masuk-group');
    const jamMasukInput = document.getElementById('jam_masuk');
    const btnNow = document.getElementById('btn-now');
    const telatInfo = document.getElementById('telat-info');
    const telatText = document.getElementById('telat-text');
    const keteranganGroup = document.getElementById('keterangan-group');
    const keteranganLabel = document.getElementById('keterangan-label');
    const keteranganWajib = document.getElementById('keterangan-wajib');
    const keteranganHint = document.getElementById('keterangan-hint');
    const previewDiv = document.getElementById('preview-absen');
    const btnPreview = document.getElementById('btn-preview');
    const jamSekarangEl = document.getElementById('jam-sekarang');
    const statusJamEl = document.getElementById('status-jam');
    const jamStandarEl = document.getElementById('jam-standar');
    
    // Update jam real-time
    function updateWaktu() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        jamSekarangEl.textContent = `${hours}:${minutes}`;
        
        // Hitung status
        const jamStandar = '07:00';
        const jamNow = `${hours}:${minutes}`;
        
        if (jamNow >= jamStandar) {
            statusJamEl.textContent = 'Bisa Absen';
            statusJamEl.className = 'font-semibold text-green-600';
        } else {
            statusJamEl.textContent = 'Sebelum Waktu';
            statusJamEl.className = 'font-semibold text-blue-600';
        }
    }
    
    // Update setiap detik
    updateWaktu();
    setInterval(updateWaktu, 1000);
    
    // Status button click handler
    statusButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            
            // Reset all buttons
            statusButtons.forEach(b => {
                b.classList.remove('bg-green-100', 'border-green-400', 'ring-2', 'ring-green-200',
                                  'bg-yellow-100', 'border-yellow-400', 'ring-2', 'ring-yellow-200',
                                  'bg-orange-100', 'border-orange-400', 'ring-2', 'ring-orange-200',
                                  'bg-red-100', 'border-red-400', 'ring-2', 'ring-red-200',
                                  'bg-purple-100', 'border-purple-400', 'ring-2', 'ring-purple-200');
                b.classList.add('border-gray-300');
            });
            
            // Set active button
            let activeClass = 'border-gray-300';
            switch(value) {
                case 'Hadir':
                    activeClass = 'bg-green-100 border-green-400 ring-2 ring-green-200';
                    break;
                case 'Izin':
                    activeClass = 'bg-yellow-100 border-yellow-400 ring-2 ring-yellow-200';
                    break;
                case 'Sakit':
                    activeClass = 'bg-orange-100 border-orange-400 ring-2 ring-orange-200';
                    break;
                case 'Alpha':
                    activeClass = 'bg-red-100 border-red-400 ring-2 ring-red-200';
                    break;
                case 'Libur_Agama':
                    activeClass = 'bg-purple-100 border-purple-400 ring-2 ring-purple-200';
                    break;
            }
            this.classList.remove('border-gray-300');
            this.classList.add(...activeClass.split(' '));
            
            // Set hidden input value
            statusInput.value = value;
            
            // Toggle jam masuk
            if (value === 'Hadir') {
                jamMasukGroup.classList.remove('hidden');
                jamMasukInput.required = true;
                
                // Set default time to now
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                jamMasukInput.value = `${hours}:${minutes}`;
                
                // Calculate telat
                calculateTelat();
            } else {
                jamMasukGroup.classList.add('hidden');
                jamMasukInput.required = false;
                jamMasukInput.value = '';
                telatInfo.classList.add('hidden');
            }
            
            // Toggle keterangan wajib
            if (value === 'Izin' || value === 'Sakit') {
                keteranganWajib.classList.remove('hidden');
                keteranganHint.textContent = 'Wajib diisi untuk izin/sakit';
                keteranganHint.className = 'text-xs text-red-600 mt-1';
            } else {
                keteranganWajib.classList.add('hidden');
                keteranganHint.textContent = 'Isikan alasan atau catatan jika diperlukan';
                keteranganHint.className = 'text-xs text-gray-500 mt-1';
            }
            
            // Hide preview
            previewDiv.classList.add('hidden');
        });
    });
    
    // Set time to now button
    btnNow.addEventListener('click', function() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        jamMasukInput.value = `${hours}:${minutes}`;
        calculateTelat();
    });
    
    // Calculate telat function
    function calculateTelat() {
        if (!jamMasukInput.value) return;
        
        const jamMasuk = jamMasukInput.value;
        const jamStandar = '07:00';
        
        // Convert to minutes for calculation
        const masukMins = parseInt(jamMasuk.split(':')[0]) * 60 + parseInt(jamMasuk.split(':')[1]);
        const standarMins = parseInt(jamStandar.split(':')[0]) * 60 + parseInt(jamStandar.split(':')[1]);
        
        if (masukMins > standarMins) {
            const telatMenit = masukMins - standarMins;
            telatInfo.classList.remove('hidden');
            
            if (telatMenit <= 30) {
                telatText.textContent = `Telat ${telatMenit} menit (Ringan)`;
                telatText.className = 'text-yellow-600';
            } else if (telatMenit <= 60) {
                telatText.textContent = `Telat ${telatMenit} menit (Sedang)`;
                telatText.className = 'text-orange-600';
            } else {
                telatText.textContent = `Telat ${telatMenit} menit (Berat)`;
                telatText.className = 'text-red-600';
            }
        } else {
            telatInfo.classList.add('hidden');
        }
    }
    
    // Calculate telat on jam masuk change
    jamMasukInput.addEventListener('change', calculateTelat);
    
    // Preview button
    btnPreview.addEventListener('click', function() {
        if (!statusInput.value) {
            alert('Pilih status kehadiran terlebih dahulu!');
            return;
        }
        
        if (statusInput.value === 'Hadir' && !jamMasukInput.value) {
            alert('Isi jam masuk untuk status Hadir!');
            return;
        }
        
        if ((statusInput.value === 'Izin' || statusInput.value === 'Sakit') && 
            !document.getElementById('keterangan').value) {
            alert('Isi keterangan untuk izin/sakit!');
            return;
        }
        
        // Update preview
        document.getElementById('preview-status').textContent = statusInput.value;
        
        if (statusInput.value === 'Hadir' && jamMasukInput.value) {
            document.getElementById('preview-jam').classList.remove('hidden');
            document.getElementById('preview-jam-value').textContent = jamMasukInput.value;
            
            // Calculate for preview
            const jamMasuk = jamMasukInput.value;
            const jamStandar = '07:00';
            const masukMins = parseInt(jamMasuk.split(':')[0]) * 60 + parseInt(jamMasuk.split(':')[1]);
            const standarMins = parseInt(jamStandar.split(':')[0]) * 60 + parseInt(jamStandar.split(':')[1]);
            
            if (masukMins > standarMins) {
                const telatMenit = masukMins - standarMins;
                document.getElementById('preview-telat').classList.remove('hidden');
                document.getElementById('preview-telat-value').textContent = `Telat ${telatMenit} menit`;
                document.getElementById('preview-telat-value').className = 'font-semibold text-red-600';
            } else {
                document.getElementById('preview-telat').classList.remove('hidden');
                document.getElementById('preview-telat-value').textContent = 'Tepat Waktu';
                document.getElementById('preview-telat-value').className = 'font-semibold text-green-600';
            }
        } else {
            document.getElementById('preview-jam').classList.add('hidden');
            document.getElementById('preview-telat').classList.add('hidden');
        }
        
        const keteranganValue = document.getElementById('keterangan').value;
        if (keteranganValue) {
            document.getElementById('preview-keterangan').classList.remove('hidden');
            document.getElementById('preview-keterangan-value').textContent = keteranganValue;
        } else {
            document.getElementById('preview-keterangan').classList.add('hidden');
        }
        
        previewDiv.classList.remove('hidden');
    });
    
    // Form validation before submit
    document.getElementById('absensiForm').addEventListener('submit', function(e) {
        if (!statusInput.value) {
            e.preventDefault();
            alert('Pilih status kehadiran!');
            return;
        }
        
        if (statusInput.value === 'Hadir' && !jamMasukInput.value) {
            e.preventDefault();
            alert('Isi jam masuk untuk status Hadir!');
            return;
        }
        
        if ((statusInput.value === 'Izin' || statusInput.value === 'Sakit') && 
            !document.getElementById('keterangan').value.trim()) {
            e.preventDefault();
            alert('Isi keterangan untuk izin/sakit!');
            return;
        }
        
        // Optional: Show loading
        document.getElementById('btn-submit').innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;
        document.getElementById('btn-submit').disabled = true;
    });
    
    // Set initial state based on old input
    if (statusInput.value) {
        const initialBtn = document.querySelector(`.status-btn[data-value="${statusInput.value}"]`);
        if (initialBtn) {
            initialBtn.click();
        }
    }
});
</script>

<!-- FITUR BARU: QR Code Scanner untuk mobile -->
<div id="qr-scanner-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4">Scan QR Code Absensi</h3>
            <div id="qr-reader" style="width: 300px; height: 300px;" class="mx-auto"></div>
            <div class="mt-4 text-center">
                <button id="close-qr" class="px-4 py-2 bg-gray-300 rounded-lg">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
.status-btn {
    transition: all 0.2s ease;
}

.status-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

#preview-absen {
    background: linear-gradient(135deg, #f6f8ff 0%, #f0f4ff 100%);
    border-color: #c3dafe;
}
</style>
@endsection
@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
<div class="max-w-6xl mx-auto mt-6 bg-white shadow-md rounded-2xl overflow-hidden border border-gray-200">

    <!-- Header Atas -->
    <div class="bg-gradient-to-r from-gray-600 to-indigo-600 text-white p-6 text-center">
        <h1 class="text-2xl font-semibold tracking-wide">📋 Data Absensi Karyawan</h1>
        <p class="text-sm mt-1 opacity-90">Pilih tanggal untuk melihat atau mengisi absensi</p>
    </div>

    <!-- Filter Pilihan Tanggal -->
    <div class="flex flex-wrap justify-center gap-3 p-5 bg-gray-50 border-b">
        <div class="flex flex-col">
            <label class="text-sm text-gray-600 mb-1">Tahun</label>
            <select id="tahun" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-400 w-32"></select>
        </div>
        <div class="flex flex-col">
            <label class="text-sm text-gray-600 mb-1">Bulan</label>
            <select id="bulan" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 w-40"></select>
        </div>
        <div class="flex flex-col">
            <label class="text-sm text-gray-600 mb-1">Tanggal</label>
            <select id="tanggal" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 w-32"></select>
        </div>
    </div>

    <!-- Header Hari & Info -->
    <div id="infoTanggal" class="text-center text-gray-700 text-lg font-medium mt-4"></div>

    <!-- FORM ABSENSI -->
    <form id="formAbsensi" method="POST" action="{{ route('absensi.store') }}" class="p-6">
        @csrf

        <!-- tanggal real -->
        <input type="hidden" name="tanggal" id="tanggal_input">

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 border">No</th>
                        <th class="px-4 py-3 border">Nama & Role</th>
                        <th class="px-4 py-3 text-center border">Hadir</th>
                        <th class="px-4 py-3 text-center border">Izin</th>
                        <th class="px-4 py-3 text-center border">Sakit</th>

                        @if(auth()->user()->role !== 'karyawan')
                        <th class="px-4 py-3 text-center border">Alpha</th>
                        @endif

                        <th class="px-4 py-3 border">Keterangan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($karyawan as $i => $k)
                        @php
                            $user = auth()->user();
                            $disabled = ($user->role === 'karyawan' && $user->id != $k->id) ? 'disabled' : '';
                        @endphp

                        <tr>
                            <td class="px-4 py-3 border text-center">{{ $i + 1 }}</td>

                            <td class="px-4 py-3 border">
                                <div class="font-semibold">{{ $k->nama }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($k->role) }}</div>
                            </td>

                            <!-- HADIR -->
                            <td class="px-4 py-3 border text-center">
                                <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Hadir"
                                       class="statusRadio accent-green-500 w-5 h-5">
                            </td>

                            <!-- IZIN -->
                            <td class="px-4 py-3 border text-center">
                                <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Izin"
                                       class="statusRadio accent-yellow-500 w-5 h-5">
                            </td>

                            <!-- SAKIT -->
                            <td class="px-4 py-3 border text-center">
                                <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Sakit"
                                       class="statusRadio accent-blue-500 w-5 h-5">
                            </td>

                            @if($user->role !== 'karyawan')
                            <!-- ALPHA -->
                            <td class="px-4 py-3 border text-center">
                                <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Alpha"
                                       class="statusRadio accent-red-500 w-5 h-5">
                            </td>
                            @endif

                            <!-- KETERANGAN -->
                            <td class="px-4 py-3 border">
                                <input {{ $disabled }} type="text" name="keterangan[{{ $k->id }}]"
                                       class="ketInput hidden w-full px-2 py-1 border rounded"
                                       placeholder="Isi keterangan...">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center mt-6">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-sm transition">
                💾 Simpan Absensi
            </button>
        </div>
    </form>
</div>

<!-- TOAST -->
<div id="toast"
     class="fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition duration-300">
</div>

<!-- SCRIPT KALENDER (tidak diubah) -->
<script>
const tahunSelect = document.getElementById('tahun');
const bulanSelect = document.getElementById('bulan');
const tanggalSelect = document.getElementById('tanggal');
const infoTanggal = document.getElementById('infoTanggal');

const bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const hariNama = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

const currentYear = new Date().getFullYear();
for (let i = currentYear - 2; i <= currentYear + 1; i++) {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = i;
    if (i === currentYear) opt.selected = true;
    tahunSelect.appendChild(opt);
}

bulanNama.forEach((b, i) => {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = b;
    if (i === new Date().getMonth()) opt.selected = true;
    bulanSelect.appendChild(opt);
});

function isiTanggal() {
    tanggalSelect.innerHTML = '';
    const year = parseInt(tahunSelect.value);
    const month = parseInt(bulanSelect.value);
    const lastDay = new Date(year, month + 1, 0).getDate();

    for (let i = 1; i <= lastDay; i++) {
        const opt = document.createElement('option');
        opt.value = i;
        opt.textContent = i;
        tanggalSelect.appendChild(opt);
    }

    tanggalSelect.value = new Date().getDate();
    tampilkanInfoTanggal();
}
isiTanggal();

function tampilkanInfoTanggal() {
    const y = tahunSelect.value;
    const m = bulanSelect.value;
    const d = tanggalSelect.value;

    const date = new Date(y, m, d);
    const hari = hariNama[date.getDay()];
    const tanggalStr = `${hari}, ${d} ${bulanNama[m]} ${y}`;

    infoTanggal.textContent = tanggalStr;
    document.getElementById('tanggal_input').value = `${y}-${parseInt(m)+1}-${d}`;
}

tahunSelect.addEventListener('change', isiTanggal);
bulanSelect.addEventListener('change', isiTanggal);
tanggalSelect.addEventListener('change', tampilkanInfoTanggal);


// =====================
// VALIDASI FORM
// =====================
document.getElementById('formAbsensi').addEventListener('submit', function(e) {
    let valid = true;
    let message = "";

    @foreach($karyawan as $k)
        let status = document.querySelector('input[name="absensi[{{ $k->id }}]"]:checked');
        let ket = document.querySelector('input[name="keterangan[{{ $k->id }}]"]');

        if (!status) {
            valid = false;
            message = "Belum Mengisi Absen.";
        } else if ((status.value === "Izin" || status.value === "Sakit") && ket.value.trim() === "") {
            valid = false;
            message = "Keterangan wajib diisi untuk Izin dan Sakit.";
        }
    @endforeach

    if (!valid) {
        e.preventDefault();
        showToast(message, true);
    }
});

// auto show/hide keterangan
document.querySelectorAll('.statusRadio').forEach(r => {
    r.addEventListener('change', function() {
        const row = this.closest('tr');
        const ket = row.querySelector('.ketInput');

        if (['Izin','Sakit'].includes(this.value)) {
            ket.classList.remove('hidden');
        } else {
            ket.classList.add('hidden');
            ket.value = "";
        }
    });
});

function showToast(msg, error = false) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.classList.remove('bg-green-600');
    toast.classList.remove('bg-red-600');
    toast.classList.add(error ? 'bg-red-600' : 'bg-green-600');

    toast.style.opacity = "1";
    setTimeout(() => toast.style.opacity = "0", 2500);
}

@if(session('status'))
    showToast("{{ session('status') }}");
@endif

</script>

@endsection

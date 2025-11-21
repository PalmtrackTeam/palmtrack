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

    <!-- Tabel Absensi -->
    <form id="formAbsensi" class="p-6">
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left border">No</th>
                        <th class="px-4 py-3 text-left border">Nama & Jabatan</th>
                        <th class="px-4 py-3 text-center border">Hadir</th>
                        <th class="px-4 py-3 text-center border">Tidak</th>
                        <th class="px-4 py-3 text-center border">Izin</th>
                        <th class="px-4 py-3 text-center border">Sakit</th>
                    </tr>
                </thead>
                <tbody id="tabelAbsensi" class="divide-y divide-gray-200 bg-white"></tbody>
            </table>
        </div>

        <div class="text-center mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-sm transition">
                💾 Simpan Absensi
            </button>
        </div>
    </form>
</div>

<script>
const karyawanDummy = [
    { nama: 'Ahmad Rozan', jabatan: 'Pemanen' },
    { nama: 'Budi Santoso', jabatan: 'Pemanen' },
    { nama: 'Candra Wijaya', jabatan: 'Pimpinan' },
    { nama: 'Dedi Saputra', jabatan: 'Sopir' },
    { nama: 'Mawaddah', jabatan: 'Karyawan' },
    { nama: 'Robert Ong', jabatan: 'Karyawan' },
    { nama: 'Andreas Cristian', jabatan: 'Karyawan' },
];

const tahunSelect = document.getElementById('tahun');
const bulanSelect = document.getElementById('bulan');
const tanggalSelect = document.getElementById('tanggal');
const infoTanggal = document.getElementById('infoTanggal');
const tbody = document.getElementById('tabelAbsensi');

const bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const hariNama = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

// Isi dropdown tahun
const currentYear = new Date().getFullYear();
for (let i = currentYear - 2; i <= currentYear + 1; i++) {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = i;
    if (i === currentYear) opt.selected = true;
    tahunSelect.appendChild(opt);
}

// Isi dropdown bulan
bulanNama.forEach((b, i) => {
    const opt = document.createElement('option');
    opt.value = i;
    opt.textContent = b;
    if (i === new Date().getMonth()) opt.selected = true;
    bulanSelect.appendChild(opt);
});

// Fungsi untuk isi dropdown tanggal sesuai bulan & tahun
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

// Tampilkan info tanggal di atas tabel
function tampilkanInfoTanggal() {
    const y = tahunSelect.value;
    const m = bulanSelect.value;
    const d = tanggalSelect.value;
    const date = new Date(y, m, d);
    const hari = hariNama[date.getDay()];
    const tanggalStr = `${hari}, ${d} ${bulanNama[m]} ${y}`;
    infoTanggal.textContent = tanggalStr;
}

// Render tabel absensi
function renderTable() {
    tbody.innerHTML = '';
    karyawanDummy.forEach((k, index) => {
        const tr = document.createElement('tr');
        tr.classList.add('hover:bg-gray-50', 'transition', 'text-gray-800');

        tr.innerHTML = `
            <td class="px-4 py-3 border text-center font-medium">${index + 1}</td>
            <td class="px-4 py-3 border">
                <div class="font-semibold">${k.nama}</div>
                <div class="text-xs text-gray-500">${k.jabatan}</div>
            </td>
            <td class="px-4 py-3 border text-center"><input type="radio" name="absensi_${index}" value="Hadir" class="accent-green-500 w-5 h-5"></td>
            <td class="px-4 py-3 border text-center"><input type="radio" name="absensi_${index}" value="Tidak" class="accent-red-500 w-5 h-5"></td>
            <td class="px-4 py-3 border text-center"><input type="radio" name="absensi_${index}" value="Izin" class="accent-yellow-500 w-5 h-5"></td>
            <td class="px-4 py-3 border text-center"><input type="radio" name="absensi_${index}" value="Sakit" class="accent-blue-500 w-5 h-5"></td>
        `;
        tbody.appendChild(tr);
    });
}

renderTable();

// Event Listener
tahunSelect.addEventListener('change', isiTanggal);
bulanSelect.addEventListener('change', isiTanggal);
tanggalSelect.addEventListener('change', tampilkanInfoTanggal);

document.getElementById('formAbsensi').addEventListener('submit', function(e) {
    e.preventDefault();
    const hasil = [];
    karyawanDummy.forEach((k, index) => {
        const pilihan = document.querySelector(`input[name="absensi_${index}"]:checked`);
        hasil.push({
            nama: k.nama,
            jabatan: k.jabatan,
            keterangan: pilihan ? pilihan.value : 'Belum diisi',
            tanggal: `${tanggalSelect.value}-${parseInt(bulanSelect.value)+1}-${tahunSelect.value}`
        });
    });
    console.table(hasil);
    alert('✅ Absensi berhasil disimpan! (Cek console untuk data)');
});
</script>
@endsection

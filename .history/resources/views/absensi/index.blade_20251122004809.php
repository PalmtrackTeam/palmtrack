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
            <select id="tahun" class="border rounded-lg px-3 py-2 w-32"></select>
        </div>
        <div class="flex flex-col">
            <label class="text-sm text-gray-600 mb-1">Bulan</label>
            <select id="bulan" class="border rounded-lg px-3 py-2 w-40"></select>
        </div>
        <div class="flex flex-col">
            <label class="text-sm text-gray-600 mb-1">Tanggal</label>
            <select id="tanggal" class="border rounded-lg px-3 py-2 w-32"></select>
        </div>
    </div>

    <!-- Header Hari & Info -->
    <div id="infoTanggal" class="text-center text-gray-700 text-lg font-medium mt-4"></div>

    <!-- Tabel Absensi -->
    <form method="POST" action="{{ route('absensi.store') }}" id="formAbsensi" class="p-6">
        @csrf

        <input type="hidden" name="tanggal" id="tanggal_input">

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 border">No</th>
                        <th class="px-4 py-3 border">Nama & Role</th>
                        <th class="px-4 py-3 border text-center">Hadir</th>
                        <th class="px-4 py-3 border text-center">Izin</th>
                        <th class="px-4 py-3 border text-center">Sakit</th>

                        @if(auth()->user()->role !== 'karyawan')
                        <th class="px-4 py-3 border text-center">Alpha</th>
                        @endif

                        <th class="px-4 py-3 border">Keterangan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($karyawan as $index => $k)

                    @php
                        $user = auth()->user();
                        $disabled = ($user->role === 'karyawan' && $user->id != $k->id) ? 'disabled' : '';
                    @endphp

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3 border text-center">{{ $index + 1 }}</td>

                        <td class="px-4 py-3 border">
                            <div class="font-semibold">{{ $k->nama }}</div>
                            <div class="text-xs text-gray-500">{{ ucfirst($k->role) }}</div>
                        </td>

                        <td class="px-4 py-3 border text-center">
                            <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Hadir"
                                   class="statusRadio accent-green-500 w-5 h-5">
                        </td>

                        <td class="px-4 py-3 border text-center">
                            <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Izin"
                                   class="statusRadio accent-yellow-500 w-5 h-5">
                        </td>

                        <td class="px-4 py-3 border text-center">
                            <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Sakit"
                                   class="statusRadio accent-blue-500 w-5 h-5">
                        </td>

                        @if(auth()->user()->role !== 'karyawan')
                        <td class="px-4 py-3 border text-center">
                            <input {{ $disabled }} type="radio" name="absensi[{{ $k->id }}]" value="Alpha"
                                   class="statusRadio accent-red-500 w-5 h-5">
                        </td>
                        @endif

                        <td class="px-4 py-3 border">
                            <input {{ $disabled }} type="text"
                                   name="keterangan[{{ $k->id }}]"
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
     class="fixed top-6 right-6 bg-green-600 text-white px-5 py-3 rounded-lg shadow-xl opacity-0 translate-y-2 transition duration-300">
</div>

<!-- SCRIPT -->
<script>
/* ------------- KALENDER (TIDAK DIUBAH) ------------- */
const tahunSelect = document.getElementById('tahun');
const bulanSelect = document.getElementById('bulan');
const tanggalSelect = document.getElementById('tanggal');
const infoTanggal = document.getElementById('infoTanggal');

const bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const hariNama = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

const currentYear = new Date().getFullYear();
for (let i = currentYear - 2; i <= currentYear + 1; i++) {
    let o = document.createElement('option');
    o.value = i; o.textContent = i;
    if (i === currentYear) o.selected = true;
    tahunSelect.appendChild(o);
}

bulanNama.forEach((b,i)=>{
    let o=document.createElement('option');
    o.value=i; o.textContent=b;
    if(i===new Date().getMonth()) o.selected=true;
    bulanSelect.appendChild(o);
});

function isiTanggal(){
    tanggalSelect.innerHTML='';
    const year=parseInt(tahunSelect.value);
    const month=parseInt(bulanSelect.value);
    const lastDay=new Date(year,month+1,0).getDate();

    for(let i=1;i<=lastDay;i++){
        let o=document.createElement('option');
        o.value=i; o.textContent=i;
        tanggalSelect.appendChild(o);
    }

    tanggalSelect.value=new Date().getDate();
    tampilkanInfoTanggal();
}
isiTanggal();

function tampilkanInfoTanggal(){
    const y=tahunSelect.value;
    const m=bulanSelect.value;
    const d=tanggalSelect.value;

    const date=new Date(y,m,d);
    const hari=hariNama[date.getDay()];
    infoTanggal.textContent=`${hari}, ${d} ${bulanNama[m]} ${y}`;

    document.getElementById('tanggal_input').value = `${y}-${parseInt(m)+1}-${d}`;
}

tahunSelect.addEventListener('change', isiTanggal);
bulanSelect.addEventListener('change', isiTanggal);
tanggalSelect.addEventListener('change', tampilkanInfoTanggal);

/* ------------- KETERANGAN OTOMATIS ------------- */
document.querySelectorAll('.statusRadio').forEach(radio => {
    radio.addEventListener('change', function () {
        let row = this.closest('tr');
        let ket = row.querySelector('.ketInput');

        if (["Izin","Sakit"].includes(this.value)) {
            ket.classList.remove('hidden');
        } else {
            ket.classList.add('hidden');
            ket.value = "";
        }
    });
});

/* ------------- VALIDASI SEBELUM SUBMIT ------------- */
document.getElementById("formAbsensi").addEventListener("submit", function(event) {
    const rows = document.querySelectorAll("tbody tr");
    let valid = true;
    let message = "";

    rows.forEach(row => {

        const radios = row.querySelectorAll(".statusRadio");
        let selected = null;
        radios.forEach(r => { if (r.checked) selected = r.value });

        const ketInput = row.querySelector(".ketInput");

        if (!selected) {
            valid = false;
            message = "Masih ada karyawan yang belum memilih status!";
            return;
        }

        // HANYA SAkit & Izin yang wajib keterangan
        if (["Izin", "Sakit"].includes(selected)) {
            if (!ketInput.value.trim()) {
                valid = false;
                message = "Keterangan wajib diisi untuk status Izin atau Sakit!";
                return;
            }
        }
    });

    if (!valid) {
        event.preventDefault();
        showToast(message, "error");
    }
});

/* ------------- TOAST FUNCTION ------------- */
function showToast(msg, type="success") {
    const toast = document.getElementById("toast");
    toast.textContent = msg;

    if (type === "error") {
        toast.classList.remove("bg-green-600");
        toast.classList.add("bg-red-600");
    } else {
        toast.classList.remove("bg-red-600");
        toast.classList.add("bg-green-600");
    }

    toast.style.opacity = "1";
    toast.style.transform = "translateY(0)";

    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(10px)";
    }, 2500);
}
</script>

@endsection

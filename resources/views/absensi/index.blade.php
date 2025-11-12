@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
<h1 class="text-2xl font-semibold mb-4">📅 Data Absensi 1 Tahun Penuh (Dummy)</h1>
<p>Isi absensi karyawan, lalu klik <strong>Submit</strong> sekali saja untuk menyimpan.</p>

<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow mt-4">
    <div class="mb-4 flex gap-2">
        <select id="tahun" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </select>
        <select id="bulan" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </select>
        <select id="minggu" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </select>
    </div>

    <form id="formAbsensi">
        <table class="w-full border-collapse border border-gray-300 mb-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-2 py-1">Tanggal</th>
                    <th class="border px-2 py-1">Nama</th>
                    <th class="border px-2 py-1">Jabatan</th>
                    <th class="border px-2 py-1">Hasil Panen (kg)</th>
                    <th class="border px-2 py-1">Upah/kg</th>
                    <th class="border px-2 py-1">Total Upah</th>
                    <th class="border px-2 py-1">Keterangan</th>
                </tr>
            </thead>
            <tbody id="tabelAbsensi"></tbody>
        </table>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Absensi</button>
    </form>
</div>

<script>
    const karyawanDummy = [
        { nama: 'Ahmad', jabatan: 'Pemanen', upahPerKg: 5000 },
        { nama: 'Budi', jabatan: 'Pemanen', upahPerKg: 5000 },
        { nama: 'Candra', jabatan: 'Mandor', upahPerKg: 7000 },
        { nama: 'Dedi', jabatan: 'Sopir', upahPerKg: 6000 },
    ];

    const tahunDummy = 2025;
    const bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    const absensiTahun = [{ tahun: tahunDummy, bulan: [] }];

    for(let m=0;m<12;m++){
        let bulanObj = { namaBulan: bulanNama[m], minggu: [] };
        for(let w=1;w<=4;w++){
            let mingguObj = { nomorMinggu: w, hari: [] };
            for(let d=1;d<=7;d++){
                const tanggal = new Date(tahunDummy, m, (w-1)*7 + d);
                karyawanDummy.forEach(k => {
                    mingguObj.hari.push({
                        tanggal: tanggal.toISOString().split('T')[0],
                        nama: k.nama,
                        jabatan: k.jabatan,
                        hasilPanen: (k.jabatan==='Pemanen')?Math.floor(Math.random()*21)+40:0,
                        upahPerKg: k.upahPerKg,
                        keterangan: 'Hadir'
                    });
                });
            }
            bulanObj.minggu.push(mingguObj);
        }
        absensiTahun[0].bulan.push(bulanObj);
    }

    const tahunSelect = document.getElementById('tahun');
    const bulanSelect = document.getElementById('bulan');
    const mingguSelect = document.getElementById('minggu');
    const tbody = document.getElementById('tabelAbsensi');

    function hitungTotalUpah(item){
        return (item.keterangan==='Hadir') ? item.hasilPanen * item.upahPerKg : 0;
    }

    function tampilkanBulan(tahun){
        bulanSelect.innerHTML = '';
        const dataTahun = absensiTahun.find(t => t.tahun==tahun);
        dataTahun.bulan.forEach((b,i) => {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = b.namaBulan;
            bulanSelect.appendChild(opt);
        });
        tampilkanMinggu(tahun, 0);
    }

    function tampilkanMinggu(tahun, bulanIndex){
        mingguSelect.innerHTML = '';
        const dataTahun = absensiTahun.find(t => t.tahun==tahun);
        const bulan = dataTahun.bulan[bulanIndex];
        bulan.minggu.forEach((m,i) => {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = `Minggu ${m.nomorMinggu}`;
            mingguSelect.appendChild(opt);
        });
        tampilkanTabel(tahun, bulanIndex, 0);
    }

    function tampilkanTabel(tahun, bulanIndex, mingguIndex){
        tbody.innerHTML = '';
        const hari = absensiTahun.find(t => t.tahun==tahun).bulan[bulanIndex].minggu[mingguIndex].hari;
        hari.forEach((item,index) => {
            const tr = document.createElement('tr');
            tr.classList.add('border-b');
            tr.innerHTML = `
                <td class="border px-2 py-1">${item.tanggal}</td>
                <td class="border px-2 py-1">${item.nama}</td>
                <td class="border px-2 py-1">${item.jabatan}</td>
                <td class="border px-2 py-1">${item.hasilPanen}</td>
                <td class="border px-2 py-1">${item.upahPerKg}</td>
                <td class="border px-2 py-1">${hitungTotalUpah(item)}</td>
                <td class="border px-2 py-1">
                    <select class="border rounded px-2 py-1 keterangan-dropdown w-full">
                        <option ${item.keterangan==='Hadir'?'selected':''}>Hadir</option>
                        <option ${item.keterangan==='Izin'?'selected':''}>Izin</option>
                        <option ${item.keterangan==='Sakit'?'selected':''}>Sakit</option>
                    </select>
                </td>
            `;
            tbody.appendChild(tr);

            tr.querySelector('.keterangan-dropdown').addEventListener('change', function(){
                item.keterangan = this.value;
                tr.children[5].textContent = hitungTotalUpah(item);
            });
        });
    }

    // Populate Tahun
    const optTahun = document.createElement('option');
    optTahun.value = tahunDummy;
    optTahun.textContent = tahunDummy;
    tahunSelect.appendChild(optTahun);

    tahunSelect.addEventListener('change', ()=> tampilkanBulan(tahunSelect.value));
    bulanSelect.addEventListener('change', ()=> tampilkanMinggu(tahunSelect.value, bulanSelect.value));
    mingguSelect.addEventListener('change', ()=> tampilkanTabel(tahunSelect.value, bulanSelect.value, mingguSelect.value));

    tampilkanBulan(tahunDummy);

    // Submit sekali
    document.getElementById('formAbsensi').addEventListener('submit', function(e){
        e.preventDefault();
        const dataToSubmit = [];
        absensiTahun[0].bulan.forEach(bulan => {
            bulan.minggu.forEach(minggu => {
                minggu.hari.forEach(item => {
                    dataToSubmit.push(item);
                });
            });
        });
        console.log('Data yang dikirim ke backend:', dataToSubmit);
        alert('Absensi berhasil di-submit! Cek console untuk data dummy.');
        // Di sini nanti bisa pakai axios/fetch POST ke backend
    });
</script>
@endsection

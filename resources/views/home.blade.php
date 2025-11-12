@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<section class="py-16 bg-gray-50">
  <div class="max-w-6xl mx-auto text-center px-6">
    <!-- Judul Utama -->
    <h2 class="text-3xl font-bold text-gray-800 mb-4">
      Selamat Datang di PT Irfan Sawit Jaya
    </h2>

    <!-- Deskripsi Singkat -->
    <p class="text-gray-700 max-w-3xl mx-auto leading-relaxed">
      PT Irfan Sawit Jaya merupakan perusahaan yang bergerak di bidang pengelolaan dan produksi hasil kelapa sawit.
      Kami berkomitmen untuk menerapkan sistem kerja yang profesional, transparan, dan berkelanjutan dalam mendukung
      pertumbuhan industri agribisnis di Indonesia.
    </p>

    <a href="/tentang"
       class="mt-6 inline-block bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition shadow-sm">
      Pelajari Lebih Lanjut
    </a>
  </div>
</section>

<!-- Tentang Kami -->
<section class="py-16 bg-white border-t border-gray-200">
  <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
  <div>
    <img src="{{ asset('images/perkebunan Sawit.jpg') }}" 
        alt="Perkebunan Sawit" 
        class="rounded-lg shadow-md">
    </div>

    <div>
      <h3 class="text-2xl font-semibold text-gray-800 mb-3">Tentang Kami</h3>
      <p class="text-gray-700 leading-relaxed">
        Berdiri dengan komitmen untuk menjaga kualitas, efisiensi, dan keberlanjutan, PT Irfan Sawit Jaya berfokus
        pada peningkatan hasil produksi kelapa sawit melalui penerapan teknologi modern dan sistem manajemen yang terukur.
        Kami percaya bahwa kemajuan bisnis harus berjalan seiring dengan tanggung jawab terhadap lingkungan dan masyarakat.
      </p>
    </div>
  </div>
</section>

<!-- Nilai Perusahaan -->
<section class="py-16 bg-gray-50 border-t border-gray-200">
  <div class="max-w-6xl mx-auto px-6 text-center">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Nilai & Prinsip Kami</h3>
    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h4 class="font-bold text-gray-800 mb-2">Keberlanjutan</h4>
        <p class="text-gray-600">
          Kami berupaya menjalankan operasional dengan menjaga keseimbangan antara produktivitas dan pelestarian alam.
        </p>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h4 class="font-bold text-gray-800 mb-2">Integritas</h4>
        <p class="text-gray-600">
          Kejujuran, keterbukaan, dan tanggung jawab menjadi prinsip utama dalam seluruh kegiatan kami.
        </p>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h4 class="font-bold text-gray-800 mb-2">Profesionalisme</h4>
        <p class="text-gray-600">
          Kami menjunjung tinggi etika kerja dan dedikasi dalam memberikan layanan terbaik bagi semua mitra kami.
        </p>
      </div>
    </div>
  </div>
</section>
@endsection

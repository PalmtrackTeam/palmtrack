@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<section class="py-16 text-center">
  <h2 class="text-3xl font-bold text-green-800 mb-6">Produk Kami</h2>

  <p class="text-gray-600 max-w-3xl mx-auto mb-12">
    PT Irfan Sawit Jaya menghasilkan berbagai produk turunan kelapa sawit berkualitas tinggi 
    yang digunakan untuk kebutuhan industri pangan, energi, kosmetik, dan pertanian. 
    Berikut beberapa produk unggulan kami.
  </p>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto px-6">

    <!-- Produk 1 -->
    <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition">
      <img src="{{ asset('images/cpo.jpg') }}" alt="CPO" class="rounded mb-4 mx-auto w-full h-48 object-cover">
      <h3 class="text-lg font-bold text-green-700">Minyak Sawit Mentah (CPO)</h3>
      <p class="text-gray-600 mt-2">Produk utama hasil ekstraksi dari buah kelapa sawit segar, digunakan sebagai bahan baku minyak goreng dan biodiesel.</p>
    </div>

    <!-- Produk 2 -->
    <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition">
      <img src="{{ asset('images/kernel-oil.jpg') }}" alt="Kernel Oil" class="rounded mb-4 mx-auto w-full h-48 object-cover">
      <h3 class="text-lg font-bold text-green-700">Kernel Oil</h3>
      <p class="text-gray-600 mt-2">Minyak inti sawit dengan kualitas tinggi, banyak dimanfaatkan dalam industri kosmetik, sabun, dan makanan olahan.</p>
    </div>

    <!-- Produk 3 -->
    <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition">
      <img src="{{ asset('images/organic-fertilizer.jpg') }}" alt="Pupuk Organik" class="rounded mb-4 mx-auto w-full h-48 object-cover">
      <h3 class="text-lg font-bold text-green-700">Pupuk Organik</h3>
      <p class="text-gray-600 mt-2">Hasil pengolahan limbah padat kelapa sawit menjadi pupuk organik ramah lingkungan yang meningkatkan kesuburan tanah.</p>
    </div>

    <!-- Produk 4 -->
    <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition">
      <img src="{{ asset('images/pko-cake.jpg') }}" alt="Palm Kernel Cake" class="rounded mb-4 mx-auto w-full h-48 object-cover">
      <h3 class="text-lg font-bold text-green-700">Palm Kernel Cake (PKC)</h3>
      <p class="text-gray-600 mt-2">Produk sampingan dari proses pengolahan inti sawit, digunakan sebagai bahan pakan ternak berkualitas tinggi.</p>
    </div>

    <!-- Produk 5 -->
    <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition">
      <img src="{{ asset('images/biodiesel.jpg') }}" alt="Biodiesel" class="rounded mb-4 mx-auto w-full h-48 object-cover">
      <h3 class="text-lg font-bold text-green-700">Biodiesel Sawit</h3>
      <p class="text-gray-600 mt-2">Energi alternatif ramah lingkungan hasil dari pengolahan minyak sawit, mendukung program energi hijau nasional.</p>
    </div>

    <!-- Produk 6 -->
    <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition">
      <img src="{{ asset('images/olein.jpg') }}" alt="RBD Olein" class="rounded mb-4 mx-auto w-full h-48 object-cover">
      <h3 class="text-lg font-bold text-green-700">RBD Olein</h3>
      <p class="text-gray-600 mt-2">Minyak goreng hasil pemurnian CPO, jernih dan stabil pada suhu tinggi, ideal untuk kebutuhan rumah tangga dan industri.</p>
    </div>

  </div>
</section>
@endsection

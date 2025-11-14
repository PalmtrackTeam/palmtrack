@extends('layouts.guest')

@section('title', 'Kontak Kami')

@section('content')
<section class="py-16 bg-gray-50">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl font-bold text-gray-800 text-center mb-6">Hubungi Kami</h2>
    <p class="text-gray-600 text-center max-w-2xl mx-auto mb-12">
      Kami siap membantu Anda. Silakan hubungi kami melalui formulir di bawah ini atau melalui informasi kontak yang tersedia.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <!-- Form Kontak -->
      <div class="bg-white shadow rounded-lg p-8">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Kirim Pesan</h3>
        <form action="#" method="POST">
          @csrf
          <div class="mb-4">
            <label class="block text-left text-gray-700 font-medium mb-2">Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Masukkan nama Anda" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400">
          </div>
          <div class="mb-4">
            <label class="block text-left text-gray-700 font-medium mb-2">Email</label>
            <input type="email" name="email" placeholder="Masukkan email Anda" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400">
          </div>
          <div class="mb-4">
            <label class="block text-left text-gray-700 font-medium mb-2">Pesan</label>
            <textarea name="pesan" rows="4" placeholder="Tulis pesan Anda di sini..." class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-green-400"></textarea>
          </div>
          <button type="submit" class="bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-800 transition">
            Kirim Pesan
          </button>
        </form>
      </div>

      <!-- Informasi Kontak -->
      <div class="bg-white shadow rounded-lg p-8 text-left">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Informasi Kontak</h3>
        <p class="text-gray-600 mb-4">Anda juga dapat menghubungi kami secara langsung melalui detail berikut:</p>
        <ul class="space-y-3 text-gray-700">
          <li><strong>Alamat:</strong> Jl. Lintas Sumatera No. 12, Kecamatan Rantau Prapat, Sumatera Utara</li>
          <li><strong>Email:</strong> info@irfansawitjaya.co.id</li>
          <li><strong>Telepon:</strong> (061) 123-4567</li>
          <li><strong>Jam Operasional:</strong> Senin - Jumat, 08.00 - 17.00 WIB</li>
        </ul>

        <div class="mt-6">
          <h4 class="text-green-700 font-semibold mb-2">Lokasi Kami</h4>
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7979.698767495059!2d99.8283!3d2.1024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x302e0519b33e0e3d%3A0x8f62e0a83e6bce1a!2sRantau%20Prapat!5e0!3m2!1sid!2sid!4v1700000000000"
            width="100%" 
            height="220" 
            class="rounded shadow" 
            style="border:0;" 
            allowfullscreen 
            loading="lazy">
          </iframe>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

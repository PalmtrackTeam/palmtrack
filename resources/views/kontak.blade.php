@extends('layouts.guest')

@section('title', 'Kontak Kami')

@section('content')
<section class="py-20 bg-gray-50">
  <div class="max-w-5xl mx-auto px-6">

    {{-- Judul Halaman --}}
    <h2 class="text-4xl font-medium text-black text-center mb-4">
        Hubungi Kami
    </h2>
    <p class="text-gray-600 text-center max-w-2xl mx-auto mb-14">
        Jika Anda membutuhkan informasi lebih lanjut, 
        jangan ragu untuk menghubungi kami melalui detail berikut.
    </p>

    <div class="bg-white shadow-lg rounded-2xl p-10">
        <h3 class="text-2xl font-semibold text-green-700 mb-6">
            Informasi Kontak
        </h3>

        <ul class="space-y-4 text-gray-700 text-lg">
          <li>
            <span class="font-semibold text-green-800">Alamat:</span>
            Jl. Lintas Sumatera No. 12, Kecamatan Rantau Prapat, Sumatera Utara
          </li>

          <li>
            <span class="font-semibold text-green-800">Email:</span>
            info@irfansawitjaya.co.id
          </li>

          <li>
            <span class="font-semibold text-green-800">Telepon:</span>
            (061) 123-4567
          </li>

          <li>
            <span class="font-semibold text-green-800">Jam Operasional:</span>
            Senin – Jumat, 08.00 – 17.00 WIB
          </li>
        </ul>

        {{-- MAP --}}
        <div class="mt-10">
          <h4 class="text-xl font-semibold text-green-700 mb-3">
              Lokasi Kami
          </h4>

          <div class="rounded-xl overflow-hidden shadow-md">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7979.698767495059!2d99.8283!3d2.1024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x302e0519b33e0e3d%3A0x8f62e0a83e6bce1a!2sRantau%20Prapat!5e0!3m2!1sid!2sid!4v1700000000000"
              width="100%" 
              height="300" 
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

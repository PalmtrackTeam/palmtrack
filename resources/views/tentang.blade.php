@extends('layouts.guest')

@section('title', 'Tentang Kami')

@section('content')
<section class="py-16 text-center">
  <h2 class="text-3xl font-bold text-gray-800 mb-6">Tentang Kami</h2>
  <div class="max-w-4xl mx-auto text-gray-700 leading-relaxed space-y-6 px-4">
    <p>
      <strong>PT Irfan Sawit Jaya</strong> merupakan perusahaan yang bergerak di bidang 
      pengolahan dan distribusi hasil kelapa sawit. Sejak awal berdiri, kami berkomitmen untuk 
      menghadirkan produk kelapa sawit yang berkualitas tinggi, bernilai ekonomi, dan ramah lingkungan. 
      Kami percaya bahwa industri sawit dapat berjalan selaras dengan prinsip keberlanjutan 
      apabila dikelola secara bertanggung jawab.
    </p>

    <p>
      Dengan dukungan tenaga kerja profesional dan fasilitas produksi yang modern, 
      PT Irfan Sawit Jaya terus berinovasi dalam meningkatkan efisiensi proses pengolahan, 
      menjaga mutu produk, serta memastikan praktik operasional yang transparan dan sesuai 
      dengan standar nasional maupun internasional.
    </p>

    <p>
      Kami juga berperan aktif dalam pemberdayaan masyarakat sekitar melalui berbagai program 
      tanggung jawab sosial perusahaan (CSR), seperti pelatihan pertanian berkelanjutan, 
      peningkatan kesejahteraan petani plasma, serta pelestarian lingkungan di area operasional kami.
    </p>

    <p>
      Dalam menjalankan seluruh kegiatan bisnis, kami berpedoman pada nilai-nilai utama yaitu 
      <strong>Integritas, Profesionalisme, dan Keberlanjutan</strong>. Nilai-nilai tersebut menjadi 
      dasar kami dalam membangun hubungan jangka panjang dengan seluruh pemangku kepentingan — 
      mulai dari mitra bisnis, pemerintah, hingga masyarakat.
    </p>

    <div class="mt-10 flex justify-center">
      <img src="{{ asset('images/perkebunan Sawit.jpg') }}" 
           alt="Perkebunan Sawit PT Irfan Sawit Jaya" 
           class="rounded-lg shadow-md w-full max-w-3xl">
    </div>

    <p class="pt-8">
      Melalui komitmen tersebut, PT Irfan Sawit Jaya bertekad untuk menjadi salah satu perusahaan 
      kelapa sawit yang tidak hanya unggul dalam produktivitas, tetapi juga memberikan kontribusi nyata 
      bagi pembangunan ekonomi berkelanjutan di Indonesia.
    </p>
  </div>
</section>
@endsection

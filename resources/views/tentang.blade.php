@extends('layouts.guest')

@section('title', 'Tentang Kami')

@section('content')

<!-- PROFIL PERUSAHAAN -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">

        <div class="space-y-6 text-gray-700 leading-relaxed">
            <h2 class="text-3xl font-bold text-gray-800">Profil Perusahaan</h2>
            <p>
                <strong class="text-green-700">PT Irfan Sawit Jaya</strong> berdiri sebagai perusahaan yang berfokus pada 
                pengolahan dan distribusi hasil kelapa sawit dengan standar tinggi.
            </p>
            <p>
                Dengan fasilitas modern dan tenaga ahli profesional, kami memastikan seluruh proses berjalan efisien, 
                bersih, dan memenuhi standar.
            </p>
            <p>
                Kami percaya bahwa industri sawit dapat menjadi pilar ekonomi yang kuat apabila dikelola secara 
                bertanggung jawab dan berkelanjutan.
            </p>
        </div>

        <img src="{{ asset('images/perkebunan Sawit.jpg') }}" 
             class="rounded-xl shadow-lg w-full">
    </div>
</section>

<!-- VISI MISI -->
<section class="py-20 bg-gray-100">
    <div class="max-w-6xl mx-auto px-6">

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Visi & Misi Kami</h2>

        <div class="grid md:grid-cols-2 gap-10">

            <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-200">
                <h3 class="text-2xl font-semibold text-green-700 mb-3">Visi</h3>
                <p class="text-gray-700 leading-relaxed">
                    Menjadi perusahaan kelapa sawit yang unggul, berdaya saing global, dan memberikan manfaat berkelanjutan 
                    bagi lingkungan serta masyarakat.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-200">
                <h3 class="text-2xl font-semibold text-green-700 mb-3">Misi</h3>
                <ul class="space-y-3 text-gray-700 leading-relaxed">
                    <li>✔ Menghasilkan produk sawit berkualitas tinggi.</li>
                    <li>✔ Mengembangkan proses produksi yang modern & ramah lingkungan.</li>
                    <li>✔ Menjalin kemitraan strategis yang berkelanjutan.</li>
                </ul>
            </div>

        </div>

    </div>
</section>

<!-- KOMITMEN KAMI -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">

        <img src="{{ asset('images/perkebunan Sawit.jpg') }}" 
             class="rounded-xl shadow-lg w-full md:order-1 order-2">

        <div class="space-y-6 text-gray-700 leading-relaxed md:order-2 order-1">
            <h2 class="text-3xl font-bold text-gray-800">Komitmen Kami</h2>
            <p>
              PT Irfan Sawit Jaya berusaha meningkatkan kualitas operasional secara bertahap, termasuk memperbaiki proses produksi dan memperkuat manajemen usaha.
            </p>
            <p>
              Kami juga mulai menjalankan beberapa kegiatan sosial dasar seperti menjaga hubungan baik dengan masyarakat sekitar serta turut mendukung kegiatan lingkungan di area operasional.
            </p>
            <p>
              Langkah-langkah kecil ini menjadi bagian dari komitmen kami untuk tumbuh sebagai perusahaan yang bertanggung jawab dan memberikan dampak positif bagi lingkungan sekitar.
        </div>

    </div>
</section>

@endsection

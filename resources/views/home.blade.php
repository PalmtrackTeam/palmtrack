@extends('layouts.guest')

@section('title', 'Beranda')

@section('content')

{{-- ======================= HERO SLIDER ======================= --}}
<section class="relative">
    <!-- BG SLIDER -->
    <div id="hero-bg" class="absolute inset-0 overflow-hidden">

        <!-- Slide 1 -->
        <div class="hero-slide absolute inset-0 bg-center bg-cover opacity-100 transition-opacity duration-1000"
            style="background-image: url('{{ asset('images/slider1.jpg') }}');"></div>

        <!-- Slide 2 -->
        <div class="hero-slide absolute inset-0 bg-center bg-cover opacity-0 transition-opacity duration-1000"
            style="background-image: url('{{ asset('images/slider2.jpg') }}');"></div>

        <!-- Slide 3 -->
        <div class="hero-slide absolute inset-0 bg-center bg-cover opacity-0 transition-opacity duration-1000"
            style="background-image: url('{{ asset('images/slider3.jpg') }}');"></div>

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black/50"></div>
    </div>

    <!-- CONTENT -->
    <div class="relative py-32"> {{-- Bikin overlay terlihat lebih panjang --}}
        <div class="max-w-6xl mx-auto text-center px-6">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white drop-shadow-md mb-4">
                Selamat Datang di PT Irfan Sawit Jaya
            </h2>
            <p class="text-white/90 max-w-3xl mx-auto leading-relaxed mb-8">
                PT Irfan Sawit Jaya merupakan perusahaan yang bergerak di bidang pengelolaan dan produksi hasil kelapa sawit.
                Kami berkomitmen untuk menerapkan sistem kerja yang profesional, transparan, dan berkelanjutan.
            </p>

            <a href="/tentang"
                class="inline-block bg-amber-400 text-gray-900 px-6 py-3 rounded-lg font-medium hover:brightness-95 transition">
                Pelajari Lebih Lanjut
            </a>
        </div>

        <!-- Arrows -->
        <button id="heroPrev" aria-label="Previous slide"
            class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/80 text-gray-700 p-3 rounded-full shadow-md hover:bg-white">
            &#10094;
        </button>
        <button id="heroNext" aria-label="Next slide"
            class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/80 text-gray-700 p-3 rounded-full shadow-md hover:bg-white">
            &#10095;
        </button>

        <!-- Dots -->
        <div id="heroDots" class="absolute left-1/2 -translate-x-1/2 bottom-8 flex gap-3"></div>
    </div>
</section>

{{-- ======================= Home SECTION ======================= --}}
<section class="py-16 bg-white border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
        <!-- Teks -->
        <div>
            <h3 class="text-3xl font-bold text-gray-800 mb-4">Kelapa Sawit</h3>
            <p class="text-gray-700 leading-relaxed mb-4">
                Sektor perkebunan dan pabrik pengolahan kelapa sawit merupakan segmen bisnis utama Perseroan 
                dalam beberapa tahun terakhir ini. Segmen kelapa sawit memberikan kontribusi pendapatan di atas 
                80% dari total pendapatan Perseroan.
            </p>
            <p class="text-gray-700 leading-relaxed">
                Masuknya Perseroan ke dalam sektor usaha kelapa sawit tak lepas dari pesatnya perkembangan 
                industri kelapa sawit dan produk turunannya akibat meningkatnya permintaan minyak nabati global. 
                Perseroan mengambil peluang tersebut dan berhasil membuahkan kesuksesan sehingga menjadi salah 
                satu perusahaan terkemuka dalam bidang industri pengolahan kelapa sawit.
            </p>
        </div>

        <!-- Gambar -->
        <div>
            <img src="{{ asset('images/sawit1.jpg') }}" 
                 alt="Kebun Sawit" 
                 class="rounded-lg shadow-md">
        </div>
    </div>
</section>

<section class="py-16 bg-white border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">

        <!-- Gambar (kiri) -->
        <div>
            <img src="{{ asset('images/sawit2.jpg') }}"
                 alt="Kebun Sawit"
                 class="rounded-lg shadow-md">
        </div>

        <!-- Teks (kanan) -->
        <div>
            <h3 class="text-3xl font-bold text-gray-800 mb-4">Kelapa Sawit</h3>
            <p class="text-gray-700 leading-relaxed mb-4">
                Sektor perkebunan dan pabrik pengolahan kelapa sawit merupakan segmen bisnis utama Perseroan 
                dalam beberapa tahun terakhir ini. Segmen kelapa sawit memberikan kontribusi pendapatan di atas 
                80% dari total pendapatan Perseroan.
            </p>
            <p class="text-gray-700 leading-relaxed">
                Masuknya Perseroan ke dalam sektor usaha kelapa sawit tak lepas dari pesatnya perkembangan 
                industri kelapa sawit dan produk turunannya akibat meningkatnya permintaan minyak nabati global. 
                Perseroan mengambil peluang tersebut dan berhasil membuahkan kesuksesan sehingga menjadi salah 
                satu perusahaan terkemuka dalam bidang industri pengolahan kelapa sawit.
            </p>
        </div>

    </div>
</section>

{{-- ======================= VALUES SECTION ======================= --}}
<section class="py-20 bg-gray-50 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 text-center">
        
        <h3 class="text-3xl font-bold text-gray-800 mb-12">Nilai & Prinsip Kami</h3>

        <div class="grid md:grid-cols-3 gap-10">

            <!-- ITEM 1 -->
            <div class="relative group rounded-xl overflow-hidden shadow-md">
                <img src="{{ asset('images/prinsip1.jpg') }}" 
                     class="w-full h-56 object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/50 group-hover:bg-black/40 transition"></div>
                
                <div class="absolute bottom-0 p-6 text-left text-white">
                    <h4 class="text-2xl font-semibold mb-1">Keberlanjutan</h4>
                    <p class="text-sm text-gray-200">
                        Menjaga keseimbangan antara produktivitas dan pelestarian lingkungan.
                    </p>
                </div>
            </div>

            <!-- ITEM 2 -->
            <div class="relative group rounded-xl overflow-hidden shadow-md">
                <img src="{{ asset('images/prinsip2.jpg') }}" 
                     class="w-full h-56 object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/50 group-hover:bg-black/40 transition"></div>

                <div class="absolute bottom-0 p-6 text-left text-white">
                    <h4 class="text-2xl font-semibold mb-1">Integritas</h4>
                    <p class="text-sm text-gray-200">
                        Kejujuran, transparansi, dan tanggung jawab dalam seluruh kegiatan.
                    </p>
                </div>
            </div>

            <!-- ITEM 3 -->
            <div class="relative group rounded-xl overflow-hidden shadow-md">
                <img src="{{ asset('images/prinsip3.jpg') }}" 
                     class="w-full h-56 object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/50 group-hover:bg-black/40 transition"></div>

                <div class="absolute bottom-0 p-6 text-left text-white">
                    <h4 class="text-2xl font-semibold mb-1">Profesionalisme</h4>
                    <p class="text-sm text-gray-200">
                        Etika kerja tinggi dan pelayanan terbaik bagi mitra perusahaan.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

@include('partials.footer')

{{-- SLIDER SCRIPT --}}
<script>
  (function() {
    const slides = Array.from(document.querySelectorAll('.hero-slide'));
    const dotsWrap = document.getElementById('heroDots');
    const prevBtn = document.getElementById('heroPrev');
    const nextBtn = document.getElementById('heroNext');
    let idx = 0;
    const intervalMs = 4500;
    let timer;

    // generate dots
    slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = 'w-3 h-3 rounded-full bg-white/60 hover:bg-white transition-opacity';
      dot.addEventListener('click', () => { goTo(i); restart(); });
      dotsWrap.appendChild(dot);
    });

    const dots = [...dotsWrap.children];

    function show(i) {
      slides.forEach((s, j) => s.style.opacity = (i === j ? '1' : '0'));
      dots.forEach((d, j) => d.style.opacity = (i === j ? '1' : '0.4'));
      idx = i;
    }

    function next() { show((idx + 1) % slides.length); }
    function prev() { show((idx - 1 + slides.length) % slides.length); }
    function goTo(i) { show(i); }

    function start() { timer = setInterval(next, intervalMs); }
    function stop() { clearInterval(timer); }
    function restart() { stop(); start(); }

    show(0);
    start();

    nextBtn.onclick = () => { next(); restart(); };
    prevBtn.onclick = () => { prev(); restart(); };

    const hero = document.querySelector('section');
    hero.onmouseenter = stop;
    hero.onmouseleave = start;

  })();
</script>

@endsection

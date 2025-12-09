@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ======================= HERO SLIDER ======================= --}}
<section class="relative">
  <!-- BG SLIDER -->
  <div id="hero-bg" class="absolute inset-0 overflow-hidden">

      <!-- Slide 1 -->
      <div class="hero-slide absolute inset-0 bg-center bg-cover opacity-100 transition-opacity duration-1000"
          style="background-image: url('https://i0.wp.com/gapki.id/dir-site/uploads/2016/06/Multifungsi-Kebun-Sawit-Dalam-Ekosistem.jpg?resize=1200%2C732&ssl=1');"></div>

      <!-- Slide 2 -->
      <div class="hero-slide absolute inset-0 bg-center bg-cover opacity-0 transition-opacity duration-1000"
          style="background-image: url('https://images.unsplash.com/photo-1586015555751-63cf04886bbb?auto=format&fit=crop&w=1920&q=80');"></div>

      <!-- Slide 3 -->
      <div class="hero-slide absolute inset-0 bg-center bg-cover opacity-0 transition-opacity duration-1000"
          style="background-image: url('https://i0.wp.com/gapki.id/dir-site/uploads/2018/07/perkebunan-kelapa-sawit.jpg?resize=1200%2C800&ssl=1');"></div>

      <!-- Dark Overlay -->
      <div class="absolute inset-0 bg-black/40"></div>
  </div>

  <!-- CONTENT -->
  <div class="relative py-24">
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

{{-- ======================= ABOUT SECTION ======================= --}}
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
                Berdiri dengan komitmen untuk menjaga kualitas, efisiensi, dan keberlanjutan, PT Irfan Sawit Jaya
                berfokus pada peningkatan hasil produksi kelapa sawit melalui teknologi modern dan manajemen terukur.
            </p>
        </div>
    </div>
</section>

{{-- ======================= VALUES SECTION ======================= --}}
<section class="py-16 bg-gray-50 border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Nilai & Prinsip Kami</h3>
        <div class="grid md:grid-cols-3 gap-8">

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-2">Keberlanjutan</h4>
                <p class="text-gray-600">Menjaga keseimbangan antara produktivitas dan pelestarian lingkungan.</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-2">Integritas</h4>
                <p class="text-gray-600">Kejujuran, transparansi, dan tanggung jawab dalam seluruh kegiatan.</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-2">Profesionalisme</h4>
                <p class="text-gray-600">Etika kerja yang tinggi dan pelayanan terbaik bagi mitra perusahaan.</p>
            </div>

        </div>
    </div>
</section>

@endsection

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

  slides.forEach((_, i) => {
    const dot = document.createElement('button');
    dot.className = 'w-3 h-3 rounded-full bg-white/60 hover:bg-white transition-opacity';
    dot.addEventListener('click', () => { show(i); restart(); });
    dotsWrap.appendChild(dot);
  });

  const dots = Array.from(dotsWrap.children);

  function show(i) {
    slides.forEach((s, j) => s.style.opacity = (j === i ? 1 : 0));
    dots.forEach((d, j) => d.style.opacity = (j === i ? 1 : 0.5));
    idx = i;
  }

  function next() { show((idx + 1) % slides.length); }
  function prev() { show((idx - 1 + slides.length) % slides.length); }

  function start() { timer = setInterval(next, intervalMs); }
  function stop() { clearInterval(timer); }
  function restart() { stop(); start(); }

  show(0); start();

  nextBtn.addEventListener('click', () => { next(); restart(); });
  prevBtn.addEventListener('click', () => { prev(); restart(); });

})();
</script>

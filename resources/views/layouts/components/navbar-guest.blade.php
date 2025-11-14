<nav class="bg-white dark:bg-gray-800 shadow-md border-b border-gray-200 dark:border-gray-700">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <div class="flex items-center space-x-3">
      <img src="{{ asset('images/logo.png') }}" alt="Logo PT Irfan Sawit Jaya" class="w-8 h-8">
      <span class="font-semibold text-lg text-gray-800 dark:text-gray-100">PT Irfan Sawit Jaya</span>
    </div>
    <ul class="hidden md:flex space-x-6 text-gray-700 dark:text-gray-200">
      <li><a href="/" class="hover:text-black dark:hover:text-white">Beranda</a></li>
      <li><a href="/tentang" class="hover:text-black dark:hover:text-white">Tentang Kami</a></li>
      <li><a href="/produk" class="hover:text-black dark:hover:text-white">Produk</a></li>
      <li><a href="/berita" class="hover:text-black dark:hover:text-white">Berita</a></li>
      <li><a href="/kontak" class="hover:text-black dark:hover:text-white">Kontak</a></li>
    </ul>
    <div>
      <a href="{{ route('login') }}" class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">Login</a>
    </div>
  </div>
</nav>

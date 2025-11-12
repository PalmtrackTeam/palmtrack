<nav class="bg-white text-black px-6 py-4 flex justify-between items-center border-b border-gray-200 shadow-sm">
  <div class="flex items-center space-x-3 ml-4">
    <img src="{{ asset('images/logo.png') }}" alt="Logo PT Irfan Sawit Jaya" class="w-8 h-8 object-contain">
    <h1 class="font-bold text-lg">PT Irfan Sawit Jaya</h1>
  </div>

  <ul class="flex space-x-6 items-center">
    <li><a href="/" class="hover:text-green-700">Beranda</a></li>
    <li><a href="/tentang" class="hover:text-green-700">Tentang Kami</a></li>
    <li><a href="/produk" class="hover:text-green-700">Produk</a></li>
    <li><a href="/berita" class="hover:text-green-700">Berita</a></li>
    <li><a href="/kontak" class="hover:text-green-700">Kontak</a></li>

    {{-- Tambahan dari Breeze --}}
    @auth
<li x-data="{ open: false }" class="relative">
  <button 
        @click="open = !open"
        class="flex items-center bg-gray-600 text-white px-3 py-1.5 rounded-md font-semibold hover:bg-gray-500 focus:outline-none shadow-sm"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zm0 2c-3.31 0-6 2.69-6 6v1h12v-1c0-3.31-2.69-6-6-6z" />
        </svg>
        <span>{{ Auth::user()->name }}</span>
        <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
        </svg>
      </button>

  <ul 
    x-show="open" 
    @click.away="open = false" 
    x-transition 
    class="absolute right-0 mt-2 w-44 bg-white text-black rounded-lg shadow-lg border border-gray-200"
  >
    <li>
    <a href="{{ route('profile.info') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
      <svg class="w-4 h-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 11c1.657 0 3-1.343 3-3V5a3 3 0 10-6 0v3c0 1.657 1.343 3 3 3z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M5 11h14v10H5z" />
      </svg>
      Informasi Profil
    </a>
  </li>

    <li>
      <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
        <svg class="w-4 h-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
        </svg>
        Edit Profil
      </a>
    </li>
    <li>
      <a href="{{ route('password.update') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
        <svg class="w-4 h-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3V5a3 3 0 10-6 0v3c0 1.657 1.343 3 3 3z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11h14v10H5z" />
        </svg>
        Ubah Password
      </a>
    </li>
    <li>
      <a href="{{ route('profile.destroy') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
        <svg class="w-4 h-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Hapus Akun
      </a>
    </li>
    <li>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100">
          <svg class="w-4 h-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
          </svg>
          Logout
        </button>
      </form>
    </li>
  </ul>
</li>
@else
  <li>
    <a href="{{ route('login') }}" 
       class="px-4 py-2 border border-black text-black rounded-md hover:bg-black hover:text-white transition">
      Login
    </a>
  </li>
  <li>
    <a href="{{ route('register') }}" 
       class="px-4 py-2 bg-black text-white rounded-md hover:bg-gray-800 transition">
      Register
    </a>
  </li>
@endauth
  </ul>
</nav>

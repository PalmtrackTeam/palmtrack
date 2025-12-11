<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - PT Irfan Sawit Jaya</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-white">


    {{-- ============================
            NAVIGATION BAR BARU
       ============================ --}}
    <nav class="bg-white text-black px-6 py-4 flex justify-between items-center border-b border-gray-200 shadow-sm">

        <!-- LOGO + TITLE -->
        <div class="flex items-center space-x-3 ml-4">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Logo PT Irfan Sawit Jaya" 
                 class="w-8 h-8 object-contain">
            <h1 class="font-bold text-lg">PT Irfan Sawit Jaya</h1>
        </div>
        {{-- =======================
              NAVBAR GUEST
        ======================== --}}
        @guest
        <ul class="flex space-x-6 items-center">
            <li><a href="/" class="hover:text-green-700">Beranda</a></li>
            <li><a href="/tentang" class="hover:text-green-700">Tentang Kami</a></li>
            <li><a href="/produk" class="hover:text-green-700">Produk</a></li>
            <li><a href="/berita" class="hover:text-green-700">Berita</a></li>
            <li><a href="/kontak" class="hover:text-green-700">Kontak</a></li>

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
        </ul>
        @endguest

       {{-- =======================
      NAVBAR AUTH
======================= --}}
@auth
<ul class="flex items-center space-x-4">

    <li x-data="{ open: false }" class="relative">
        <!-- BUTTON DROPDOWN -->
        <button 
            @click="open = !open"
            class="flex items-center gap-2 px-3 py-1.5 rounded-full hover:bg-gray-100 transition"
        >
            <!-- CIRCLE AVATAR -->
            <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
            </div>

            <!-- NAME -->
            <span class="font-medium text-gray-800">{{ Auth::user()->nama_lengkap }}</span>

            <svg class="w-4 h-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9l6 6 6-6" />
            </svg>
        </button>

        <!-- DROPDOWN MENU -->
        <div 
            x-show="open"
            @click.away="open = false"
            x-transition
            class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden"
        >

            <!-- HEADER INFO -->
            <div class="px-4 py-3 border-b">
                <p class="font-semibold text-gray-900">
                    {{ Auth::user()->nama_lengkap }}
                </p>
                <p class="text-sm text-gray-600">
                    {{ Auth::user()->email }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    {{ Auth::user()->role ?? 'User' }}
                </p>
            </div>

            <!-- PROFIL SAYA -->
            <a href="{{ route('profile.info') }}" 
               class="block px-4 py-2 text-gray-800 hover:bg-gray-100 text-sm">
                Profil Saya
            </a>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 text-sm font-medium">
                    Keluar
                </button>
            </form>

        </div>
    </li>

</ul>
@endauth

    </nav>


    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif

    @stack('scripts')

</body>
</html>

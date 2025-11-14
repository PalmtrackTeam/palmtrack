<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white text-gray-900">
    <!-- Navbar Guest -->
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo PT Irfan Sawit Jaya" class="w-8 h-8 object-contain">
                <span class="font-bold text-gray-800">PT Irfan Sawit Jaya</span>
            </div>

            <!-- Menu Navigasi -->
            <ul class="hidden md:flex space-x-6 text-sm font-medium text-gray-700">
                <li><a href="/" class="hover:text-gray-900">Beranda</a></li>
                <li><a href="/tentang" class="hover:text-gray-900">Tentang Kami</a></li>
                <li><a href="/produk" class="hover:text-gray-900">Produk</a></li>
                <li><a href="/berita" class="hover:text-gray-900">Berita</a></li>
                <li><a href="/kontak" class="hover:text-gray-900">Kontak</a></li>
            </ul>

            {{-- Tombol Login & Register --}}
            <div class="flex items-center space-x-3">
            <a href="{{ route('login') }}" 
                class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                Login
            </a>
            <a href="{{ route('register') }}" 
                class="border border-gray-700 text-gray-800 dark:text-gray-100 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                Register
            </a>
            <!-- <div>
                <a href="{{ route('login') }}" class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">Masuk</a>
                <a href="{{ route('register') }}" class="ml-2 border border-gray-900 text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-100 transition">Daftar</a>
            </div> -->

            </div>
        </div>
    </nav>

    <!-- Konten Halaman -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white text-black text-center py-6 mt-16 border-t border-gray-200">
        <p class="text-sm">&copy; {{ date('Y') }} PT Irfan Sawit Jaya. Semua hak dilindungi.</p>
    </footer>

</body>
</html>

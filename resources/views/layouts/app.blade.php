<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
    {{-- Alpine.js untuk interaktivitas --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans" x-data="{ sidebarOpen: true }">

    {{-- Navbar atas --}}
    @include('partials.navbar')

    {{-- Konten utama dengan sidebar --}}
    <main class="min-h-screen flex">

        {{-- Sidebar kiri --}}
        <aside 
            class="w-52 bg-white text-gray-800 flex flex-col transform transition-transform duration-300 ease-in-out shadow-lg"
        >
            <nav class="flex-1 p-4 space-y-2 text-sm">
                <a href="{{ route('beranda.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-100 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h6m8-11v10a1 1 0 01-1 1h-6"/>
                    </svg>
                    Beranda
                </a>

                <a href="{{ route('absensi.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-100 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Absensi
                </a>

                <a href="{{ route('pengeluaran.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-100 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-2.21 0-4 .79-4 2v2c0 1.21 1.79 2 4 2s4-.79 4-2v-2c0-1.21-1.79-2-4-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 12v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5"/>
                    </svg>
                    Pengeluaran
                </a>

                <a href="{{ route('pemasukan.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-100 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v8m0 0l-3-3m3 3l3-3m-9-4V5a2 2 0 012-2h10a2 2 0 012 2v3"/>
                    </svg>
                    Pemasukan
                </a>

                {{-- Dropdown Profil --}}
                <div x-data="{ open: false }" class="pt-2 border-t border-gray-200 mt-4">
                    <button @click="open = !open" class="w-full text-left py-2.5 px-4 rounded hover:bg-gray-100 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
                            </svg>
                            Profil
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" :class="{ 'rotate-180': open }"
                             class="h-4 w-4 transform transition-transform duration-200 text-gray-600"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition class="mt-2 space-y-1 pl-6">
                        <a href="{{ route('profile.edit') }}" class="block py-2 px-4 rounded hover:bg-gray-100">Edit Profil</a>
                        <a href="{{ route('password.edit') }}" class="block py-2 px-4 rounded hover:bg-gray-100">Ubah Password</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 px-4 rounded hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                        <a href="{{ route('profile.destroy') }}" class="block py-2 px-4 rounded hover:bg-gray-100 text-red-500">Hapus Akun</a>
                    </div>
                </div>
            </nav>
        </aside>

        {{-- Isi konten halaman --}}
        <div class="flex-1 p-6 transition-all duration-300">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    @vite('resources/js/app.js')
</body>
</html>

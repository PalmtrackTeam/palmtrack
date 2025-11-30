<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - PT Irfan Sawit Jaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <!-- Navigation Bar -->
    <nav class="bg-green-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold">PT Irfan Sawit Jaya</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Jika user sudah login -->
                        <span class="text-green-100">{{ auth()->user()->nama_lengkap }}</span>
                        <a href="{{ route('dashboard') }}" 
                           class="bg-green-700 hover:bg-green-600 px-3 py-1 rounded text-sm transition">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-500 px-3 py-1 rounded text-sm">
                                Logout
                            </button>
                        </form>
                    @else
                        <!-- Jika user belum login -->
                        <a href="{{ route('login') }}" 
                           class="bg-green-700 hover:bg-green-600 px-3 py-1 rounded text-sm transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-green-600 hover:bg-green-500 px-3 py-1 rounded text-sm transition">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} PT Irfan Sawit Jaya. All rights reserved.</p>
            <p class="mt-2 text-gray-400">Sistem Manajemen Perkebunan Kelapa Sawit</p>
        </div>
    </footer>

    <!-- Flash Messages -->
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
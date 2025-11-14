<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    {{-- Navbar Auth --}}
    @include('layouts.components.navbar-auth')

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.components.sidebar')

        {{-- Konten utama --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>

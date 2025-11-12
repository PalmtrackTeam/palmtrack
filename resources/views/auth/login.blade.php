<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-100 to-gray-300 dark:from-gray-900 dark:to-gray-800">

    <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-2xl px-10 py-8 w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white tracking-wide">
                PT Irfan Sawit Jaya
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masuk ke akun Anda</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-gray-700 focus:border-gray-700">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-gray-700 focus:border-gray-700">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember & Lupa Password -->
            <div class="flex items-center justify-between mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember"
                        class="rounded border-gray-300 text-gray-700 dark:bg-gray-800 dark:border-gray-700 focus:ring-gray-600">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <!-- Tombol -->
            <button type="submit"
                class="w-full bg-gray-800 hover:bg-black text-white py-2 rounded-lg transition-all duration-200">
                Masuk
            </button>

            <!-- Daftar -->
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-gray-700 dark:text-gray-200 hover:underline">
                    Daftar Sekarang
                </a>
            </p>
        </form>
    </div>

</body>
</html>

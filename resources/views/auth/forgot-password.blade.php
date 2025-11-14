<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-100 to-gray-300 dark:from-gray-900 dark:to-gray-800">

    <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-2xl px-10 py-8 w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white tracking-wide">
                PT Irfan Sawit Jaya
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Lupa kata sandi Anda? Tidak masalah!
            </p>
        </div>

        <!-- Pesan informasi -->
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 text-center">
            Masukkan alamat email Anda, dan kami akan mengirimkan link untuk mengatur ulang kata sandi.
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 dark:text-green-400 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-gray-700 focus:border-gray-700">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="flex items-center justify-end mt-6">
                <button type="submit"
                    class="w-full bg-gray-800 hover:bg-black text-white py-2 rounded-lg transition-all duration-200">
                    Kirim Link Reset Password
                </button>
            </div>

            <!-- Kembali ke login -->
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                Sudah ingat password Anda?
                <a href="{{ route('login') }}" class="font-semibold text-gray-700 dark:text-gray-200 hover:underline">
                    Kembali ke Login
                </a>
            </p>
        </form>
    </div>

</body>
</html>

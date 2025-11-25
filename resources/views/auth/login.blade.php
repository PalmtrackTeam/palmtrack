<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-md rounded-2xl px-10 py-8 border border-gray-200">

        <!-- Title -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                PT Irfan Sawit Jaya
            </h1>
            <p class="text-gray-600 text-sm mt-1">
                Silakan masuk ke akun Anda
            </p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

      <!-- Username / Email -->
<div class="mb-4">
    <label class="text-sm font-medium text-gray-700">Username atau Email</label>
    <input type="text" name="login" value="{{ old('login') }}" required
        class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700"
        placeholder="Masukkan username atau email">
</div>

            <!-- Password -->
            <div class="mb-4">
                <label class="text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required
                    class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700">
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember"
                        class="rounded border-gray-300 text-gray-700 focus:ring-gray-700">
                    <span class="ml-2">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-gray-500 hover:text-gray-700">
                        Lupa password?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-gray-800 text-white py-2 rounded-lg hover:bg-gray-700 transition font-medium shadow-sm">
                Masuk
            </button>

            <!-- Register -->
            @if (Route::has('register'))
            <p class="text-center text-sm text-gray-600 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-gray-800 hover:underline">
                    Daftar sekarang
                </a>
            </p>
            @endif
        </form>
    </div>

</body>
</html>

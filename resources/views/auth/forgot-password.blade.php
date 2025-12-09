<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | PT Irfan Sawit Jaya</title>
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
                Reset password akun Anda
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm">
                {{ session('status') }}
            </div>
        @endif

        <!-- Info text -->
        <div class="mb-4 text-sm text-gray-600">
            Lupa kata sandi? Tidak masalah. Masukkan email Anda dan kami akan mengirim link untuk mereset password.
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label class="text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700"
                    placeholder="Masukkan Email">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-gray-800 text-white py-2 rounded-lg hover:bg-gray-700 transition font-medium shadow-sm">
                Kirim Link Reset Password
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Kembali ke halaman
            <a href="{{ route('login') }}" class="font-semibold text-gray-800 hover:underline">
                Login
            </a>
        </p>

    </div>

</body>
</html>

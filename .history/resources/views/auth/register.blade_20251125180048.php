<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-100 to-gray-300 dark:from-gray-900 dark:to-gray-800">

<div class="bg-white dark:bg-gray-900 shadow-2xl rounded-2xl px-10 py-8 w-full max-w-2xl">

    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white tracking-wide">
            PT Irfan Sawit Jaya
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat akun baru Anda</p>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Username -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username *</label>
                <input name="username" type="text" value="{{ old('username') }}" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800
                       dark:text-white px-3 py-2">
            </div>

            <!-- Nama Lengkap -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap *</label>
                <input name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800
                       dark:text-white px-3 py-2">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email *</label>
                <input name="email" type="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800
                       dark:text-white px-3 py-2">
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kata Sandi *</label>
                <input name="password" type="password" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800
                       dark:text-white px-3 py-2">
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi *</label>
                <input name="password_confirmation" type="password" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800
                       dark:text-white px-3 py-2">
            </div>

            <!-- PILIH BLOK -->
            <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi *</label>
    <select name="kategori" required
    <select name="kategori" required
        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-3 py-2">
    <option value="">Pilih Lokasi</option>
    @foreach ($kategori as $k)
        <option value="{{ $k->kategori }}">{{ ucfirst($k->kategori) }}</option>
    @endforeach
</select>

</div>


            <!-- No Telepon -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No Telepon</label>
                <input name="no_telepon" type="text" value="{{ old('no_telepon') }}"
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800
                       dark:text-white px-3 py-2">
            </div>

        </div>

        <!-- Alamat -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
            <textarea name="alamat" rows="3"
                      class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white px-3 py-2">{{ old('alamat') }}</textarea>
        </div>

        <button type="submit"
                class="w-full bg-gray-800 hover:bg-black text-white py-2 rounded-lg font-medium">
            Daftar Akun
        </button>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold hover:underline">Masuk Sekarang</a>
        </p>

    </form>
</div>

</body>
</html>

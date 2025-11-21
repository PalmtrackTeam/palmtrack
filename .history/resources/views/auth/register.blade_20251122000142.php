<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-100 to-gray-300 dark:from-gray-900 dark:to-gray-800">

    <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-2xl px-10 py-8 w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white tracking-wide">
                PT Irfan Sawit Jaya
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat akun baru Anda</p>
        </div>

        <!-- Pesan Sukses -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nama -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-gray-700 focus:border-gray-700">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-gray-700 focus:border-gray-700">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kata Sandi</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-gray-700 focus:border-gray-700">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-gray-700 focus:border-gray-700">
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pilih Role -->
            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Role</label>
                <select id="role" name="role"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg shadow-sm focus:ring-gray-700 focus:border-gray-700">
                    <option value="karyawan">Karyawan</option>
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-100 to-gray-300 dark:from-gray-900 dark:to-gray-800">

    <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-2xl px-10 py-8 w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white tracking-wide">
                PT Irfan Sawit Jaya
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat akun baru Anda</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nama -->
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input id="nama" name="nama" type="text" value="{{ old('nama') }}" required autofocus
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor HP -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor HP</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kata Sandi</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Role</label>
                <select id="role" name="role"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="owner">Owner</option>
                    <option value="mandor">Mandor</option>
                    <option value="karyawan">Karyawan</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-gray-800 hover:bg-black text-white py-2 rounded-lg transition-all duration-200">
                Daftar Akun
            </button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-gray-700 dark:text-gray-200 hover:underline">
                    Masuk Sekarang
                </a>
            </p>
        </form>
    </div>

</body>
</html>

            <!-- Tombol -->
            <button type="submit"
                class="w-full bg-gray-800 hover:bg-black text-white py-2 rounded-lg transition-all duration-200">
                Daftar Akun
            </button>

            <!-- Link Login -->
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-gray-700 dark:text-gray-200 hover:underline">
                    Masuk Sekarang
                </a>
            </p>
        </form>
    </div>

</body>
</html>

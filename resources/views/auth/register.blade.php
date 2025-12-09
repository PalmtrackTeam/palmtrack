<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | PT Irfan Sawit Jaya</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center">

    <div class="w-full max-w-2xl bg-white shadow-md rounded-2xl px-10 py-8 border border-gray-200">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                PT Irfan Sawit Jaya
            </h1>
            <p class="text-gray-600 text-sm mt-1">
                Buat akun baru Anda
            </p>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">
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
                    <label class="text-sm font-medium text-gray-700">Username *</label>
                    <input name="username" type="text" value="{{ old('username') }}" required
                        class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700">
                </div>

                <!-- Nama Lengkap -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Nama Lengkap *</label>
                    <input name="nama_lengkap" type="text" value="{{ old('nama_lengkap') }}" required
                        class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Email *</label>
                    <input name="email" type="email" value="{{ old('email') }}" required
                        class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Kata Sandi *</label>
                    <input name="password" type="password" required
                        class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700">
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Konfirmasi Kata Sandi *</label>
                    <input name="password_confirmation" type="password" required
                        class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700">
                </div>

                <!-- PILIH BLOK LADANG -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Blok Ladang *</label>
                    <select name="id_blok" required
                        class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700">
                        <option value="">Pilih Blok Ladang</option>
                        @foreach ($bloks as $blok)
                            <option value="{{ $blok->id_blok }}"
                                data-kategori="{{ $blok->kategori }}"
                                {{ old('id_blok') == $blok->id_blok ? 'selected' : '' }}>
                                {{ $blok->nama_blok }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategori -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Kategori Blok</label>
                    <div class="mt-1 p-2 border border-gray-300 rounded-lg bg-gray-50">
                        <span id="kategori-info" class="text-gray-700">-</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Ditentukan otomatis berdasarkan blok</p>
                </div>

                <!-- Telepon -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">No Telepon</label>
                    <input name="no_telepon" type="text" value="{{ old('no_telepon') }}"
                        class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700">
                </div>

            </div>

            <!-- ALAMAT -->
            <div class="mb-6">
                <label class="text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="alamat" rows="3"
                    class="mt-1 w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-gray-700 focus:border-gray-700">{{ old('alamat') }}</textarea>
            </div>

            <button type="submit"
                class="w-full bg-gray-800 text-white py-2 rounded-lg hover:bg-gray-700 transition font-medium shadow-sm">
                Daftar Akun
            </button>

            <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-gray-800 hover:underline">
                    Masuk Sekarang
                </a>
            </p>

        </form>
    </div>

<script>
    document.querySelector('select[name="id_blok"]').addEventListener('change', function () {
        const kategori = this.options[this.selectedIndex].getAttribute('data-kategori');
        const info = document.getElementById('kategori-info');

        if (kategori === 'dekat') {
            info.textContent = 'Dekat';
            info.className = 'text-green-600 font-medium';
        } else if (kategori === 'jauh') {
            info.textContent = 'Jauh';
            info.className = 'text-orange-600 font-medium';
        } else {
            info.textContent = '-';
            info.className = 'text-gray-700';
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const select = document.querySelector('select[name="id_blok"]');
        if (select.value) select.dispatchEvent(new Event('change'));
    });
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white shadow-md rounded-2xl px-10 py-8 border border-gray-200">

        <h1 class="text-2xl font-bold text-center text-gray-800 mb-2">
            Verifikasi Email
        </h1>

        <p class="text-sm text-center text-gray-600 mb-6">
            Terima kasih telah mendaftar. Sebelum melanjutkan, silakan verifikasi
            alamat email Anda dengan mengklik tautan yang telah kami kirimkan
            ke email Anda.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm text-center">
                Link verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif

        <div class="flex flex-col gap-4 mt-6">

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    class="w-full bg-gray-800 text-white py-2 rounded-lg hover:bg-gray-700 transition font-medium shadow-sm">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit"
                        class="text-sm text-gray-500 hover:text-gray-700 underline">
                    Logout
                </button>
            </form>

        </div>
    </div>

</body>
</html>

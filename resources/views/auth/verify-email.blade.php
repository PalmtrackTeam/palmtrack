<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-white dark:bg-black px-4">

    <div class="w-full max-w-md border border-gray-200 dark:border-gray-800 rounded-xl p-8 shadow-lg">

        <h1 class="text-2xl font-semibold text-center text-black dark:text-white mb-4">
            Verifikasi Email
        </h1>

        <p class="text-sm text-center text-gray-700 dark:text-gray-300 mb-4">
            Thanks for signing up! Before getting started, please verify your email
            address by clicking the link we just sent to your email.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm text-center bg-gray-100 dark:bg-gray-900
                        text-black dark:text-white border border-gray-300 dark:border-gray-700
                        rounded-lg px-4 py-3">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="flex flex-col gap-4 mt-6">

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    class="w-full py-2 rounded-lg bg-black text-white
                           hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-200">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit"
                        class="text-sm underline text-gray-600 dark:text-gray-400
                               hover:text-black dark:hover:text-white">
                    Log Out
                </button>
            </form>

        </div>
    </div>

</body>
</html>

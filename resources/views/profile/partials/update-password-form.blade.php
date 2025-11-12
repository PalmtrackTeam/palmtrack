@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="max-w-xl mx-auto bg-white border border-gray-200 shadow-sm rounded-2xl p-8 mt-10">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Ubah Password</h2>

    @if (session('status') === 'password-updated')
        <div class="mb-6 p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
            ✅ Password berhasil diperbarui.
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('PATCH')

        {{-- Password Saat Ini --}}
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                Password Saat Ini
            </label>
            <input type="password" id="current_password" name="current_password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-gray-500 focus:ring-gray-400 transition"
                   required>
            @error('current_password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Baru --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password Baru
            </label>
            <input type="password" id="password" name="password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-gray-500 focus:ring-gray-400 transition"
                   required>
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Konfirmasi Password Baru
            </label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-gray-500 focus:ring-gray-400 transition"
                   required>
            @error('password_confirmation')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit"
                    class="px-5 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition">
                Simpan Password
            </button>
        </div>
    </form>
</div>
@endsection

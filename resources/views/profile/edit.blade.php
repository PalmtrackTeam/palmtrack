@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-xl mx-auto bg-white border border-gray-200 shadow-sm rounded-2xl p-8 mt-10">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Edit Profil</h2>

    {{-- Pesan sukses --}}
    @if (session('status') === 'profile-updated')
        <div class="mb-6 p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
            ✅ Profil berhasil diperbarui.
        </div>
    @endif

    {{-- Form Edit Profil --}}
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" id="name" name="name"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-gray-500 focus:ring-gray-400 transition"
                   value="{{ old('name', auth()->user()->name) }}" required>
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
            <input type="email" id="email" name="email"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-gray-500 focus:ring-gray-400 transition"
                   value="{{ old('email', auth()->user()->email) }}" required>
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit"
                    class="px-5 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

    {{-- Form Hapus Akun --}}
    <div class="mt-10 border-t border-gray-200 pt-6">
        <h3 class="text-lg font-semibold text-red-600 mb-3">Hapus Akun</h3>
        <p class="text-sm text-gray-600 mb-4">
            Menghapus akun akan menghapus semua data secara permanen. Tindakan ini tidak bisa dibatalkan.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <div class="mb-4">
                <input type="password" name="password" placeholder="Masukkan password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-red-500 focus:ring-red-400" required>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-500">
                Hapus Akun
            </button>
        </form>
    </div>
</div>
@endsection

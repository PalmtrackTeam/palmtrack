@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-3xl mx-auto mt-12 bg-white shadow-md rounded-2xl p-8">
  <div class="flex flex-col items-center text-center">
    {{-- Avatar user --}}
    <div class="w-24 h-24 bg-green-600 text-white rounded-full flex items-center justify-center text-3xl font-bold">
      {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
    </div>

    {{-- Nama dan email --}}
    <h2 class="text-2xl font-semibold mt-4">{{ Auth::user()->name }}</h2>
    <p class="text-gray-500">{{ Auth::user()->email }}</p>

    {{-- Tombol aksi --}}
    <div class="mt-6 flex gap-4">
      <a href="{{ url('/dashboard') }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition">Dashboard</a>

      <a href="{{ route('profile.edit') }}" class="bg-yellow-400 text-green-900 px-4 py-2 rounded-lg font-semibold hover:bg-yellow-300 transition">
        Edit Profil
      </a>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
          Logout
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

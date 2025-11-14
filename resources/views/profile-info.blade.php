@extends('layouts.app')

@section('title', 'Informasi Profil')

@section('content')
<div class="max-w-3xl mx-auto mt-16 bg-white border border-gray-200 rounded-xl shadow-sm">
  <div class="px-8 py-6 border-b border-gray-200 text-center">
    <h2 class="text-2xl font-semibold text-gray-800">Informasi Profil</h2>
  </div>

  <div class="px-8 py-8 space-y-6">
    <div>
      <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Nama</p>
      <p class="text-gray-800 font-medium border border-gray-200 rounded-lg px-4 py-2 bg-gray-50">
        {{ $user->name }}
      </p>
    </div>

    <div>
      <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Email</p>
      <p class="text-gray-800 font-medium border border-gray-200 rounded-lg px-4 py-2 bg-gray-50">
        {{ $user->email }}
      </p>
    </div>

    <div>
      <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Tanggal Bergabung</p>
      <p class="text-gray-800 font-medium border border-gray-200 rounded-lg px-4 py-2 bg-gray-50">
        {{ $user->created_at->format('d F Y') }}
      </p>
    </div>

    <div>
      <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Status Akun</p>
      <p class="text-gray-800 font-medium border border-gray-200 rounded-lg px-4 py-2 bg-gray-50">
        Aktif
      </p>
    </div>
  </div>

  <div class="px-8 py-6 border-t border-gray-200 text-center">
    <a href="{{ route('profile.edit') }}" 
       class="inline-block bg-gray-800 text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition-colors">
      Edit Profil
    </a>
  </div>
</div>
@endsection

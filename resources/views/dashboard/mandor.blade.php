<!-- @extends('layouts.auth')
@section('title', 'Dashboard Mandor')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Halo Mandor {{ Auth::user()->name }}</h1>
<p class="text-gray-600 dark:text-gray-300">Kelola data karyawan dan pantau absensi harian di sini.</p>
@endsection -->

@extends('layouts.app')

@section('content')
<div class="min-h-screen flex justify-center items-center bg-gradient-to-r from-blue-100 to-blue-50">
    <div class="bg-white shadow-lg rounded-xl p-6 w-1/2">
        <h2 class="text-xl font-bold mb-4 border-b-2 border-blue-500 pb-2 flex items-center gap-2">
            🧑‍💼 {{ strtoupper($user->name) }}
        </h2>
        <div class="space-y-3">
            <div><strong>Role:</strong> Admin</div>
            <div><strong>Email:</strong> {{ $user->email }}</div>
        </div>
    </div>
</div>
@endsection

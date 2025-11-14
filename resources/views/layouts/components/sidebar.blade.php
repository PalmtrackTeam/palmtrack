<!-- <aside class="w-64 bg-gray-900 text-gray-100 min-h-screen p-4 space-y-4">
  <div class="text-lg font-semibold mb-6 border-b border-gray-700 pb-3">
    Menu Utama
  </div>

  @php
    $role = Auth::user()->role ?? 'guest';
  @endphp

  {{-- Menu berdasarkan role --}}
  @if ($role === 'pimpinan')
      <a href="{{ route('dashboard.pimpinan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">📊 Dashboard</a>
      <a href="{{ route('data.admin') }}" class="block py-2 px-3 rounded hover:bg-gray-800">👷 Kelola Mandor</a>
      <a href="{{ route('laporan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">📈 Laporan Perusahaan</a>

  @elseif ($role === 'mandor')
      <a href="{{ route('dashboard.mandor') }}" class="block py-2 px-3 rounded hover:bg-gray-800">📋 Dashboard</a>
      <a href="{{ route('data.karyawan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">👷 Data Karyawan</a>
      <a href="{{ route('absensi.mandor') }}" class="block py-2 px-3 rounded hover:bg-gray-800">🕓 Absensi Harian</a>

  @elseif ($role === 'karyawan')
      <a href="{{ route('dashboard.karyawan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">🏠 Dashboard</a>
      <a href="{{ route('absensi.karyawan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">🕓 Isi Absensi</a>
      <a href="{{ route('profil.karyawan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">👤 Profil Saya</a>
  @endif
</aside> -->


<aside class="w-64 bg-gray-900 text-white min-h-screen p-4">
    @auth
        @php
            $role = Auth::user()->role;
        @endphp

        @if ($role === 'super_admin')
            <a href="{{ route('dashboard.super_admin') }}" class="block py-2 px-3 rounded hover:bg-gray-800">🏠 Dashboard</a>
            <a href="{{ route('data.admin') }}" class="block py-2 px-3 rounded hover:bg-gray-800">👥 Data Admin</a>
            <a href="{{ route('laporan.super_admin') }}" class="block py-2 px-3 rounded hover:bg-gray-800">📊 Laporan</a>

        @elseif ($role === 'admin')
            <a href="{{ route('dashboard.admin') }}" class="block py-2 px-3 rounded hover:bg-gray-800">📋 Dashboard</a>
            <a href="{{ route('data.karyawan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">👷 Data Karyawan</a>
            <a href="{{ route('absensi.admin') }}" class="block py-2 px-3 rounded hover:bg-gray-800">🕓 Absensi Harian</a>

        @elseif ($role === 'karyawan')
            <a href="{{ route('dashboard.karyawan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">🏠 Dashboard</a>
            <a href="{{ route('absensi.karyawan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">🕓 Isi Absensi</a>
            <a href="{{ route('profil.karyawan') }}" class="block py-2 px-3 rounded hover:bg-gray-800">👤 Profil Saya</a>
        @endif
    @endauth

    @guest
        {{-- Kalau belum login, sidebar kosong / atau bisa isi tombol login --}}
        <div class="text-gray-400 text-sm mt-4">
            Silakan <a href="{{ route('login') }}" class="text-blue-400 underline">login</a> untuk melihat menu.
        </div>
    @endguest
</aside>
